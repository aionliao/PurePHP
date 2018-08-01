<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>页面跳转...</title>
	<style>
		* {
			margin: 0;
			padding: 0;
		}

		body {
			background-color: #fff;
			color: #333;
			font-family: 'Microsoft YaHei';
			margin: 40px;
		}

		a {
			color: #333;
		}

		.tips {
			font-size: 60px;
		}

		.tips span {
			font-size: 120px;
		}
		
		.link {
			line-height: 40px;
		}
	</style>
</head>

<body>
	<div class="tips">
		<span>
			<?php if($results == 'failure'){ ?>
			:(
			<?php }else{ ?>
			:)
			<?php } ?>
		</span>
		<?php echo $msg; ?>
	</div>
	<div class="link">
        <a href="<?php if($url == ''){ ?>javascript:window.history.go(-1);<?php }else{ echo $url;} ?>">
			<span class="sec"></span> 秒后返回上页，点击链接直接跳转...</a>或者，你可以
		<a href="/">返回首页</a>
	</div>
</body>

</html>
<script>
	sec = 5;
	function time() {
		document.querySelector('.sec').innerHTML = sec;
		sec--;
		if (sec <= 0)
            <?php if ($url == '') {
				if($results == 'success'){ ?>
			window.location.href=document.referrer;
				<?php } else { ?>
            window.history.go(-1);
            <?php } } else { ?>
            window.location.href='<?php echo $url; ?>';
            <?php } ?>
            setTimeout('time()', 1000);
	}
	time();
</script>