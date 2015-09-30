local config = require "config"
local redis = require "resty.redis"
local utility = require "utility"

local redis_pool = {}

function redis_pool:getClient() 
	if ngx.ctx[redis_pool] then
		return true, ngx.ctx[redis_pool]
	end

	local redis = redis:new()

	redis:set_timeout(1000)
	-- dns resolver, 先判断是否ip,不是将域名解析对应ip
	local res = string.match(config.REDISHOST, "^(%d+).(%d+).(%d+).(%d+)$")
	if not res then
		local address = utility.resolveDomain(config.REDISHOST);
		ngx.log(ngx.ERR, '#### '.. address)
		if address == nil then
			return false, "redis unknown address"
		else
			config.REDISHOST = address
		end
	end

	local ok, err = redis:connect(config.REDISHOST, config.REDISPORT)
	if not ok then
		return false, err
	end

	if config.REDISAUTH ~= '' then
		local ok, err = redis:auth(config.REDISAUTH)
		if not ok then
			return false, err
		end
	end

	ngx.ctx[redis_pool] = redis
	return true, ngx.ctx[redis_pool]
end

function redis_pool:close()
	if ngx.ctx[redis_pool] then
		ngx.ctx[redis_pool]:set_keepalive(10000, 100)
		ngx.ctx[redis_pool] = nil
	end
end

return redis_pool

