<?php 
   $search_name="none";
   $getselect="--Please Select--";
?>
<script src="keywords_search.js"></script>
<div class="content-block-title">
    搜索关键字
</div>
<div class="content-block-search">
    <div id="searchKeyWords" class="searchKeyWords">
        Key: <select id="select" name="select" >
            <option class="seach_name" selected disabled style="display:none" value=""><?php echo "<p>".$getselect."<p>"; ?></option>
            <option value="int">int</option>
            <option value="char">char</option>
            <option value="return">return</option>
            <option value="struct">struct</option>
            <option value="typedef">typedef</option>
            <option value="void">void</option>
            <option value="switch">switch</option>
            <option value="if">if</option>
            <option value="else">else</option>
            <option value="break">break</option>
            <option value="continue">continue</option>
            <option value="goto">goto</option>
            <option value="do">do</option>
            <option value="while">while</option>
            <option value="for">for</option>
            <option value="sizeof">sizeof</option>
            <option value="\n">转义字符\n</option>
            <option value="\\">转义字符\\</option>
            <option value="\'">转义字符\'</option>
            <option value="\?">转义字符\?</option>
            <option value="short">short</option>
            <option value="double">double</option>
            <option value="float">float</option>
            <option value="long">long</option>
            <option value="long long">long<span> </span>long</option>
            <option value="long double">long<span> </span>double</option>
            <option value="unsigned int">unsigned<span> </span>int</option>
            <option value="unsigned long">unsigned<span> </span>long</option>
            <option value="unsigned long long">unsigned<span> </span>long<span> </span>long</option>
            <option value="enum">enum</option>
        </select>
        <td colspan="5">
            <input type="submit" value="Search" onclick="searchTo()" class="bt_search">
            <input type="submit" value="Gather" onclick="MoveTo()" class="bt_move">
            <div id="show_result" class="show_result"> 
                <?php 
                echo "<p>".$search_name."<p>"; 
                ?>
            </div>
        </td>
        <td colspan="5">
        <canvas id="resmyCanvas" width="200" height="50" style="border:1px solid #c3c3c3;">
        Your browser does not support the canvas element.
        </canvas>
        <script type="text/javascript">
            var canvas=document.getElementById('resmyCanvas');
            var ctx=canvas.getContext('2d');
            ctx.height=100;
            // alert(selectcnt);
            ctx.fillStyle='#ffffff';
            ctx.fillRect(0,0,200,50)
        </script>
        </td>
    </div>
    <!-- <a href="keywords_sum.php">综合查看</a> -->
</div>



