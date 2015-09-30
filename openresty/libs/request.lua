local request = {}
local request_method = ngx.var.request_method
local params 

function request:getParams()	
	if "GET" == request_method then
		params = ngx.req.get_uri_args()
	elseif "POST" == request_method then
		ngx.req.read_body()
		params = ngx.req.get_post_args()
	end

	return params
end

function request:getParam(name)
	params = request:getParams();
	return params[name]
end

function request:getHeaders()
	return ngx.req.get_headers()
end

function request:getHeader(name)
	local headers = ngx.req.get_headers()
	return headers[name];
end

function request:getServer(name)
	return ngx.var[name]
end

function request:getMethod()
	return request_method
end 

return request