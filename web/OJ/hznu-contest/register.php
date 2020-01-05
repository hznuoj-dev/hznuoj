<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/hznu-contest/header.php";  
?>
<div class="am-container" style="padding-top: 50px;">
    <div class="am-g" style="margin-bottom: 20px;">
        <ul class="am-nav am-nav-tabs am-nav-justify">
            <li class="am-active" id="tab-list"><a href="#">已报名列表</a></li>
            <li id="tab-register"><a href="#" >报名/修改信息</a></li>
        </ul>
    </div>
    <div id="content-list">
        <div class="am-g">
            <?php
            $sql="SELECT COUNT(*) FROM formal_contest_user WHERE contest_id = $contest_id";
            $res=$mysqli->query($sql);
            $register_cnt=$res->fetch_array()[0];
            ?>
            报名队伍数: <?php echo $register_cnt ?> 
            <table class="am-table" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>队名</th>
                        <th>院校</th>
                        <th>队员一</th>
                        <th>队员二</th>
                        <th>队员三</th>
                        <th>注册时间</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $sql = 
                "
                SELECT team_name, school, register_time, name, anonymous
                FROM formal_contest_team 
                LEFT JOIN formal_contest_tmlist on formal_contest_team.team_id = formal_contest_tmlist.team_id 
                LEFT JOIN formal_contest_member on formal_contest_tmlist.member_id = formal_contest_member.member_id
                WHERE contest_id = '3'
                ORDER BY register_time DESC, formal_contest_tmlist.id 
                ";
                $res=$mysqli->query($sql);
                while($row=$res->fetch_array()){ 
                    echo "<tr>";
                    echo "<td>".htmlentities($row['team_name'])."</td>";
                    echo "<td>".htmlentities($row['school'])."</td>";

                    if($row['anonymous']) echo "<td>****</td>";
                    else echo "<td>".htmlentities($row['name'])."</td>";

                    $row=$res->fetch_array();
                    if($row['anonymous']) echo "<td>****</td>";
                    else echo "<td>".htmlentities($row['name'])."</td>";

                    $row=$res->fetch_array();
                    if($row['anonymous']) echo "<td>****</td>";
                    else echo "<td>".htmlentities($row['name'])."</td>";

                    echo "<td>".htmlentities($row['register_time'])."</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="content-register" style="display: none;"> 
        <?php if (!$has_login): ?>  
            <div class="am-text-center">
                报名信息与HZNUOJ账号绑定，请先<a href="../loginpage.php">登录</a>或者<a href="../registerpage.php">注册</a>。 
            </div>
        <?php elseif($is_end): ?> 
            <div class="am-text-center">
                报名已截止。
            </div>
        <?php endif ?>
        <div class="am-g" style="max-width: 800px;">
            <form class="am-form" action="contest_register.php" method="post" data-am-validator id="form_register">
                <?php include_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/set_post_key.php"?>
                <fieldset <?php if(!$has_login || $is_end) echo "disabled";?>>
                    <div class="am-form-group">
                        <label for="institute">院校</label>
                        <select name="institute" id="school" data-am-selected="{searchBox: 1, btnWidth: '100%', maxHeight: 250}" required>
                            <?php
                            $json_string = file_get_contents("./res/school.json");
                            $json = json_decode($json_string, true);
                            foreach($json as $provience => $school_list) {
                                foreach ($school_list as $single_school) {
                                    $selected = $single_school == $school ? "selected" : "";
                                    echo "<option $selected value=\"$single_school\">$single_school</option>"; 
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="am-form-group">
                        <label for="team_name">队名</label>
                        <input type="text" class="am-form-field" id="team_name" name="team_name" placeholder="请输入队名" required minlength="1" maxlength="16" value="<?php echo htmlentities($team_name); ?>">
                        <hr data-am-widget="divider" style="" class="am-divider am-divider-dashed" />
                    </div>
                    <div data-am-widget="tabs"
                           class="am-tabs am-tabs-d2"
                            >
                        <ul class="am-tabs-nav am-cf">
                            <li class="am-active"><a href="[data-tab-panel-0]">队员一</a></li>
                            <li class=""><a href="[data-tab-panel-1]">队员二</a></li>
                            <li class=""><a href="[data-tab-panel-2]">队员三</a></li>
                        </ul>
                        <div class="am-tabs-bd">
                            <?php
                            $str = ['一', '二', '三'];
                            for ($i = 0 ; $i < 3 ; ++$i) {
                                $name[$i] = htmlentities($name[$i]);
                                $stu_id[$i] = htmlentities($stu_id[$i]);
                                $phone[$i] = htmlentities($phone[$i]);
                                $email[$i] = htmlentities($email[$i]);
                                $active = $i==0 ? "am-active" : "";
                                echo <<<HTML
                                  <div data-tab-panel-$i class="am-tab-panel $active">
                                        <label for="name$i">姓名：</label> 
                                        <input type="text" class="am-form-field" id="name$i" name="name$i" placeholder="请输入队员{$str[$i]}的姓名"  value="{$name[$i]}">
                                        <label for="stu_id$i">学号：</label> 
                                        <input type="text" class="am-form-field" id="stu_id$i" name="stu_id$i" placeholder="请输入队员{$str[$i]}的学号"  value="{$stu_id[$i]}">  
                                        <label for="phone$i">Tel：</label> 
                                        <input type="number" class="am-form-field" id="phone$i" name="phone$i" placeholder="请输入队员{$str[$i]}的手机号码"  value="{$phone[$i]}">
                                        <label for="email$i">Email：</label> 
                                        <input type="email" class="am-form-field" id="email$i" name="email$i" placeholder="请输入队员{$str[$i]}的邮箱"  value="{$email[$i]}">
                                  </div>
HTML;
                            }
                            ?>
                        </div>
                    </div>
                    <hr data-am-widget="divider" style="" class="am-divider am-divider-dashed" />
                    <div class="am-checkbox">
                        <input <?php if($anonymous) echo "checked"; ?> type="checkbox" class="" name="anonymous" id="anonymous" value="yes"> 匿名
                    </div>
                    <div class="am-text-center" style="margin: 20px 0;" id="register_submit">
                        <button type="submit" class="am-btn am-btn-primary" id="submit">确认提交</button>
                    </div>
                </fieldset>
                <div class="am-text-center am-text-secondary">
                    *队长一人注册报名即可，无需所有队员各自报名。报名时间截止后信息将无法修改。
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/OJ/template/hznu/footer.php" ?>

<script>
    var aim="ajax/update_info.php";
    $("#tab-list").click(function(){
        $(this).addClass("am-active");
        $("#tab-register").removeClass("am-active");
        $("#content-list").fadeIn();
        $("#content-register").hide();
    });
    $("#tab-register").click(function(){
        $(this).addClass("am-active");
        $("#tab-list").removeClass("am-active");
        $("#content-list").hide();
        $("#content-register").fadeIn();
    });
    $("#submit").click(function(){
        aim="ajax/update_info.php"; 
    });
    $("#form_register").validator({
        submit: function(){
            if(this.isFormValid()){
                $.ajax({
                    type: "POST",  
                    url: aim,
                    data: {
                        team_name: $("#team_name").val(),   
                        school: $("#school").val(),   
                        name: [$("#name0").val(), $("#name1").val(), $("#name2").val()], 
                        stu_id: [$("#stu_id0").val(), $("#stu_id1").val(), $("#stu_id2").val()], 
                        phone: [$("#phone0").val(), $("#phone1").val(), $("#phone2").val()],
                        email: [$("#email0").val(), $("#email1").val(), $("#email2").val()],
                        anonymous: $("#anonymous").is(":checked") ? 1 : 0,
                    },
                    context: this, 
                    success: function(data){ 
                        alert(data);
                    },
                    complete: function(){
                        console.log("ajax complete!");
                    },
                    error: function(xmlrqst,info){
                        console.log(info);
                    }
                })
            }
            return false;
        }
    });
</script>
