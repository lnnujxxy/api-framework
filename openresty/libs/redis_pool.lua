local config = require "config"
local redis = require "resty.redis"

local _M = {}
_M._VERSION = "0.1"

local TIMEOUT = 1000; -- 1s 
local IDEL_TIMEOUT = 60000; -- 60s
local POOL_SIZE = 1000; --单个worker，连接池中最大数

function _M.getClient(self) 
	if ngx.ctx.redis_pool then
		return true, ngx.ctx.redis_pool
	end
	local redis = redis:new()

	redis:set_timeout(TIMEOUT)

	local ok, err = redis:connect(config.REDISHOST, config.REDISPORT)
	if not ok then
		return false, err
	end

	if config.REDISAUTH then
		local ok, err = redis:auth(config.REDISAUTH)
		if not ok then
			return false, err
		end
	end

	ngx.ctx.redis_pool = redis
	return true, ngx.ctx.redis_pool
end

function _M.close(self)
	if ngx.ctx.redis_pool then
		ngx.ctx.redis_pool:set_keepalive(IDEL_TIMEOUT, POOL_SIZE)
		ngx.ctx.redis_pool = nil
	end
end

return _M