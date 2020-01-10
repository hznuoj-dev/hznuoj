<?php 
require_once("admin-header.php");

if ($view_errors == "") { 
	$view_errors="Error! Maybe you don't have the privilege!";
}

?>


<div align="center">
	<img async src="/OJ/image/403.png" style="transform:scale(1.0)"> 
</div>


<h1 align="center">
	<?php echo $view_errors ?>
	<div class="sub header" style="margin-top:0.5em"><a href="javascript:goBack()">上一页</a> | <a href="/OJ/admin/index.php">主页</a></div>
</h1>

<?php
	require_once("admin-footer.php");
?>

<script>
	function goBack() {
		window.history.back();
	}
</script>