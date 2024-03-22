<?php

$studytags = [
  'HelloWorld!', '基础数据类型', '运算符', '控制结构', '函数', '指针', '结构体', '数组',

  '基础输入输出', '转义字符', '整型', '浮点型', '字符型', '算术运算', '逻辑运算', '二进制运算',
  '函数指针', '指针数组', '指针基础', '结构体数组', '结构体基础', '递归函数', '内置函数', '自定义函数', '函数基础',
  '一维数组', '字符串', '二维数组', '找最值', '排序', '二分',
  '顺序结构', '选择结构', '循环结构', '科学计算', '单分支', '多分支', '多重循环', '迭代', '枚举'
];


//三级tag
$studytags1 = [
  'HelloWorld!', '基础数据类型', '运算符', '控制结构', '函数', '指针', '结构体', '数组'
];
$studytags2 = [
  '基础输入输出', '转义字符', '整型', '浮点型', '字符型', '算术运算', '逻辑运算', '二进制运算',
  '函数指针', '指针数组', '指针基础', '结构体数组', '结构体基础', '递归函数', '内置函数', '自定义函数', '函数基础',
  '一维数组', '字符串', '二维数组', '顺序结构', '选择结构', '循环结构',
];
$studytags3 = [
  '找最值', '排序', '二分', '科学计算', '单分支', '多分支', '多重循环', '迭代', '枚举'
];

$fatarray = [
  'HelloWorld!', '基础数据类型', '运算符', '控制结构', '函数', '指针', '结构体', '数组', '一维数组', '顺序结构', '选择结构', '循环结构',
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

$tagfa['基础输入输出'] = 'HelloWorld!';
$tagfa['转义字符'] = 'HelloWorld!';
$tagfa['整型'] = '基础数据类型';
$tagfa['浮点型'] = '基础数据类型';
$tagfa['字符型'] = '基础数据类型';
$tagfa['算术运算'] = '运算符';
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
  'HelloWorld!' => ['x' => 200, 'y' => 480],
  '基础数据类型' => ['x' => 403, 'y' => 430],
  '运算符' => ['x' => 530, 'y' => 225],
  '控制结构' => ['x' => 520, 'y' => 600],
  '函数' => ['x' => 740, 'y' => 640],
  '指针' => ['x' => 693, 'y' => 257],
  '结构体' => ['x' => 803, 'y' => 365],
  '数组' => ['x' => 780, 'y' => 515],

  '基础输入输出' => ['x' => 343, 'y' => 590],
  '转义字符' => ['x' => 308, 'y' => 670],

  '整型' => ['x' => 285, 'y' => 270],
  '浮点型' => ['x' => 321, 'y' => 200],
  '字符型' => ['x' => 403, 'y' => 250],

  '算术运算' => ['x' => 383, 'y' => 100],
  '逻辑运算' => ['x' => 483, 'y' => 120],
  '二进制运算' => ['x' => 553, 'y' => 105],

  '指针基础' => ['x' => 648, 'y' => 120],
  '指针数组' => ['x' => 753, 'y' => 60],
  '函数指针' => ['x' => 823, 'y' => 130],

  '结构体基础' => ['x' => 993, 'y' => 160],
  '结构体数组' => ['x' => 1075, 'y' => 290],

  '一维数组' => ['x' => 884, 'y' => 480],
  '字符串' => ['x' => 957, 'y' => 670],
  '二维数组' => ['x' => 945, 'y' => 805],
  '找最值' => ['x' => 976, 'y' => 405],
  '排序' => ['x' => 1058, 'y' => 505],
  '二分' => ['x' => 1008, 'y' => 565],

  '函数基础' => ['x' => 820, 'y' => 752],
  '自定义函数' => ['x' => 760, 'y' => 756],
  '内置函数' => ['x' => 700, 'y' => 820],
  '递归函数' => ['x' => 650, 'y' => 720],

  '顺序结构' => ['x' => 368, 'y' => 705],
  '选择结构' => ['x' => 448, 'y' => 743],
  '循环结构' => ['x' => 523, 'y' => 735],

  '科学计算' => ['x' => 280, 'y' => 770],
  '单分支' => ['x' => 366, 'y' => 843],
  '多分支' => ['x' => 414, 'y' => 850],
  '多重循环' => ['x' => 488, 'y' => 850],
  '迭代' => ['x' => 541, 'y' => 830],
  '枚举' => ['x' => 600, 'y' => 855],
];

?>


<script>
  // var buscolor = '#F59A23';
  var fatarray = [
    'HelloWorld!', '基础数据类型', '运算符', '控制结构', '函数', '指针', '结构体', '数组', '一维数组', '顺序结构', '选择结构', '循环结构',
  ];

  var links = [{ ///////////hello
      source: 'HelloWorld!',
      target: '基础输入输出',
      tooltip: {
        show: false
      }
    },
    {
      source: 'HelloWorld!',
      target: '转义字符',
      tooltip: {
        show: false
      }
    }, ///////////基础数据类型
    {
      source: '基础数据类型',
      target: '整型',
      tooltip: {
        show: false
      }
    },
    {
      source: '基础数据类型',
      target: '字符型',
      tooltip: {
        show: false
      }
    },
    {
      source: '基础数据类型',
      target: '浮点型',
      tooltip: {
        show: false
      }
    }, ///////////运算符
    {
      source: '运算符',
      target: '二进制运算',
      tooltip: {
        show: false
      }
    },
    {
      source: '运算符',
      target: '算术运算',
      tooltip: {
        show: false
      }
    },
    {
      source: '运算符',
      target: '逻辑运算',
      tooltip: {
        show: false
      }
    }, ///////////指针
    {
      source: '指针',
      target: '指针基础',
      tooltip: {
        show: false
      }
    },
    {
      source: '指针',
      target: '指针数组',
      tooltip: {
        show: false
      }
    },
    {
      source: '指针',
      target: '函数指针',
      tooltip: {
        show: false
      }
    }, ///////////结构体
    {
      source: '结构体',
      target: '结构体基础',
      tooltip: {
        show: false
      }
    },
    {
      source: '结构体',
      target: '结构体数组',
      tooltip: {
        show: false
      }
    }, ///////////数组
    {
      source: '数组',
      target: '一维数组',
      tooltip: {
        show: false
      }
    },
    {
      source: '数组',
      target: '字符串',
      tooltip: {
        show: false
      }
    },
    {
      source: '数组',
      target: '二维数组',
      tooltip: {
        show: false
      }
    },
    {
      source: '一维数组',
      target: '找最值',
      tooltip: {
        show: false
      }
    },
    {
      source: '一维数组',
      target: '排序',
      tooltip: {
        show: false
      }
    },
    {
      source: '一维数组',
      target: '二分',
      tooltip: {
        show: false
      }
    }, ///////////函数
    {
      source: '函数',
      target: '函数基础',
      tooltip: {
        show: false
      }
    },
    {
      source: '函数',
      target: '自定义函数',
      tooltip: {
        show: false
      }
    },
    {
      source: '函数',
      target: '内置函数',
      tooltip: {
        show: false
      }
    },
    {
      source: '函数',
      target: '递归函数',
      tooltip: {
        show: false
      }
    }, ///////////控制结构
    {
      source: '控制结构',
      target: '顺序结构',
      tooltip: {
        show: false
      }
    },
    {
      source: '控制结构',
      target: '选择结构',
      tooltip: {
        show: false
      }
    },
    {
      source: '控制结构',
      target: '循环结构',
      tooltip: {
        show: false
      }
    },
    {
      source: '顺序结构',
      target: '科学计算',
      tooltip: {
        show: false
      }
    },
    {
      source: '选择结构',
      target: '单分支',
      tooltip: {
        show: false
      }
    },
    {
      source: '选择结构',
      target: '多分支',
      tooltip: {
        show: false
      }
    },
    {
      source: '循环结构',
      target: '多重循环',
      tooltip: {
        show: false
      }
    },
    {
      source: '循环结构',
      target: '迭代',
      tooltip: {
        show: false
      }
    },
    {
      source: '循环结构',
      target: '枚举',
      tooltip: {
        show: false
      }
    },
    ///////////////////////

    {
      source: '基础数据类型',
      target: '运算符',
      tooltip: {
        show: false
      },
    },
    {
      source: '基础数据类型',
      target: '指针',
      tooltip: {
        show: false
      }
    },
    {
      source: '基础数据类型',
      target: '结构体',
      tooltip: {
        show: false
      }
    },
    {
      source: '基础数据类型',
      target: '数组',
      tooltip: {
        show: false
      }
    },
    {
      source: 'HelloWorld!',
      target: '控制结构',
      tooltip: {
        show: false
      }
    },
    {
      source: 'HelloWorld!',
      target: '基础数据类型',
      tooltip: {
        show: false
      }
    },
    {
      source: '控制结构',
      target: '数组',
      tooltip: {
        show: false
      }
    },
    {
      source: '控制结构',
      target: '函数',
      tooltip: {
        show: false
      }
    },
  ];
  var additionalProperties = {
    lineStyle: {
      opacity: 0.9,
      width: 2.5,
      curveness: 0.1,
      color: '#31b6f9'
    },
    symbol: ['circle', 'arrow']
  };

  // 从后向前遍历数组，添加属性
  for (var i = links.length - 1; i >= links.length - 8; i--) {
    if (links[i]) {
      Object.assign(links[i], additionalProperties);
    }
  }
</script>