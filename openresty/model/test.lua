require "base_type"
test=class(base_type)	-- 定义一个类 test 继承于 base_type
test.foo = "bar"

function test:ctor()	-- 定义 test 的构造函数
	print("test ctor")
end
 
function test:hello2()	-- 重载 base_type:hello 为 test:hello
	return "hello test"
end