#!/usr/bin/php
<? 
	include('db.php');
	$log = fopen("/log.txt","a"); 
	fwrite($log, "Звернення до db - \r\n"); 
	   
	$date = mysql_query("SELECT scheduled_all_date,scheduled_date,record_num FROM content WHERE scheduled_date = CURDATE()");
	fwrite($log, "Запит к db - \r\n"); 
	while($row = mysql_fetch_assoc($date)) {
		if($row[scheduled_all_date] <= date("Y-m-d H:i")){
			fwrite($log, "є контент на публікацію - \r\n");
			$user = mysql_query("SELECT submitter,be_publish FROM content WHERE record_num = $row['record_num']");
				while($rrow = mysql_fetch_assoc($user)) { 
					if($rrow[be_publish] < 1){
						mysql_query("UPDATE users SET download_cunt = download_cunt + 3 WHERE record_num = $rrow['submitter']");
					}
					fwrite($log, "додавання користувачу можливості скачувати контент - \r\n");
				}
			mysql_query("UPDATE content SET enabled = 1, encoded_date = NOW(),be_publish = 1 WHERE record_num = $row['record_num']");
			updateRelatedContent($row['record_num']);
			fwrite($log, "опубліковано - \r\n");
		}
	}
	function updateRelatedContent($id) { 
		$result = mysql_query("SELECT * FROM content WHERE record_num = '$id'");
		$row = mysql_fetch_assoc($result);
		$relatesContent = array();
		$string = mysql_real_escape_string("$row['title'] $row['keywords']");
		$rresult = mysql_query("SELECT content.*, (MATCH ('title','tags') AGAINST ('$string')) as score FROM content WHERE enabled = 1 AND record_num != $row['record_num'] AND MATCH ('title','tags') AGAINST ('$string'  IN BOOLEAN MODE) HAVING score > 0  ORDER BY score DESC LIMIT 0,50");	        
		while($rrow = mysql_fetch_assoc($rresult)) {
			$relatesContent[] = $rrow['record_num'];	
		}
		$related = implode(",",$relatesContent); 
		mysql_query("UPDATE content SET related = '$related' WHERE record_num = '$row['record_num']'");
	}
	fclose($log);
	exit();
?>