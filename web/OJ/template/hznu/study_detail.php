<?php
$title = "Study Detail";
require_once("header.php");
?>

<div class="am-g" id="main-graph" style="width:85%;height:800px;">
  <div class="am-u-sm-12" style="width: 100%;height: 600px;">
    <div id="study-way" style="width: 100%;height: 100%;"></div>
  </div>
  <div class="am-u-sm-12" style="height: 200px; margin-top:50px">
    <div id="ability-way" style="width: 70%;height: 100%;"></div>
  </div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="modal-study">
  <div class="am-modal-dialog" style="font-size: 20px; width:30%; border-radius: 20px" tabindex="-1" id="modal-study">
    <div class="am-modal-hd">Solved</div>
    <div class="am-modal-bd" id="modal-solved-bd"></div>
    <hr style="margin: 0px;">
    <div class="am-modal-hd">Recommended</div>
    <div class="am-modal-bd" id="modal-recommend-bd"></div>
  </div>
</div>

<?php
require_once("footer.php");
?>

<script src="plugins/echarts/echarts.min.5.4.js"></script>
<script type="text/javascript">
  const fatarray = <?php echo json_encode($fatarray);  ?>;
  const studydata = <?php echo json_encode($studydata);  ?>;
  const neednum = <?php echo json_encode($neednum); ?>;
  const links = <?php echo json_encode($links) ?>;
  const abilityLinks = <?php echo json_encode($abilityLinks); ?>;
  const abilitypoint = <?php echo json_encode($abilitypoint); ?>;
  const okabilitynum = <?php echo json_encode($okabilitynum); ?>;
  const user_detail = '<?php echo USER_DETAIL ?>';
  const user_id = '<?php echo $user ?>';

  const studywayDom = document.getElementById('study-way');
  const width = studywayDom.offsetWidth;
  const height = studywayDom.offsetHeight;
  const studyChart = echarts.init(studywayDom);

  const categoriesData = [
    <?php
    foreach ($categoriesData as $key => $value) {
      echo "{name: '$value', itemStyle: {color: '{$pointcolor[0][$key]}' }},";
    }
    ?>
  ];

  const updatedData = [];
  <?php
  // 计算每个点的相对位置
  $maxX = max(array_column($pointposition, 'x')) - 150;
  $maxY = max(array_column($pointposition, 'y'));
  foreach ($pointposition as $tag => $position) {
    $relativeX = $position['x'] / $maxX;
    $relativeY = $position['y'] / $maxY;
    echo "updatedData.push({
      name: '" . $tag . "',
      x: " . $relativeX . " * width,
      y: " . $relativeY . " * height,
      symbol: 'circle',
      symbolSize: 50,
      itemStyle: {
        color: '" . $studycolor[$tag] . "',
        shadowColor: 'rgba(0, 0, 0, 0.3)',
        shadowBlur: 8,
        shadowOffsetX: 3,
        shadowOffsetY: 3,
      }
    });";
  }
  ?>
  const abilityData = [];
  <?php
  foreach ($abilitypoint as $tag => $position) {
    echo "abilityData.push({
            name: '" . $tag . "',
            x: " . $position['x'] . ",
            y: " . $position['y'] . ",
            itemStyle: {
              color: '" . $abilitycolor[$tag] . "',
            }
          });";
  }
  // 添加空节点，撑开图的高度
  echo "abilityData.push({name: '', x: 0, y: 0, symbol:'circle', symbolSize: 0});";
  ?>

  graphDom = document.getElementById('main-graph');
  graphDom.style.marginTop = '30px';
  graphDom.style.marginBottom = '30px';

  const studywayOption = {
    title: {
      text: user_detail && (user_detail + '的') + '学习里程碑',
      left: "1%",
      top: "5%",
    },
    legend: {
      data: ["新手", "入门", "熟练", "精通"],
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
        return `知识点：${params.data.name}<br/>
          已完成：${studydata[params.data.name]} 题<br/>
          阶段目标：${neednum[params.data.name]} 题`;
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
      categories: categoriesData,
      data: updatedData,
      links: links,
      lineStyle: {
        opacity: 0.9,
        width: 2,
        curveness: 0.1,
        color: '#bdcec8'
      }
    }, ]
  };

  studyChart.setOption(studywayOption);
  studyChart.on('click', function(params) {
    if (params.dataType == 'edge') {

    } else {
      if (fatarray.includes(params.data.name)) {
        //收起或展开节点
      } else {
        getProblem(params);
      }
    }
  });
  window.addEventListener('resize', studyChart.resize);

  /////////////////////////////////////////////学习能力路线

  var abilitywayDom = document.getElementById('ability-way');
  var abilityChart = echarts.init(abilitywayDom);
  const abilitywayOption = {
    title: {
      text: '学习能力路线',
      left: "1%",
      top: "10%",
    },
    tooltip: {
      formatter: function(params) {
        return `能力：${params.data.name}<br/>
          已完成：${okabilitynum[params.data.name]} 题<br/>
          目标：${abilitypoint[params.data.name].need} 题`;
      }
    },
    animationDurationUpdate: 1500,
    animationEasingUpdate: 'quinticInOut',
    series: [{
      width: "90%",
      type: 'graph',
      layout: 'none',
      symbol: "diamond",
      symbolSize: 80,
      roam: false,
      label: {
        show: true,
        fontSize: 10,
      },
      edgeSymbolSize: [0, 10],
      edgeLabel: {
        fontSize: 30
      },
      data: abilityData,
      links: abilityLinks,
    }, ]
  };

  abilityChart.setOption(abilitywayOption);
  abilityChart.on('click', function(params) {
    if (params.dataType == 'edge') {

    } else {
      if (fatarray.includes(params.data.name)) {
        //收起或展开节点
      } else {

      }
    }
  });
  window.addEventListener('resize', abilityChart.resize);

  //点击事件
  function getProblem(params) {
    // 获取被点击的标签
    const tag = params.data.name;
    $.ajax({
      url: './template/hznu/getstudyproblems.php',
      type: 'POST',
      data: {
        user: user_id,
        tag: tag
      },
      success: function(response) {
        const data = JSON.parse(response);
        // 生成问题链接的函数
        function generateProblemLinks(problems, containerId) {
          let links = problems.map(function(problem) {
            const colorClass = 'am-badge-secondary';
            return '<span class="am-badge ' + colorClass + ' am-round">' +
              '<a href="/OJ/problem.php?id=' + problem.id + '" style="color: white;">' + problem.id + '</a></span>';
          });
          if (links.length === 0) {
            links = ['<span style="font-size: 16px; color:#bbbbbb">No Problem Now!</span>'];
          }
          $('#' + containerId).html(links.join(' '));
        }

        generateProblemLinks(data.solved, 'modal-solved-bd');
        generateProblemLinks(data.unsolved, 'modal-recommend-bd');

        // 更新弹窗标题
        $('.am-modal-hd').eq(0).text('Solved (' + data.solved.length + ')');
        $('.am-modal-hd').eq(1).text('Recommended (' + data.unsolved.length + ')');

        // 显示弹窗
        $('#modal-study').modal();
      }
    });
  }
</script>
