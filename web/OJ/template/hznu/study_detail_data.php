<?php

$studytags = [
  'HelloWorld!', '基础数据类型', '运算符', '控制结构', '函数', '指针', '结构体', '数组',

  '基础输入输出', '转义字符', '整型', '浮点型', '字符型', '加减乘除运算', '逻辑运算', '二进制运算',
  '函数指针', '指针数组', '指针基础', '结构体数组', '结构体基础', '递归函数', '内置函数', '自定义函数', '函数基础',
  '一维数组', '字符串', '二维数组', '找最值', '排序', '二分',
  '顺序结构', '选择结构', '循环结构', '科学计算', '单分支', '多分支', '多重循环', '迭代', '枚举'
];


//三级tag
$studytags1 = [
  'HelloWorld!', '基础数据类型', '运算符', '控制结构', '函数', '指针', '结构体', '数组'
];
$studytags2 = [
  '基础输入输出', '转义字符', '整型', '浮点型', '字符型', '加减乘除运算', '逻辑运算', '二进制运算',
  '函数指针', '指针数组', '指针基础', '结构体数组', '结构体基础', '递归函数', '内置函数', '自定义函数', '函数基础',
  '一维数组', '字符串', '二维数组', '顺序结构', '选择结构', '循环结构',
];
$studytags3 = [
  '找最值', '排序', '二分', '科学计算', '单分支', '多分支', '多重循环', '迭代', '枚举'
];

$tagfa['找最值'] = '一维数组';
$tagfa['排序'] = '一维数组';
$tagfa['二分'] = '一维数组';
$tagfa['科学计算'] = '顺序结构';
$tagfa['单分支'] = '选择结构';
$tagfa['多分支'] = '选择结构';
$tagfa['多重循环'] = '循环结构';
$tagfa['迭代'] = '循环结构';
$tagfa['枚举'] = '循环结构';

$tagfa['基础输入输出'] = 'HelloWorld';
$tagfa['转义字符'] = 'HelloWorld';
$tagfa['整型'] = '基础数据类型';
$tagfa['浮点型'] = '基础数据类型';
$tagfa['字符型'] = '基础数据类型';
$tagfa['加减乘除运算'] = '运算符';
$tagfa['逻辑运算'] = '运算符';
$tagfa['二进制运算'] = '运算符';
$tagfa['函数基础'] = '函数';
$tagfa['自定义函数'] = '函数';
$tagfa['内置函数'] = '函数';
$tagfa['递归函数'] = '函数';
$tagfa['函数指针'] = '指针';
$tagfa['指针数组'] = '指针';
$tagfa['指针基础'] = '指针';
$tagfa['顺序结构'] = '控制结构';
$tagfa['选择结构'] = '控制结构';
$tagfa['循环结构'] = '控制结构';
$tagfa['结构体基础'] = '结构体';
$tagfa['结构体数组'] = '结构体';
$tagfa['一维数组'] = '数组';
$tagfa['字符串'] = '数组';
$tagfa['二维数组'] = '数组';




$pointposition = [
  'HelloWorld!' => ['x' => 0, 'y' => 400],
  '基础数据类型' => ['x' => 500, 'y' => 400],
  '运算符' => ['x' => 1000, 'y' => 400],
  '控制结构' => ['x' => 1700, 'y' => 400],
  '函数' => ['x' => 1350, 'y' => 200],
  '指针' => ['x' => 2100, 'y' => 200],
  '结构体' => ['x' => 2850, 'y' => 200],
  '数组' => ['x' => 2800, 'y' => 400],

  '基础输入输出' => ['x' => -100, 'y' => 525],
  '转义字符' => ['x' => 100, 'y' => 525],

  '整型' => ['x' => 350, 'y' => 200],
  '浮点型' => ['x' => 500, 'y' => 200],
  '字符型' => ['x' => 650, 'y' => 200],

  '加减乘除运算' => ['x' => 850, 'y' => 525],
  '逻辑运算' => ['x' => 1000, 'y' => 525],
  '二进制运算' => ['x' => 1150, 'y' => 525],

  '指针基础' => ['x' => 1925, 'y' => 50],
  '指针数组' => ['x' => 2100, 'y' => 50],
  '函数指针' => ['x' => 2275, 'y' => 50],

  '结构体基础' => ['x' => 2725, 'y' => 50],
  '结构体数组' => ['x' => 2975, 'y' => 50],

  '一维数组' => ['x' => 2600, 'y' => 525],
  '字符串' => ['x' => 2800, 'y' => 525],
  '二维数组' => ['x' => 3000, 'y' => 525],
  '找最值' => ['x' => 2450, 'y' => 650],
  '排序' => ['x' => 2600, 'y' => 650],
  '二分' => ['x' => 2750, 'y' => 650],

  '函数基础' => ['x' => 1100, 'y' => 50],
  '自定义函数' => ['x' => 1275, 'y' => 50],
  '内置函数' => ['x' => 1425, 'y' => 50],
  '递归函数' => ['x' => 1600, 'y' => 50],

  '顺序结构' => ['x' => 1400, 'y' => 525],
  '选择结构' => ['x' => 1650, 'y' => 525],
  '循环结构' => ['x' => 2000, 'y' => 525],

  '科学计算' => ['x' => 1400, 'y' => 650],
  '单分支' => ['x' => 1585, 'y' => 650],
  '多分支' => ['x' => 1715, 'y' => 650],
  '多重循环' => ['x' => 1875, 'y' => 650],
  '迭代' => ['x' => 2000, 'y' => 650],
  '枚举' => ['x' => 2125, 'y' => 650],
];

$breakpoint = [
  'b_HelloWorld!' => ['x' => 0, 'y' => 455],
  'b_基础输入输出' => ['x' => -100, 'y' => 455],
  'b_转义字符' => ['x' => 100, 'y' => 455],

  'b_基础数据类型' => ['x' => 500, 'y' => 300],
  'b_整型' => ['x' => 350, 'y' => 300],
  'b_字符型' => ['x' => 650, 'y' => 300],

  'b_运算符' => ['x' => 1000, 'y' => 450],
  'b_加减乘除运算' => ['x' => 850, 'y' => 450],
  'b_二进制运算' => ['x' => 1150, 'y' => 450],

  'b_函数' => ['x' => 1350, 'y' => 135],
  'b_函数基础' => ['x' => 1100, 'y' => 135],
  'b_自定义函数' => ['x' => 1275, 'y' => 135],
  'b_内置函数' => ['x' => 1425, 'y' => 135],
  'b_递归函数' => ['x' => 1600, 'y' => 135],

  'b_控制结构' => ['x' => 1700, 'y' => 450],
  'b_顺序结构' => ['x' => 1400, 'y' => 450],
  'b_选择结构' => ['x' => 1650, 'y' => 450],
  'b_循环结构' => ['x' => 2000, 'y' => 450],

  'b_选择结构_bottom' => ['x' => 1650, 'y' => 575],
  'b_单分支' => ['x' => 1585, 'y' => 575],
  'b_多分支' => ['x' => 1715, 'y' => 575],

  'b_循环结构_bottom' => ['x' => 2000, 'y' => 575],
  'b_多重循环' => ['x' => 1875, 'y' => 575],
  'b_枚举' => ['x' => 2125, 'y' => 575],

  'b_指针' => ['x' => 2100, 'y' => 135],
  'b_指针基础' => ['x' => 1925, 'y' => 135],
  'b_函数指针' => ['x' => 2275, 'y' => 135],

  'b_结构体' => ['x' => 2850, 'y' => 135],
  'b_结构体基础' => ['x' => 2725, 'y' => 135],
  'b_结构体数组' => ['x' => 2975, 'y' => 135],

  'b_数组' => ['x' => 2800, 'y' => 450],
  'b_一维数组' => ['x' => 2600, 'y' => 450],
  'b_二维数组' => ['x' => 3000, 'y' => 450],

  'b_一维数组_bottom' => ['x' => 2600, 'y' => 575],
  'b_找最值' => ['x' => 2450, 'y' => 575],
  'b_二分' => ['x' => 2750, 'y' => 575],

  'b_基础数据类型_right' => ['x' => 800, 'y' => 400],
  'b_基础数据类型_right_top' => ['x' => 800, 'y' => 300],
  'b_函数_bottom' => ['x' => 1350, 'y' => 300],
  'b_指针_bottom' => ['x' => 2100, 'y' => 300],
  'b_数组_top' => ['x' => 2800, 'y' => 300],
  'b_结构体_bottom' => ['x' => 2850, 'y' => 300],
];
?>


<script>
  // var buscolor = '#F59A23';
  var buscolor = '#996633';
  var buswidth = 5;

  var links = [
    //HelloWorld!
    {
      source: 'HelloWorld!',
      target: 'b_HelloWorld!',
      tooltip: {
        show: false
      }
    },
    {
      source: 'b_HelloWorld!',
      target: 'b_基础输入输出',
      tooltip: {
        show: false
      }
    },
    {
      source: 'b_HelloWorld!',
      target: 'b_转义字符',
      tooltip: {
        show: false
      }
    },
    {
      source: 'b_基础输入输出',
      target: '基础输入输出',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_转义字符',
      target: '转义字符',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    //基础数据类型
    {
      source: '基础数据类型',
      target: 'b_基础数据类型',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_基础数据类型',
      target: 'b_整型',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_基础数据类型',
      target: 'b_字符型',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_基础数据类型',
      target: '浮点型',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_整型',
      target: '整型',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_字符型',
      target: '字符型',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    //运算符
    {
      source: '运算符',
      target: 'b_运算符',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_运算符',
      target: 'b_加减乘除运算',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_运算符',
      target: 'b_二进制运算',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_运算符',
      target: '逻辑运算',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_加减乘除运算',
      target: '加减乘除运算',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_二进制运算',
      target: '二进制运算',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    //函数
    {
      source: '函数',
      target: 'b_函数',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_自定义函数',
      target: 'b_函数基础',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_函数',
      target: 'b_自定义函数',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_函数',
      target: 'b_内置函数',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_内置函数',
      target: 'b_递归函数',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_函数基础',
      target: '函数基础',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_自定义函数',
      target: '自定义函数',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_内置函数',
      target: '内置函数',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_递归函数',
      target: '递归函数',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    //控制结构
    {
      source: '控制结构',
      target: 'b_控制结构',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_顺序结构',
      target: 'b_选择结构',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_选择结构',
      target: 'b_控制结构',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_控制结构',
      target: 'b_循环结构',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_顺序结构',
      target: '顺序结构',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_选择结构',
      target: '选择结构',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_循环结构',
      target: '循环结构',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: '顺序结构',
      target: '科学计算',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: '选择结构',
      target: 'b_选择结构_bottom',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_单分支',
      target: 'b_选择结构_bottom',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_多分支',
      target: 'b_选择结构_bottom',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_单分支',
      target: '单分支',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_多分支',
      target: '多分支',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: '循环结构',
      target: 'b_循环结构_bottom',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_循环结构_bottom',
      target: 'b_多重循环',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_循环结构_bottom',
      target: 'b_枚举',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_多重循环',
      target: '多重循环',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_循环结构_bottom',
      target: '迭代',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_枚举',
      target: '枚举',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    //指针
    {
      source: '指针',
      target: 'b_指针',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_指针',
      target: 'b_指针基础',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_指针',
      target: 'b_函数指针',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_指针基础',
      target: '指针基础',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_指针',
      target: '指针数组',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_函数指针',
      target: '函数指针',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    //结构体
    {
      source: '结构体',
      target: 'b_结构体',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_结构体',
      target: 'b_结构体基础',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_结构体',
      target: 'b_结构体数组',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_结构体基础',
      target: '结构体基础',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_结构体数组',
      target: '结构体数组',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    //数组
    {
      source: '数组',
      target: 'b_数组',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_一维数组',
      target: 'b_数组',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_二维数组',
      target: 'b_数组',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_一维数组',
      target: '一维数组',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_数组',
      target: '字符串',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_二维数组',
      target: '二维数组',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: '一维数组',
      target: 'b_一维数组_bottom',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_一维数组_bottom',
      target: 'b_找最值',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_一维数组_bottom',
      target: 'b_二分',
      tooltip: {
        show: false
      },
    },
    {
      source: 'b_找最值',
      target: '找最值',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_一维数组_bottom',
      target: '排序',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    {
      source: 'b_二分',
      target: '二分',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
    },
    //总线
    {
      source: 'HelloWorld!',
      target: '基础数据类型',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: '基础数据类型',
      target: 'b_基础数据类型_right',
      tooltip: {
        show: false
      },
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: 'b_基础数据类型_right',
      target: '运算符',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: '控制结构',
      target: '数组',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: '运算符',
      target: '控制结构',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: 'b_基础数据类型_right',
      target: 'b_基础数据类型_right_top',
      tooltip: {
        show: false
      },
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: 'b_基础数据类型_right_top',
      target: 'b_函数_bottom',
      tooltip: {
        show: false
      },
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: 'b_函数_bottom',
      target: '函数',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: 'b_函数_bottom',
      target: 'b_指针_bottom',
      tooltip: {
        show: false
      },
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: 'b_指针_bottom',
      target: '指针',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: 'b_指针_bottom',
      target: 'b_数组_top',
      tooltip: {
        show: false
      },
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: 'b_数组_top',
      target: '数组',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: 'b_数组_top',
      target: 'b_结构体_bottom',
      tooltip: {
        show: false
      },
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
    {
      source: 'b_结构体_bottom',
      target: '结构体',
      tooltip: {
        show: false
      },
      symbol: ['circle', 'arrow'],
      lineStyle: {
        color: buscolor,
        width: buswidth
      },
    },
  ];
</script>