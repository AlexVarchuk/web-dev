<?
    include_once('db.php');
    include_once('getid3/getid3.php');
    ini_set('memory_limit', '4048M');
    //cheching status
    $result = mysql_query("SELECT * FROM encoding_log_status");
    $row = mysql_fetch_array($result); 
    if($row['encoder_running'] == 1) {
        exit("Encoding already marked running!");
    }
    $counter = 0;
    if(is_odd($thumbwidth)) {
        $thumbwidth++; 
    }
    if(is_odd($thumbheight)) {
        $thumbheight++; 
    }
    $rresult = mysql_query("SELECT record_num FROM content WHERE approved = 1 AND photos = 0") or die(mysql_error());
    if(mysql_num_rows($rresult) < 1) {
        echo "No videos to encode!";
    }

    $encoderTotal = mysql_num_rows($rresult);
    mysql_query("UPDATE status SET encoder_running = 1, encoder_total = '$encoderTotal'"); 
    $result = mysql_query("SELECT * FROM content WHERE approved = 1 AND photos = 0 order by record_num");
    while($row = mysql_fetch_array($result)) {
        //create dir for video
        $replace = array("#"," "); 
        $row['filename'] = str_replace($replace,'',$row['filename']); 
        $row['filename'] = str_replace($replace,'',$row['filename']); 
        mysql_query("UPDATE content SET filename = '$row['filename']' WHERE record_num = '$row['record_num']'");
        $rrow[id] = $row['record_num'];
        $first = $row['filename']['0'];
        $second = $row['filename']['1']; 
        $third = $row['filename']['2'];
        $forth = $row['filename']['3']; 
        $fifth = $row['filename']['4'];
        $dir_ok = mkdir($video_path.'/'.$first.'/'.$second.'/'.$third.'/'.$forth.'/'.$fifth, 0777, true);
        $subdir = $first.'/'.$second.'/'.$third.'/'.$forth.'/'.$fifth.'/'; 

        $q = "cp \"".$content_path."/".$row['orig_filename']."\" \"$video_path/$subdir".$row['orig_filename']."\" 2>&1"; 
        echo "Copying... ".$row['orig_filename']." -> ".$row['orig_filename']."\n";
        echo "Command: ".$q."\n";
        shell_exec($q);
        $ruta_video = $video_path."/".$subdir.$row['orig_filename'];     
        $informacion_video = pathinfo($ruta_video);
        $nombre_sin_extension = $informacion_video['dirname']."/".$informacion_video['filename'];                     
        $duracion_total= shell_exec ("$ffmpeg_path -i \"".$ruta_video."\" 2>&1 |  grep -oP \"(?<=Duration: ).*(?=, start.*)\" ");          
        $tamano_video = shell_exec ("$ffprobe_path -select_streams v -show_streams \"".$ruta_video."\" 2>/dev/null | grep width | sed -e 's/width=//'");                   
        $tiempo_total = strtotime($duracion_total) - strtotime('TODAY');
        $frame_inicio_watermark =floor($tiempo_total*35/100);
        $frame_fin_watermark = $frame_inicio_watermark + floor($tiempo_total*10/100);      
        $fp = fopen("/encode.txt", "a"); 
        $mytext = date('l jS \of F Y h:i:s A')." Start encode file. File size ".filesize($ruta_video)."  byte \r\n";
        fwrite($fp, $mytext);

        if($tamano_video < 480 ){
            echo "we in 1 block where id $rrow['id']";
            echo "||||||".$tamano_video."|||||";
            mysql_query("UPDATE content SET encoded_size = 1, 480px = 1 WHERE record_num = '$rrow[id]'");
            var_dump(shell_exec(" ffmpeg -i \"".$ruta_video."\" ".$nombre_sin_extension.".mp4"));
            var_dump(shell_exec(" $ffmpeg_path -i \"".$ruta_video."\" ".$nombre_sin_extension."_480.mp4 -y 2>&1"));
            echo "make 480px";
        }elseif($tamano_video >= 480 && $tamano_video < 720){
            echo "we in 1 block where id $rrow['id']";
            mysql_query("UPDATE content SET encoded_size = 1, 480px = 1 WHERE record_num = '$rrow[id]'");
            var_dump(shell_exec(" ffmpeg -i \"".$ruta_video."\" -c:a aac -b:a 128k -c:v libx264 -crf 23 ".$nombre_sin_extension.".mp4"));
            var_dump(shell_exec(" $ffmpeg_path -i \"".$ruta_video."\" -s hd480 -c:v libx264 -crf 23 -c:a aac -strict -2 ".$nombre_sin_extension."_480.mp4 -y 2>&1"));
            echo "make 480px";
        }elseif ($tamano_video >= 720 && $tamano_video < 1080){
            echo "we in 2 block where id $rrow[id]";
            mysql_query("UPDATE content SET encoded_size = 1, 480px = 1, 720px = 1 WHERE record_num = '$rrow[id]'");
            var_dump(shell_exec(" ffmpeg -i \"".$ruta_video."\" -c:a aac -b:a 128k -c:v libx264 -crf 23 ".$nombre_sin_extension.".mp4"));
            var_dump(shell_exec(" $ffmpeg_path -i \"".$ruta_video."\" -s hd480 -c:v libx264 -crf 23 -c:a aac -strict -2 ".$nombre_sin_extension."_480.mp4 -y 2>&1"));
            echo "make 480px";
            var_dump(shell_exec(" $ffmpeg_path -i \"".$ruta_video."\" -s hd720 -c:v libx264 -crf 23 -c:a aac -strict -2 ".$nombre_sin_extension."_720.mp4 -y 2>&1"));
            echo "make 720px";
        }elseif ($tamano_video >= 1080){
            echo "we in 3 block where id $rrow[id]";
            mysql_query("UPDATE content SET encoded_size = 1, 480px = 1, 720px = 1, 1080px = 1 WHERE record_num = '$rrow[id]'");
            var_dump(shell_exec(" ffmpeg -i \"".$ruta_video."\" -c:a aac -b:a 128k -c:v libx264 -crf 23 ".$nombre_sin_extension.".mp4"));
            var_dump(shell_exec(" $ffmpeg_path -i \"".$ruta_video."\"  -s hd480 -c:v libx264 -crf 23 -c:a aac -strict -2 ".$nombre_sin_extension."_480.mp4 -y 2>&1"));
            echo "make 480px";
            var_dump(shell_exec(" $ffmpeg_path -i \"".$ruta_video."\"   -s hd720 -c:v libx264 -crf 23 -c:a aac -strict -2 ".$nombre_sin_extension."_720.mp4 -y 2>&1"));
            echo "make 720px";
            var_dump(shell_exec(" $ffmpeg_path -i \"".$ruta_video."\"   ".$nombre_sin_extension."_1080.mp4 -y 2>&1"));
            echo "make 1080px";
        }                     
        shell_exec("cd ".$informacion_video['dirname'].";$mp4box_path -inter 500 \"".$nombre_sin_extension."_".$size.".mp4\""); 
       
        $mytext = date('l jS \of F Y h:i:s A')." End encode file. File size ".filesize($ruta_video)."  byte \r\n";
        if($fp){
            fwrite($fp, $mytext);
        }else{
            $fp = fopen("/encode.txt", "a");
            fwrite($fp, $mytext);
        } 
        fclose($fp);       
     
        $target_movie = $video_path."/$subdir".$row['filename'];
        $getID3 = new getID3;
        $ThisFileInfo = $getID3->analyze("$video_path/$subdir".$row['filename']);
        $reallengthid3 = $ThisFileInfo['playtime_string']; 
        $thisarray = explode(':',$reallengthid3);
        $length = ($thisarray[0]*60)+$thisarray[1];
        $movie_width = $ThisFileInfo['video']['resolution_x'];
        $movie_height = $ThisFileInfo['video']['resolution_y'];
        if($numFrames == 0) {
            $numFrames = $length*15; 
        }
        dbReconnect();
        mysql_query("UPDATE content SET length = '$length' WHERE record_num = '$rrow['id']'");
        $dirname = str_replace('.flv','',$row['orig_filename']); 
        @mkdir($thumb_path."/".$subdir.$dirname); 
        //create thumbnails
        echo "Total Length: $length\n";
        $interval = floor($length/13); 
        $first = 1; 
        for($i = 0; $i <11; $i++) {
            $target_imagec = "$thumb_path/$subdir$dirname/".$row['orig_filename']."-$i".".jpg";
                echo "Creating thumb #$i at second: $first...";
                shell_exec("$ffmpeg_path -ss $first -i \""."$video_path/$subdir".$row['filename']."\" -vcodec mjpeg -vframes 1 -an -f rawvideo -s ".$thumbwidth."x".$thumbheight." \"$target_image\"");
                if(file_exists($target_image)) {
                    echo "Success!\n";
                }
                else {
                    echo "Failure! Unable to create thumbnail!\n";
                }
                if(file_exists("/usr/local/bin/mogrify")) {
                    shell_exec("/usr/local/bin/mogrify $imagick_command $target_image"); 
                }
                else {
                    shell_exec("/usr/bin/mogrify $imagick_command $target_image"); 
                }
            $first = $first+$interval;
        }
        if($row['source_thumb_url']) { 
            $fileSource = file_get_contents($row['source_thumb_url']);
            if(strlen($fileSource) > 48) { 
                $target_image = "$thumb_path/$subdir$dirname/".$row["orig_filename"]."-1.jpg";
                shell_exec("$mogrify_path -resize ".($thumbwidth)."x".($thumbheight)."^ -gravity Center -extent ".$thumbwidth."x".$thumbheight." \"$target_image\"");
                file_put_contents($target_imageb,$fileSource);
                dbReconnect();
                mysql_query("UPDATE content SET main_thumb = 1 WHERE record_num = '$rrow['id']'") or die(mysql_error());            
            }
        }        
        dbReconnect(); 
        mysql_query("UPDATE content SET approved = 2, encoded_date = NOW(), movie_width = '$movie_width', movie_height = '$movie_height' WHERE record_num = '$rrow['id']'") or die(mysql_error());
        mysql_query("UPDATE status SET encoder_done = encoder_done+1") or die(mysql_error());
                    
        //add to appropriate categories.
        if($row[keywords]) { 
            $parray = explode(',',$row[keywords]);
            foreach($parray as $p) {
                $p = trim($p); 
                $tresult = mysql_query("SELECT record_num FROM niches WHERE name LIKE '$p' OR (csv_match != '' AND csv_match LIKE '%$p%')");
                if(mysql_num_rows($tresult) > 0) {
                    $trow = mysql_fetch_array($tresult);
                    mysql_query("INSERT INTO content_niches (niche, content) VALUES ($trow['record_num'], $rrow['id'])");
                    unset($trow, $tresult); 
                }
            }
        }
            
            
        //delete source
        unlink("$content_path/".$row['orig_filename']);
        if($deleteHotlinkedVideos && $row['hotlinked']) { 
            unlink("$video_path/$subdir".$row['filename']);
        }      
        echo "Success! $counter\n\n";
        $counter++;
    }       
    echo "\n----------------------\nEncoding Complete - $counter videos converted"; 
    mysql_query("UPDATE status SET encoder_running = '0', encoder_total = '0', encoder_done = '0'");
?> 