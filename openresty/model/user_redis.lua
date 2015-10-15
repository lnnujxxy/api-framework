local redis_pool = require "redis_pool"
local cjson_safe = require "cjson.safe"

local user_redis = {}

user_redis._VERSION = '1.0'

local function getLoginHashKey() 
	return 'hashkey:login';
end

user_redis.setUser = function(self, uid, row)
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

user_redis.getUser = function(self, uid)
	local ok, redis = redis_pool:getClient()
	
	if not ok then
		ngx.log(ngx.ERR, 'redis client fail')
		return false;
	end

	return redis:hget(getLoginHashKey(), uid);
end

return user_redis