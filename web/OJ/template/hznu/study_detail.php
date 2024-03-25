<?php $title = "Study Detail";

require_once("header.php");

require_once("study_detail_data.php");
?>

<?php
$user = $_GET['user'];
if (!is_valid_user_name($user)) {
  echo "No such User!";
  exit(0);
}

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


///////////////////////////////////////////////////////////////////////////////cs
// if($user == "admin")
//   foreach ($studytags as $tag)
//     $studydata[$tag] = 1;

///////////////////////////////////////////////////////////////////////////////cs

//此处修改层次颜色
$color = ['#cccccc', '#d0f0c0', '#aadf8f', '#85c96e'];

$neednum = array();
$neednum = array_fill_keys($studytags, [0, 1, 2, 4]);
//neednum['xx'] = [0, 1, 2, 4];


foreach ($studytags3 as $tag) {
  $studydata[$tagfa[$tag]] = 0;
  $neednum[$tagfa[$tag]] = [0, 0, 0, 0];
}
foreach ($studytags2 as $tag) {
  $studydata[$tagfa[$tag]] = 0;
  $neednum[$tagfa[$tag]] = [0, 0, 0, 0];
}

foreach ($studytags3 as $tag) {
  $studydata[$tagfa[$tag]] += $studydata[$tag];
  $counts = $neednum[$tag];
  for ($i = 0; $i < count($counts); $i++) {
    $neednum[$tagfa[$tag]][$i] += $counts[$i];
  }
}
foreach ($studytags2 as $tag) {
  $studydata[$tagfa[$tag]] += $studydata[$tag];
  $counts = $neednum[$tag];
  for ($i = 0; $i < count($counts); $i++) {
    $neednum[$tagfa[$tag]][$i] += $counts[$i];
  }
}


$nowcolor = array();

foreach ($studytags as $tag) {
  for ($i = 3; $i >= 0; $i--) {
    if ($studydata[$tag] >= $neednum[$tag][$i]) {
      $nowcolor[$tag] = $color[$i];
      break;
    }
  }
}

?>

<style>
</style>

<div class="am-g" style="margin-top: 0px;">
  <div class="am-u-sm-12" style="justify-content: center;">
    <div id="main" style="width: 3100px;height: 700px;"></div>
  </div>
</div>

<?php
require_once("footer.php");
?>

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
  var width = chartDom.offsetWidth;
  var height = chartDom.offsetHeight;
  var myChart = echarts.init(chartDom);
  var option;

  var studydata = <?php echo json_encode($studydata); ?>;
  var neednum = <?php echo json_encode($neednum); ?>;

  option = {
    title: {
      text: '学习里程碑',
      left: "1%",
      top: "5%",
    },
    legend: {
      data: ["Newbie", "Learner", "Expert", "Master"],
      selectedMode: false, //控制是否可以点击
      left: "1%",
      top: "15%",
      width: "1%",
      textStyle: {
        color: "black",
        fontSize: 16
      }
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
      symbolSize: 70,
      roam: false,
      label: {
        show: true,
        fontSize: 12
      },
      edgeSymbolSize: [0, 14],
      edgeLabel: {
        fontSize: 30
      },
      categories: [{
        name: 'Newbie',
        itemStyle: {
          color: '#cccccc',
        }
      }, {
        name: 'Learner',
        itemStyle: {
          color: '#d0f0c0'
        }
      }, {
        name: 'Expert',
        itemStyle: {
          color: '#aadf8f'
        }
      }, {
        name: 'Master',
        itemStyle: {
          color: '#85c96e'
        }
      }],
      data: [
        <?php
        $count = 0;
        foreach ($pointposition as $tag => $position) {
          if ($count < 8) { //前八个方形
            echo "{name: '" . $tag . "',x: " . $position['x'] . ",y: " . $position['y'] .
              ",itemStyle: {color:'" . $nowcolor[$tag] . "' }},";
          } else {
            echo "{name: '" . $tag . "',x: " . $position['x'] . ",y: " . $position['y'] .
              ", symbol:'circle', symbolSize: 70, itemStyle: {color:'" . $nowcolor[$tag] . "'}},";
          }
          $count++;
        }
        foreach ($breakpoint as $tag => $position) {
          echo "{name: '" . $tag . "',x: " . $position['x'] . ",y: " . $position['y'] .
            ", symbol:'circle', symbolSize: 0, tooltip: { show: false }, label: {show: false}},";
        }
        ?>
      ],
      links: links,
      lineStyle: {
        opacity: 0.9,
        width: 3,
        curveness: 0,
        color: '#996633'
      }
    }, ]
  };

  option && myChart.setOption(option);

  myChart.on('click', function(params) {

    if (params.dataType === 'node' && params.data.name[0] != 'b') {
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
          var solvedLinks = data.solved.map(function(problem, index) {
            var score = problem.score;
            var colorClass;
            if (score <= 20) colorClass = 'am-badge-success';
            else if (score <= 40) colorClass = 'am-badge-secondary';
            else if (score <= 60) colorClass = 'am-badge-primary';
            else if (score <= 80) colorClass = 'am-badge-warning';
            else colorClass = 'am-badge-danger';
            return '<span class="am-badge ' + colorClass + ' am-round">' +
              '<a href="/OJ/problem.php?id=' + problem.id + '" style="color: white;">' + problem.id + '</a></span>';
          });
          if (solvedLinks.length === 0) {
            solvedLinks = ['No Problem Now!'];
          }
          $('#modal-solved-bd').html(solvedLinks.join(' '));

          // 生成未解决问题的链接
          var unsolvedLinks = data.unsolved.map(function(problem, index) {
            var score = problem.score;
            var colorClass;
            if (score <= 20) colorClass = 'am-badge-success';
            else if (score <= 40) colorClass = 'am-badge-secondary';
            else if (score <= 60) colorClass = 'am-badge-primary';
            else if (score <= 80) colorClass = 'am-badge-warning';
            else colorClass = 'am-badge-danger';

            /////////////////////////////////cs
            // if (index <= 2) colorClass = 'am-badge-success';
            // else if (index <= 4) colorClass = 'am-badge-secondary';
            // else if (index <= 6) colorClass = 'am-badge-primary';
            // else if (index <= 8) colorClass = 'am-badge-warning';
            // else colorClass = 'am-badge-danger';
            //////////////////////////////////cs

            return '<span class="am-badge ' + colorClass + ' am-round">' +
              '<a href="/OJ/problem.php?id=' + problem.id + '" style="color: white;">' + problem.id + '</a></span>';
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
    }
  });
</script>
