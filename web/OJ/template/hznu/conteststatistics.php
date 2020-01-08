<?php
/**
* This file is created
* by yybird
* @2016.03.24
* last modified
* by yybird
* @2016.03.24
**/
?>

<?php $title="Statistics";?>
<?php include "contest_header.php" ?>
<div style="margin-top:40px;" align = "center">
    <h1 align="center">Contest Statistics</h1>
    <hr/>
    <table class="am-table am-table-striped" style = "max-width:1440px" >
        <thead>
            <tr>
                <th>#</th>
                <th>AC</th>
                <th>PE</th>
                <th>WA</th>
                <th>TLE</th>
                <th>MLE</th>
                <th>OLE</th>
                <th>RE</th>
                <th>CE</th>
                <th>Total</th>
                <th>C</th>
                <th>C++</th>
                <th>Pascal</th>
                <th>Java</th>
                <th>Ruby</th>
                <th>Bash</th>
                <th>Python</th>
                <th>PHP</th>
                <th>Perl</th>
                <th>C#</th>
                <th>Obj-c</th>
                <th>FreeBasic</th>
            </tr> 
        </thead>
        <tbody>
            <?php
            for ($i=0;$i<$pid_cnt;$i++){
                $PID[$i] = PID($i);

                if ($i&1)
                    echo "<tr><td>";
                else
                    echo "<tr><td>";
                echo "<a href='problem.php?cid=$cid&pid=$i'>$PID[$i]</a>";
                for ($j=0;$j<22;$j++) {
                    if(!isset($R[$i][$j])) $R[$i][$j]="";
                    echo "<td>".$R[$i][$j];
                }
                echo "</tr>";
            }
            echo "<tr><td>Total"; 
            for ($j=0;$j<22;$j++) {
                if(!isset($R[$i][$j])) $R[$i][$j]="";
                echo "<td>".$R[$i][$j];
            }
            echo "</tr>";
            ?>
        </tbody>
    </table>
</div>


<?php include "footer.php" ?>