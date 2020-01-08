<?php
$title="Error";
require_once("header.php");
?>

<?php
	if ($view_errors == "") { 
		$view_errors="You don't have the privilege to view this page!";
	}
?>

<div align="center">
	<img async src="/OJ/image/403.png" style="transform:scale(1.0)"> 
</div>

<h1 align="center">
	<?php echo $view_errors ?>
	<div class="sub header" style="margin-top:0.5em"><a href="javascript:goBack()">上一页</a> | <a href="/">主页</a></div>
	</h1>

<?php require_once("footer.php") ?>
<script>
	function goBack() {
		window.history.back();
	}
</script>