<?php
/*
接口
	使用接口，你可以指定某个类必须实现那些方法，但是不需要定义这些方法的具体内容，
	我们可以通过interface来定义一个接口，就像定义标准类一样，但其中定义所有的方法都是空的，接口中定义的所有的方法都必须是public
	实现一个接口，可以使用implement操作符，类中必须实现接口中定义的所用的方法，如果实现多个接口，可以用逗号来分割多个接口的名称

注意
	实现多个接口时，接口中的方法不能有重名
	接口也可以继承，通过使用extends操作符
	接口中可以定义常量，接口常量和类常量的使用完全相同，他们都是定值，不能被子类或子接口修改
*/
//例子1
//声明接口
interface TemplateInterface {
    public function setVariable($name,$var);
    public function getHtml($template);
}
//实现接口
class Template implements TemplateInterface {
    private $vars=array();
    public function setVariable($name,$var){
        $this->vars[$name]=$var;
    }
    public function getHtml($template){
        foreach($this->vars as $names=>$value){
            $template=str_replace('{'.$names.'}',$value,$template);
        }
        return $template;
    }
}
　　
//例子2
interface a{
    public function foo();
}
interface b{
    public function bar();
}
//继承接口
interface c extends a, b{
    public function baz();
}
//实现接口
class d implements c{
    public function foo(){
         
    }
    public function bar(){
         
    }
    public function baz(){
         
    }
}
 
interface a{
    const $b="hello";
}

/*
抽象类
	php5支持抽象类和抽象方法。抽象类不能直接被实例化，必须先继承该抽象类，
	然后再实例化子类，任何一个类，如果他里面至少有一个方法是被声明为抽象的，
	那这个类就必须被声明为抽象的，如果类方法被声明为抽象的，那么其中就不能包括具体的功能实现
	继承一个抽象类的时候，子类必须实现抽象类中的所有的抽象方法，
	另外，这些方法的可见性必须和抽象类中一样（或更轻松），
	如果抽象类中的某个抽象方法被声明为proteected，那么子类中实现的方法应该声明为pritected或者public 
*/
abstract class AbstractClass{
    //抽象方法
    abstract protected function getValue();
    abstract protected function prefieValue($p);
     
    //普通方法(非抽象方法)
    public function printOut(){
        print $this->getValue();
    }
}
 
class ConcreteClass1 extends AbstractClass{
    protected function getValue(){
        return "ConcreteClass1";
    }
 
    public function prefieValue($p){
        return "{$p}concreteclass1";
    }
}
 
class ConcreteClass2 extends AbstractClass{
    protected function getValue(){
        return "ConcreteClass2";
    }
 
    public function prefieValue($p){
        return "{$p}concreteclass2";
    }
}
 
$class1=new ConcreteClass1();
$class1->printOut();
echo $class1->prefieValue('FOO_');
/*
打印结果：ConcreteClass1
FOO_concreteclass1
*/

//调用静态方法的抽象类仍然可行的 类常量不可以的
abstract class Foo
{   
	const $a="hello";
    static function bar()
    {
        echo "test\n";
    }
}
Foo::$a;//没有输出
Foo::bar();//test