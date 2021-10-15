<?php $title="Key_words"; ?>
<?php

require_once "template/hznu/header.php"; 
?>
<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js">
</script>
<style>
    .box{
      border: 1px solid #eee;
      padding: 30px 30px 0px 30px;
      margin: 25px 0px 15px 0;
      box-shadow: 2px 2px 10px 0 #ccc;
    }
    .class-name-ch{
      font-size: xx-large;
    }
    .class-name-en{
      
    }
    .class-title{
      padding-bottom: 15px;
    }
    .class-description{
      color: #515151;
    }
    .content-block{
      margin-bottom: 50px;
    }
    .content-block:last-child{
      margin-bottom: 15px;
    }
    .content-block-title{
      font-size: x-large;
      font-weight: bold;
      border-bottom: 1px solid #eee;
      margin-bottom: 10px;
    }
    .content-block-body{
      padding-left: 20px;
    }
    .detail-table{
      width: 100%;
      -ms-word-break: break-all;
      word-break: break-all;
    }
    .detail-table>tbody>tr>td{
      border-left: 1px solid #eee;
      border-bottom: 1px solid #eee;
      padding: 10px;
    }
    .detail-table tr td:first-child{
      border-left: 0;
    }
    .detail-table tbody tr:last-child td{
      border-bottom: 0;
    }
    .class-mean{
      float: right;
    }

    .box {
        border: 1px solid #eee;
        padding: 30px 50px;
        /* margin: 25px 0 15px 0; */
        box-shadow: 2px 2px 10px 0 #ccc;    
    }
    li{
        line-height: 300%;
        list-style:none;
    }
    a {
        color: #0e90d2;
    }
    a {
        text-decoration: none;
    }
    a {
        background-color: transparent;
    }
    .am-list>li>a {
        display: block;
        /* padding: 0.1rem 0; */
    }
    a:hover {
        color: #0066CC;
    }
    .am-text-truncate {
        word-wrap: normal;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    /* *, :after, :before {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    } */
    ul {
        display: block;
        list-style-type: disc;
        margin-block-start: 0em;
        margin-block-end: 0em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        padding-inline-start: 0px;
    }
    li {
        line-height: 200%;
        list-style: none;
    }
    li:hover{
        color: #3a90ca;
        /* font-weight: bolder; */
    }
    hr {
        border: 0.4px solid #E0E0E0;
        /* background-color: #FFFFFF; */
        /* background-color:#00ff00; */
    }
   </style>
  <div class="am-container" style="padding-top: 30px;">
    <div class="box">
      <div class="class-title">
        <div class="class-name-ch">
          <span>关键词整理</span>
          <div class="class-mean">
            <span class="am-badge am-badge-success am-text-xl">等级制度</span>
          </div>
        </div>
        <div class="class-name-en">
          Key words for levels
        </div>
        <br>
        <div class="class-name-explain">
           <p>整理关键词、数据类型、数据结构，通过一定的评分标准来实现评级</p>   
        </div>
      </div>
    </div>
    <div class="am-g">
      <div class="am-u-md-8">
        <div class="box">
        <h1>知识点整理</h1>
          <div class="content-block">
            <div class="content-block-title">
              <span><div class="keyWords">关键词:</div></span>
            </div>
            <div class="content-block-body">
              <ul>
                <li>可用于定义的：<span>struct</span>、<span>typedef</span>、<span>void</span></li>
                <li>可用于选择的：<span>switch-case-default</span>、<span>if-else if-else</span></li>
                <li>可用于改变流程的：<span>break</span>、<span>continue</span>、<span>return</span>、<span>goto</span></li>
                <li>可用于循环的：<span>do-while</span>、<span>for</span>、<span>while</span></li>
                <li>可用于计算的：<span>sizeof</span></li>
                <li>转义字符：<span>\n</span>、<span>\\</span>、<span>\'</span>、<span>\"</span>、<span>\?</span></li>
              </ul>
            </div>
         </div>
           <div class="content-block">
            <div class="content-block-title">
              <span><div class="types">数据类型：</div></span>
            </div>
            <div class="content-block-body">
              <table width="841" class="detail-table">
                <tbody>
                  <tr>
                     <td colspan="5">
                        <p>数据类型</p>
                     </td>
                     <td colspan="5">
                        <p>输入格式</p>
                     </td>
                     <td colspan="5">
                        <p>输出格式</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>char</p>
                     </td>
                     <td colspan="5">
                        <p>%c</p>
                     </td>
                     <td colspan="5">
                        <p>%c</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>short</p>
                     </td>
                     <td colspan="5">
                        <p>%hd</p>
                     </td>
                     <td colspan="5">
                        <p>%d</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>int</p>
                     </td>
                     <td colspan="5">
                        <p>%d</p>
                     </td>
                     <td colspan="5">
                        <p>%d</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>double</p>
                     </td>
                     <td colspan="5">
                        <p>%lf</p>
                     </td>
                     <td colspan="5">
                        <p>%lf/%f</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>float</p>
                     </td>
                     <td colspan="5">
                        <p>%f</p>
                     </td>
                     <td colspan="5">
                        <p>%f</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>long</p>
                     </td>
                     <td colspan="5">
                        <p>%ld</p>
                     </td>
                     <td colspan="5">
                        <p>%ld</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>long long</p>
                     </td>
                     <td colspan="5">
                        <p>%lld</p>
                     </td>
                     <td colspan="5">
                        <p>%lld</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>long double</p>
                     </td>
                     <td colspan="5">
                        <p>%Lf</p>
                     </td>
                     <td colspan="5">
                        <p>%Lf</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>unsiged int</p>
                     </td>
                     <td colspan="5">
                        <p>%u</p>
                     </td>
                     <td colspan="5">
                        <p>%u</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>unsiged long</p>
                     </td>
                     <td colspan="5">
                        <p>%lu</p>
                     </td>
                     <td colspan="5">
                        <p>%lu</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>unsiged long long</p>
                     </td>
                     <td colspan="5">
                        <p>%llu</p>
                     </td>
                     <td colspan="5">
                        <p>%llu</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>enum</p>
                     </td>
                     <td colspan="5">
                        <p>无</p>
                     </td>
                     <td colspan="5">
                        <p>无</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>字符串类型</p>
                     </td>
                     <td colspan="5">
                        <p>%s</p>
                     </td>
                     <td colspan="5">
                        <p>%s</p>
                     </td>
                  </tr> 
                   <tr>
                     <td colspan="5">
                        <p>指针类型</p>
                     </td>
                     <td colspan="5">
                        <p>%p</p>
                     </td>
                     <td colspan="5">
                        <p>%p</p>
                     </td>
                  </tr> 
                </tbody>
              </table>
            </div>
          <div class="content-block">
            <div class="content-block-title">
              <span><div class="dataStructure">程序结构:</div></span>
            </div>
            <div class="content-block-body">
              <ul>
                <li>顺序结构：从头到尾依次执行</li>
                <li>分支结构：程序中包含if、switch、枚举等中一个的</li>
                <li>循环结构：包含while、do-while、for等中一个的</li>
                <li>嵌套结构：比如while和if一起用，两个for循环嵌套的</li>
                <li>递归结构：函数里调用自身的</li>
                <li>模块化结构：将主程序分成若干模块，每个模块一个函数的</li>
                <li>链式结构：使用链表 </li>
              </ul>
            </div>
           </div>
           <div class="content-block">
            <div class="content-block-title">
              <span><div class="scoringCriteria">评分标准:</div></span>
            </div>
            <div class="content-block-body">
              <p>分数加分规则：</p>
                <ol>
                  <li>
                    根据题目中对应知识点使用的次数来给分比如用10遍算是接触，100遍算是记住，1000遍算是学会，10000遍算是掌握(同一题可以重复计算次数)
                  </li>
                  <li>
                    根据题目中使用改关键词或是数据类型的难易程度基于分值，完成该题就能得到分数，简单题给高分，难题给低分，然后在某一阶段（例如60、70分）的时候简单题不再给分，让分数的增长曲线成山峰状，从低到高再到低。
                  </li>
                </ol>
                <br>
               <p>分数标准</p>
                  <table width="841" class="detail-table">
                  <tbody>
                     <tr>
                        <td colspan="5">
                           <p>熟练度</p>
                        </td>
                        <td colspan="5">
                           <p>颜色</p>
                        </td>
                     </tr>
                     <tr>
                        <td colspan="5">
                           <p>newbie</p>
                        </td>
                        <td colspan="5">
                        <canvas id="myCanvas" width="200" height="50" style="border:1px solid #c3c3c3;">
                        Your browser does not support the canvas element.
                        </canvas>
                        <script type="text/javascript">
                           var canvas=document.getElementById('myCanvas');
                           var ctx=canvas.getContext('2d');
                           ctx.height=100;
                           ctx.fillStyle='#fffacd';
                           ctx.fillRect(0,0,200,50);
                        </script>
                        </td>
                     </tr> 
                     <tr>
                        <td colspan="5">
                           <p>pupil</p>
                        </td>
                        <td colspan="5">
                        <canvas id="myCanvas1" width="200" height="50" style="border:1px solid #c3c3c3;">
                        Your browser does not support the canvas element.
                        </canvas>
                        <script type="text/javascript">
                           var canvas=document.getElementById('myCanvas1');
                           var ctx=canvas.getContext('2d');
                           ctx.height=100;
                           ctx.fillStyle='#90ee90';
                           ctx.fillRect(0,0,200,50);
                        </script>
                        </td>
                     </tr> 
                     <tr>
                        <td colspan="5">
                           <p>expert</p>
                        </td>
                        <td colspan="5">
                        <canvas id="myCanvas2" width="200" height="50" style="border:1px solid #c3c3c3;">
                        Your browser does not support the canvas element.
                        </canvas>
                        <script type="text/javascript">
                           var canvas=document.getElementById('myCanvas2');
                           var ctx=canvas.getContext('2d');
                           ctx.height=100;
                           ctx.fillStyle='#87CEFA';
                           ctx.fillRect(0,0,200,50);
                        </script>
                        </td>
                     </tr> 
                     <tr>
                        <td colspan="5">
                           <p>candidate master</p>
                        </td>
                        <td colspan="5">
                        <canvas id="myCanvas3" width="200" height="50" style="border:1px solid #c3c3c3;">
                        Your browser does not support the canvas element.
                        </canvas>
                        <script type="text/javascript">
                           var canvas=document.getElementById('myCanvas3');
                           var ctx=canvas.getContext('2d');
                           ctx.height=100;
                           ctx.fillStyle='#BA55D3';
                           ctx.fillRect(0,0,200,50);
                        </script>
                        </td>
                     </tr> 
                     <tr>
                        <td colspan="5">
                           <p>master</p>
                        </td>
                        <td colspan="5">
                        <canvas id="myCanvas4" width="200" height="50" style="border:1px solid #c3c3c3;">
                        Your browser does not support the canvas element.
                        </canvas>
                        <script type="text/javascript">
                           var canvas=document.getElementById('myCanvas4');
                           var ctx=canvas.getContext('2d');
                           ctx.height=100;
                           ctx.fillStyle='#FF0000';
                           ctx.fillRect(0,0,200,50);
                        </script>
                        </td>
                     </tr> 
                  </tbody>
                  </table>
            </div>
           </div>
           </div>
        </div>
      </div>
      <div class="am-u-md-4">
        <div class="am-sticky-placeholder" style="height: 182.2px; margin: 25px 0px 15px;"><div class="am-sticky-placeholder" style="height: 199.8px; margin: 0px;"><div class="box" data-am-sticky="{top:60}" style="margin: 0px;">
          <div class="content-block">
            <div class="content-block-title">
              索引
            </div>
            <div class="content-block-body">
              <ol>
                <li><a class="scroll_keywords">关键词</a></li>
                <li><a class="scroll_types">数据类型</a></li>
                <li><a class="scroll_dataStructure">程序结构</a></li>
                <li><a class="scroll_scoringCriteria">评分标准</a></li>
                <!-- <li><a class="scroll_top">返回顶部</li> -->
              </ol> 
            </div>
          </div>
           <?php $user=$_SESSION['user_id'];if($user==TRUE)include_once "keywords_search.php"?>
           <br>
          </div>
      </div>
      
      </div>
    </div>
  </div><!-- container -->
  <script type="text/javascript"> 
      jQuery(document).ready(function($){ 
      // $('.scroll_top').click(function(){$('html,body').animate({scrollTop: '0px'}, 800);}); 
      $('.scroll_keywords').click(function(){$('html,body').animate({scrollTop:$('.keyWords').offset().top-60}, 800);}); 
      $('.scroll_types').click(function(){$('html,body').animate({scrollTop:$('.types').offset().top-60}, 800);}); 
      $('.scroll_dataStructure').click(function(){$('html,body').animate({scrollTop:$('.dataStructure').offset().top-60}, 800);}); 
      $('.scroll_scoringCriteria').click(function(){$('html,body').animate({scrollTop:$('.scoringCriteria').offset().top-60}, 800);}); 
      }); 
      
  </script> 

<?php require_once "template/hznu/footer.php" ?>
