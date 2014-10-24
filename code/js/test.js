   1:  <script>
   2:      function Circle( radius ){ 
   3:          this.r = radius; 
   4:          this.des = "圆形";
   5:          
   6:          this.showInfo = function(){
   7:              alert("这是一个"+this.des);
   8:   
   9:          }
  10:      }
  11:   
  12:      function Circle_area(r){ return Circle.PI*this.r*this.r; }
  13:   
  14:      function Circle_perimeter(r){ return  2*Circle.PI*r;}
  15:   
  16:      Circle.PI = 3.14;
  17:      Circle.perimeter = Circle_perimeter;
  18:      Circle.prototype.area = Circle_area;
  19:      
  20:      var c = new Circle(3);
  21:      
  22:      //测试类属性
  23:      //alert(Circle.PI )//3.14
  24:      //alert(c.PI)//undefined 因为类属性是和类本身，也就是函数本身相关联的，和类实例没有直接关系。
  25:      //alert(c.constructor.PI)//3.14 如果想通过类实例访问类属性，那么就需要先访问该实例的构造函数，进而访问该类属性
  26:      //alert(Circle.des)//undefined 因为函数Circle函数中的this.des中的this指代的不是函数本身，而是调用r的对象，而且只能是对象。
  27:      //alert(c.des)//圆形 this此时为实例化的 对象c。
  28:   
  29:      /*结论：
  30:          面向对象的角度：类属性是类对象的直接属性，且该属性与基于该类对象生成的实例对象没有直接关系，无法直接调用。
  31:          可以直接通过 类名.属性名 调用该类属性。如果想通过该类对象的实例对象调用类属性，那么可以使用 对象实例.constructor属性
  32:          调用该对象的类对象，然后通过类对象调用其类属性
  33:          javascript函数角度：类属性是javascript函数对象的直接属性变量（这里之所以称之为属性变量是由于javascript变量和属性的同一
  34:          性），且该属性变量与基于该函数对象构造出来的对象引用（生成了一个对象，这个对象实际上是一个空对象，并且保存了对构造
  35:          函数以及构造函数初始化时函数内部this关键字下的相关属性和函数的引用[c.prototype和构造函数中this.下面的相关属性、函数]：）
  36:          没有直接关系，如果想通过基于构造函数生成的对象c调用构造函数对象的属性变量PI，那么需要通过c.constructor属性找到该构造
  37:          函数对象，并通过该对象获取其属性变量。
  38:      */
  39:   
  40:      //测试类方法
  41:      //alert(Circle.perimeter(3)); //18.4 直接调用函数的类方法。
  42:      //alert( c.perimeter(3) ); //FF:c.perimeter is not a function IE:对象或属性不支持此方法。因为perimeter函数是Circle类的类方法，和实例对象没有直接关系
  43:      //alert(c.constructor.perimeter(3));//18.84 调用该对象构造函数（类函数）的方法（函数）。
  44:      //alert(c.area(3))//28.25.... Circle类的prototype原型属性下的area方法将会被Circle类的实例对象继承。
  45:      //alert(Circle.area(3));//FF: 错误： Circle.area is not a function  因为area方法是Circle类的原型属性的方法，并不是Circle类的直接方法。
  46:   
  47:      //结论：同上，把属性换成了方法，把属性变量换成了函数。
  48:      
  49:      //测试prototype对象属性
  50:      //alert(c.prototype); //undefined 实例对象没有ptototype属性
  51:      //alert(Circle.prototype); //object Object 
  52:      //alert(Circle.prototype.constructor)//返回Circle的函数体（函数代码体），相当于alert(Circle)
  53:      //alert(Circle.prototype.area(3));//NaN 方法调用成功，但是返回结果却是NaN，原因是area函数内部的this.r是undefined。
  54:      //alert(Circle.prototype.PI) //undefined因为PI属性是Circle类函数的直接属性，并不会在prototype属性下存在
  55:      //alert(Circle.prototype.constructor.PI)//3.14 通过Circle类的原型对象调用该原型对象的构造函数（类函数），再通过类函数调用PI属性。
  56:   
  57:      /*结论：prototype原型对象是javascript基于原型链表实现的一个重要属性。
  58:          Javascript角度：1. 实例对象没有prototype属性，只有构造函数才有prototype属性，也就是说构造函数本身保存了对prototype属性
  59:          的引用。。2. prototype属性对象有一个constructor属性，保存了引用他自己的构造函数的引用（看起来像是个循环：A指向B，B指向A...）
  60:          3.prototype对象（不要被我这里的属性对象，对象，对象属性搞晕乎了，说是属性对象，就是说当前这个东西他首先是某个对象的属性，
  61:          同时自己也是个对象。对象属性就是说它是某个对象的属性。）的属性变量和属性对象将会被该prototype对象引用的构造函数所创建的
  62:          对象继承(function A(){} A.prototype.pa = function(){} var oA = new A(); 那么oA将会继承属性函数pa)。
  63:      */
  64:   
  65:      /*这里对 对象属性，对象方法不再做详细测试。
  66:          1.javascript对象实例的在通过其构造函数进行实例化的过程中，保存了对构造函数中所有this关键字引用的属性和方法的引用（这里不
  67:          讨论对象直接量语法的情况）。但如果构造函数中没有通过this指定，对象实例将无法调用该方法。2.javascript可以通过构造函数创建
  68:          多个实例，实例会通过__proto__属性继承原型对象的属性和方法。如果对实例对象的属性和方法进行读写操作，不会影响其原型对象的
  69:          属性和方法，也就是说，对于原型对象，javascript实例对象只能读，不能写。那当我们对实例对象的属性和方法进行修改的时候也可以
  70:          改变其值这是为什么呢？其实当我们试图在实例对象中使用继承自原型对象的属性或方法的时候，javascript会在我们的实例对象中复
  71:          制一个属性或方法的副本，这样，我们操作的时候，其实操作的就是实例对象自己的属性或方法了。
  72:  
  73:  
  74:      */
  75:      //测试__proto__属性
  76:      //alert(c.__proto__)//FF:object IE8:undefined 该属性指向Circle.prototype，也就是说调用该对象将会返回Circle的prototype属性。
  77:      //由于IE8及以下版本不支持__proto__属性，所以以下结果都在FF下得出。
  78:      //alert(c.__proto__.PI)//undefined 因为函数原型下面没有PI属性，PI是类函数Circle的直接属性
  79:      //alert(c.__proto__.area(3))//NaN 该函数执行成功，返回值为NaN是由于函数体中的this.r为undefined。
  80:   
  81:      /*结论：__proto__属性保存了对创建该对象的构造函数引用prototype属性的引用，也就是说构造函数可以引用prototype，基于该构
  82:      造函数生成的实例也可以引用，只不过引用的方式不一样。*/
  83:  </script>