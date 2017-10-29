<?	
	require_once('/db.php');
	$currentcategory=$_GET['id'];
	$videos = new VideosJson();        		   
	if($currentcategory=="random"){
		$result = mysql_query("SELECT subniches.record_num FROM subniches WHERE category = 21");
		$max_results = 10;
		while ($row = mysql_fetch_assoc($result)){
			$subniches[] = $row;						
		}
		foreach ($subniches as $row) {
            if ($_COOKIE[$row['record_num']] != '') {                 
                $cookiemas[$row['record_num']] = $_COOKIE[$row['record_num']];    
            }
        }
        arsort($cookiemas);
        $subniche_mass = array_keys($cookiemas);
        $length = count($subniche_mass);
		if ($length < 3){
			$farray = $videos->getVideos("recomended");
		}else{
			$farray = $videos->getVideos('recomendedSubnicheVideo',true, $subniche_mass[0]);
		}
	}else{
		$farray = $videos->getVideos($currentcategory);
	}
	$max_results = 10;
	$result= array_slice($farray, 0, $max_results);		
	foreach ($result as $row){
		$dirname = str_replace('.flv','',$row["orig_filename"]);
		$subdir = $row["filename"][0].'/'.$row["filename"][1].'/'.$row["filename"][2].'/'.$row["filename"][3].'/'.$row["filename"][4].'/'; 
		$dirname = $subdir.$dirname; 
		$link = generateUrl('video',$row['title'],$row['record_num']);
		echo ' <div class="col_2">';
		echo ' <a class="mg_margin_subcat" href="'.$link.'"><img src="';
		if($row["embed"]==""){
			echo $thumb_url.'/'.$dirname.'/'.$row["orig_filename"].'-'.$row["main_thumb"].'.jpg"' ;
		}else{
			echo '/media/thumbs/embedded/'.$row["record_num"].'.jpg"' ;			
		}									
		echo ' alt="" height="150" class="inline_img"/> <p class="img_description mg_image_subcat">'.truncate($row['title'],20).'</p></a>';		
		echo ' </div>';
	}
?>
						
