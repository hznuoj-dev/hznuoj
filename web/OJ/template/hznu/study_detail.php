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
$neednum = array();

//此处修改
$sql = "SELECT pt.tag, COUNT(DISTINCT s.problem_id) as count
        FROM solution s
        JOIN problem_tag pt ON s.problem_id = pt.problem_id
        WHERE s.user_id = '$user' AND s.result = 4 AND pt.tag IN ('tag1', 'tag2', 'tag3')
        GROUP BY pt.tag";
$result = $mysqli->query($sql);

while ($row = $result->fetch_assoc()) {
  $studydata[$row['tag']] = $row['count'];
}

$result->free();

$neednum = array();
$neednum['tag1'] = 1; //此处修改
$neednum['tag2'] = 5;
$neednum['tag3'] = 5;
?>

<div class="am-g" style="margin-top: 50px;">
  <div class="am-u-sm-8" style="display: flex; justify-content: center;">
    <div id="main" style="width: 80%;height:500px;"></div>
  </div>
  <div class="am-u-sm-4">
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
      type: 'graph',
      layout: 'none',
      symbolSize: 60,
      roam: true,
      label: {
        show: true,
        fontSize: 18
      },
      edgeSymbol: ['circle', 'arrow'],
      edgeSymbolSize: [0, 10],
      edgeLabel: {
        fontSize: 30
      },
      data: [{
          name: 'tag1', //此处修改
          x: 300,
          y: 300,
          itemStyle: {
            color: '<?php echo $studydata['tag1'] < $neednum['tag1'] ? '#cccccc' : '#85c96e'; ?>'
          }
        },
        {
          name: 'tag2',
          x: 500,
          y: 300,
          itemStyle: {
            color: '<?php echo $studydata['tag2'] < $neednum['tag2'] ? '#cccccc' : '#85c96e'; ?>'
          }
        },
        {
          name: 'tag3',
          x: 700,
          y: 300,
          itemStyle: {
            color: '<?php echo $studydata['tag3'] < $neednum['tag3'] ? '#cccccc' : '#85c96e'; ?>'
          }
        }
      ],
      links: [{
          source: 'tag1', //此处修改
          target: 'tag2'
        },
        {
          source: 'tag2',
          target: 'tag3'
        }
      ],
      lineStyle: {
        opacity: 0.9,
        width: 2,
        curveness: 0
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