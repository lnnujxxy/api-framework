local config = {}

config._VERSION = '1.0'

-- DB Config
config.DBHOST = '182.92.34.93'
config.DBPORT = 3989
config.DBNAME = 'biaobai_test'
config.DBUSER = 'biaobai'
config.DBPW = 'biaobai'
config.DBCHARSET = 'UTF8'

-- Redis Config
--config.REDISHOST = '10.140.94.65'
config.REDISHOST = '0fe69f3a165a11e5.m.cnbja.kvstore.aliyuncs.com'
config.REDISPORT = 6379
config.REDISAUTH = '0fe69f3a165a11e5:biaobai123QWE'

return config
