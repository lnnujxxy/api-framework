local router = require 'router'
local user = require 'user'
require "test"
local r = router.new()

local hello = function (params) 
	a=test.new(1)	-- 输出两行，base_type ctor 和 test ctor 。这个对象被正确的构造了。
	ngx.print(a.foo)
	-- ngx.print("hello")
end

r:match({
	GET = {
	  -- ["/hello"]       = function(params) ngx.print("someone said hello") end,
	  ["/hello"]       = hello,
	  ["/ios/user/getMbRegVcode/:sv"] = user.getMbRegVcode,
	  ["/ios/user/mbReg/:sv"] = user.mbReg,
	  ["/ios/user/api/:sv"] = user.api
	},
	POST = {
	  ["/app/:id/comments"] = function(params)
	    ngx.print("comment " .. params.comment .. " created on app " .. params.id)
	  end,
	  ["/hello/api/:sv"] = user.api
	}
})

ngx.req.read_body()
local ok, errmsg = r:execute(
	ngx.var.request_method,
	ngx.var.request_uri,
	ngx.req.get_uri_args(),  -- all these parameters
	ngx.req.get_post_args(), -- will be merged in order
	{ngx_lua = 1}
)         -- into a single "params" table

if not ok then
	ngx.print("Not found!")
	ngx.log(ngx.ERR, errmsg)
end