local redis_pool = require "redis_pool"
local user_redis = {}

user_redis._VERSION = '1.0'

function user_redis:isLogin(uid, value)
	local ok, redis = redis_pool:getClient()
	
	if not ok then
		ngx.log(ngx.ERR, 'redis client fail')
	end
	
	redis:set(uid, value)

	return redis:get(uid) == value 
end

return user_redis