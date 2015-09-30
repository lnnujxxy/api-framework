local response = {}
response._VERSION = '1.0'
local cjson = require 'cjson'

function response:setHeader(name, value)
	ngx.header[name] = value
end

function response:output(data, status)
	if not status then
		status = ngx.OK
	end
	ngx.status = status
	ngx.say(cjson.encode(data))
end

return response