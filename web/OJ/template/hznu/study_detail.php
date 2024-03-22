<?php $title = "Study Detail";

require_once("header.php");

?>

<style>
  html {
    width: 100%;
    height: 100%;
  }

  body {
    width: 100%;
    height: 100%;
  }
</style>

<div class="am-g" style="width:85%;height:78%">
  <div class="am-u-sm-12" style="justify-content: center;width: 100%;height: 100%;">
    <div id="main" style="width: 100%;height: 100%;"></div>
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

<?php
require_once("footer.php");
?>

<script src="plugins/echarts/echarts.min.5.4.js"></script>
<script type="text/javascript">
  var chartDom = document.getElementById('main');
  var width = chartDom.offsetWidth;
  var height = chartDom.offsetHeight;
  var myChart = echarts.init(chartDom);
  var option;

  var studydata = <?php echo json_encode($studydata);  ?>;
  var neednum = <?php echo json_encode($neednum); ?>;

  var updatedData = [];
  <?php
  $maxX = max(array_column($pointposition, 'x')) - 150;
  $maxY = max(array_column($pointposition, 'y'));

  foreach ($pointposition as $tag => $position) {
    $relativeX = $position['x'] / $maxX;
    $relativeY = $position['y'] / $maxY;

    echo "updatedData.push({name: '" . $tag . "', x: " . $relativeX . " * width, y: " . $relativeY .
      " * height, symbol:'circle', symbolSize: 50, itemStyle: {color:'" . $nowcolor[$tag] . "'}});";
  }
  ?>

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
      symbolSize: 50,
      roam: false,
      label: {
        show: true,
        fontSize: 10
      },
      edgeSymbolSize: [0, 10],
      edgeLabel: {
        fontSize: 30
      },
      categories: [{
        name: 'Newbie',
        itemStyle: {
          color: '<?php echo $pointcolor[0]; ?>',
        }
      }, {
        name: 'Learner',
        itemStyle: {
          color: '<?php echo $pointcolor[1]; ?>',
        }
      }, {
        name: 'Expert',
        itemStyle: {
          color: '<?php echo $pointcolor[2]; ?>',
        }
      }, {
        name: 'Master',
        itemStyle: {
          color: '<?php echo $pointcolor[3]; ?>',
        }
      }],
      data: updatedData,
      links: links,
      lineStyle: {
        opacity: 0.9,
        width: 2,
        curveness: 0.1,
        color: '#ffbc61'
      }
    }, ]
  };

  option && myChart.setOption(option);

  myChart.on('click', function(params) {

    if (fatarray.includes(params.data.name)) {
      var dataIndex = params.dataIndex;
      // 收起或展开节点
      myChart.dispatchAction({
        type: params.data.collapsed ? 'expand' : 'collapse',
        seriesIndex: 0,
        dataIndex: dataIndex
      });
    } else {

      // 获取被点击的标签
      var tag = params.data.name;

      // 发送 AJAX 请求
      $.ajax({
        url: './template/hznu/getstudyproblems.php',
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