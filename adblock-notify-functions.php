<?php
/***************************************************************
 * Insert elements in the DOM : HTML & SCRIPT
 ***************************************************************/
function an_prepare(){
	$an_option = TitanFramework::getInstance( 'adblocker_notify' );

	//Retrieve options
	//General Options
	$anOptionChoice = $an_option->getOption( 'an_option_choice' );
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
	an_remove_cookie('anCookie', $anOptionCookie);
	
	//redirect URL with JS
	$anPermalink = an_url_redirect($anPageRedirect);


	//Modal box effect
	$anOptionModalEffect = an_modal_parameter($anOptionModalEffect);
	//Modal box close
	$anOptionModalClose = an_modal_close($anOptionModalClose);
	
	//Style construct
	//Overlay RGA color
	$anOptionModalOverlay = hex2rgba($anOptionModalBgcolor, $anOptionModalBgopacity/100);
	
	//DOM and Json
	$output .= '<div id="an-Modal" class="reveal-modal" ';
	$output .= 'style="background:'. $anOptionModalBxcolor .';';
	if(!empty($anOptionModalBxtext))
	$output .= 'color:'. $anOptionModalBxtext;
	$output .= '"></div>';
	$output .= '<script type="text/javascript">';
	$output .= '/* <![CDATA[ */';
	$output .= 'var anOptions =' . 
		json_encode(array(
		'anOptionChoice' => $anOptionChoice,
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
		'anGetUserIpAdress'=> $anGetUserIpAdress,
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
	
	echo $output;

	}
add_action('wp_footer', 'an_prepare');


/***************************************************************
 * Deregister custom stylesheet if option is empty 
 ***************************************************************/
function an_deregister_styles() {
	$anBlockerNotify = unserialize(get_option( 'adblocker_notify_options'));

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
 * Admin Panel Favico
 ***************************************************************/
function an_add_favicon() {
    $screen = get_current_screen();
    if ( $screen->id != 'toplevel_page_'. AN_ID )
        return;

  	$favicon_url = AN_URL . 'img/icon_bweb.png';
	echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}
add_action('admin_head', 'an_add_favicon');
 
/***************************************************************
 * Page views & page blocked counter
 ***************************************************************/
function an_adblock_counter() {
	if ( current_user_can( 'manage_options' ) || empty ($_POST['an_state']))
	return;
	
	$anCount = get_option('adblocker_notify_counter');
	
	if(empty($anCount)){
		add_option('adblocker_notify_counter', array('total' => 0, 'blocked' => 0, 'deactivated' => 0, 'history' => array()));
		$anCount = get_option('adblocker_notify_counter');
	}
	
	$an_state = $_POST['an_state'];

	//update option with new values
	$anCount[$an_state] = $anCount[$an_state]+1;
	update_option('adblocker_notify_counter', $anCount);

	//then update history
	an_history_counter($an_state);

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
function an_history_counter($val=null) {
	$anCount = get_option( 'adblocker_notify_counter' );
	$anToday = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
	//$anToday = date( 'Y-m-d', strtotime( '3 day', strtotime( date( 'Y-m-d', current_time( 'timestamp', 0 ) ) ) ) );
	
	if(empty($anCount['history'][0])){

		$anCount['history'][0] = array('date' => $anToday, 'total' => $anCount['total'], 'blocked' => $anCount['blocked']);
		update_option('adblocker_notify_counter', $anCount);
	
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

		update_option('adblocker_notify_counter', $anCount);
	}

}

/***************************************************************
 * Data history extraction & order revert for chart
 ***************************************************************/
function an_data_histoty($val=null){
	$anCount = get_option( 'adblocker_notify_counter' );
	if(empty($anCount['history'][0]))
	return;

	foreach($anCount['history'] as $row){
		$anOutput .= $row[$val].',';
	}
	$anOutput = trim($anOutput, ",");
	return $anOutput;
}


/***************************************************************
 * Display
 ***************************************************************/
function an_get_counters() {
	$anCount = get_option('adblocker_notify_counter');
	
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
			  <td></td>
			  <td>Total</td> 
			  <td>Today</td>
			</tr>
			<tr>
			  <td style="text-align:left;"><span style="color:#34495e">&#9608</span> Pages Views</td>
			  <td>' .$anCount['total'] .'</td> 
			  <td>' .$anCount['history'][0]['total'] .'</td>
			</tr>
			<tr>
			  <td style="text-align:left;"><span style="color:#e74c3c">&#9608</span> Pages with Adblock</td>
			  <td>' .$anCount['blocked'] .'</td> 
			  <td>' .$anCount['history'][0]['blocked'] .'</td>
			</tr>
		</table>

		<div class="an-canvas-container-donut">
			<div class="an-average"><span>Total</span>' .$average. '%<span>Ads blocked</span></div>
			<canvas id="an-canvas-donut" height="180"></canvas>
		</div>

		<div class="an-canvas-container-donut">
			<div class="an-average"><span>Today</span>' .$averageToday. '%<span>Ads blocked</span></div>
			<canvas id="an-canvas-donut-today" height="180"></canvas>
		</div>
		<p>
			<strong>' . $anCount['deactivated'] . '</strong> Ad Blocker software deactivated
			<br />
			<i>You may probably increase this number by improving your custom messages</i>
		</p>
		<div id="an-canvas-container-line">
			<canvas id="an-canvas-line"></canvas>
		</div>
		<p>
			<a href="options-general.php?page='.AN_ID.'" class="button action">Settings</a>&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp<a href="options-general.php?page='.AN_ID.'&an-reset=true"  onclick="javascript:if(!confirm(\'Are you sure you want to delete permanently your datas?\')) return false;" class="button action">Reset Stats</a>
		</p>
		<i>Admins are excluded from this statistics.</i>
	
		<style>#an_dashboard_widgets i {color:#bbb; font-size: 0.85em}.an-stats-table {width: 100%;}.an-stats-table, .an-stats-table td, .an-stats-table th {border: 1px solid #f8f8f8;border-collapse:collapse;}.an-top {	background-color: #f8f8f8;}.an-canvas-container-donut {width:50%; float: left; position:relative;}.an-canvas-container-donut,#an_dashboard_widgets .inside{text-align:center}#an-canvas-donut,#an-canvas-donut-today {display:inline-block; margin-top:10px;}.an-average{position:absolute;left:50%;top:70px;font-size:1.45em;font-weight:700;display:inline-block;width:85px;text-align:center;margin-left:-43px}.an-average span{text-align:center;font-size:.6em;display:block}#an-canvas-container-line{margin-top:5px;width:100%}</style>
		<script type="text/javascript">
		var doughnutData = [
				{
					value: '.$totalNoBlocker.',
					color:"#34495e"
				},
				{
					value : '.$anCount['blocked'].',
					color : "#e74c3c"
				}			
			];
		var doughnutDataToday = [
				{
					value: '.$totalNoBlockerToday.',
					color:"#34495e"
				},
				{
					value : '.$anCount['history'][0]['blocked'].',
					color : "#e74c3c"
				}			
			];
		
		var lineChartData = {
			labels : ["Today","Day -1","Day -2","Day -3","Day -4","Day -5","Day -6"],
			datasets : [
				{
					fillColor : "rgba(50, 82, 110,0.5)",
					strokeColor : "rgba(50, 82, 110,0.8)",
					pointColor : "rgba(250,250,250,1)",
					pointStrokeColor : "rgba(50, 82, 110,1)",
					data : ['. an_data_histoty('total') .']
				},
				{
					fillColor : "rgba(231, 76, 60,0.6)",
					strokeColor : "rgba(173, 52, 40,0.8)",
					pointColor : "rgba(250,250,250,0.8)",
					pointStrokeColor : "#e74c3c",
					data : ['. an_data_histoty('blocked') .']
				}
			]
			
		}
		//var myLine = new Chart(document.getElementById("an-canvas-line").getContext("2d")).Line(lineChartData);

		jQuery(document).ready(function($) {
			var myLine = new Chart(document.getElementById("an-canvas-line").getContext("2d")).Line(lineChartData);
			var widthdonut = $("#an_dashboard_widgets .inside .an-canvas-container-donut").width();
			var widthline = $("#an_dashboard_widgets .inside").width();
			$("canvas").attr("width",widthdonut);
			$("canvas#an-canvas-line").attr("width",widthline);
			var myDoughnut = new Chart(document.getElementById("an-canvas-donut").getContext("2d")).Doughnut(doughnutData);
			var myDoughnut = new Chart(document.getElementById("an-canvas-donut-today").getContext("2d")).Doughnut(doughnutDataToday);
			var myLine = new Chart(document.getElementById("an-canvas-line").getContext("2d")).Line(lineChartData);
		});

		</script>
	';
	echo $output;
}
 
function an_dashboard_widgets() {
	global $wp_meta_boxes;
	wp_add_dashboard_widget('an_dashboard_widgets', AN_NAME .' Stats', 'an_get_counters');
}
add_action('wp_dashboard_setup', 'an_dashboard_widgets');


/***************************************************************
 * Reset plugin options
 ***************************************************************/
function an_stats_notice() {
	echo '<div class="updated top"><p><strong>'. AN_NAME .' stats have been successfully cleared.</strong></p></div>';
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
