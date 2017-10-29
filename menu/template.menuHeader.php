<ul class="iconos_cabecera mgmenu">
	 <li class="iconos_menu" id="videomenu" first="true">
	 	<span>
	 		<a>
			 	<img src="icons/icon_video.png" title="Videos" alt="Videos"/>
			 	<br>
			 	<a class="auxNombreMenu">A</a>
			 	<a class="nombreMenu">Videos</a>
			 	<span class="down_icon"></span>
			</a>
		</span><!-- Begin Item --> 
        <div class="dropdown_fullwidth mgdropdown">
			<div class="mgmenu_tabs "><!-- Begin Item Container -->             
				<ul class="mgmenu_tabs_nav mg_tabs"> 
				 	<li>
						<a id="Inewest" href="#newest" class="current default mgsubcat" onclick="location='/newest/';">RECENTLY ADDED</a>
					</li>	               
                    <li>
                    	<a id="ImostViewed" href="#mostViewed" class="mgsubcat" onclick="location='/most-viewed/';">MOST VIEWED</a>
                    </li>
                    <li>
                    	<a id="Irecommended" href="#recommended" class="mgsubcat" onclick="location='/top-rated/';">MOST RATED</a>
                    </li>
                    <li>
                    	<a id="Irandom" href="#random" class="mgsubcat" onclick="location='/recomendedforyou/';">RECOMENDED FOR YOU</a>                        	
                    </li>							
                    <li>
                    	<a id="IHD" href="#HD"  class="mgsubcat" onclick="location='/hd/';">HD</a>
                    </li>
                </ul>                   
				<div class="mgmenu_tabs_panels""><!-- Begin Panels Container -->
					<div id="random" class="mgmenu_tabs_hide" dinamico="videomenu"></div>
					<div id="mostViewed" class="mgmenu_tabs_hide" dinamico="videomenu"></div>
					<div id="recommended" class="mgmenu_tabs_hide" dinamico="videomenu" ></div>
					<div id="newest" class="toAjax" dinamico="videomenu"></div>
					<div id="HD" class="mgmenu_tabs_hide" dinamico="videomenu" ></div>				   
				</div><!-- End Panels Container -->
				</div>                              
        </div><!-- End Item Container -->           
    </li><!-- End Item -->
	<!--PHOTO-->  
   	<li class="iconos_menu" id="photo" first="true">
	   	<span>
	   		<a>
				<img src="icons/icon_photos.png" title="Photos" alt="Photos"/>
		   		<a class="auxNombreMenu">A</a>
		   		<a class="nombreMenu">Photos</a>
		   		<span class="down_icon"></span>
	   		</a>
	   	</span><!-- Begin Item --> 
		<div class="dropdown_fullwidth mgdropdown">
			<div class="mgmenu_tabs">	<!-- Begin Item Container -->
				<ul class="mgmenu_tabs_nav mg_tabs">
					<li>
						<a id="ImostViewedPhoto" href="#mostViewedPhoto" class="current default mgsubcat" onclick="location='/most-viewed-photo/';">MOST VIEWED</a>
					</li>
                    <li>
                    	<a id="IrecommendedPhoto" class="mgsubcat"  href="#recommendedPhoto" onclick="location='/top-rated-photo/';">MOST RATED</a>
                    </li>
					<li>
						<a id="InewestPhoto" class="mgsubcat"  href="#newestPhoto" onclick="location='/newest-photo/';">RECENTLY ADDED</a>
					</li>
                    <li>
                    	<a id="IrandomPhoto" class="mgsubcat" href="#randomPhoto" onclick="location='/random-photo/';">RANDOM</a>
                    </li>
                    <li>
                    	<a id="IrecomendedStraigthPhoto" class="mgsubcat" href="#recomendedStraigthPhoto" onclick="location='/photo_category/all/';">ALL PHOTOS</a>
                    </li>
        		</ul>
        		<div class="mgmenu_tabs_panels"><!-- Begin Panels Container -->
					<div id="mostViewedPhoto" class="toAjax" dinamico="photos"></div>
					<div id="recommendedPhoto" class="mgmenu_tabs_hide" dinamico="photos" ></div>
					<div id="newestPhoto" class="mgmenu_tabs_hide" dinamico="photos"></div>							
					<div id="randomPhoto" class="mgmenu_tabs_hide" dinamico="photos" ></div>	  					   
					<div id="recomendedStraigthPhoto" class="mgmenu_tabs_hide" dinamico="photos" ></div>	  					   
				</div><!-- End Panels Container -->							
			</div>
		</div><!-- End Item Container -->    
	</li><!-- End Item -->        
</ul><!-- End Mega Menu -->	




