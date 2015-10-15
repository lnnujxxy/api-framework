local utility = {}
local cjson_safe = require "cjson.safe"
local config = require "config"

utility._VERSION = '0.1'

utility.getClientIP = function() 
	clientIP = ngx.req.get_headers()["X-Real-IP"]	
	if clientIP == nil then
		clientIP = ngx.req.get_headers()["x_forworded_for"]
	end

	if clientIP == nil then
		clientIP = ngx.var.remote_addr
	end

	return clientIP
end

utility.htmlspecialchars = function(str)
	local rs = str or nil

	if rs and type(rs) == 'string' then
		rs = string.gsub(rs, '&', '&amp;')
		rs = string.gsub(rs, '"', '&quot;')
		rs = string.gsub(rs, "'", '&#039')
		rs = string.gsub(rs, '<', '&lt;')
		rs = string.gsub(rs, '>', '&gt;')
	end

	return rs
end


utility.sort = function(t, order) 
	local keys = {}
	for k in pairs(t) do keys[#keys+1] = k end

	-- if order function given, sort by it by passing the table and keys a, b,
	-- otherwise just sort the keys 
	if order then
		table.sort(keys, function(a,b) return order(t, a, b) end)
	else
		table.sort(keys)
	end

	sorted = {}
	for i, key in ipairs(keys) do
		sorted[key] = t[key]		
	end	

	return sorted
end

utility.resolveDomain = function(domain)
	local ngx_cache = ngx.shared.ngx_cache
	local address, flags = ngx_cache:get(domain)
	if address ~= nil then
		ngx.log(ngx.ERR, '####'..address)
		return ngx_cache:get(domain)
	end

	local resolver = require "resty.dns.resolver"

    local r, err = resolver:new{
        nameservers = { "8.8.8.8" }
    }
    if not r then
        return nil
    end

    local answers, err = r:tcp_query(domain, { qtype = r.TYPE_A })
    if not answers then
        return nil
    end

    for i, ans in ipairs(answers) do
    	ngx_cache:set(domain, ans.address)
    	return ans.address
    end
end

utility.getParams = function()
	local args
	if "GET" == ngx.var.request_method then
	    args = ngx.req.get_uri_args()
	elseif "POST" == ngx.var.request_method then
	    ngx.req.read_body()
	    args = ngx.req.get_post_args()
	end

	
	local params 

	if args.pm and args.ky then
		local resty_rsa = require "rsa"
		
		--ngx.log(ngx.ERR, "RSA_PRIV_KEY: " .. config.RSA_PRIV_KEY)
		local priv, err = resty_rsa:new({ private_key = config.RSA_PRIV_KEY , password = config.RSA_PASSWORD})
	    if not priv then
	        ngx.log(ngx.ERR, "new rsa err: " .. err)
	        return
	    end
	    local key = priv:decrypt(ngx.decode_base64(args.ky))

	    local aes = require "resty.aes"
    	local str = require "resty.string"
    	local aes_128_cbc_with_iv = aes:new(key, nil, aes.cipher(128, "cbc"), {iv=key})
        params = aes_128_cbc_with_iv:decrypt(ngx.decode_base64(args.pm))
        ngx.header['aes_key'] = key
        -- ngx.log(ngx.ERR, "$$$ params : " .. params)
        return cjson_safe.decode(params)
	elseif args.pm and not args.ky then
		params = ngx.decode_base64(args.pm);
		return cjson_safe.decode(params)
	end
end

utility.output = function(code, msg, content)
	local ret = {
		code = code,
		msg = ngx.encode_base64(msg),
		content = content
	}

	ngx.header.content_type = 'application/json; charset=utf-8';
	ngx.say(cjson_safe.encode(ret))

	local mysql_pool = require("mysql_pool");
	mysql_pool:close();

	local redis_pool = require("redis_pool");
	redis_pool:close();

	ngx.exit(ngx.HTTP_OK)
end

return utility
