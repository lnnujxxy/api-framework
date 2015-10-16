local mysql_pool = require "mysql_pool"

local _M = {
	table = 'sz_user'
}

_M._VERSION = '1.0'

local mt = {__index = _M}

local function get_crypt_pw(pw) 
	local salt = ngx.md5(ngx.now())
	local crypt_pw = ngx.md5(pw .. salt)

	return salt, crypt_pw
end

function _M.new(self)
	return setmetatable({table = 'sz_user'}, mt)
end

function _M.register(self, mb, pw)
	local ok, mysql = mysql_pool:getClient()
	
	if not ok then
		ngx.log(ngx.ERR, 'mysql client fail'..mysql)
		return false
	end
	
	local mb = ngx.quote_sql_str(mb) or ''
	local salt, crypt_pw = get_crypt_pw(pw)
	salt = ngx.quote_sql_str(salt) or ''
	crypt_pw = ngx.quote_sql_str(crypt_pw) or ''

	local sql = "INSERT INTO " .. self.table .. " SET "
		  sql = sql .. " mobile = " .. mb .. ", password = " .. crypt_pw .. ", salt = " .. salt
	ngx.log(ngx.ERR, sql)
	local res, err, errno, sqlstate = mysql_pool:query(sql)
	if not res then
		ngx.log(ngx.ERR, "bad result: " .. (err or "nil") .. ": " .. (errno or "nil") .. ": " .. (sqlstate or ''))
		return false
	end
	
	return true
end

function _M.getRow(self, mb) 
	local ok, mysql = mysql_pool:getClient()
	
	if not ok then
		ngx.log(ngx.ERR, 'mysql client fail'..mysql)
		return false
	end

	local mb = ngx.quote_sql_str(mb) or ''
	local sql = "SELECT * FROM " .. self.table .. " WHERE mobile = " .. mb
	local res, err, errno, sqlstate = mysql_pool:query(sql)
	if not res then
		ngx.log(ngx.ERR, "bad result: " .. err .. ": " .. errno .. ": " .. (sqlstate or ''))
		return false
	end 
	
	return res
end

return _M