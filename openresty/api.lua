local router = require 'router'
local user = require 'user'
local r = router.new()

local hello = function (params) 
	ngx.print("hello 123123")
end

r:match({
	GET = {
	  -- ["/hello"]       = function(params) ngx.print("someone said hello") end,
	  ["/hello"]       = hello,
	  ["/hello/:name"] = user.hello
	},
	POST = {
	  ["/app/:id/comments"] = function(params)
	    ngx.print("comment " .. params.comment .. " created on app " .. params.id)
	  end
	}
})

ngx.req.read_body()
local ok, errmsg = r:execute(
	ngx.var.request_method,
	ngx.var.request_uri,
	ngx.req.get_uri_args(),  -- all these parameters
	ngx.req.get_post_args(), -- will be merged in order
	{other_arg = 1}
)         -- into a single "params" table

if not ok then
	ngx.print("Not found!")
	ngx.log(ngx.ERR, errmsg)
end