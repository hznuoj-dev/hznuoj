<?php

require("admin-header.php");
if (!HAS_PRI("manage_course_team")) {
    echo "Permission denied!";
    exit(1);
}

require_once('../include/db_info.inc.php');

function addTeam($term, $course_name, $teacher_name, $class_week_time, $class_id_in_school)
{
    global $mysqli;

    if (empty($term) || empty($course_name) || empty($teacher_name) || empty($class_week_time) || empty($class_id_in_school)) {
        echo "All fields are required!";
        exit(1);
    }

    $stmt = $mysqli->prepare("INSERT INTO course_team (term, course_name, teacher_name, class_week_time, class_id_in_school) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $term, $course_name, $teacher_name, $class_week_time, $class_id_in_school);
    $stmt->execute();
    $team_id = $stmt->insert_id;
    $stmt->close();

    return $team_id;
}

function delTeam($team_id)
{
    global $mysqli;

    $stmt = $mysqli->prepare("DELETE FROM course_team WHERE team_id = ?");
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM course_team_relation WHERE team_id = ?");
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $stmt->close();
}

function updateTeamRelation($team_id, $studentNumbers)
{
    global $mysqli;

    // Delete existing relations for the team
    $stmt = $mysqli->prepare("DELETE FROM course_team_relation WHERE team_id = ?");
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $stmt->close();

    // Insert new relations
    foreach ($studentNumbers as $stu_id) {
        // Fetch user_id using stu_id
        $user_stmt = $mysqli->prepare("SELECT user_id FROM users WHERE stu_id = ?");
        $user_stmt->bind_param("s", $stu_id);
        $user_stmt->execute();
        $user_stmt->bind_result($user_id);
        if ($user_stmt->fetch()) {
            $user_stmt->close();
            $stmt = $mysqli->prepare("INSERT INTO course_team_relation (user_id, team_id) VALUES (?, ?)");
            $stmt->bind_param("si", $user_id, $team_id);
            if (!$stmt->execute()) {
                echo "Execution failed: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $user_stmt->close();
        }
    }
}

if (isset($_POST['add_team'])) {
    addTeam($_POST['term'], $_POST['course_name'], $_POST['teacher_name'], $_POST['class_week_time'], $_POST['class_id_in_school']);
    exit(0);
}

if (isset($_POST['delete_team'])) {
    delTeam($_POST['team_id']);
    exit(0);
}

if (isset($_POST['import_team'])) {
    $data = $_POST['data'];
    $team_id = $_POST['team_id'];
    if (isset($_POST['is_add'])) {
        $team_id = addTeam($data['term'], $data['course_name'], $data['teacher_name'], $data['class_week_time'], $data['class_id_in_school']);
    }
    echo "team_id: $team_id";
    $studentNumbers = $data['studentNumbers'];
    if (isset($team_id)) {
        updateTeamRelation($team_id, $studentNumbers);
    }
    exit(0);
}

$sql = "SELECT * FROM course_team";
$result = $mysqli->query($sql);
$course_teams = [];
while ($row = $result->fetch_object()) {
    $course_teams[] = $row;
}
$result->free();

?>

<style>

</style>

<title>Manage Course Team</title>
<h1>Manage Course Team</h1>
<hr />

<div class="">
    <table class='table table-striped table-hover table-bordered table-condensed' style='white-space: nowrap; text-align: center; vertical-align: middle;'>
        <thead>
            <tr>
                <th style='text-align: center; vertical-align: middle;'>Team ID</th>
                <th style='text-align: center; vertical-align: middle;'>Term</th>
                <th style='text-align: center; vertical-align: middle;'>Course Name</th>
                <th style='text-align: center; vertical-align: middle;'>Teacher Name</th>
                <th style='text-align: center; vertical-align: middle;'>Class Week Time</th>
                <th style='text-align: center; vertical-align: middle;'>Class ID in School</th>
                <th style='text-align: center; vertical-align: middle;'>Operations</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($course_teams as $row) {
                echo "<tr>";
                echo "<td style='text-align: center; vertical-align: middle;'>{$row->team_id}</td>";
                echo "<td style='text-align: center; vertical-align: middle;'>{$row->term}</td>";
                echo "<td style='text-align: center; vertical-align: middle;'>{$row->course_name}</td>";
                echo "<td style='text-align: center; vertical-align: middle;'>{$row->teacher_name}</td>";
                echo "<td style='text-align: center; vertical-align: middle;'>{$row->class_week_time}</td>";
                echo "<td style='text-align: center; vertical-align: middle;'>{$row->class_id_in_school}</td>";
                echo "<td style='text-align: center; vertical-align: middle;'>
                <a class='btn btn-primary btn-sm import-from-input'>Import (Input)</a>
                <a class='btn btn-primary btn-sm import-from-excel'>Import (Excel)</a>
                <a class='btn btn-danger btn-sm delete-team'>Delete</a>
                  </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <div class='btn btn-success' id="add-new-team">Add New Team</div>
    <div class='btn btn-success' id="add-new-team-from-excel">Add New Team From Excel</div>
    <a href="/OJ/image/course_team_example.xlsx" download class="btn btn-link">下载Excel示例</a>
</div>

<div class="modal fade" id="add-team-modal" tabindex="-1" role="dialog" aria-labelledby="add-team-modal">
    <div class="modal-dialog" role="document" style="margin-top: 25vh;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="add-team-modal">Add New Team</h4>
            </div>
            <div class="modal-body">
                <form id="add-team-form">
                    <div class="form-group">
                        <label for="term">Term</label>
                        <input type="text" class="form-control" id="term" name="term" required>
                    </div>
                    <div class="form-group">
                        <label for="course_name">Course Name</label>
                        <input type="text" class="form-control" id="course_name" name="course_name" required>
                    </div>
                    <div class="form-group">
                        <label for="teacher_name">Teacher Name</label>
                        <input type="text" class="form-control" id="teacher_name" name="teacher_name" required>
                    </div>
                    <div class="form-group">
                        <label for="class_week_time">Class Week Time</label>
                        <input type="text" class="form-control" id="class_week_time" name="class_week_time" required>
                    </div>
                    <div class="form-group">
                        <label for="class_id_in_school">Class ID in School</label>
                        <input type="text" class="form-control" id="class_id_in_school" name="class_id_in_school" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-add-team">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="del-team-modal" tabindex="-1" role="dialog" aria-labelledby="del-team-modal">
    <div class="modal-dialog" role="document" style="margin-top: 25vh;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="del-team-modal">Delete Team</h4>
            </div>
            <div class="modal-body">
                确定删除课程组 <strong id="confirm-del-team-name"></strong> ？这将删除课程组和课程组相关的相关记录。
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-del-team" data-dismiss="modal">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="import-team-modal" tabindex="-1" role="dialog" aria-labelledby="import-team-modal">
    <div class="modal-dialog" role="document" style="margin-top: 25vh;width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="import-team-modal">Import Team（注意：会直接覆盖原始数据！）</h4>
            </div>
            <div class="modal-body">
                <div id="analyze-desc" style="font-size: 15px;"></div>
                <input type="file" id="stu-id-excel" style="margin: 15px 0px;" accept=".xlsx, .xls">
                <textarea id="stu-id-input" style="width: 100%; height:200px; margin: 15px 0px;"></textarea>
                <div id="analyze-result"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-import-team" data-dismiss="modal">Import</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
    async function analyzeExcel(file, is_add) {
        try {
            let reader = new FileReader();

            let data = await new Promise((resolve, reject) => {
                reader.onload = function(e) {
                    resolve(new Uint8Array(e.target.result));
                };
                reader.onerror = function(error) {
                    reject(error);
                };
                reader.readAsArrayBuffer(file);
            });

            let workbook = XLSX.read(data, {
                type: 'array'
            });
            let firstSheet = workbook.Sheets[workbook.SheetNames[0]];
            let jsonData = XLSX.utils.sheet_to_json(firstSheet, {
                header: 1
            });

            const term = is_add ? jsonData[0][0].slice(0, 15) : '';
            const course_name = is_add ? jsonData[1][3].split('：')[1] : '';
            const teacher_name = is_add ? jsonData[2][3].split('：')[1] : '';
            const class_id_in_school = is_add ? jsonData[2][6].split('：')[1] : '';
            const class_week_time = is_add ? jsonData[3][0].split('：')[1].split('{')[0] : '';

            let studentNumbers = [];
            for (let i = 5; i < jsonData.length; i++) {
                let row = jsonData[i];
                if (row[1] && row[1].toString().length === 13) {
                    studentNumbers.push(row[1]);
                }
            }

            return {
                term,
                course_name,
                teacher_name,
                class_id_in_school,
                class_week_time,
                studentNumbers
            };
        } catch (error) {
            console.error(error);
        }
    }

    function importModalInit(){
        $('#stu-id-excel').val('');
        $('#stu-id-input').val('');
        $('#analyze-result').html('');
        $('#stu-id-excel').css('display', 'none');
        $('#stu-id-input').css('display', 'none');
    }

    // 从Excel导入学生到已有课程组
    $('.import-from-excel').click(function() {
        const item = $(this).closest('tr');
        const team_id = item.find('td').first().text();
        const item_values = item.children().toArray().map(i => $(i).text());
        importModalInit();
        $('#stu-id-excel').css('display', 'block');
        $('#import-team-modal').modal();
        $('#import-team-modal').attr('data-is-add', 'false');
        $('#analyze-desc').html(`导入学生到课程组：<strong>${item_values[1]} - ${item_values[2]} - ${item_values[3]} - ${item_values[4]}</strong>`);
        $('#confirm-import-team').off('click').on('click', function() {
            const fileInput = $('#stu-id-excel')[0];
            const file = fileInput.files[0];
            if (file) {
                console.log("**", file);
                analyzeExcel(file, false).then(data => {
                    console.log("**", data);
                    $.post('manage_course_team.php', {
                        import_team: true,
                        team_id,
                        data
                    }, function(data) {
                        location.reload();
                    });
                });
            }
        });
    });

    // 从输入导入学生到已有课程组
    $('.import-from-input').click(function() {
        const item = $(this).closest('tr');
        const team_id = item.find('td').first().text();
        const item_values = item.children().toArray().map(i => $(i).text());
        importModalInit();
        $('#stu-id-input').css('display', 'block');
        $('#import-team-modal').modal();
        $('#analyze-desc').html(`导入学生到课程组：<strong>${item_values[1]} - ${item_values[2]} - ${item_values[3]} - ${item_values[4]}</strong>`);
        $('#confirm-import-team').off('click').on('click', function() {
            let studentNumbers = $('#stu-id-input').val().split(/[\s,]+/).map(i => i.trim()).filter(i => i);
            $.post('manage_course_team.php', {
                import_team: true,
                team_id,
                data: {
                    studentNumbers
                }
            }, function(data) {
                location.reload();
            });
        });
    });

    // 从Excel新建课程组
    $('#add-new-team-from-excel').click(function() {
        importModalInit();
        $('#stu-id-excel').css('display', 'block');
        $('#import-team-modal').modal();
        $('#import-team-modal').attr('data-is-add', 'true');
        $('#analyze-desc').html(`<strong>新建课程组</strong>`);
        $('#confirm-import-team').off('click').on('click', function() {
            const fileInput = $('#stu-id-excel')[0];
            const file = fileInput.files[0];
            if (file) {
                analyzeExcel(file, true).then(data => {
                    $.post('manage_course_team.php', {
                        import_team: true,
                        is_add: true,
                        data
                    }, function(data) {
                        location.reload();
                    });
                });
            }
        });
    });

    $('#stu-id-input').change(function() {
        let studentNumbers = $('#stu-id-input').val().split(/[\s,]+/).map(i => i.trim()).filter(i => i);
        let _html = "解析到以下内容：<br>";
        _html += `学号：<br>${studentNumbers.join(', ')}`;
        $('#analyze-result').html(_html);
    });

    $('#stu-id-excel').change(function() {
        let fileInput = $('#stu-id-excel')[0];
        let file = fileInput.files[0];
        let is_add = $('#import-team-modal').attr('data-is-add') === 'true';
        analyzeExcel(file, is_add).then(data => {
            const {
                course_name,
                teacher_name,
                class_id_in_school,
                class_week_time,
                studentNumbers
            } = data;
            let _html = "解析到以下内容：<br>";
            _html += course_name && `课程名称：${course_name}<br>`;
            _html += teacher_name && `教师名称：${teacher_name}<br>`;
            _html += class_id_in_school && `课程ID：${class_id_in_school}<br>`;
            _html += class_week_time && `每周上课时间：${class_week_time}<br>`;
            _html += `学号：<br>${studentNumbers.join(', ')}`;
            $('#analyze-result').html(_html);
        });
    });

    // 添加新课程组
    $('#add-new-team').click(() => {
        $('#add-team-modal').modal();
    });
    $('#confirm-add-team').click(() => {
        let form = $('#add-team-form')[0];
        if (form.checkValidity()) {
            let formData = new FormData(form);
            formData.append('add_team', true);
            $.ajax({
                url: 'manage_course_team.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    location.reload();
                }
            });
        } else {
            form.reportValidity();
        }
    });

    // 删除课程组
    $('.delete-team').click(function() {
        let item = $(this).closest('tr');
        let team_id = item.find('td').first().text();
        $('#del-team-modal').modal();
        $('#confirm-del-team-name').text(`${item.children().eq(1).text()} - ${item.children().eq(2).text()}
                                - ${item.children().eq(3).text()} - ${item.children().eq(4).text()}`);
        $('#confirm-del-team').off('click').on('click', function() {
            if (item) {
                $.post('manage_course_team.php', {
                    delete_team: true,
                    team_id: team_id
                }, function(data) {
                    location.reload();
                });
            }
        });
    });
</script>

<?php
require_once("admin-footer.php")
?>