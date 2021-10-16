<?php
require_once("./include/db_info.inc.php");
require_once "include/check_post_key.php";
require_once("./include/my_func.inc.php");
$user_id = $_SESSION['tmp_user_id'];
$npassword = $_POST['npassword'];
$rpassword = $_POST['rpassword'];
if (!preg_match("/^.*(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])\w/", $npassword) || !preg_match("/^.{6,22}$/", $npassword)) {
echo "<script type=\"text/javascript\"> alert(\"The password must consist of upper case letters, lower case letters and numbers, with a length of 6 ~ 22 digits!\"); history.go(-1); </script>";
  exit(0);
}
if ($npassword != $rpassword) {
echo "<script type=\"text/javascript\"> alert(\"Two password different!\"); history.go(-1); </script>";
  exit(0);
}
//echo "<script type=\"text/javascript\"> alert(\"$npassword\");  </script>";
$new_password = pwGen($npassword);
$sql = "UPDATE `users` SET `password` = '".($new_password)."' WHERE `user_id` = '".($user_id)."'";
$mysqli->query($sql) or die ($mysqli->error);
echo "<script type=\"text/javascript\"> alert(\"success!\"); history.go(-2); </script>";
unset($_SESSION['tmp_user_id']);
?>
