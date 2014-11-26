<?php
/***************************************************************
 * Insert elements in the DOM : HTML & SCRIPT
 ***************************************************************/
function an_prepare(){
	$an_option = TitanFramework::getInstance( 'adblocker_notify' );

	//Retrieve options
	//General Options
	$anOptionChoice = $an_option->getOption( 'an_option_choice' );
	$anOptionStats = $an_option->getOption( 'an_option_stats' );
	$anOptionSelectors = $an_option->getOption( 'an_option_selectors' );
	$anOptionCookie = $an_option->getOption( 'an_option_cookie' );
	$anOptionCookieLife = $an_option->getOption( 'an_option_cookie_life' );
	$anModalTitle = $an_option->getOption( 'an_modal_title' );
	$anModalText = $an_option->getOption( 'an_modal_text' );
	$anPageRedirect = $an_option->getOption( 'an_page_redirect' );
	$anPageNojsActivation = $an_option->getOption( 'an_page_nojs_activation' );
	$anPageNojsRedirect = $an_option->getOption( 'an_page_nojs_redirect' );

	//Modal Options
	$anOptionModalEffect = $an_option->getOption( 'an_option_modal_effect' );
	$anOptionModalSpeed = $an_option->getOption( 'an_option_modal_speed' );
	$anOptionModalClose = $an_option->getOption( 'an_option_modal_close' );
	$anOptionModalBgcolor = $an_option->getOption( 'an_option_modal_bgcolor' );
	$anOptionModalBgopacity = $an_option->getOption( 'an_option_modal_bgopacity' );
	$anOptionModalBxcolor = $an_option->getOption( 'an_option_modal_bxcolor' );
	$anOptionModalBxtitle = $an_option->getOption( 'an_option_modal_bxtitle' );
	$anOptionModalBxtext = $an_option->getOption( 'an_option_modal_bxtext' );
	$anOptionModalCustomCSS = $an_option->getOption( 'an_option_modal_custom_css' );

	//Modal Options
	$anAlternativeActivation = $an_option->getOption( 'an_alternative_activation' );
	$anAlternativeElement = $an_option->getOption( 'an_alternative_elements' );
	$anAlternativeText = $an_option->getOption( 'an_alternative_text' );
	$anAlternativeClone = $an_option->getOption( 'an_alternative_clone' );
	$anAlternativeProperties = $an_option->getOption( 'an_alternative_properties' );
	$anAlternativeCss = $an_option->getOption( 'an_alternative_custom_css' );
	
	//remove cookie if deactivate
	an_remove_cookie(AN_COOKIE, $anOptionCookie);
	
	//redirect URL with JS
	$anPermalink = an_url_redirect($anPageRedirect);

	//Modal box effect
	$anOptionModalEffect = an_modal_parameter($anOptionModalEffect);
	//Modal box close
	$anOptionModalClose = an_modal_close($anOptionModalClose);
	
	//Style construct
	//Overlay RGA color
	$anOptionModalOverlay = hex2rgba($anOptionModalBgcolor, $anOptionModalBgopacity/100);

	//Load random selectors
	$anSelectors = unserialize( get_option( 'adblocker_notify_selectors' ) );
	
	//DOM and Json
	if($anOptionSelectors == false) {
		$output .= '<div id="an-Modal" class="reveal-modal" ';
	} else {
		$output .= '<div id="' . $anSelectors['selectors'][0] . '" class="' . $anSelectors['selectors'][1] . '" ';
	}
	$output .= 'style="background:'. $anOptionModalBxcolor .';';
	if(!empty($anOptionModalBxtext))
	$output .= 'color:'. $anOptionModalBxtext;
	$output .= '"></div>';
	$output .= '<script type="text/javascript">';
	$output .= '/* <![CDATA[ */';
	$output .= 'var anOptions =' . 
		json_encode(array(
			'anOptionChoice' => $anOptionChoice,
			'anOptionStats' => $anOptionStats,
			'anOptionCookie' => $anOptionCookie,
			'anOptionCookieLife' => $anOptionCookieLife,
			'anModalTitle' => $anModalTitle, 
			'anModalText' => do_shortcode($anModalText), 
			'anPageRedirect' => $anPageRedirect, 
			'anPermalink' => $anPermalink,
			'anOptionModalEffect' => $anOptionModalEffect, 
			'anOptionModalspeed' => $anOptionModalSpeed, 
			'anOptionModalclose' => $anOptionModalClose,
			'anOptionModalOverlay' => $anOptionModalOverlay,
			'anOptionModalBxtitle' => $anOptionModalBxtitle,
			'anAlternativeActivation' => $anAlternativeActivation, 
			'anAlternativeElement' => $anAlternativeElement, 
			'anAlternativeText' => do_shortcode($anAlternativeText),
			'anAlternativeClone' => $anAlternativeClone,
			'anAlternativeProperties' => $anAlternativeProperties,
		));
	$output .= '/* ]]> */';
	$output .= '</script>';

	//NO JS Redirect
	if(!empty($anPageNojsActivation) && !$_COOKIE[AN_COOKIE]) {
		
		//redirect URL with NO JS
		$anNojsPermalink = an_url_redirect($anPageNojsRedirect);
		
		if($anNojsPermalink != "undefined"){
			//Set new cookie value	
			an_nojs_cookie($expiration);		
			$output .= '<noscript><meta http-equiv="refresh" content="0; url='. $anNojsPermalink .'" /></noscript>';
		}
	}
	$output = apply_filters( 'an_prepare', $output );
	echo $output;

}
add_action('wp_footer', 'an_prepare');


/***************************************************************
 * Deregister custom stylesheet if option is empty 
 ***************************************************************/
function an_deregister_styles() {
	$anBlockerNotify = unserialize( get_option( 'adblocker_notify_options') );

	if(empty($anBlockerNotify['an_option_modal_custom_css']) && empty($anBlockerNotify['an_alternative_custom_css'])){ 
		wp_deregister_style( 'tf-compiled-options-adblocker_notify' );
	}

}
add_action( 'wp_print_styles', 'an_deregister_styles', 100);


/***************************************************************
 * Append fake ad to Wordpress content
 ***************************************************************/
function an_fake_an_content($content) {
	$content .= '<div id="adsense" class="an-sponsored" style="position:absolute; z-index:-1; visibility: hidden;"><img class="an-advert-banner" alt="sponsored" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"></div>';
	return $content;
}
add_filter( 'the_content', 'an_fake_an_content' );


/***************************************************************
 * Generate redirection URL with page ID
 ***************************************************************/
function an_url_redirect($pageId){
	if ( is_main_query() ) {
		$currentPage = get_queried_object_id();
	} else {
		global $wp;
		$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
		$currentPage = url_to_postid($current_url);
	}
	
	if(!empty($pageId) && $pageId != $currentPage){
		$anPermalink = get_permalink( $pageId );
	} else {
		$anPermalink = "undefined";
	}
	return $anPermalink;
}


/***************************************************************
 * Remove cookie when option is disabled
 ***************************************************************/
function an_remove_cookie($cookieName, $anOptionCookie){
	if (isset($_COOKIE[$cookieName]) && $anOptionCookie != 1) {
		unset($_COOKIE[$cookieName]);
		setcookie($cookieName, null, -1, '/');
	}
}


/***************************************************************
 * Restart cookie on every options save.
 ***************************************************************/
function an_restart_cookie(){
	if (isset($_COOKIE[AN_COOKIE])) {
		unset($_COOKIE[AN_COOKIE]);
		setcookie(AN_COOKIE, null, -1, '/');
	}
}
add_action('tf_admin_options_saved_adblocker_notify', 'an_restart_cookie');


/***************************************************************
 * Set cookie for No JS redirection.
 ***************************************************************/
function an_nojs_cookie($expiration){
	$expiration = time()+($expiration*24*60*60);
	if (!isset($_COOKIE[AN_COOKIE])) {
		setcookie(AN_COOKIE, true, $expiration, '/');
	}
}
 
 
/***************************************************************
 * Modal Box effect parameter
 ***************************************************************/
function an_modal_parameter($key){
	switch ($key) {
		case '':
		case 1:
			$key = 'fadeAndPop';
			break;
		case 2:
			$key = 'fade';
			break;
		case 3:
			$key = 'none';
			break;
		default : 
			$key = 'fadeAndPop';
			break;
	}
	return $key;
}


/***************************************************************
 * Modal Boxe closing option
 ***************************************************************/
function an_modal_close($key){
	switch ($key) {
		case '':
		case 1:
			$key = true;
			break;
		case 2:
			$key = false;
			break;
		default : 
			$key = true;
			break;
	}
	return $key;
}


/***************************************************************
 * Convert hexdec color string to rgb(a) string
 * Src: http://mekshq.com/how-to-convert-hexadecimal-color-code-to-rgb-or-rgba-using-php/
 ***************************************************************/
function hex2rgba($color, $opacity = false) {
	$default = 'rgb(0,0,0)';

	//Return default if no color provided
	if(empty($color))
          return $default; 

		//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
}


/***************************************************************
 * Page views & page blocked counter
 ***************************************************************/

function an_adblock_counter() {
	if ( current_user_can( 'manage_options' ) || empty ($_POST['an_state']))
	return;
	
	$an_states = $_POST['an_state'];
	$anCount = get_option('adblocker_notify_counter');
	
	foreach ($an_states as $an_state){
		if(empty($anCount)){
			$anCount = array('total' => 0, 'blocked' => 0, 'deactivated' => 0, 'history' => array());
			add_option('adblocker_notify_counter', $anCount);
		}
	
		//update option with new values
		$anCount[$an_state]++;
	
		//then update history
		$anCount = an_history_counter($an_state, $anCount);
		
	}
	
	//update db	
	update_option('adblocker_notify_counter', $anCount);
	

	exit;
}
add_action( 'wp_ajax_call_an_adblock_counter', 'an_adblock_counter' );
add_action( 'wp_ajax_nopriv_call_an_adblock_counter', 'an_adblock_counter' );


/***************************************************************
 * Calcul date diff
 ***************************************************************/
function an_date_diff($toDay, $toCheck){
	$todayObj = new DateTime($toDay);
	$expiredObj = new DateTime($toCheck);
	$dateDiff = $todayObj->diff($expiredObj);
	return $dateDiff->days;
} 


/***************************************************************
 * Page history counter
 ***************************************************************/
function an_history_counter($val=null, $anCount) {
	$anToday = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
	//$anToday = date( 'Y-m-d', strtotime( '3 day', strtotime( date( 'Y-m-d', current_time( 'timestamp', 0 ) ) ) ) );
	
	if(empty($anCount['history'][0])){

		$anCount['history'][0] = array('date' => $anToday, 'total' => $anCount['total'], 'blocked' => $anCount['blocked']);
	
	} else {
		
		$anDate = $anCount['history'][0]['date'];
		$anDiff = an_date_diff($anToday, $anDate);
		
		if($anDate == $anToday){
			
			//increase current date
			if( $val == 'total' ){
				$anCount['history'][0]['total'] = $anCount['history'][0]['total'] + 1;
			} elseif( $val == 'blocked' ){
				$anCount['history'][0]['blocked'] = $anCount['history'][0]['blocked'] + 1;
			}
			
		} else if($anDiff > 0){
			
			//remove last + add new one
			if( $val == 'total' ){
				$anNew = array('date' => $anToday, 'total' => 1, 'blocked' => 0);
			} elseif( $val == 'blocked' ){
				$anNew = array('date' => $anToday, 'total' => 1, 'blocked' => 1);
			}
			$anCount['history'] = array_merge(array($anNew),$anCount['history']);
			
			if (count($anCount['history'] == 8)) {
				$anOld = an_date_diff($anToday, $anCount['history'][7]['date']);
				if ($anOld == 7 && count($anCount['history'] == 8)) {
					
					//remove last + add new one ($anRemove is a rubbish var)
					$anRemove = array_pop($anCount['history']);
				}
			}

		} 
	}
	
	return $anCount;

}


/***************************************************************
 * Data history extraction & order revert for chart
 ***************************************************************/
function an_widget_data_histoty($val=null){
	$anCount = get_option( 'adblocker_notify_counter' );
	if(empty($anCount['history'][0]))
	return;

	foreach($anCount['history'] as $row){
		$anOutput[] = $row[$val];
	}
	return $anOutput;
}


/***************************************************************
 * Display the Dashboard Widget
 ***************************************************************/
function an_get_counters() {
	$anCount = get_option('adblocker_notify_counter');
	
	if(empty($anCount)) {
		echo '<p>No data</p>';
		return $output;
	}


	//prevent plugin's counter to be higher than the page counter if page is refreshed during the ajax call or if wordpress caching systeme in not badly configured
	if($anCount['blocked'] > $anCount['total']){
		$anCount['total'] = $anCount['blocked'];
	}
	if($anCount['history'][0]['blocked'] > $anCount['history'][0]['total']){
		$anCount['history'][0]['total'] = $anCount['history'][0]['blocked'];
	}
	
	//update db	
	update_option('adblocker_notify_counter', $anCount);

	if(empty($anCount['total']))
	$anCount['total'] = 0;

	if(empty($anCount['history'][0]['total']))
	$anCount['history'][0]['total'] = 0;

	if(empty($anCount['blocked']))
	$anCount['blocked'] = 0;

	if(empty($anCount['history'][0]['blocked']))
	$anCount['history'][0]['blocked'] = 0;

	if(empty($anCount['deactivated']))
	$anCount['deactivated'] = 0;

	$totalNoBlocker = $anCount['total'] - $anCount['blocked'];
	if($anCount['total'] != 0){
		$average = round(($anCount['blocked']/$anCount['total'])*100,2);
	} else {
		$average = 0;
	}
	
	$totalNoBlockerToday = $anCount['history'][0]['total'] - $anCount['history'][0]['blocked'];
	if($anCount['total'] != 0){
		$averageToday = round(($anCount['history'][0]['blocked']/$anCount['history'][0]['total'])*100,2);
	} else {
		$averageToday = 0;
	}

	$output .= '
		<table class="an-stats-table">
			<tr class="an-top">
			  <td><a href="#" class="antooltip" data-antooltip="'. __( 'Admins are excluded from this statistics.', 'an-translate' ) .'"><span class="dashicons dashicons-info"></span></a></td>
			  <td>'. __( 'Total', 'an-translate' ) .'</td> 
			  <td>'. __( 'Today', 'an-translate' ) .'</td>
			</tr>
			<tr>
			  <td style="text-align:left;"><span style="color:#34495e">&#9608</span> '. __( 'Pages Views', 'an-translate' ) .'</td>
			  <td>' .$anCount['total'] .'</td> 
			  <td>' .$anCount['history'][0]['total'] .'</td>
			</tr>
			<tr>
			  <td style="text-align:left;"><span style="color:#e74c3c">&#9608</span> '. __( 'Pages with Adblock', 'an-translate' ) .'</td>
			  <td>' .$anCount['blocked'] .'</td> 
			  <td>' .$anCount['history'][0]['blocked'] .'</td>
			</tr>
		</table>

		<div class="an-canvas-container-donut">
			<div class="an-average"><span>'. __( 'Total', 'an-translate' ) .'</span>' .$average. '%<span>'. __( 'Ads blocked', 'an-translate' ) .'</span></div>
			<canvas id="an-canvas-donut" height="180"></canvas>
		</div>

		<div class="an-canvas-container-donut">
			<div class="an-average"><span>'. __( 'Today', 'an-translate' ) .'</span>' .$averageToday. '%<span>'. __( 'Ads blocked', 'an-translate' ) .'</span></div>
			<canvas id="an-canvas-donut-today" height="180"></canvas>
		</div>
		<p>
			<strong>' . $anCount['deactivated'] . '</strong> '. __( 'Ad Blocker software deactivated', 'an-translate' ) .'
			<a href="#" class="antooltip" data-antooltip="'. __( 'You may probably increase this number by improving your custom messages', 'an-translate' ) .'."><span class="dashicons dashicons-info"></span></a>
		</p>
		<div id="an-canvas-container-line">
			<canvas id="an-canvas-line"></canvas>
		</div>
		<p>
			<a href="options-general.php?page='.AN_ID.'" class="button button button-primary action">'. __( 'Settings', 'an-translate' ) .'</a>
			&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp
			<a href="options-general.php?page='.AN_ID.'&an-reset=true"  onclick="javascript:if(!confirm(\''. __( 'Are you sure you want to delete permanently your datas?', 'an-translate' ) .'\')) return false;" class="button action">'. __( 'Reset Stats', 'an-translate' ) .'</a>
		</p>';
	
	$output .= '<script type="text/javascript">';
	$output .= '/* <![CDATA[ */';
	$output .= 'var anWidgetOptions =' . 
		json_encode(array(
			'totalNoBlocker' => $totalNoBlocker,
			'anCountBlocked' => $anCount['blocked'],
			'totalNoBlockerToday' => $totalNoBlockerToday,
			'anCountBlockedHistory' => $anCount['history'][0]['blocked'],
			'anDataHistotyTotal' => an_widget_data_histoty('total'), 
			'anDataHistotyBlocked' => an_widget_data_histoty('blocked'), 
		));
	$output .= '/* ]]> */';
	$output .= '</script>';

	echo $output;
}
 

/***************************************************************
 * Register the Dashboard Widget display function
 ***************************************************************/
function an_dashboard_widgets() {
	$adBlockeNotify = unserialize(get_option( 'adblocker_notify_options'));
	if( $adBlockeNotify['an_option_stats'] != 2 ) { 		
		global $wp_meta_boxes;
		wp_add_dashboard_widget('an_dashboard_widgets', '<img src="' . AN_URL . 'img/icon-bweb.svg" class="bweb-logo" alt="b*web"/>&nbsp;&nbsp;'. __( 'Adblock Notify Stats', 'an-translate' ), 'an_get_counters');
 		//Chart JS
    	wp_enqueue_script( 'an_chart_js', AN_URL . 'lib/chart-js/Chart.min.js', array( 'jquery' ),  NULL);
   		//CSS & JS
		add_action( 'admin_enqueue_scripts', 'an_register_admin_scripts' );
	}
}
add_action('wp_dashboard_setup', 'an_dashboard_widgets');


/***************************************************************
 * Reset plugin options
 ***************************************************************/
function an_stats_notice() {
	echo '<div class="updated top"><p><strong>Adblock Notify stats have been successfully cleared.</strong></p></div>';
}

function an_reset_stats() {
    $screen = get_current_screen();
    if ( $screen->id != 'toplevel_page_'. AN_ID )
        return;
		
	if($_GET['an-reset'] == 'true'){
		
		delete_option( 'adblocker_notify_counter' );
		add_action( 'admin_notices', 'an_stats_notice' );
	}
}
add_filter('admin_head', 'an_reset_stats' );


/***************************************************************
 * Generate random selector or file name
 ***************************************************************/
function an_random_slug() {
	$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	$prefix = array();
	$alphaLength = strlen($alphabet) - 1;
	for ($i = 0; $i < 12; $i++) {
		$n = rand(0, $alphaLength);
		$prefix[] = $alphabet[$n];
	}
	return implode($prefix);
}


/***************************************************************
 * Create new Style and Script files in a temp directory
 ***************************************************************/
function an_change_files_css_selectors($tempFolderPath, $tempFolderURL, $file, $oldFileName=null, $newFileName, $newSelectors, $content=''){

	//Get default css and js files
	$fileExt = pathinfo($file, PATHINFO_EXTENSION);
	$fileContent = file_get_contents($file) . $content;
	
	//Replace default selectors with new ones
	$defaultSelectors = array('an-Modal', 'reveal-modal', 'an-alternative');
	$fileContent = str_replace($defaultSelectors, $newSelectors, $fileContent);

	//Load WP_Filesystem API
	WP_Filesystem();
	global $wp_filesystem;

	//Verify that we can create the file
	if ( $wp_filesystem->exists( $tempFolderPath.$oldFileName ) ) {
		if ( ! $wp_filesystem->is_writable( $tempFolderPath.$oldFileName ) ) {
			return false;
		}
		if ( ! $wp_filesystem->is_readable( $tempFolderPath.$oldFileName ) ) {
			return false;
		}
	}
	//Verify directory
	$uploadDir = wp_upload_dir();
	if ( ! $wp_filesystem->is_dir( $uploadDir['basedir'] ) ) {
		return false;
	}
	if ( ! $wp_filesystem->is_writable( $uploadDir['basedir'] ) ) {
		return false;
	}
		
	//Creat new dir and files
	if ( $wp_filesystem->is_dir( $tempFolderPath ) ) {
		array_map('unlink', glob( $tempFolderPath . '*.' . $fileExt) );
	} else {
		$wp_filesystem->mkdir( $tempFolderPath );
	}
	$wp_filesystem->put_contents( $tempFolderPath . $newFileName . '.' . $fileExt, $fileContent, 0644 );
	$file = $tempFolderURL . $newFileName . '.' . $fileExt;
	
	return $newFileName . '.' . $fileExt;
}


/***************************************************************
 * Save scripts and styles with new random selectors after saving Titan Options
 ***************************************************************/ 
function an_save_setting_random_selectors() {

	$an_option = unserialize( get_option( 'adblocker_notify_options' ) );
	
	//Define new temp path
	$uploadDir = wp_upload_dir();
	$tempFolderPath = trailingslashit( $uploadDir['basedir'] ) . 'an-temp/';
	$tempFolderURL = trailingslashit( $uploadDir['baseurl'] ) . 'an-temp/';
		

	if($an_option['an_option_selectors'] == true){
	
		//Flush semectors
		if($an_option['an_option_flush'] == true || !file_exists($tempFolderPath) ){
			
			//Retrieve old files infos
			$anScripts = unserialize( get_option( 'adblocker_notify_selectors' ) );
		
			//Define new selectors
			$newSelectors = array(an_random_slug(), an_random_slug(), an_random_slug());
			
			//Generate new css and js files
			$titanCssContent = an_update_titan_css_selectors($newSelectors);
			$newCSS = an_change_files_css_selectors($tempFolderPath, $tempFolderURL, AN_URL . 'css/an-style.min.css', $anScripts['files']['css'], an_random_slug(), $newSelectors, $titanCssContent );
			$newJS = an_change_files_css_selectors($tempFolderPath, $tempFolderURL, AN_URL . 'js/an-scripts.min.js', $anScripts['files']['js'], an_random_slug(), $newSelectors );
			
			//Upload dir and temp dir are not writable
			if($newCSS == false || $newJS== false){
				$tempFolderPath = false;
			}
		
			//Store data
			$newFiles = array( 
							'temp-path' => $tempFolderPath,
							'temp-url' => $tempFolderURL,
							'files'=> array( 
								'css'=> $newCSS, 
								'js' => $newJS
							), 
							'selectors' => $newSelectors 
						);
		
			update_option('adblocker_notify_selectors', serialize($newFiles));
			
		}
		
		//remove option
		$an_option['an_option_flush'] = false;
		update_option( 'adblocker_notify_options', serialize($an_option));
	
	} else {
		
		// Remove temp files
		an_delete_temp_folder($tempFolderPath);
			
	}
	
}
add_action('tf_admin_options_saved_adblocker_notify', 'an_save_setting_random_selectors',99);


/***************************************************************
 * Admin Panel notice if wrong CHMOD on "wp-content/uploads"
 ***************************************************************/
function an_error_admin_notices() {
    $screen = get_current_screen();
    if ( $screen->id != 'toplevel_page_'. AN_ID )
        return;

	$anScripts = unserialize( get_option( 'adblocker_notify_selectors' ) );

	if($anScripts['temp-path'] == false)
	echo '
		<div class="error">
			<p>'. __( 'There was an error creating Adblock Notify CSS and JS files. Upload directory is not writable. Please CHMOD "wp-content/uploads" to 0777', 'an-translate' ) .' &nbsp;&nbsp;&nbsp;&nbsp;
				[ <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank" title="Changing File Permissions"> Changing File Permissions</a> ]
			</p>
			<p>'. __( 'Don\'t worry, we thought about it. Adblock Notify will print the scripts directly in your DOM, but for performance purpose it is recommended to change your uploads directory CHMOD!', 'an-translate' ) .'</p>
		</div>
	';

}
add_action('admin_notices', 'an_error_admin_notices');


/***************************************************************
 * Edit Titan Generated CSS
 ***************************************************************/
function an_update_titan_css_selectors($newSelectors){
	
	$uploadDir = wp_upload_dir();
	$titanCssFile = trailingslashit( $uploadDir['basedir'] ) . 'titan-framework-adblocker_notify-css.css';
	
	//Get TitanFramework style
	$fileContent = file_get_contents($titanCssFile);

	//Remove TitanFramework Generated Style
	unlink(trailingslashit( $uploadDir['basedir'] ) . 'titan-framework-adblocker_notify-css.css');

	return $fileContent;
}


/***************************************************************
 * Print Style & Sripts if temp dir. is not writable
 ***************************************************************/
function an_print_change_files_css_selectors(){

	//Get TitanFramework style
	$tfOptions = unserialize( get_option( 'adblocker_notify_options' ) );
	$tfStyle .= $tfOptions['an_alternative_custom_css'];
	$tfStyle .= $tfOptions['an_option_modal_custom_css'];

	//Get AN style and script
	$anCSS = AN_URL . 'css/an-style.min.css';
	$anJS = AN_URL . 'js/an-scripts.min.js';
	
	//Replace default selectors with new ones
	$anScripts = unserialize( get_option( 'adblocker_notify_selectors' ) );
	$newSelectors = $anScripts['selectors'];
	$defaultSelectors = array('an-Modal', 'reveal-modal', 'an-alternative');
	
	$anCSSFileContent =  str_replace($defaultSelectors, $newSelectors, file_get_contents($anCSS) . $tfStyle);
	$anJSFileContent =  str_replace($defaultSelectors, $newSelectors, file_get_contents($anJS));
	
	echo '	<style type="text/css">
				' . $anCSSFileContent . '
			</style>
			<script type="text/javascript">
				// <![CDATA[
					var ajax_object = { ajaxurl : "'.admin_url('admin-ajax.php').'" };
				// ]]>
	 			' . $anJSFileContent . '
			</script>';
	
	echo $output;
}