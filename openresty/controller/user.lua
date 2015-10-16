local user_mysql    = require "user_mysql"
local user_redis    = require "user_redis"
local utility       = require "utility"
local msg           = require "msg"
local cjson_safe    = require "cjson.safe"
local sms           = require "sms"
local resty_aes     = require "resty.aes"
local resty_string  = require "resty.string"
local request       = require "request"
local response      = require "response"

local _M = {}
_M._VERSION = '0.1'

function _M.hello(params)
    local aes_128_cbc_md5 = resty_aes:new("1234567812345678",
        nil, resty_aes.cipher(128, "cbc"), {iv="1234567812345678"})
        -- AES 128 CBC with IV and no SALT
    local encrypted = "vkYbld7ogr5nNi/bRp6XMA=="
    ngx.say("AES 128 CBC (WITH IV) Decrypted: ", aes_128_cbc_md5:decrypt(ngx.decode_base64(encrypted)))
	ngx.say("hello1 "..params.name)
end

function _M.api(params)
	ngx.log(ngx.ERR, "$$$ user api")
	params = request:getParams(params)

    for k, v in pairs(params) do 
        ngx.say("k = " .. k .. "    v = " .. v)
    end
end

-- 注册验证码接口
function _M.getMbRegVcode(params)
    local params = request:getParams(params)
    if not params.mb then
        response.sendResponse(msg.ERROR_NO_PARAM_FAIL, msg.ERROR_MSG_PARAM_FAIL)
    end

    math.randomseed(ngx.now())
    local vcode = tostring(math.random() * 100000):sub(0, 4)
    local content = msg.TEXT_REG_VCODE:gsub("$vcode", vcode)

    if sms.sendSMSByML(params.mb, content) then
        response.sendResponse(msg.SUCC_NO_COMMON, msg.SUCC_MSG_COMMON)
    else 
        response.sendResponse(msg.ERROR_NO_SEND_SMS_FAIL, msg.ERROR_MSG_SEND_SMS_FAIL)
    end
end

-- 手机号注册接口
function _M.mbReg(params)
    local params = request:getParams(params)
    if not params.mb or not params.sms or not params.pw then
        response.sendResponse(msg.ERROR_NO_PARAM_FAIL, msg.ERROR_MSG_PARAM_FAIL)
    end

    local row = user_mysql:getRow(params.mb)
      
    if row[1] and row[1].uid then
        response.sendResponse(msg.ERROR_NO_MB_HAVE_REG_FAIL, msg.ERROR_MSG_MB_HAVE_REG_FAIL)
    end

    if not user_mysql:register(params.mb, params.pw) then 
        response.sendResponse(msg.ERROR_NO_MB_REG_FAIL, msg.ERROR_MSG_MB_REG_FAIL)
    end
    local row = user_mysql:getRow(params.mb)

    if not row then
        response.sendResponse(msg.ERROR_NO_MB_REG_FAIL, msg.ERROR_MSG_MB_REG_FAIL)
    end
    
    local content = cjson_safe.encode(row)
    user_redis:setUser(row[1].uid, content)
    response.sendResponse(msg.SUCC_NO_COMMON, msg.SUCC_MSG_COMMON, content)
end

return _M