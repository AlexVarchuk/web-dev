<?
	exec('php /var/www/site.com/admin/exec.php > /dev/null 2>/dev/null &', $output,$return);
	if(!$return){
		echo 'The encoder is running in the background. You can go to <a href="/admin/">admin</a>, and continue work!';
		print_r($output);
	}
?>