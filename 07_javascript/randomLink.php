<script>
	liens = new Array;
	liens[0] = "products.php";
	liens[1] = "products.php";
	liens[2] = "about_us.php";
	liens[3] = "index.php";
	function goToUrl()
	{
		secret = Math.round(Math.random() * 3);
		window.open(liens[secret],'_self');
	}
</script>