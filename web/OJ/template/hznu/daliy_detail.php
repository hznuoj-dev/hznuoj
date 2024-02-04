<?php
require_once("header.php");
?>

<?php $current_year = date("Y");

$sql = "SELECT * FROM dailydetails";
$res = $mysqli->query($sql);
$row = $res->fetch_assoc();

$lastday = date("Y-m-d", strtotime($row['start_time']));
$today = date("Y-m-d", strtotime($row['end_time']));
?>

<?php
if ($_GET['user']) $user = $_GET['user'];
else $user = $_POST['daliy_detail'];
if (!is_valid_user_name($user)) {
  echo "No such User!";
  exit(0);
}
$user_mysql = $mysqli->real_escape_string($user);

// 获取热力图相关数据
$sql = "SELECT MIN(judgetime) as actime
            FROM solution
            WHERE user_id = '$user_mysql' AND result = 4
            GROUP BY problem_id;";
$result = $mysqli->query($sql);

$daliy_detail_data = array();
if ($result->num_rows > 0) {
  // 输出每行数据
  while ($row = $result->fetch_assoc()) {
    $daliy_detail_data[] = $row["actime"];
  }
}

$daliy_detail_data_json = json_encode($daliy_detail_data);
$result->free();
?>

<div class="am-g" style='margin-top:40px; display: flex; justify-content: center;'>
  <div class='am-u-md-12' style='display: flex; justify-content: center;'>
    <div id='chart_daliy_detail' style='height:980px;width:80%;'></div>
  </div>
</div>

<?php require_once("footer.php") ?>

<!-- <script src="https://registry.npmmirror.com/echarts/5/files/dist/echarts.min.js"></script> -->
<script src="../../plugins/echarts/echarts.min.5.4.js"></script>
<script type="text/javascript">
  var dom = document.getElementById('chart_daliy_detail');
  var myChart = echarts.init(dom, null, {
    renderer: 'canvas',
    useDirtyRect: false
  });
  var option;

function getDaliyDetailData(lastday, today, alldata) {

    console.log(lastday, today);

    var date = +echarts.number.parseDate(lastday);
    var end = +echarts.number.parseDate(today);
    var dayTime = 3600 * 24 * 1000;
    var data = [];
    for (let time = date; time <= end; time += dayTime) {
      let timeStr = echarts.format.formatTime('yyyy-MM-dd', time);
      let count = alldata.filter(record => record.startsWith(timeStr)).length;
      data.push([timeStr, count]);
    }
    return data;
  }

  option = {
    title: {
      text: '每日做题情况',
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
        }, // 1为浅绿色
        {
          min: 2,
          max: 2,
          color: '#aadf8f'
        }, // 2为稍深的绿色
        {
          min: 3,
          max: 3,
          color: '#85c96e'
        }, // 3为更深的绿色
        {
          min: 4,
          max: 4,
          color: '#60b34d'
        }, // 4为更深的绿色
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
      cellSize: ['auto', 25],
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
      data: getDaliyDetailData('<?php echo $lastday ?>', '<?php echo $today ?>', <?php echo $daliy_detail_data_json ?>)
    }
  };


  if (option && typeof option === 'object') {
    myChart.setOption(option);
  }

  window.addEventListener('resize', myChart.resize);
</script>