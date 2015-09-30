local utility = {}

utility._VERSION = '1.0'

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

return utility
