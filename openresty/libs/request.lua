local resty_aes 	= require "resty.aes"
local resty_rsa 	= require "resty.rsa"
local cjson_safe 	= require "cjson.safe"
local config 		= require "config"

local _M = {}
_M._VERSION = "0.1"

function _M.getParams(self)	
	local args, params

	if "GET" == ngx.var.request_method then
	    args = ngx.req.get_uri_args()
	elseif "POST" == ngx.var.request_method then
	    ngx.req.read_body()
	    args = ngx.req.get_post_args()
	end

	if args.pm and args.ky then
		local priv, err = resty_rsa:new({private_key = config.RSA_PRIV_KEY , password = config.RSA_PASSWORD})

	    if not priv then
	        ngx.log(ngx.ERR, "new rsa err: " .. err)
	        return {}
	    end

	    local key = priv:decrypt(ngx.decode_base64(args.ky))
    	local aes_128_cbc_with_iv = resty_aes:new(key, nil, resty_aes.cipher(128, "cbc"), {iv=key})
        params = aes_128_cbc_with_iv:decrypt(ngx.decode_base64(args.pm))
        ngx.header['aes_key'] = key
        -- ngx.log(ngx.ERR, "$$$ params : " .. params)
        params = cjson_safe.decode(params)
	elseif args.pm and not args.ky then
		params = ngx.decode_base64(args.pm);
		params = cjson_safe.decode(params)
	else 
		params = {}
	end

	if type(params) ~= "table" then
		return {}
	end

	return params
end

function _M.getParam(self, name)
	local params = self:getParams()
	return params[name]
end

function _M.getHeaders(self)
	return ngx.req.get_headers()
end

function _M.getHeader(self, name)
	local headers = self:getHeaders()
	return headers[name];
end

function _M.getServer(self, name)
	return ngx.var[name]
end

function _M.getMethod(self)
	return request_method
end 

return _M