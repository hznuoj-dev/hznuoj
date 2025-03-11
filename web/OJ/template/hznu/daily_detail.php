<?php $title = "Daily Detail"; ?>
<?php
require_once("header.php");
?>

<style>
  .study-data-item {
    width: 240px;
    height: 80px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    flex-direction: column;
    margin-left: 60px;
  }

  .study-data-num {
    font-size: 25px;
  }

  .study-data-title {
    font-size: 13px;
    font-weight: 400;
    color: #888;
  }
</style>

<?php
if (HAS_PRI("set_more_settings")) {
  echo <<<HTML
  <a href='student_daily_detail.php?class=null' class='am-btn am-btn-secondary custom-link' style='margin: 20px 0 0 20px;'>
    查看班级学生每日详情
  </a>
HTML;
}
?>
<div class="am-g am-margin-top" style='display:flex;flex-direction:column;align-items:center;'>
  <div class="am-margin-bottom" id='chart-daily-detail' style='height:300px;'></div>

  <div class="study-data-show am-margin-bottom-xl" style='width:900px;display:flex;flex-wrap:wrap;'>
    <div class="study-data-item">
      <div class="study-data-num"><?php echo $total_problems ?> 道</div>
      <div class="study-data-title">全时间段通过题目数量</div>
    </div>
    <div class="study-data-item">
      <div class="study-data-num"><?php echo $month_problems ?> 道</div>
      <div class="study-data-title">最近一个月通过题目数量</div>
    </div>
    <div class="study-data-item">
      <div class="study-data-num"><?php echo $week_problems ?> 道</div>
      <div class="study-data-title">最近一周通过题目数量</div>
    </div>
    <div class="study-data-item">
      <div class="study-data-num"><?php echo $max_streak ?> 天</div>
      <div class="study-data-title">最大连续刷题天数</div>
    </div>
    <div class="study-data-item">
      <div class="study-data-num"><?php echo $max_streak_month ?> 天</div>
      <div class="study-data-title">最近一个月最大连续刷题天数</div>
    </div>
    <div class="study-data-item">
      <div class="study-data-num"><?php echo $max_streak_week ?> 天</div>
      <div class="study-data-title">最近一周最大连续刷题天数</div>
    </div>
  </div>

  <div id='chart-solved-detail' style='height:500px;width:900px'></div>

  <div class="am-btn am-btn-primary" id="ai-study-gen">生成AI个性化学习反馈</div>
</div>

<?php require_once("footer.php") ?>

<!-- <script src="https://registry.npmmirror.com/echarts/5/files/dist/echarts.min.js"></script> -->
<script src="plugins/echarts/echarts.min.5.4.js"></script>
<script type="text/javascript">
  // 日刷题热力图图表
  let dom = document.getElementById('chart-daily-detail');
  const daily_chart = echarts.init(dom, null, {
    renderer: 'canvas',
    useDirtyRect: false
  });
  let option;

  function getDailyDetailData(lastday, today, alldata) {
    const date = +echarts.number.parseDate(lastday);
    const end = +echarts.number.parseDate(today);
    const dayTime = 3600 * 24 * 1000;
    let dailyData = [];
    for (let time = date; time <= end; time += dayTime) {
      let timeStr = echarts.format.formatTime('yyyy-MM-dd', time);
      let count = alldata.filter(record => record.startsWith(timeStr)).length;
      dailyData.push([timeStr, count]);
    }
    // 计算列数（周数）
    let start = new Date(lastday);
    start.setDate(start.getDate() - start.getDay() + 7); // 找到开始日期所在周的周日
    let columns = 0;
    while (start.getTime() <= end) {
      columns++;
      start.setDate(start.getDate() + 7); // 增加一周
    }
    return {
      dailyData,
      columns
    };
  }

  const result = getDailyDetailData('<?php echo $lastday ?>', '<?php echo $today ?>', <?php echo $daily_detail_data_json ?>);
  const dailyData = result.dailyData;
  const columns = result.columns;
  const user_detail = '<?php echo USER_DETAIL ?>';

  const cellSize = 25;
  const width = columns * cellSize + 200;
  document.getElementById('chart-daily-detail').style.width = width + 'px';

  option = {
    title: {
      text: user_detail && (user_detail + '的') + '每日做题情况',
      top: 20,
      left: 'center',
      textStyle: {
        fontSize: 20
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
      pieces: [{
        min: 1,
        max: 1,
        color: '#d0f0c0'
      }, {
        min: 2,
        max: 2,
        color: '#aadf8f'
      }, {
        min: 3,
        max: 3,
        color: '#85c96e'
      }, {
        min: 4,
        max: 4,
        color: '#60b34d'
      }, {
        gte: 5,
        color: '#006400'
      }]
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
    daily_chart.setOption(option);
    daily_chart.resize();
  }

  window.addEventListener('resize', daily_chart.resize);

  // 通过题目难度分类图表
  const solvedom = document.getElementById('chart-solved-detail');
  const solved_chart = echarts.init(solvedom, null, {
    renderer: 'canvas',
    useDirtyRect: false
  });
  option = {
    title: {
      text: user_detail && (user_detail + '的') + '通过题目难度分类',
      top: 0,
      left: 'center',
      textStyle: {
        fontSize: 20
      }
    },
    legend: {},
    tooltip: {
      trigger: 'axis',
      axisPointer: {
        type: 'shadow'
      },
      formatter: function(params) {
        return `题目难度分数区间：${params[0].axisValue} 分<br>通过数量：${params[0].value} 道`;
      }
    },
    xAxis: {
      type: 'category',
      data: <?php echo $score_ranges_json ?>,
    },
    yAxis: {
      type: 'value'
    },
    series: [{
      data: <?php echo $score_data_json ?>,
      type: 'bar'
    }]
  };

  option && solved_chart.setOption(option);

  // 个性化学习反馈按钮点击
  const chatbox = window.ChatBox;
  chatbox.add();
  $('#ai-study-gen').click(() => {
    if (!chatbox || !chatbox.chatCore || !chatbox.chatCore.isStop) return;
    const prompt = `你现在是一名指导老师，您所在的班级中一名学生的学习情况如下：` +
      `\n\n全时间段通过题目数量：<?php echo $total_problems ?> 道` +
      `\n最近一个月通过题目数量：<?php echo $month_problems ?> 道` +
      `\n最近一周通过题目数量：<?php echo $week_problems ?> 道` +
      `\n最大连续刷题天数：<?php echo $max_streak ?> 天` +
      `\n最近一个月最大连续刷题天数：<?php echo $max_streak_month ?> 天` +
      `\n最近一周最大连续刷题天数：<?php echo $max_streak_week ?> 天` +
      `综合题目AC率：<?php echo $ac_rate ?>%` +
      `下面是所在班级学生的平均数据作为相对数据参考：` +
      `\n\n全时间段通过题目数量：<?php echo $avg_total_problems ?> 道` +
      `\n最近一个月通过题目数量：<?php echo $avg_month_problems ?> 道` +
      `\n最近一周通过题目数量：<?php echo $avg_week_problems ?> 道` +
      `\n最大连续刷题天数：<?php echo $avg_max_streak ?> 天` +
      `\n最近一个月最大连续刷题天数：<?php echo $avg_max_streak_month ?> 天` +
      `\n最近一周最大连续刷题天数：<?php echo $avg_max_streak_week ?> 天` +
      `\n综合题目AC率：<?php echo $avg_ac_rate ?>%` +
      `\n\n请根据以上个人数据和班级平均数据，为学生他的生成个性化学习反馈。`;
    chatbox.openAndChat(prompt, user_detail + '的个性化学习反馈');
  });
</script>