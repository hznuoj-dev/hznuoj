<?php
require_once("header.php");
?>

<?php
if ($_GET['user']) $user = $_GET['user'];
else $user = $_POST['study_detail'];
if (!is_valid_user_name($user)) {
  echo "No such User!";
  exit(0);
}
$studydata = array();
$studydata['HelloWorld!'] = 0; //此处修改
$studydata['基础数据类型'] = 0;
$studydata['输入输出'] = 0;
$studydata['控制结构'] = 0;
$studydata['顺序结构'] = 0;
$studydata['选择结构'] = 0;
$studydata['循环结构'] = 0;
$studydata['数组'] = 0;
$studydata['一维数组'] = 0;
$studydata['二维数组'] = 0;
$studydata['字符串'] = 0;
$studydata['函数'] = 0;
$studydata['函数基础'] = 0;
$studydata['自定义函数'] = 0;
$studydata['内置函数'] = 0;
$studydata['指针'] = 0;
$studydata['指针基础'] = 0;
$studydata['指针数组'] = 0;
$studydata['函数指针'] = 0;
$studydata['结构体'] = 0;
$studydata['结构体基础'] = 0;
$studydata['结构体数组'] = 0;
$studydata['综合'] = 0;

//此处修改
$sql = "SELECT pt.tag, COUNT(DISTINCT s.problem_id) as count
        FROM solution s
        JOIN problem_tag pt ON s.problem_id = pt.problem_id
        WHERE s.user_id = '$user' AND s.result = 4 AND pt.tag IN ('HelloWorld!', '基础数据类型', '输入输出', '控制结构', '顺序结构', '选择结构', '循环结构', '数组', '一维数组', '二维数组', '字符串', '函数', '函数基础', '自定义函数', '内置函数', '指针', '指针基础', '指针数组', '函数指针', '结构体', '结构体基础', '结构体数组', '综合')
        GROUP BY pt.tag;";
$result = $mysqli->query($sql);

while ($row = $result->fetch_assoc()) {
  $studydata[$row['tag']] = $row['count'];
}

$result->free();

//此处修改层次颜色
$color = ['#cccccc', '#d0f0c0', '#aadf8f', '#85c96e'];

$neednum['HelloWorld!'] = [0, 1, 4, 6];
$neednum['基础数据类型'] = [0, 1, 1, 6];
$neednum['输入输出'] = [0, 1, 1, 1];
$neednum['控制结构'] = [0, 1, 1, 1];
$neednum['顺序结构'] = [0, 2, 4, 6];
$neednum['选择结构'] = [0, 2, 4, 6];
$neednum['循环结构'] = [0, 2, 4, 6];
$neednum['数组'] = [0, 2, 4, 6];
$neednum['一维数组'] = [0, 2, 4, 6];
$neednum['二维数组'] = [0, 2, 4, 6];
$neednum['字符串'] = [0, 2, 4, 6];
$neednum['函数'] = [0, 2, 4, 6];
$neednum['函数基础'] = [0, 2, 4, 6];
$neednum['自定义函数'] = [0, 2, 4, 6];
$neednum['内置函数'] = [0, 2, 4, 6];
$neednum['指针'] = [0, 2, 4, 6];
$neednum['指针基础'] = [0, 2, 4, 6];
$neednum['指针数组'] = [0, 2, 4, 6];
$neednum['函数指针'] = [0, 2, 4, 6];
$neednum['结构体'] = [0, 2, 4, 6];
$neednum['结构体基础']  = [0, 2, 4, 6];
$neednum['结构体数组']  = [0, 2, 4, 6];
$neednum['综合'] = [0, 2, 4, 6];
?>

<div class="am-g" style="margin-top: 50px;">
  <div class="am-u-sm-12" style="display: flex; justify-content: center;">
    <div id="main" style="width: 1800px;height:1000px;"></div>
  </div>
</div>
<div class="am-u-sm-12">
  <div style="margin-bottom: 10px;">知识点推荐题目（点击知识点获取）：</div>
  <div class="am-panel am-panel-default">
    <div class="am-panel-hd"><b>Solved:</b></div>
    <div class="am-panel-bd">
      <p></p>
    </div>
  </div>
  <div class="am-panel am-panel-default">
    <div class="am-panel-hd"><b>Recommended:</b></div>
    <div class="am-panel-bd">
      <p></p>
    </div>
  </div>
</div>



<?php require_once("footer.php") ?>

<script src="../../plugins/echarts/echarts.min.5.4.js"></script>
<script type="text/javascript">
  var chartDom = document.getElementById('main');
  var myChart = echarts.init(chartDom);
  var option;


  var studydata = <?php echo json_encode($studydata); ?>;
  var neednum = <?php echo json_encode($neednum); ?>;


  option = {
    title: {
      text: '学习里程碑'
    },
    tooltip: {
      formatter: function(params) {
        return '标签：' + params.data.name + '<br/>已完成：' +
          studydata[params.data.name] + '<br/>目标：' + neednum[params.data.name];
      }
    },
    animationDurationUpdate: 1500,
    animationEasingUpdate: 'quinticInOut',
    series: [{
      width: "90%",
      type: 'graph',
      layout: 'none',
      symbol: "roundRect",
      symbolSize: 90,
      roam: false,
      label: {
        show: true,
        fontSize: 15
      },
      edgeSymbolSize: [0, 10],
      edgeLabel: {
        fontSize: 30
      },
      data: [
        <?php
        for ($i = 3; $i >= 0; $i--) {
          if ($studydata['HelloWorld!'] >= $neednum['HelloWorld!'][$i]) {
            $nowcolor = $color[$i];
            break;
          }
        }
        echo "{name: 'HelloWorld!',x: 0,y: 400,itemStyle: {color:'$nowcolor'}},"
        ?> {
          name: '基础数据类型',
          x: 150,
          y: 400,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['基础数据类型'] >= $neednum['基础数据类型'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '输入输出',
          x: 300,
          y: 400,

          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['输入输出'] >= $neednum['输入输出'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '控制结构',
          x: 450,
          y: 400,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['控制结构'] >= $neednum['控制结构'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '数组',
          x: 800,
          y: 130,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['数组'] >= $neednum['数组'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '函数',
          x: 800,
          y: 310,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['函数'] >= $neednum['函数'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '指针',
          x: 800,
          y: 490,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['指针'] >= $neednum['指针'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '结构体',
          x: 800,
          y: 670,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['结构体'] >= $neednum['结构体'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '综合',
          x: 1250,
          y: 400,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['综合'] >= $neednum['综合'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '顺序结构',
          x: 535,
          y: 290,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['顺序结构'] >= $neednum['顺序结构'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '选择结构',
          x: 600,
          y: 400,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['选择结构'] >= $neednum['选择结构'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '循环结构',
          x: 535,
          y: 510,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['循环结构'] >= $neednum['循环结构'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '一维数组',
          x: 700,
          y: 210,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['一维数组'] >= $neednum['一维数组'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '二维数组',
          x: 800,
          y: 210,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['二维数组'] >= $neednum['二维数组'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '字符串',
          x: 900,
          y: 210,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['字符串'] >= $neednum['字符串'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '函数基础',
          x: 700,
          y: 390,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['函数基础'] >= $neednum['函数基础'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '自定义函数',
          x: 800,
          y: 390,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['自定义函数'] >= $neednum['自定义函数'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '内置函数',
          x: 900,
          y: 390,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['内置函数'] >= $neednum['内置函数'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '指针基础',
          x: 700,
          y: 570,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['指针基础'] >= $neednum['指针基础'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '指针数组',
          x: 800,
          y: 570,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['指针数组'] >= $neednum['指针数组'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '函数指针',
          x: 900,
          y: 570,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['函数指针'] >= $neednum['函数指针'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '结构体基础',
          x: 750,
          y: 750,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['结构体基础'] >= $neednum['结构体基础'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        },
        {
          name: '结构体数组',
          x: 850,
          y: 750,
          symbol: "circle",
          symbolSize: 78,
          itemStyle: {
            color: '<?php
                    for ($i = 3; $i >= 0; $i--) {
                      if ($studydata['结构体数组'] >= $neednum['结构体数组'][$i]) {
                        echo $color[$i];
                        break;
                      }
                    }
                    ?>'
          }
        }
      ],
      links: [{
          source: 'HelloWorld!', //此处修改
          target: '基础数据类型',
          symbol: ['circle', 'arrow'],
          tooltip: {
            show: false
          }
        },
        {
          source: '基础数据类型',
          target: '输入输出',
          symbol: ['circle', 'arrow'],
          tooltip: {
            show: false
          }
        },
        {
          source: '输入输出',
          target: '控制结构',
          symbol: ['circle', 'arrow'],
          tooltip: {
            show: false
          }
        },
        {
          source: '控制结构',
          target: '数组',
          symbol: ['circle', 'arrow'],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: 0.5
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '控制结构',
          target: '函数',
          symbol: ['circle', 'arrow'],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: 0.2
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '控制结构',
          target: '指针',
          symbol: ['circle', 'arrow'],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: -0.2
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '控制结构',
          target: '结构体',
          symbol: ['circle', 'arrow'],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: -0.5
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '控制结构',
          target: '顺序结构',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: 0.3
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '控制结构',
          target: '选择结构',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: 0
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '控制结构',
          target: '循环结构',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: -0.3
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '数组',
          target: '一维数组',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: -0.3
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '数组',
          target: '二维数组',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: 0
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '数组',
          target: '字符串',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: 0.3
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '函数',
          target: '函数基础',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: -0.3
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '函数',
          target: '自定义函数',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: 0
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '函数',
          target: '内置函数',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: 0.3
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '指针',
          target: '指针基础',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: -0.3
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '指针',
          target: '指针数组',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: 0
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '指针',
          target: '函数指针',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: 0.3
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '结构体',
          target: '结构体基础',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: -0.2
          },
          tooltip: {
            show: false
          }
        },
        {
          source: '结构体',
          target: '结构体数组',
          symbol: ['circle', ''],
          lineStyle: {
            opacity: 0.9,
            width: 2,
            curveness: 0.2
          },
          tooltip: {
            show: false
          }
        }
      ],
      lineStyle: {
        opacity: 0.9,
        width: 2,
        curveness: 0,
      }
    }]
  };

  option && myChart.setOption(option);

  myChart.on('click', function(params) {
    // 获取被点击的标签
    var tag = params.data.name;

    // 发送 AJAX 请求
    $.ajax({
      url: 'getstudyproblems.php',
      type: 'POST',
      data: {
        user: '<?php echo $user; ?>',
        tag: tag
      },
      success: function(response) {
        var data = JSON.parse(response);

        // 生成已解决问题的链接
        var solvedLinks = data.solved.map(function(problemId) {
          return '<a href="/OJ/problem.php?id=' + problemId + '">' + problemId + '</a>';
        });
        $('.am-panel-bd').eq(0).html(solvedLinks.join(' '));

        // 生成未解决问题的链接
        var unsolvedLinks = data.unsolved.map(function(problemId) {
          return '<a href="/OJ/problem.php?id=' + problemId + '">' + problemId + '</a>';
        });
        $('.am-panel-bd').eq(1).html(unsolvedLinks.join(' '));
      }
    });
  });
</script>
