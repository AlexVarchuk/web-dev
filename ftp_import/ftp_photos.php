<? 
	require "header.php";
	$ftp_path = $basepath.'/ftp_photos';
	$content_path = $gallery_path;	
	if(is_numeric($_GET[submitter])) {
		$array = scandir($ftp_path); 
		foreach($array as $i) {			
			if($_GET[random]=="on"){
				$_GET[submitter] = rand(85,111);
			}	
			if($i != '.' && $i != '..' && !is_dir($ftp_path.'/'.$i)) {
				$uniq = uniqid(); 
				$newfilename = $uniq.'.jpg';
				unset($array);
				$newfilename = str_replace('#','',$newfilename); 
				$newfilename = str_replace('\'','',$newfilename);
				$newfilename = str_replace('&','',$newfilename);
				if(rename($ftp_path.'/'.$i,$content_path.'/'.$newfilename)){
					$array = scandir($content_path.'/'.$newfilename);
					foreach($array as $j) {
						if($j != '.' && $j != '..'){
							$array[] = $j; 
						}
					}
					mysql_query("INSERT INTO content (title, filename, orig_filename, thumbnail, description, keywords, date_added, submitter, ip, approved, paysite, photos) VALUES ('$i', '$newfilename', '$newfilename', '', '', '', NOW(), '$_GET[submitter]', '', 0, '$_GET[paysite]', 1)") or die(mysql_error()); 
					$insert = mysql_insert_id();
						if($_POST[niche]) {{
								mysql_query("INSERT INTO content_niches (content,niche) VALUES ('$insert','$i')");
						}				
					mysql_query("INSERT INTO images (title, filename, gallery) VALUES ('','$newfilename','$insert')");
					$insertImg = mysql_insert_id();				
					mysql_query("UPDATE content SET thumbnail = '$insertImg' WHERE record_num = '$insert'");				
					$counter++; 
					if(strlen($i) > 0 && is_dir($ftp_path.'/'.$i)) { 
						shell_exec("rm -rf \"$ftp_path/$i\"");
					}
				}
			}
		}
	}
	function dir_copy($srcdir, $dstdir, $offset = '', $verbose = false){
	    if(!isset($offset)) $offset=0;
	    $num = 0;
	    $fail = 0;
	    $sizetotal = 0;
	    $fifail = '';
	    if(!is_dir($dstdir)) mkdir($dstdir);
	    if($curdir = opendir($srcdir)) {
	        while($file = readdir($curdir)) {
	            if($file != '.' && $file != '..') {
	                $srcfile = $srcdir . '/' . $file;    
	                $dstfile = $dstdir . '/' . $file;    
	                if(is_file($srcfile)) {
	                    if(is_file($dstfile)) $ow = filemtime($srcfile) - filemtime($dstfile); else $ow = 1;
	                    if($ow > 0) {
	                        if($verbose) echo "Copying '$srcfile' to '$dstfile'...<br />";
	                        if(copy($srcfile, $dstfile)) {
	                            touch($dstfile, filemtime($srcfile)); $num++;
	                            chmod($dstfile, 0777);  
	                            $sizetotal = ($sizetotal + filesize($dstfile));
	                            if($verbose) echo "OK\n";
	                        }
	                        else {
	                            echo "Error: File '$srcfile' could not be copied!<br />\n";
	                            $fail++;
	                            $fifail = $fifail.$srcfile.'|';
	                        }
	                    }
	                }
	                else if(is_dir($srcfile)) {
	                    $res = explode(',',$ret);
	                    $ret = dir_copy($srcfile, $dstfile, $verbose); 
	                    $mod = explode(',',$ret);
	                    $imp = array($res[0] + $mod[0],$mod[1] + $res[1],$mod[2] + $res[2],$mod[3].$res[3]);
	                    $ret = implode(',',$imp);
	                }
	            }
	        }
	        closedir($curdir);
	    }
	    $red = explode(',',$ret);
	    $ret = ($num + $red[0]).','.(($fail-$offset) + $red[1]).','.($sizetotal + $red[2]).','.$fifail.$red[3];
	    return $ret;
	}
	function buildSelect(name_tables){
		if($name_tables === "users"){
			$result = mysql_query("SELECT * FROM users ORDER BY username ASC");
			while($row =mysql_fetch_array($result)) {
				echo "<option value='$row[record_num]'>$row[username]</option>";
			}
		}elseif ($name_tables === "niches") {
			$presult =  mysql_query("SELECT * FROM niches ORDER BY name ASC");
			while($srow = mysql_fetch_array($presult)) {
				echo "<option $checked value='$srow[record_num]'>$srow[name]</option>";
			}
		}elseif ($name_tables === "paysites") {
			$result = mysql_query("SELECT * FROM paysites ORDER BY name ASC");
			while($row =mysql_fetch_array($result)) {
				echo "<option value='$row[record_num]'>$row[name]</option>";
			}
		}
	}
?>
<div id="right_column">
    <div id="right_top">
    	<div id="right_home"></div>
        <div id="right_right">  
        <a href="index.php">Admin Home</a>          
        <span><a href="ftp_photos.php">FTP Import (Photos)</a></span>  
        </div>
    </div>
    <div id="right_bg">  
  		<h2>FTP<strong>Import (Photos)</strong></h2>
        <div id="index_left" style='width: 100%;'>  
			<form id="form2" name="form2" method="GET" action="">
				<? if($_REQUEST[submitter]) { ?>
				  <p align='center'><? echo $counter; ?> Galleries have been added successfully. <br /><br /><a href='queue.php'>Click here to proceed to the approval queue</a>.</p>
				<? } else { ?>
				  <p align='center'>This script will add all the content from "<i><b><? echo $ftp_path; ?></b></i>" to the approval queue. You will still need to add titles, descriptions, etc to each peice of content.  </p>
				  <p align='center'><strong style='color: #ff0000;'>***YOU NEED TO CHMOD ALL FILES IN THIS DIRECTORY TO 777 FOR THIS TO WORK, OTHERWISE PHP WILL NOT HAVE THE NESSESARY PERMISSIONS!***</strong><br />
				  </p>
				    <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr valign="top">
							<td>
								<table width="327" border="0" align="center">
									<tr>
										<td width="108">Random:</td>
										<td width="108"><input name="random" type="checkbox"></td>
										<td width="108">Uploader:</td>
										<td width="209">
											<select name="submitter"> селект в функцію
												<? 
												buildSelect(users);
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td valign="top">VALUE 1:</td>
										<td>											
											<select name='niche[]' size="10" multiple="multiple">
													<?
													buildSelect(niches);													
													?>
											</select>
											<br/>
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table width="327" border="0" align="center">
									<tr>
										<td width="108">VALUE 2</td>
										<td width="209">
											<select name="paysite" id="paysite">
												<?
												buildSelect(paysites);
												?>
											</select>
										</td>
									</tr>									
								</table>
							</td>
						</tr>
						<tr valign="top">
							<td colspan="2" align="center"><br />
							<input type="submit" name="button2" id="button2" value="Import" /></td>
						</tr>
					</table>
				    <? } ?>
			</form>
    	</div>
    </div>
</div>
<? require "footer.php"; ?>
