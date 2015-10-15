local msg = {}


msg.TEXT_REG_VCODE = '$vcode（注册验证码），30分钟内有效。请勿将验证码泄露给他人。';
msg.TEXT_FIND_PW_VCODE = '$vcode（找回密码验证码），30分钟内有效。请勿将验证码泄露给他人。';

msg.SUCC_NO_COMMON = 0;
msg.SUCC_MSG_COMMON = '成功';
msg.ERROR_NO_MAX_VCODE_LIMIT = 1000;
msg.ERROR_MSG_MAX_VCODE_LIMIT = '对不起，你获取验证码的次数超过了限制。为保证你的手机安全，请明天再来试试。';
msg.ERROR_NO_SEND_SMS_FAIL = 1001;
msg.ERROR_MSG_SEND_SMS_FAIL = '验证码发送失败，请重试。';
msg.ERROR_NO_CHECK_CODE_FAIL = 1002;
msg.ERROR_MSG_CHECK_CODE_FAIL = '输入的邀请码非法，请使用正确邀请码。';
msg.ERROR_NO_CHECK_SMS_FAIL = 1003;
msg.ERROR_MSG_CHECK_SMS_FAIL = '输入的验证码错误，请输入正确验证码。';
msg.ERROR_NO_MB_REG_FAIL = 1004;
msg.ERROR_MSG_MB_REG_FAIL = '注册失败，请重试！';
msg.ERROR_NO_MB_LOGIN_FAIL = 1005;
msg.ERROR_MSG_MB_LOGIN_FAIL = '登录失败，请重试！';
msg.ERROR_NO_MB_NOREG_FAIL = 1006;
msg.ERROR_MSG_MB_NOREG_FAIL = '该手机还没有注册，请先注册！';
msg.ERROR_NO_MB_HAVE_REG_FAIL = 1007;
msg.ERROR_MSG_MB_HAVE_REG_FAIL = '该手机已经注册，请直接登录！';
msg.ERROR_NO_NOLOGIN_FAIL = 1008;
msg.ERROR_MSG_NOLOGIN_FAIL = '该用户未登录，请先登录';
msg.ERROR_NO_UPDATE_USER_FAIL = 1009;
msg.ERROR_MSG_UPDATE_USER_FAIL = '更新用户信息失败';
msg.ERROR_NO_PARAM_FAIL = 1010;
msg.ERROR_MSG_PARAM_FAIL = '接口传递参数错误，请检查！';
msg.ERROR_NO_MAX_LOGIN_FAIL = 1011;
msg.ERROR_MSG_MAX_LOGIN_FAIL = '该手机号登录错误次数已达上限';

return msg
