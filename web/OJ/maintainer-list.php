<?php $title = "Maintainer List"; ?>
<?php include "template/hznu/header.php" ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HZNUOJ -- Maintainer List</title>
    <style>
        .box {
            border: 1px solid #eee;
            padding: 30px;
            margin: 25px 0 15px 0;
            box-shadow: 2px 2px 10px 0 #ccc;
        }

        .am-table tr:first-child td {
            border-top: 0;
        }

        .am-table {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <div class="am-container" style="padding-top: 20px; max-width: 800px;">
        <h1>Maintainer History</h1>
        <hr>

        <?php
        $people = [
            [
                'Name' => 'Zeyao Dai',
                'Status' => 'Active',
                'School Year' => '2021',
                'Sex' => 'Male',
                'Nickname' => 'Dzy521',
                'E-Mail' => 'zeyaodai@gmail.com',
                'Blog' => ['https://github.com/daizeyao'],
                'Motto' => 'NULL.'
            ],
            [
                'Name' => 'Jiafu Yang',
                'Status' => 'Active',
                'School Year' => '2021',
                'Sex' => 'Male',
                'Nickname' => 'learner',
                'E-Mail' => '2733197830@qq.com',
                'Blog' => ['https://yangjiafu.cn'],
                'Motto' => 'NULL.'
            ],
            [
                'Name' => 'Lyuzhi Pan',
                'Status' => 'Inactive',
                'School Year' => '2017',
                'Sex' => 'Male',
                'Nickname' => 'Dup4',
                'E-Mail' => 'panlvzhi@foxmail.com',
                'Blog' => ['https://github.com/Dup4', 'https://dup4.cn'],
                'Motto' => 'NULL.'
            ],
            [
                'Name' => 'Kaiqing Zhang',
                'Status' => 'Inactive',
                'School Year' => '2016',
                'Sex' => 'Male',
                'Nickname' => 'ZKin',
                'E-Mail' => 'zkqhnfg@gmail.com',
                'Blog' => ['https://github.com/ZKingQ'],
                'Motto' => 'Make the world a better place'
            ],
            [
                'Name' => 'Lixin Wei',
                'Status' => 'Inactive',
                'School Year' => '2015',
                'Sex' => 'Male',
                'Nickname' => 'D_Star, The_Dawn_Star',
                'E-Mail' => 'wlx65005@gmail.com',
                'Blog' => ['https://github.com/wlx65003', 'http://blog.csdn.net/wlx65003', 'https://d-star.xyz'],
                'Motto' => 'After all, tomorrow is another day.'
            ],
            [
                'Name' => 'Ruiyu Zeng',
                'Status' => 'Inactive',
                'School Year' => '2014',
                'Sex' => 'Male',
                'Nickname' => 'hhhh',
                'E-Mail' => '398585603@qq.com',
                'Blog' => ['none'],
                'Motto' => 'none'
            ],
            [
                'Name' => 'Yupeng Chen',
                'Status' => 'Inactive',
                'School Year' => '2012',
                'Sex' => 'Male',
                'Nickname' => 'yybird, yuncity',
                'E-Mail' => 'cyp@hsACM.com',
                'Blog' => ['none'],
                'Motto' => 'Who am I? Where am I? Why am I here?'
            ]
        ];

        foreach ($people as $person) {
            echo '<div class="box">';
            echo '<table class="am-table">';
            echo '<tbody>';
            foreach ($person as $key => $value) {
                echo '<tr>';
                echo '<td width="20%">' . $key . '</td>';
                if ($key == 'Name') {
                    $badgeClass = $person['Status'] == 'Active' ? 'am-badge-success' : '';
                    echo '<td>' . $value . ' <span class="am-badge ' . $badgeClass . '">' . $person['Status'] . '</span></td>';
                } elseif ($key == 'Blog') {
                    echo '<td>';
                    foreach ($value as $link) {
                        echo '<a href="' . $link . '">' . $link . '</a><br>';
                    }
                    echo '</td>';
                } elseif ($key == 'E-Mail') {
                    echo '<td><a href="mailto:' . $value . '">' . $value . '</a></td>';
                } else {
                    echo '<td>' . $value . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }
        ?>

    </div>
</body>

</html>
<?php include "template/hznu/footer.php" ?>
