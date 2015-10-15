local config = require "config"
local mysql = require "resty.mysql"
local utility = require "utility"

local mysql_pool = {}

function mysql_pool:getClient()
	if ngx.ctx[mysql_pool] then
		return true, ngx.ctx[mysql_pool]
	end
	local db, err = mysql:new()

	if not db then
		 return false, "mysql.socket_failed: " .. (err or "nil")
	end

	db:set_timeout(1000)
	--解析域名
	-- local res = string.match(config.DBHOST, "^(%d+).(%d+).(%d+).(%d+)$")
	-- if not res then
	-- 	local address = utility.resolveDomain(config.DBHOST);
	-- 	ngx.log(ngx.ERR, '#### '.. address)
	-- 	if address == nil then
	-- 		return false, "mysql unknown address"
	-- 	else
	-- 		config.DBHOST = address
	-- 	end
	-- end

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

	ngx.ctx[mysql_pool] = db

	return true, ngx.ctx[mysql_pool]
end

function mysql_pool:close() 
	if ngx.ctx[mysql_pool] then
		ngx.ctx[mysql_pool]:set_keepalive(10000, 100)
		ngx.ctx[mysql_pool] = nil
	end
end

function mysql_pool:query(sql)
	local _, client = self:getClient();
	local res, err, errno, sqlstate = client:query(sql);

	if not res then
		return false, "mysql.query_failed: " .. (err or "nil") .. ", errno:" .. (errno or "nil"), sqlstate
	end

	return res, err, errno, sqlstate;
end

return mysql_pool
