<?php
/***************************************************************
 * Launch options framework (Titan Framework)
 ***************************************************************/
// Don't do anything when we're activating a plugin to prevent errors
// on redeclaring Titan classes
if ( ! empty( $_GET['action'] ) && ! empty( $_GET['plugin'] ) ) {
    if ( $_GET['action'] == 'activate' ) {
        return;
    }
}
// Check if the framework plugin is activated
$useEmbeddedFramework = true;
$activePlugins = get_option('active_plugins');
if ( is_array( $activePlugins ) ) {
    foreach ( $activePlugins as $plugin ) {
        if (is_string( $plugin ) && stripos( $plugin, '/titan-framework.php' ) !== false) {
						$useEmbeddedFramework = false;
						break;
				}
    }
}
// Use the embedded Titan Framework
if ( $useEmbeddedFramework && ! class_exists( 'TitanFramework' ) ) {
		require_once( AN_PATH . 'lib/titan-framework/titan-framework.php' );
} 

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
    'name' => AN_NAME .' Options',
) );

$modalTab = $an_panel->createTab( array(
    'name' => 'Modal Visual Options',
) );

$alternativeTab = $an_panel->createTab( array(
    'name' => 'Alternative Message',
) );


/***************************************************************
 * Create tab's options
 ***************************************************************/

//Adblock Notify Options
$generalTab->createOption( array(
    'type' => 'note',
    'desc' => '
			<div style="color:black; font-style: normal;">
				<h3>Welcome to Adblock Notify Plugin</h3>
				<p>
					You can notify users with an activated Adblocker software by one of THREE ways !
					<ol>
						<li>A pretty cool and lightweight <strong>Modal Box</strong> with a custom content : <i class="an-red">the COMPLIANT solution</i></li>
						<li>A simple <strong>redirection</strong> to the page of your choice : <i class="an-red">the AGRESSIVE solution</i></li>
						<li>A custom <strong>alternative message</strong> where your hidden ads would normally appear : <i class="an-red">the TRANSPARENT solution</i></li>
					</ol>
				</p>
				<p>
					Only one of the two first options can be activated at the same time. The third one is standalone and can be setting up independently.
					<br />
					You can easily switch between them without losing your options.
				</p>
				<p>
					<strong>Adblock Notify</strong> nativally uses cookies for a better user experience and a less intrusive browsing of your site. It means visitors will see the Modal Box only once or be redirected to your custom page once.
					<br />
					You can deactivate them, however if your visitor has an activated adblocker software they will see a modal box or get a redirection on every visited page. 
				</p>
				<p>
					<strong class="an-red">Adblock Notify Stats widget</strong> is available on your admin dashboard (if not visible, go to the top menu and visit "Screen Options"). 
				</p>
			</div>
	',
) );
$generalTab->createOption( array(
    'name' => 'Modal Box or Redirection ?',
    'id' => 'an_option_choice',
    'options' => array(
        '1' => 'None (only stats)',
        '2' => 'Modal Box (+ stats)',
        '3' => 'Page redirection (+ stats)'
    ),
    'type' => 'radio',
    'desc' => 'Would you like to use the Modal Box or redirect users to a custom page when adblock is detected? - Default: None',
    'default' => '1',
) );


$generalTab->createOption( array(
		'name' => 'Cookies Options',
		'type' => 'heading',
) );
$generalTab->createOption( array(
		'name' => 'Cookies activation',
		'id' => 'an_option_cookie',
		'type' => 'select',
		'desc' => 'Prevent Modal Box from opening or Page redirection on every visited page - Default: Yes<br /> <span class="an-red">Your own cookie is automatically reset on options save to see settings changes.</span>',
		'options' => array(
				'1' => 'Yes',
				'2' => 'No',
		),
		'default' => '1',
) );
$generalTab->createOption( array(
		'name' => 'Cookies Lifetime <i>(Days)</i>',
		'id' => 'an_option_cookie_life',
		'type' => 'number',
		'desc' => 'Set the lifetime of the cookie session - Default: 30 days',
		'default' => '30',
		'min' => '1',
		'max' => '360',
		'step' => '1',
) );

$generalTab->createOption( array(
		'name' => 'Modal Box Options',
		'type' => 'heading',
) );
$generalTab->createOption( array(
		'name' => 'Modal Title',
		'id' => 'an_modal_title',
		'type' => 'text',
		'desc' => 'The title of the modal box',
		'default' => 'Adblocker detected! Please consider reading this notice.',
) );
$generalTab->createOption( array(
		'name' => 'Modal Text',
		'id' => 'an_modal_text',
		'type' => 'editor',
		'rows' => '13',
		'desc' => 'The text of the modal box : images & shortcodes are supported.',
		'default' => '
			<p>We\'ve detected that you are using AdBlock Plus ore some other adblocking software which is preventing the page from fully loading.</p>
			<p>We don\'t have any banner, Flash, animation, obnoxious sound, or popup ad. We do not implement these annoying types of ads!</p>
			<p>We need money to operate the site, and almost all of it comes from our online advertising.</p> 
			<p><strong>Please add <a title="http://b-website.com/" href="b-website.com" target="_blank">www.b-website.com</a> to your ad blocking whitelist or disable your adblocking software.<strong></p>
		',
) );


$generalTab->createOption( array(
		'name' => 'Redirection Options',
		'type' => 'heading',
) );
$generalTab->createOption( array(
		'name' => 'Target Page',
		'id' => 'an_page_redirect',
		'type' => 'select-pages',
		'desc' => 'Select a page to redirect to. List your current published pages',
) );

$generalTab->createOption( array(
		'name' => 'No JS Redirection <span class="blink an-red">Warning</span>',
		'type' => 'heading',
) );
$generalTab->createOption( array(
    'name' => 'Redirect if no JS detected?',
    'id' => 'an_page_nojs_activation',
    'type' => 'checkbox',
	'desc' => 'Yes  <i>(This option used your Cookies Options)</i> - Default: Unchecked<br /><strong class="an-red">Will redirect visitor to a custom page if Javascript is disable. It is <u>NOT SEO friendly</u>, use it only on private site.</strong>',
    'default' => false,
) );
$generalTab->createOption( array(
		'name' => 'Target Page',
		'id' => 'an_page_nojs_redirect',
		'type' => 'select-pages',
		'desc' => 'Select a page to redirect to. List your current published pages',
) );


//Modal Visual Options
$modalTab->createOption( array(
    'name' => 'Modal Box Settings',
    'type' => 'heading',
) );
$modalTab->createOption( array(
    'name' => 'Modal Box effect',
    'id' => 'an_option_modal_effect',
    'type' => 'select',
    'desc' => 'The Modal Box animation effect - Default: Fade and Pop',
    'options' => array(
        '1' => 'Fade and Pop',
        '2' => 'Fade',
        '3' => 'None',
    ),
    'default' => '1',
) );
$modalTab->createOption( array(
    'name' => 'Animation Speed <i>(Milliseconds)</i>',
    'id' => 'an_option_modal_speed',
    'type' => 'number',
    'desc' => '<i>The Modal Box animation speed. Will not be applied if modal effect is set to <strong style="color:black">None</strong> - Default: 350ms</i>',
    'default' => '350',
    'min' => '0',
    'max' => '5000',
	'step' => '10',
) );
$modalTab->createOption( array(
    'name' => 'Modal Close on background click',
    'id' => 'an_option_modal_close',
    'type' => 'select',
    'desc' => 'If you click background will Modal close? - Default: Yes',
    'options' => array(
        '1' => 'Yes',
        '2' => 'No',
    ),
    'default' => '1',
) );

$modalTab->createOption( array(
    'name' => 'Modal Box Style',
    'type' => 'heading',
) );
$modalTab->createOption( array(
    'name' => 'Overlay Color <i>(Background)</i>',
    'id' => 'an_option_modal_bgcolor',
    'type' => 'color',
    'default' => '#000000',
    'desc' => 'Default: #000000',
) );
$modalTab->createOption( array(
    'name' => 'Overlay Opacity <i>(%)</i>',
    'id' => 'an_option_modal_bgopacity',
    'type' => 'number',
    'desc' => '<i>Modal Box overlay (background) opacity - Default: 80%</i>',
    'default' => '80',
    'min' => '0',
    'max' => '100',
	'step' => '5',
) );
$modalTab->createOption( array(
    'name' => 'Modal Box Background Color',
    'id' => 'an_option_modal_bxcolor',
    'type' => 'color',
    'default' => '#dddddd',
    'desc' => 'Default: #dddddd',
) );
$modalTab->createOption( array(
    'name' => 'Modal Box Title Color',
    'id' => 'an_option_modal_bxtitle',
    'type' => 'color',
    'desc' => 'Default is your theme &lt;h1&gt; color',
    'default' => '',
) );
$modalTab->createOption( array(
    'name' => 'Modal Box Text Color',
    'id' => 'an_option_modal_bxtext',
    'type' => 'color',
    'desc' => 'Default is your theme body text color',
    'default' => '',
) );
$modalTab->createOption( array(
    'name' => 'Custom CSS <br /><i>(Advance users)<i>',
    'id' => 'an_option_modal_custom_css',
    'type' => 'code',
    'desc' => 'Put your custom CSS rules here. Modal Box ID is <strong class="an-red">#an-Modal</strong>',
    'lang' => 'css',
) );


//Alternative Message Options
$alternativeTab->createOption( array(
    'type' => 'note',
    'desc' => '
			<div style="color:black; font-style: normal;">
				<h3>Alternative Message</h3>
				<p>
					You can insert a custom message where your hidden ads would normally appear.
				</p>
				<p>
					<strong>Note:</strong> Some minimal HTML knowledge is required to set up this functionality.
				</p>
			</div>
	',
) );
$alternativeTab->createOption( array(
    'name' => 'Activate this option?',
    'id' => 'an_alternative_activation',
    'type' => 'checkbox',
	'desc' => 'Yes - Default: Unchecked<br /><strong class="an-red">If unchecked, below options will not be used</strong>',
    'default' => false,
) );

$alternativeTab->createOption( array(
		'name' => 'Required Settings',
		'type' => 'heading',
) );
$alternativeTab->createOption( array(
		'name' => 'Advert containers <i>(Comma separated)</i>',
		'id' => 'an_alternative_elements',
		'type' => 'text',
		'desc' => 'The Element <strong class="an-red">CLASS</strong> or <strong class="an-red">ID</strong> of your ads containers. - Default: Empty
		<br /><strong> Eg: #my-ad, .hentry .adsense, .sponsored</strong> 
		(Read <a href="http://api.jquery.com/category/selectors/" target="_blank">Selectors | jQuery API Documentation</a> for more details)
		',
) );
$alternativeTab->createOption( array(
		'name' => 'Alternative Text',
		'id' => 'an_alternative_text',
		'type' => 'editor',
		'rows' => '8',
		'desc' => 'The alternative text to display when ads are hidden. Images & shortcodes are supported, but use them with caution.',
		'default' => '
			<p><strong>AdBlock detected!</strong></p>
			<p>Please add <a title="http://b-website.com/" href="b-website.com" target="_blank">www.b-website.com</a> to your adblocking whitelist or disable your adblocking software.</p>
		',
) );

$alternativeTab->createOption( array(
    'name' => 'Optional Settings',
    'type' => 'heading',
) );
$alternativeTab->createOption( array(
    'type' => 'note',
    'desc' => '
			<div style="color:black; font-style: normal;">
				<p>
					<strong class="an-red">What does "Clone ad container" mean?</strong>
					<br />
					It means you can ask Adblock Notify Plugin to copy the CSS properties of the element that contains your ad to a new element which will not be hidden by an adblocker software. With this process, your design should not break.
					<br />
					The new element will be the same type (DIV,SPAN,etc.) as its source, and will have the <strong class="an-red">.an-alternative</strong> class.
				</p>
				<p>
				Available options are:
				<ol>
					<li><i class="an-red">Custom Mode</i>: Will try to catch all the CSS rules defined in your theme files, and let you choose which ones to keep (see Custom Mode CSS properties).</li>
					<li><i class="an-red">Soft Mode (Recommended)</i>: Will try to catch all the CSS rules defined in your theme files, and add them to the new created element. If the browser does not support this feature, it will try Hard Mode fetching.</li>
					<li><i class="an-red">Hard Mode</i>: Will try to fetch all the elements CSS rules based on browser CSS compilation (not reading directly in your CSS files). This option may add a lot of inline CSS rules to your newly created element.</li>
				</ol>
				</p>
				<p>
					This feature is performed through Javascript (+jQuery) and is 95% functional on all modern browser even on IE8+. For the 5% left, the plugin will drop potential JS errors and insert <strong class="an-red">.an-alternative</strong> div.
					<br />
					<strong><i>Tested and works great on Chrome, Firefox, Safari, Opera, IE8+</i></strong>
				</p>
				<p>
					<strong class="an-red">What\'s appended if I don\'t turn on this option?</strong>
					<br />
					The plugin will append a new "clean" DIV element with  <strong class="an-red">.an-alternative</strong> class just before the advert container. You can add your own custom rules with the Custom CSS field below.
				</p>
			</div>
	',
) );
$alternativeTab->createOption( array(
    'name' => 'Clone ad container?',
    'id' => 'an_alternative_clone',
    'type' => 'select',
    'desc' => 'Will copy your original ad container CSS properties - Default: No<br /><strong>This feature is not 100% reliable but could help for a quick set up.</strong>',
    'options' => array(
        '1' => 'Custom Mode',
        '2' => 'Soft Mode (Recommended)',
        '3' => 'Hard Mode',
        '4' => 'None',
    ),
    'default' => '2',
) );
$alternativeTab->createOption( array(
		'name' => 'Custom Mode CSS properties <i>(Comma separated)</i>',
		'id' => 'an_alternative_properties',
		'type' => 'text',
		'desc' => 'The element CSS properties you want to clone - Default: Empty
		<br /><strong> Eg: color, width, height, background-color, border</strong> 
		Read <a href="http://www.w3schools.com/cssref/" target="_blank">CSS Reference | w3schools.com</a> for more details.
		',
) );
$alternativeTab->createOption( array(
    'name' => 'Custom CSS <br /><i>(Advance users)<i>',
    'id' => 'an_alternative_custom_css',
    'type' => 'code',
    'desc' => 'Put your custom CSS rules here. The new Element class is <strong class="an-red">.an-alternative</strong>
				<p>
				<strong>NOTE:</strong> If you\'ve activated the ads containers cloning, you can still add custom CSS on your text.
				<br />If you really have to overload <strong>.an-alternative</strong> with your own CSS properties, you may probably need to use <strong class="an-red">!important</strong> after each of them, but this is not advised.
				</p>',
    'lang' => 'css',
) );

/***************************************************************
 * Launch options framework instance
 ***************************************************************/
$generalTab->createOption( array(
    'type' => 'save',
		//'use_reset' => false
) );

$modalTab->createOption( array(
    'type' => 'save',
) );

$alternativeTab->createOption( array(
    'type' => 'save',
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