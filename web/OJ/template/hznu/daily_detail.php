<?php $title = "Daily Detail"; ?>
<?php
require_once("header.php");
?>

<?php
if (HAS_PRI("set_more_settings")) {
  echo <<<HTML
  <a href='student_daily_detail.php?class=null' class='am-btn am-btn-secondary custom-link' style='margin: 20px 0 0 20px;'>
    查看班级学生每日详情
  </a>
HTML;
}
?>
<div class="am-g" style='margin-top:40px; display: flex; justify-content: center;'>
  <div class='am-u-md-12' style='display: flex; justify-content: center;'>
    <div id='chart_daily_detail' style='height:70vh;width:1000px;'></div>
  </div>
</div>

<?php require_once("footer.php") ?>

<!-- <script src="https://registry.npmmirror.com/echarts/5/files/dist/echarts.min.js"></script> -->
<script src="plugins/echarts/echarts.min.5.4.js"></script>
<script type="text/javascript">
  var dom = document.getElementById('chart_daily_detail');
  var myChart = echarts.init(dom, null, {
    renderer: 'canvas',
    useDirtyRect: false
  });
  var option;

  function getDailyDetailData(lastday, today, alldata) {
    var date = +echarts.number.parseDate(lastday);
    var end = +echarts.number.parseDate(today);
    var dayTime = 3600 * 24 * 1000;
    var data = [];
    for (let time = date; time <= end; time += dayTime) {
      let timeStr = echarts.format.formatTime('yyyy-MM-dd', time);
      let count = alldata.filter(record => record.startsWith(timeStr)).length;
      data.push([timeStr, count]);
    }
    // 计算列数（周数）
    var start = new Date(lastday);
    start.setDate(start.getDate() - start.getDay() + 7); // 找到开始日期所在周的周日
    var columns = 0;
    while (start.getTime() <= end) {
      columns++;
      start.setDate(start.getDate() + 7); // 增加一周
    }
    return {
      data: data,
      columns: columns
    };
  }

  var result = getDailyDetailData('<?php echo $lastday ?>', '<?php echo $today ?>', <?php echo $daily_detail_data_json ?>);
  var dailyData = result.data;
  var columns = result.columns;
  const user_detail = '<?php echo USER_DETAIL ?>';

  var cellSize = 25;
  var width = columns * cellSize + 200;
  document.getElementById('chart_daily_detail').style.width = width + 'px';

  option = {
    title: {
      text: user_detail && (user_detail + '的') + '每日做题情况',
      top: 20,
      left: 'center',
      textStyle: {
        fontSize: 25
      }
    },
    tooltip: {
      formatter: function(params) {
        return '日期：' + params.value[0] + '<br/>过题数：' + params.value[1];
      }
    },
    visualMap: {
      left: 'center',
      top: 65,
      type: 'piecewise',
      orient: 'horizontal',
      pieces: [
        // {min: 0, max: 0, color: 'white'},  // 0的时候是白色
        {
          min: 1,
          max: 1,
          color: '#d0f0c0'
        },
        {
          min: 2,
          max: 2,
          color: '#aadf8f'
        },
        {
          min: 3,
          max: 3,
          color: '#85c96e'
        },
        {
          min: 4,
          max: 4,
          color: '#60b34d'
        },
        {
          gte: 5,
          color: '#006400'
        } // 5
      ]
    },
    calendar: {
      top: 120,
      left: 30,
      right: 30,
      cellSize: [25, 25],
      range: ['<?php echo $lastday ?>', '<?php echo $today ?>'],
      itemStyle: {
        borderWidth: 1
      },
      yearLabel: {
        show: false
      }
    },
    series: {
      type: 'heatmap',
      coordinateSystem: 'calendar',
      data: dailyData
    }
  };

  if (option && typeof option === 'object') {
    myChart.setOption(option);
    myChart.resize();
  }

  window.addEventListener('resize', myChart.resize);
</script>
