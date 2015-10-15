local user_mysql = require "user_mysql"
local user_redis = require "user_redis"
local utility = require "utility"
local msg = require "msg"
local cjson_safe = require "cjson.safe"
local user = {}

user._VERSION = '1.0'

user.hello = function(params)
	local aes = require "resty.aes"
    local str = require "resty.string"

    local aes_128_cbc_md5 = aes:new("1234567812345678",
        nil, aes.cipher(128, "cbc"), {iv="1234567812345678"})
        -- AES 128 CBC with IV and no SALT
    local encrypted = "vkYbld7ogr5nNi/bRp6XMA==";
    ngx.say("AES 128 CBC (WITH IV) Decrypted: ", aes_128_cbc_md5:decrypt(ngx.decode_base64(encrypted)))
	ngx.say("hello1 "..params.name)
end

user.api = function(params)
	ngx.log(ngx.ERR, "$$$ user api")
	params = utility.getParams(params)
end

-- 注册验证码接口
user.getMbRegVcode = function(params)
    local params = utility.getParams(params)
    if params.mb == nil then
        utility.output(msg.ERROR_NO_PARAM_FAIL, msg.ERROR_MSG_PARAM_FAIL)
    end

    local sms = require('sms')
    math.randomseed(ngx.now())
    local vcode = string.sub(tostring(math.random() * 100000), 0, 4);
    -- ngx.say(ngx.header['aes_key'])
    local content = string.gsub(msg.TEXT_REG_VCODE, "$vcode", vcode);

    if sms.sendSMSByML(params.mb, content) then
        utility.output(msg.SUCC_NO_COMMON, msg.SUCC_MSG_COMMON);
    else 
        utility.output(msg.ERROR_NO_SEND_SMS_FAIL, msg.ERROR_MSG_SEND_SMS_FAIL);
    end
end

user.mbReg = function(params)
    local params = utility.getParams(params);
    if not params.mb or not params.sms or not params.pw then
        utility.output(msg.ERROR_NO_PARAM_FAIL, msg.ERROR_MSG_PARAM_FAIL);
    end

    local row = user_mysql:getRow(params.mb);
      
    if row[1] and row[1].uid then
        utility.output(msg.ERROR_NO_MB_HAVE_REG_FAIL, msg.ERROR_MSG_MB_HAVE_REG_FAIL);
    end

    if not user_mysql:register(params.mb, params.pw) then 
        utility.output(msg.ERROR_NO_MB_HAVE_REG_FAIL, msg.ERROR_MSG_MB_REG_FAIL);
    end
    local row = user_mysql:getRow(params.mb);

    if not row then
        utility.output(msg.ERROR_NO_MB_REG_FAIL, msg.ERROR_MSG_MB_REG_FAIL);
    end
    local content = cjson_safe.encode(row);
    user_redis:setUser(row[1].uid, content);
    utility.output(msg.SUCC_NO_COMMON, msg.SUCC_MSG_COMMON, content);
end

return user