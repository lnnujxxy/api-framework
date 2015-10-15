local config = {}

config._VERSION = '1.0'

-- DB Config
config.DBHOST = 'biaobaoapp.mysql.rds.aliyuncs.com'
config.DBPORT = 3989
config.DBNAME = 'size_test'
config.DBUSER = 'biaobai'
config.DBPW = 'biaobai'
config.DBCHARSET = 'UTF8'

-- Redis Config
--config.REDISHOST = '10.140.94.65'
config.REDISHOST = '0fe69f3a165a11e5.m.cnbja.kvstore.aliyuncs.com'
config.REDISPORT = 6379
config.REDISAUTH = '0fe69f3a165a11e5:biaobai123QWE'

config.RSA_PUBLIC_KEY = [[
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCwrQbcGfgbEz0f573qxYgSs+1m
8aTsmLIvTRcTJ/rO0YfRHCvwEcCjLIvWLFW7AiPlUETu+RAjyuj6SQiUR0i0s7L9
ZAV9R0c9dSlSWkANhlJFIO34ziGzVqW/+PrNza/kpZ+qJ+XoFEt7/NBXLDw2M6/+
7uuZDXTOMDvliCf85wIDAQAB
-----END PUBLIC KEY-----
]]

config.RSA_PRIV_KEY = [[
-----BEGIN ENCRYPTED PRIVATE KEY-----
MIICxjBABgkqhkiG9w0BBQ0wMzAbBgkqhkiG9w0BBQwwDgQI0SjJlO3ehxUCAggA
MBQGCCqGSIb3DQMHBAjcrlgtnAQNHASCAoBu3acrekwCdqHjIugXp5pKRBq61U6X
zYJfFg3hmckGLJmokIxZyjeV53jdGkw15f7ed6eYMSnpX5krcI/948W1eART6eZc
mDywKSR9THrrWARKB4wo7QClycgcRpuu575Osuc+gQruPb8K3gvL7BJYvOP1si/R
YyN1Bfv88lW43hHGMVz5z8MwKzYGOv1auTM4wH12sx1LYfVoJSMeixiHHQf7ylgO
5U1bTiPbJuKXe3dFh2nPa8mxwT65FEVBl00CXKxBRqZ6/JCndTCZfoXH+tTiGlj7
fwiAMPXF2erpvEc9My1nW6FJEC58s6BYrrujosvVH53kx627GpGTCLNFN/wuEgKz
po5FVFBlYYLtOaSCZw2lA7F2a2vxbQ4DpRwRbVQlAbTcl0KXt2S7PM2Ngu/lALbW
Kj2cDE4qg6NdXV9eWMPMUm25w2mR1uh/5KkxzSnbvcIChWtpgwt8qguq5Uahyc4I
7NRAABJ2Xmt4zh+EiainsvL/u5XfSQ43+WRKd+S42R824ZszP3jFc9D1njtaqAfO
60NQzFB1p3bdnmNhdjrPGwVtRCWLkRB1/ZnPtIFARHA3hW2IzTMH40XPhl3lqu8N
kos6Vkry8JA8Dd+SwVrwodfknjLP4m9J+k8hVpWS0fUKLfOMJOsuv+8BvmStOXfa
2aO+xdSFQTf/UVJFNPdZz9uNSfvRWdawha+/WB89eYn82w7QZRbdrwazRjJupmU8
XmJnz55U8QajwCclVZ6fw/TyGHzzCO+cmvryb+4gsfTYpATAWxqRT11t0i0YO4pq
nkj7VfwXPBjd/Zsq/7gbEWYfyAK8WILsfKi30PenOY82MdkRVROGknz4
-----END ENCRYPTED PRIVATE KEY-----
]]

config.RSA_PASSWORD = "bb#$34^&"

return config
