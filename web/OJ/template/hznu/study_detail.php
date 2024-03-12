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

$studytags = [
  'HelloWorld!', '基础数据类型', '输入输出', '控制结构', '顺序结构', '选择结构',
  '循环结构', '数组', '一维数组', '二维数组', '字符串', '函数', '函数基础', '自定义函数', '内置函数',
  '指针', '指针基础', '指针数组', '函数指针', '结构体', '结构体基础', '结构体数组', '综合'
];

$studydata = array();
$studydata = array_fill_keys($studytags, 0);
//studydata['xx']=0;

$sql = "SELECT pt.tag, COUNT(DISTINCT s.problem_id) as count
        FROM solution s
        JOIN problem_tag pt ON s.problem_id = pt.problem_id
        WHERE s.user_id = '$user' AND s.result = 4 AND pt.tag IN ('" . implode("', '", $studytags) . "')
        GROUP BY pt.tag;";
$result = $mysqli->query($sql);

while ($row = $result->fetch_assoc()) {
  $studydata[$row['tag']] = $row['count'];
}

$result->free();

//此处修改层次颜色
$color = ['#cccccc', '#d0f0c0', '#aadf8f', '#85c96e'];

$neednum = array();
$neednum = array_fill_keys($studytags, [0, 1, 2, 4]);
//neednum['xx'] = [0, 1, 2, 4];

$nowcolor = array();

foreach ($studytags as $tag) {
  for ($i = 3; $i >= 0; $i--) {
    if ($studydata[$tag] >= $neednum[$tag][$i]) {
      $nowcolor[$tag] = $color[$i];
      break;
    }
  }
}

$pointposition = [
  'HelloWorld!' => ['x' => 0, 'y' => 400],
  '基础数据类型' => ['x' => 150, 'y' => 400],
  '输入输出' => ['x' => 300, 'y' => 400],
  '控制结构' => ['x' => 450, 'y' => 400],
  '数组' => ['x' => 800, 'y' => 130],
  '函数' => ['x' => 800, 'y' => 310],
  '指针' => ['x' => 800, 'y' => 490],
  '结构体' => ['x' => 800, 'y' => 670],
  '综合' => ['x' => 1250, 'y' => 400],

  '顺序结构' => ['x' => 535, 'y' => 290],
  '选择结构' => ['x' => 600, 'y' => 400],
  '循环结构' => ['x' => 535, 'y' => 510],
  '一维数组' => ['x' => 700, 'y' => 210],
  '二维数组' => ['x' => 800, 'y' => 210],
  '字符串' => ['x' => 900, 'y' => 210],
  '函数基础' => ['x' => 700, 'y' => 390],
  '自定义函数' => ['x' => 800, 'y' => 390],
  '内置函数' => ['x' => 900, 'y' => 390],
  '指针基础' => ['x' => 700, 'y' => 570],
  '指针数组' => ['x' => 800, 'y' => 570],
  '函数指针' => ['x' => 900, 'y' => 570],
  '结构体基础' => ['x' => 750, 'y' => 750],
  '结构体数组' => ['x' => 850, 'y' => 750]
];

?>

<style>
</style>

<div class="am-g" style="margin-top: 50px;">
  <div class="am-u-sm-12" style="display: flex; justify-content: center;">
    <div id="main" style="width: 1800px;height: 1000px;"></div>
  </div>
</div>


<div class="am-modal am-modal-no-btn" tabindex="-1" id="modal-study">
  <div class="am-modal-dialog" style="font-size: 20px; width:30%; border-radius: 20px" tabindex="-1" id="modal-study">

    <div class="am-modal-hd">Solved
      <a class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>

    <div class="am-modal-bd" id="modal-solved-bd">
      <i class="am-icon-spinner am-icon-pulse"></i> Loading...
    </div>

    <div class="am-modal-hd">Recommended
      <a class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>

    <div class="am-modal-bd" id="modal-recommend-bd">
      <i class="am-icon-spinner am-icon-pulse"></i> Loading...
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

  var links = [{
      source: 'HelloWorld!',
      target: '基础数据类型'
    },
    {
      source: '基础数据类型',
      target: '输入输出'
    },
    {
      source: '输入输出',
      target: '控制结构'
    },
    {
      source: '控制结构',
      target: '数组',
      lineStyle: {
        curveness: 0.5
      }
    },
    {
      source: '控制结构',
      target: '函数',
      lineStyle: {
        curveness: 0.2
      }
    },
    {
      source: '控制结构',
      target: '指针',
      lineStyle: {
        curveness: -0.2
      }
    },
    {
      source: '控制结构',
      target: '结构体',
      lineStyle: {
        curveness: -0.5
      }
    },
    {
      source: '控制结构',
      target: '顺序结构',
      lineStyle: {
        curveness: 0.3
      }
    },
    {
      source: '控制结构',
      target: '选择结构',
      lineStyle: {
        curveness: 0
      }
    },
    {
      source: '控制结构',
      target: '循环结构',
      lineStyle: {
        curveness: -0.3
      }
    },
    {
      source: '数组',
      target: '一维数组',
      lineStyle: {
        curveness: -0.3
      }
    },
    {
      source: '数组',
      target: '二维数组',
      lineStyle: {
        curveness: 0
      }
    },
    {
      source: '数组',
      target: '字符串',
      lineStyle: {
        curveness: 0.3
      }
    },
    {
      source: '函数',
      target: '函数基础',
      lineStyle: {
        curveness: -0.3
      }
    },
    {
      source: '函数',
      target: '自定义函数',
      lineStyle: {
        curveness: 0
      }
    },
    {
      source: '函数',
      target: '内置函数',
      lineStyle: {
        curveness: 0.3
      }
    },
    {
      source: '指针',
      target: '指针基础',
      lineStyle: {
        curveness: -0.3
      }
    },
    {
      source: '指针',
      target: '指针数组',
      lineStyle: {
        curveness: 0
      }
    },
    {
      source: '指针',
      target: '函数指针',
      lineStyle: {
        curveness: 0.3
      }
    },
    {
      source: '结构体',
      target: '结构体基础',
      lineStyle: {
        curveness: -0.2
      }
    },
    {
      source: '结构体',
      target: '结构体数组',
      lineStyle: {
        curveness: 0.2
      }
    }
  ];

  for (let i = 0; i < links.length; i++) {
    if (i < 7) {
      links[i].symbol = ['circle', 'arrow'];
    }
    links[i].tooltip = {
      show: false
    };
  }

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
        $count = 0;
        foreach ($pointposition as $tag => $position) {
          if ($count < 9) {
            echo "{name: '" . $tag . "',x: " . $position['x'] . ",y: " . $position['y'] .
              ",itemStyle: {color:'" . $nowcolor[$tag] . "'}},";
          } else {
            echo "{name: '" . $tag . "',x: " . $position['x'] . ",y: " . $position['y'] .
              ", symbol:'circle', symbolSize: 78, itemStyle: {color:'" . $nowcolor[$tag] . "'}},";
          }
          $count++;
        }
        ?>
      ],
      links: links,
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
        var solvedLinks = data.solved.map(function(problemId, index) {
          return '<a href="/OJ/problem.php?id=' + problemId + '">' + problemId + '</a>';
        });
        if (solvedLinks.length === 0) {
          solvedLinks = ['No Problem Now!'];
        }
        $('#modal-solved-bd').html(solvedLinks.join(' '));

        // 生成未解决问题的链接
        var unsolvedLinks = data.unsolved.map(function(problemId, index) {
          return '<a href="/OJ/problem.php?id=' + problemId + '">' + problemId + '</a>';
        });
        if (unsolvedLinks.length === 0) {
          unsolvedLinks = ['No Problem Now!'];
        }
        $('#modal-recommend-bd').html(unsolvedLinks.join(' '));

        // 更新弹窗标题
        $('.am-modal-hd').eq(0).text('Solved (' + data.solved.length + ')');
        $('.am-modal-hd').eq(1).text('Recommended (' + data.unsolved.length + ')');

        // 显示弹窗
        $('#modal-study').modal();
      }
    });
  });
</script>
