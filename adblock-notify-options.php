<?php
add_action( 'tf_create_options', 'an_create_options' );
function an_create_options() {
	
	/***************************************************************
	 * Launch options framework instance
	 ***************************************************************/
	$an_option = TitanFramework::getInstance( 'adblocker_notify' );
	
	
	/***************************************************************
	 * Create option menu item
	 ***************************************************************/
	$an_panel = $an_option->createAdminPanel( array(
			'name' => AN_NAME, 
			'icon' => AN_URL . 'img/icon_bweb.png',
			'id' => AN_ID,
	) );
	
	
	/***************************************************************
	 * Create option panel tabs
	 ***************************************************************/
	$generalTab = $an_panel->createTab( array(
		'name' => __( 'Adblock Notify Options', 'an-translate' ),
	) );
	
	$modalTab = $an_panel->createTab( array(
		'name' => __( 'Modal Visual Options', 'an-translate' ),
	) );
	
	$alternativeTab = $an_panel->createTab( array(
		'name' => __( 'Alternative Message', 'an-translate' ),
	) );
	
	
	/***************************************************************
	 * Create tab's options
	 ***************************************************************/
	
	//Adblock Notify Options
	$generalTab->createOption( array(
		'type' => 'note',
		'desc' => '
				<div style="color:black; font-style: normal;">
					<h3>'. __( 'Welcome to Adblock Notify Plugin', 'an-translate' ) .'</h3>
					<p>
						'. __( 'You can notify users with an activated Adblocker software by one of THREE ways !', 'an-translate' ) .'
						<ol>
							<li>'. __( 'A pretty cool and lightweight Modal Box with a custom content:', 'an-translate' ) .' <i class="an-red">'. __( 'the COMPLIANT solution', 'an-translate' ) .'</i></li>
							<li>'. __( 'A simple redirection to the page of your choice:', 'an-translate' ) .' <i class="an-red">'. __( 'the AGRESSIVE solution', 'an-translate' ) .'</i></li>
							<li>'. __( 'A custom alternative message where your hidden ads would normally appear:', 'an-translate' ) .' <i class="an-red">'. __( 'the TRANSPARENT solution', 'an-translate' ) .'</i></li>
						</ol>
					</p>
					<p>
						'. __( 'Only one of the two first options can be activated at the same time. The third one is standalone and can be setting up independently.', 'an-translate' ) .'
						<br />
						'. __( 'You can easily switch between them without losing your options.', 'an-translate' ) .'
					</p>
					<p>
						<strong>'. __( 'Adblock Notify', 'an-translate' ) .'</strong> '. __( 'nativally uses cookies for a better user experience and a less intrusive browsing of your site. It means visitors will see the Modal Box only once or be redirected to your custom page once.', 'an-translate' ) .'
						<br />
						'. __( 'You can deactivate them, however if your visitor has an activated adblocker software they will see a modal box or get a redirection on every visited page.', 'an-translate' ) .'
					</p>
					<p>
						<strong class="an-red">'. __( 'Adblock Notify Stats widget is available on your admin dashboard (if not visible, go to the top menu and visit "Screen Options").', 'an-translate' ) .'</strong> 
					</p>
				</div>
		',
	) );
	$generalTab->createOption( array( 
		'name' => __( 'Modal Box or Redirection ?', 'an-translate' ),
		'id' => 'an_option_choice',
		'options' => array(
			'1' => __( 'None (only stats)', 'an-translate' ),
			'2' => __( 'Modal Box (+ stats)', 'an-translate' ),
			'3' => __( 'Page redirection (+ stats)', 'an-translate' )
		),
		'type' => 'radio',
		'desc' => __( 'Would you like to use the Modal Box or redirect users to a custom page when adblock is detected? - Default: None', 'an-translate' ),
		'default' => '1',
	) );
	
	
	$generalTab->createOption( array(
			'name' => __( 'Cookies Options', 'an-translate' ),
			'type' => 'heading',
	) );
	$generalTab->createOption( array(
			'name' => __( 'Cookies activation', 'an-translate' ),
			'id' => 'an_option_cookie',
			'type' => 'select',
			'desc' => __( 'Prevent Modal Box from opening or Page redirection on every visited page - Default: Yes', 'an-translate' ) .'<br /> <span class="an-red">'. __( 'Your own cookie is automatically reset on options save to see settings changes.', 'an-translate' ) .'</span>',
			'options' => array(
					'1' => __( 'Yes', 'an-translate' ),
					'2' => __( 'No', 'an-translate' ),
			),
			'default' => '1',
	) );
	$generalTab->createOption( array(
			'name' => __( 'Cookies Lifetime', 'an-translate' ) .' <i>('. __( 'Days', 'an-translate' ) .')</i>',
			'id' => 'an_option_cookie_life',
			'type' => 'number',
			'desc' => __( 'Set the lifetime of the cookie session - Default: 30 days', 'an-translate' ),
			'default' => '30',
			'min' => '1',
			'max' => '360',
			'step' => '1',
	) );
	
	$generalTab->createOption( array(
			'name' =>  __( 'Modal Box Options', 'an-translate' ),
			'type' => 'heading',
	) );
	$generalTab->createOption( array(
			'name' =>  __( 'Modal Title', 'an-translate' ),
			'id' => 'an_modal_title',
			'type' => 'text',
			'desc' =>  __( 'The title of the modal box', 'an-translate' ),
			'default' =>  __( 'Adblocker detected! Please consider reading this notice.', 'an-translate' ),
	) );
	$generalTab->createOption( array(
			'name' =>  __( 'Modal Text', 'an-translate' ),
			'id' => 'an_modal_text',
			'type' => 'editor',
			'rows' => '13',
			'desc' =>  __( 'The text of the modal box : images & shortcodes are supported.', 'an-translate' ),
			'default' => '
				<p>'. __( 'We\'ve detected that you are using AdBlock Plus ore some other adblocking software which is preventing the page from fully loading.', 'an-translate' ) .'</p>
				<p>'. __( 'We don\'t have any banner, Flash, animation, obnoxious sound, or popup ad. We do not implement these annoying types of ads!', 'an-translate' ) .'</p>
				<p>'. __( 'We need money to operate the site, and almost all of it comes from our online advertising.', 'an-translate' ) .'</p> 
				<p><strong>'. __( 'Please add', 'an-translate' ) .'<a title="http://b-website.com/" href="b-website.com" target="_blank">www.b-website.com</a> '. __( 'to your ad blocking whitelist or disable your adblocking software.', 'an-translate' ) .'<strong></p>
			',
	) );
	
	
	$generalTab->createOption( array(
			'name' =>  __( 'Redirection Options', 'an-translate' ),
			'type' => 'heading',
	) );
	$generalTab->createOption( array(
			'name' =>  __( 'Target Page', 'an-translate' ),
			'id' => 'an_page_redirect',
			'type' => 'select-pages',
			'desc' =>  __( 'Select a page to redirect to. List your current published pages', 'an-translate' ),
	) );
	
	$generalTab->createOption( array(
			'name' =>  __( 'No JS Redirection', 'an-translate' ) .' <span class="blink an-red">'. __( 'Warning', 'an-translate' ) .'</span>',
			'type' => 'heading',
	) );
	$generalTab->createOption( array(
		'name' => __( 'Redirect if no JS detected?', 'an-translate' ),
		'id' => 'an_page_nojs_activation',
		'type' => 'checkbox',
		'desc' => __( 'Yes', 'an-translate' ) .'  <i>('. __( 'This option used your Cookies Options', 'an-translate' ) .')</i> - '. __( 'Default: Unchecked', 'an-translate' ) .'<br /><strong class="an-red">'. __( 'Will redirect visitor to a custom page if Javascript is disable. It is NOT SEO friendly, use it only on private site.', 'an-translate' ) .'</strong>',
		'default' => false,
	) );
	$generalTab->createOption( array(
			'name' => __( 'Target Page', 'an-translate' ),
			'id' => 'an_page_nojs_redirect',
			'type' => 'select-pages',
			'desc' => __( 'Select a page to redirect to. List your current published pages', 'an-translate' ),
	) );
	
	
	//Modal Visual Options
	$modalTab->createOption( array(
		'name' => __( 'Modal Box Settings', 'an-translate' ),
		'type' => 'heading',
	) );
	$modalTab->createOption( array(
		'name' => __( 'Modal Box effect', 'an-translate' ),
		'id' => 'an_option_modal_effect',
		'type' => 'select',
		'desc' => __( 'The Modal Box animation effect - Default: Fade and Pop', 'an-translate' ),
		'options' => array(
			'1' => __( 'Fade and Pop', 'an-translate' ),
			'2' => __( 'Fade', 'an-translate' ),
			'3' => __( 'None', 'an-translate' ),
		),
		'default' => '1',
	) );
	$modalTab->createOption( array(
		'name' => __( 'Animation Speed', 'an-translate' ) .' <i>('. __( 'Milliseconds', 'an-translate' ) .')</i>',
		'id' => 'an_option_modal_speed',
		'type' => 'number',
		'desc' => '<i>'. __( 'The Modal Box animation speed. Will not be applied if modal effect is set to  None - Default: 350ms', 'an-translate' ) .'</i>',
		'default' => '350',
		'min' => '0',
		'max' => '5000',
		'step' => '10',
	) );
	$modalTab->createOption( array(
		'name' => __( 'Modal Close on background click', 'an-translate' ),
		'id' => 'an_option_modal_close',
		'type' => 'select',
		'desc' => __( 'If you click background will Modal close? - Default: Yes', 'an-translate' ),
		'options' => array(
			'1' => __( 'Yes', 'an-translate' ),
			'2' => __( 'No', 'an-translate' ),
		),
		'default' => '1',
	) );
	
	$modalTab->createOption( array(
		'name' => __( 'Modal Box Style', 'an-translate' ),
		'type' => 'heading',
	) );
	$modalTab->createOption( array(
		'name' => __( 'Overlay Color', 'an-translate' ) .' <i>('. __( 'Background', 'an-translate' ) .')</i>',
		'id' => 'an_option_modal_bgcolor',
		'type' => 'color',
		'default' => '#000000',
		'desc' => __( 'Default:', 'an-translate' ) .' #000000',
	) );
	$modalTab->createOption( array(
		'name' => __( 'Overlay Opacity', 'an-translate' ) .' <i>(%)</i>',
		'id' => 'an_option_modal_bgopacity',
		'type' => 'number',
		'desc' => '<i>'. __( 'Modal Box overlay (background) opacity - Default: 80%', 'an-translate' ) .'</i>',
		'default' => '80',
		'min' => '0',
		'max' => '100',
		'step' => '5',
	) );
	$modalTab->createOption( array(
		'name' => __( 'Modal Box Background Color', 'an-translate' ),
		'id' => 'an_option_modal_bxcolor',
		'type' => 'color',
		'default' => '#dddddd',
		'desc' => __( 'Default:', 'an-translate' ) .' #dddddd',
	) );
	$modalTab->createOption( array(
		'name' => __( 'Modal Box Title Color', 'an-translate' ),
		'id' => 'an_option_modal_bxtitle',
		'type' => 'color',
		'desc' => __( 'Default is your theme &lt;h1&gt; color', 'an-translate' ),
		'default' => '',
	) );
	$modalTab->createOption( array(
		'name' => __( 'Modal Box Text Color', 'an-translate' ),
		'id' => 'an_option_modal_bxtext',
		'type' => 'color',
		'desc' => __( 'Default is your theme body text color', 'an-translate' ),
		'default' => '',
	) );
	$modalTab->createOption( array(
		'name' => __( 'Custom CSS', 'an-translate' ) .' <br /><i>('. __( 'Advance users', 'an-translate' ) .')<i>',
		'id' => 'an_option_modal_custom_css',
		'type' => 'code',
		'desc' => __( 'Put your custom CSS rules here. Modal Box ID is', 'an-translate' ) .' <strong class="an-red">#an-Modal</strong>',
		'lang' => 'css',
	) );
	
	
	//Alternative Message Options
	$alternativeTab->createOption( array(
		'type' => 'note',
		'desc' => '
				<div style="color:black; font-style: normal;">
					<h3>'. __( 'Alternative Message', 'an-translate' ) .'</h3>
					<p>
						'. __( 'You can insert a custom message where your hidden ads would normally appear.', 'an-translate' ) .'
					</p><p>
					'. __( 'The plugin will append a new "clean" DIV element just before the advert container to display your custom message.', 'an-translate' ) .'
					</p><p>
						<strong>'. __( 'Note:', 'an-translate' ) .'</strong> '. __( 'Some minimal HTML knowledge is required to set up this functionality.', 'an-translate' ) .'
					</p>
				</div>
		',
	) );
	$alternativeTab->createOption( array(
		'name' => __( 'Activate this option?', 'an-translate' ),
		'id' => 'an_alternative_activation',
		'type' => 'checkbox',
		'desc' => __( 'Yes - Default: Unchecked', 'an-translate' ) .'<br /><strong class="an-red">'. __( 'If unchecked, below options will not be used', 'an-translate' ) .'</strong>',
		'default' => false,
	) );
	
	$alternativeTab->createOption( array(
			'name' => __( 'Required Settings', 'an-translate' ),
			'type' => 'heading',
	) );
	$alternativeTab->createOption( array(
			'name' => __( 'Advert containers', 'an-translate' ) .' <i>('. __( 'Comma separated', 'an-translate' ) .')</i>',
			'id' => 'an_alternative_elements',
			'type' => 'text',
			'desc' => __( 'The Element CLASS or ID of your ads containers. - Default: Empty', 'an-translate' ) .'
			<br /><strong> Eg: #my-ad, .hentry .adsense, .sponsored</strong> 
			<br />('. __( 'Read', 'an-translate' ) .' <a href="http://api.jquery.com/category/selectors/" target="_blank">'. __( 'Selectors | jQuery API Documentation', 'an-translate' ) .'</a> '. __( 'for more details', 'an-translate' ) .')',
	) );
	$alternativeTab->createOption( array(
			'name' => __( 'Alternative Text', 'an-translate' ),
			'id' => 'an_alternative_text',
			'type' => 'editor',
			'rows' => '8',
			'desc' => __( 'The alternative text to display when ads are hidden. Images & shortcodes are supported, but use them with caution.', 'an-translate' ),
			'default' => '
				<p><strong>'. __( 'AdBlock detected!', 'an-translate' ) .'</strong></p>
				<p>'. __( 'Please add', 'an-translate' ) .' <a title="http://b-website.com/" href="b-website.com" target="_blank">www.b-website.com</a> '. __( 'to your adblocking whitelist or disable your adblocking software.', 'an-translate' ) .'</p>
			',
	) );
	
	$alternativeTab->createOption( array(
		'name' => __( 'Optional Settings', 'an-translate' ),
		'type' => 'heading',
	) );
	$alternativeTab->createOption( array(
		'name' => __( 'Clone ad container?', 'an-translate' ),
		'id' => 'an_alternative_clone',
		'type' => 'select',
		'desc' => __( 'Will copy your original ad container CSS properties - Default: No', 'an-translate' ) .'<br /><strong>'. __( 'This feature is not 100% reliable but could help for a quick set up.', 'an-translate' ) .'</strong>',
		'options' => array(
			'1' => __( 'Custom Mode', 'an-translate' ),
			'2' => __( 'Soft Mode (Recommended)', 'an-translate' ),
			'3' => __( 'Hard Mode', 'an-translate' ),
			'4' => __( 'No', 'an-translate' ),
		),
		'default' => '2',
	) );
	$alternativeTab->createOption( array(
		'type' => 'note',
		'desc' => '
				<div style="color:black; font-style: normal;">
					<p>
						<strong class="an-red">'. __( 'What does "Clone ad container" mean?', 'an-translate' ) .'</strong>
						<br />
						'. __( 'It means you can ask Adblock Notify Plugin to copy the CSS properties of the element that contains your ad to a new element which will not be hidden by an adblocker software. With this process, your design should not break.', 'an-translate' ) .'
						<br />
						'. __( 'The new element will be the same type (DIV,SPAN,etc.) as its source, and will have the .an-alternative class.', 'an-translate' ) .'
					</p>
					<p>
					'. __( 'Available options are:', 'an-translate' ) .'
					<ol>
						<li><i class="an-red">'. __( 'Custom Mode', 'an-translate' ) .'</i>'. __( ': Will try to catch all the CSS rules defined in your theme files, and let you choose which ones to keep (see Custom Mode CSS properties).', 'an-translate' ) .'</li>
						<li><i class="an-red">'. __( 'Soft Mode (Recommended)', 'an-translate' ) .'</i>'. __( ': Will try to catch all the CSS rules defined in your theme files, and add them to the new created element. If the browser does not support this feature, it will try Hard Mode fetching.', 'an-translate' ) .'</li>
						<li><i class="an-red">'. __( 'Hard Mode', 'an-translate' ) .'</i>'. __( ': Will try to fetch all the elements CSS rules based on browser CSS compilation (not reading directly in your CSS files). This option may add a lot of inline CSS rules to your newly created element.', 'an-translate' ) .'</li>
					</ol>
					</p>
					<p>
						'. __( 'This feature is performed through Javascript (+jQuery) and is 95% functional on all modern browser even on IE8+. For the 5% left, the plugin will drop potential JS errors and insert .an-alternative div.', 'an-translate' ) .'
						<br />
						<strong><i>'. __( 'Tested and works great on Chrome, Firefox, Safari, Opera, IE8+', 'an-translate' ) .'</i></strong>
					</p>
					<p>
						<strong class="an-red">'. __( 'What\'s appended if I don\'t turn on this option?', 'an-translate' ) .'</strong>
						<br />
						'. __( 'The plugin will append a new "clean" DIV element with .an-alternative class just before the advert container. You can add your own custom rules with the Custom CSS field below.', 'an-translate' ) .'
					</p>
				</div>
		',
	) );
	$alternativeTab->createOption( array(
			'name' => __( 'Custom Mode CSS properties', 'an-translate' ) .' <i>('. __( 'Comma separated', 'an-translate' ) .')</i>',
			'id' => 'an_alternative_properties',
			'type' => 'text',
			'desc' => __( 'The element CSS properties you want to clone - Default: Empty', 'an-translate' ) .'
			<br /><strong>  '. __( 'Eg: color, width, height, background-color, border', 'an-translate' ) .'</strong> 
			<br />('. __( 'Read', 'an-translate' ) .' <a href="http://www.w3schools.com/cssref/" target="_blank"> '. __( 'CSS Reference | w3schools.com', 'an-translate' ) .'</a>  '. __( 'for more details', 'an-translate' ) .')
			',
	) );
	$alternativeTab->createOption( array(
		'name' => __( 'Custom CSS', 'an-translate' ) .' <br /><i>( '. __( 'Advance users', 'an-translate' ) .')<i>',
		'id' => 'an_alternative_custom_css',
		'type' => 'code',
		'desc' => __( 'Put your custom CSS rules here. The new Element class is .an-alternative', 'an-translate' ) .'
					<p>
					<strong> '. __( 'NOTE:', 'an-translate' ) .'</strong>  '. __( 'If you\'ve activated the ads containers cloning, you can still add custom CSS on your text.', 'an-translate' ) .'
					<br /> '. __( 'If you really have to overload .an-alternative with your own CSS properties, you may probably need to use !important after each of them, but this is not advised.', 'an-translate' ) .'
					</p>',
		'lang' => 'css',
	) );
	
	/***************************************************************
	 * Launch options framework instance
	 ***************************************************************/
	$generalTab->createOption( array(
		'type' => 'save',
		'save' => __( 'Save Changes', 'an-translate' ),
		'reset' => __( 'Reset to Defaults', 'an-translate' ),
	) );
	
	$modalTab->createOption( array(
		'type' => 'save',
		'save' => __( 'Save Changes', 'an-translate' ),
		'reset' => __( 'Reset to Defaults', 'an-translate' ),
	) );
	
	$alternativeTab->createOption( array(
		'type' => 'save',
		'save' => __( 'Save Changes', 'an-translate' ),
		'reset' => __( 'Reset to Defaults', 'an-translate' ),
	) );
	
	
	/***************************************************************
	 * Launch options framework instance
	 ***************************************************************/
	 
	function an_save_setting_data() {
		$an_option = TitanFramework::getInstance( 'adblocker_notify' );
	
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
	
	
	}
	add_action('wp_head' , 'an_save_setting_data' );
	
} //en of an_create_options