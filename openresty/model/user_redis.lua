local redis_pool = require "redis_pool"
local cjson_safe = require "cjson.safe"

local _M = {}
_M._VERSION = '0.1'

local function getLoginHashKey() 
	return 'hashkey:login';
end

function _M.setUser(self, uid, row)
	ngx.log(ngx.ERR, 'uid = '.. uid);
	local ok, redis = redis_pool:getClient()
	
	if not ok then
		ngx.log(ngx.ERR, 'redis client fail')
		return false;
	end

	local json = cjson_safe.encode(row);
	redis:hset(getLoginHashKey(), uid, json);

	return true; 
end

function _M.getUser(self, uid)
	local ok, redis = redis_pool:getClient()
	
	if not ok then
		ngx.log(ngx.ERR, 'redis client fail')
		return false;
	end

	return redis:hget(getLoginHashKey(), uid);
end

return _M