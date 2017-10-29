<?	
	require_once('/db.php');
	$currentcategory=$_GET['id'];
	$videos = new VideosJson();        		   
	if($currentcategory=="recommendedPhoto"){
		$result = mysql_query("SELECT subniches.record_num FROM subniches WHERE category = 21");
			$max_results = 10;

			while ($row = mysql_fetch_assoc($result)){
				$num_cookie = $row['record_num'];
				if ($_COOKIE[$num_cookie] > $max_results){
					$max_results = $_COOKIE[$num_cookie];
					$cookie = $num_cookie;
				}				
			}
			if (!$cookie){
				$farray = $videos->getVideos("recomendedPhoto");
			}else{
				$farray = $videos->getVideos('recomendedSubnichePhoto',true, $cookie );
			}
	}else{
		$farray = $videos->getVideos($currentcategory);
	}
	$max_results = 10;
	$result= array_slice($farray, 0, $max_results); 
	foreach ($result as $row){
		$link = generateUrl('photo_page',$row['name'],$row['thumbnail']);
		echo ' <div class="col_2">';
		echo ' <a class="mg_margin_subcat" href="'.$link.'"><img src="';
		echo $gallery_url.'/thumbs/'.$row['filename'].'"' ;							
		echo ' alt="" height="150" class="inline_img"/></a>';
		echo ' <p class="img_description mg_image_subcat">'.ucwords(truncate($row['title'],30)).'</p>';
		echo ' </div>';
	}
?>
