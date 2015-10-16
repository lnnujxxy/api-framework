local config = require "config"
local mysql = require "resty.mysql"

local _M = {}
_M._VERSION = "0.1"

local TIMEOUT = 1000; -- 1s 
local IDEL_TIMEOUT = 60000; -- 60s
local POOL_SIZE = 1000; --单个worker，连接池中最大数

function _M.getClient(self)
	if ngx.ctx.mysql_pool then
		return true, ngx.ctx.mysql_pool
	end
	local db, err = mysql:new()

	if not db then
		 return false, "mysql.socket_failed: " .. (err or "nil")
	end

	db:set_timeout(TIMEOUT)

	local ok, err = db:connect({
		host = config.DBHOST,
		port = config.DBPORT,
		database = config.DBNAME,
		user = config.DBUSER,
		password = config.DBPW,
		max_packet_size = 1024 * 1024
	})

	if not ok then
		 return false, "mysql.cant_connect: " .. (err or "nil") 
	end

	local res, err, errno, sqlstate = db:query("SET NAMES " .. config.DBCHARSET)
	if not res then
		return false, "mysql.query_failed: " .. (err or "nil") .. ", errno:" .. (errno or "nil") ..
			", sqlstate :" .. (sqlstate or "nil")
	end

	ngx.ctx.mysql_pool = db

	return true, ngx.ctx.mysql_pool
end

function _M.close(self) 
	if ngx.ctx.mysql_pool then
		ngx.ctx.mysql_pool:set_keepalive(IDEL_TIMEOUT, POOL_SIZE)
		ngx.ctx.mysql_pool = nil
	end
end

function _M.query(self, sql)
	local _, client = self:getClient()
	local res, err, errno, sqlstate = client:query(sql)

	if not res then
		return false, "mysql.query_failed: " .. (err or "nil") .. ", errno:" .. (errno or "nil"), sqlstate
	end

	return res, err, errno, sqlstate
end

return _M
