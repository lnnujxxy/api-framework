local user_redis = require "user_redis"
local user = {}

user._VERSION = '1.0'

user.hello = function(params)
	user_redis:isLogin('10000', 'login')	
	ngx.say("hello "..params.name)
end

return user