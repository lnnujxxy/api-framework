local cjson_safe 	= require "cjson.safe"
local redis_pool 	= require "redis_pool"
local mysql_pool 	= require "mysql_pool"

local _M = {}
_M._VERSION = '0.1'

function _M.setHeader(name, value)
	ngx.header[name] = value
end

function _M.sendResponse(code, msg, content)
	local ret = {
		code = code,
		msg = ngx.encode_base64(msg),
		content = content
	}

	ngx.header.content_type = 'application/json; charset=utf-8';
	ngx.say(cjson_safe.encode(ret))

	-- 释放连接放到连接池
	mysql_pool:close();
	redis_pool:close();

	ngx.exit(ngx.HTTP_OK)
end

return _M