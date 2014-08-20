<?php
/*
Plugin Name: Collapse-Pro-Matic
Text Domain: colomat
Domain Path: /languages
Plugin URI: http://plugins.twinpictures.de/premium-plugins/collapse-pro-matic/
Description: Collapse-Pro-Matic is the premium version of Collapse-O-Matic.
Version: 1.0.2
Author: twinpictures, baden03
Author URI: http://twinpictures.de/
License: GPL2
*/

require 'plugin-updates/plugin-update-checker.php';

/**
 * Class WP_Collapse_Pro_Matic
 * @package WP_Collapse_Pro_Matic
 * @category WordPress Plugins
 */

class WP_Collapse_Pro_Matic {

	/**
	 * Current version
	 * @var string
	 */
	var $version = '1.0.2';

	/**
	 * Used as prefix for options entry
	 * @var string
	 */
	var $domain = 'colomat';
	
	/**
	 * Name of the options
	 * @var string
	 */
	var $options_name = 'WP_Collapse_Pro_Matic_options';

	/**
	 * @var array
	 */
	var $options = array(
		'download_key' => '',
		'email' => '',
		'style' => 'light',
		'tag' => 'span',
		'offset' => 0,
		'trigpos' => 'above',
		'wraptag' => '',
		'wrapclass' => '',
		'targtag' => 'div',
		'targpos' => '',
		'duration' => 'fast',
		'slideEffect' => 'slideFade',
		'cookie_expire' => 7,
		'custom_css' => '',
		'sub_expands' => 30
	);

	/**
	 * PHP5 constructor
	 */
	function __construct() {
		// set option values
		$this->_set_options();
		
		if ( !empty($this->options['download_key']) && !empty($this->options['email']) ) {
			$MyUpdateChecker = new PluginUpdateChecker(
					'http://plugins.twinpictures.de/wp-content/premium-updates/collapse-pro-matic-info.php',
					__FILE__
			);
			$MyUpdateChecker->addQueryArgFilter(array( $this, 'wsh_filter_update_checks' ) );
		}
	
		// load text domain for translations
		load_plugin_textdomain( 'colomat', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
		//load the script and style if not viewing the dashboard
		if (!is_admin()){
			add_action('init', array( $this, 'collapsTronicInit' ) );
		}
		
		// add actions
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_actions' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action('wp_head', array( $this, 'colomat_js_vars' ) );
		
		add_shortcode('expand', array($this, 'shortcode'));
		
		//add expandsub shortcodes
		for ($i=1; $i<$this->options['sub_expands']; $i++) {
			add_shortcode('expandsub'.$i, array($this, 'shortcode'));
		}
		// Add shortcode support for widgets  
		add_filter('widget_text', 'do_shortcode'); 
	}
	
	function wsh_filter_update_checks($queryArgs) {
		$queryArgs['download_key'] = $this->options['download_key'];
		$queryArgs['email'] = $this->options['email'];
		return $queryArgs;
	}
	
	//global javascript vars
	function colomat_js_vars(){
		echo "<script type='text/javascript'>\n";
		echo "var colomatduration = '".$this->options['duration']."';\n";
		echo "var colomatslideEffect = '".$this->options['slideEffect']."';\n";
		echo "var colomatcookielife = ".$this->options['cookie_expire'].";\n";
		echo "</script>";
		if( !empty( $this->options['custom_css'] ) ){
			echo "\n<style>\n";
			echo $this->options['custom_css'];
			echo "\n</style>\n";
		}
	}

	/**
	 * Callback init
	 */
	function collapsTronicInit() {
		//load up jQuery the Jedi way
		wp_enqueue_script('jquery');
		
		//collapse script
		wp_register_script('collapseomatic-js', plugins_url('js/collapsepro.js', __FILE__), array('jquery'), '0.7');
		wp_enqueue_script('collapseomatic-js');
		
		//css
		wp_register_style( 'collapseomatic-css', plugins_url('/'.$this->options['style'].'_style.css', __FILE__) , array (), '1.5.2' );
		wp_enqueue_style( 'collapseomatic-css' );
	}

	/**
	 * Callback admin_menu
	 */
	function admin_menu() {
		if ( function_exists( 'add_options_page' ) AND current_user_can( 'manage_options' ) ) {
			// add options page
			$page = add_options_page('Collapse-Pro-Matic Options', 'Collapse-Pro-Matic', 'manage_options', 'collapse-o-matic-options', array( $this, 'options_page' ));
		}
	}

	/**
	 * Callback admin_init
	 */
	function admin_init() {
		// register settings
		register_setting( $this->domain, $this->options_name );
	}
	
	/**
	 * Callback shortcode
	 */
	function shortcode($atts, $content = null){
		//find a random number, if no id is assigned
		$options = $this->options;
		$ran = rand(1, 10000);
		extract(shortcode_atts(array(
			'title' => '',
			'swaptitle' => '',
			'alt' => '',
			'swapalt' => '',
			'notitle' => '',
			'id' => 'id'.$ran,
			'tag' => $options['tag'],
			'trigclass' => '',
			'trigpos' => $options['trigpos'],
			'targtag' => $options['targtag'],
			'targclass' => '',
			'targpos' => $options['targpos'],
			'rel' => '',
			'expanded' => '',
			'excerpt' => '',
			'excerptpos' => 'below-trigger',
			'excerpttag' => 'div',
			'excerptclass' => '',
			'swapexcerpt' => false,
			'findme' => '',
			'offset' => $options['offset'],
			'scrollonclose' => '',
			'startwrap' => '',
			'endwrap' => '',
			'elwraptag' => $options['wraptag'],
			'elwrapclass' => $options['wrapclass'],
			'cookiename' => '',
		), $atts));
		
		$ewo = '';
		$ewc = '';
		if($elwraptag){
			$ewclass = '';
			if($elwrapclass){
				$ewclass = 'class="'.$elwrapclass.'"';
			}
			$ewo = '<'.$elwraptag.' '.$ewclass.'>';
			$ewc = '</'.$elwraptag.'>';
		}
		$eDiv = '';
		if($content){
			$inline_class = '';
			$collapse_class = 'collapseomatic_content ';
			if($targpos == 'inline'){
				$inline_class = 'colomat-inline ';
				$collapse_class = 'collapseomatic_content_inline ';
			}
			$eDiv = '<'.$targtag.' id="target-'.$id.'" class="'.$collapse_class.$inline_class.$targclass.'">'.do_shortcode($content).'</'.$targtag.'>';
		}
		if($excerpt){
			if($targpos == 'inline'){
				$excerpt .= $eDiv;
				$eDiv = '';
			}
			if($excerptpos == 'above-trigger'){
				$nibble = '<'.$excerpttag.' id="excerpt-'.$id.'" class="'.$excerptclass.'">'.$excerpt.'</'.$excerpttag.'>';
			}
			else{
				$nibble = '<'.$excerpttag.' id="excerpt-'.$id.'" class="collapseomatic_excerpt '.$excerptclass.'">'.$excerpt.'</'.$excerpttag.'>';
			}
			//swapexcerpt
			if($swapexcerpt !== false){
				$nibble .= '<'.$excerpttag.' id="swapexcerpt-'.$id.'" style="display:none;">'.$swapexcerpt.'</'.$excerpttag.'>';
			}
		}
		$altatt = '';
		if($alt){
			$altatt = 'alt="'.$alt.'" title="'.$alt.'"';
		}
		else if( !$notitle ){
			$altatt = 'title="'.$title.'"';
		}
		$relatt = '';
		if($rel){
			$relatt = 'rel="'.$rel.'"';
		}
		if($expanded){
			$trigclass .= ' colomat-close';
		}
		$anchor = '';
		if($findme){
			$trigclass .= ' find-me';
			$loc = '';
			if($findme != 'true' && $findme != 'auto'){
				$loc = $findme;
			}
			$anchor = '<a id="find-'.$id.'" name="'.$loc.'" alt="'.$offset.'"> </a>';
		}
		$closeanchor = '';
		if($scrollonclose && (is_numeric($scrollonclose) || $scrollonclose == 0)){
			$trigclass .= ' scroll-to-trigger';
			$closeanchor = '<a id="scrollonclose-'.$id.'" name="'.$scrollonclose.'"> </a>';
		}
		$cookie = '';
		if($cookiename){
			$cookie = 'cookie = "'.$cookiename.'"';
		}
		$link = $closeanchor.$anchor.'<'.$tag.' class="collapseomatic '.$trigclass.'" id="'.$id.'" '.$relatt.' '.$altatt.' '.$cookie.'>'.$startwrap.$title.$endwrap.'</'.$tag.'>';
		if($swaptitle){
			$link .= "<".$tag." id='swap-".$id."' alt='".$swapalt."' style='display:none;'>".$startwrap.$swaptitle.$endwrap."</".$tag.">";
		}
	
		$eDiv = '';
		if($content){
			$eDiv = '<'.$targtag.' id="target-'.$id.'" class="collapseomatic_content '.$targclass.'">'.do_shortcode($content).'</'.$targtag.'>';
		}
		
		if($excerpt){
			if($excerptpos == 'above-trigger'){
				if($trigpos == 'below'){
					$retStr = $ewo.$eDiv.$nibble.$link.$ewc;
				}
				else{
					$retStr = $ewo.$nibble.$link.$eDiv.$ewc;
				}
			}
			else if($excerptpos == 'below-trigger'){
				if($trigpos == 'below'){
					$retStr =  $ewo.$eDiv.$link.$nibble.$ewc;
				}
				else{
					$retStr = $ewo.$link.$nibble.$eDiv.$ewc;
				}
			}
			else{
				if($trigpos == 'below'){
					$retStr = $ewo.$eDiv.$link.$nibble.$ewc;
				}
				else{
					$retStr = $ewo.$link.$eDiv.$nibble.$ewc;
				}
			}
		}
		else{
			if($trigpos == 'below'){
				$retStr = $ewo.$eDiv.$link.$ewc;
			}
			else{
				$retStr = $ewo.$link.$eDiv.$ewc;
			}
		}
		return $retStr;
	}
	
	// Add link to options page from plugin list
	function plugin_actions($links) {
		$new_links = array();
		$new_links[] = '<a href="options-general.php?page=collapse-o-matic-options">' . __('Settings', 'colomat') . '</a>';
		return array_merge($new_links, $links);
	}

	/**
	 * Admin options page
	 */
	function options_page() {
		$like_it_arr = array('made you feel all warm and fuzzy on the inside', 'restored your faith in humanity... even if only for a fleeting second', 'rocked your world', 'provided a positive vision of future living', 'inspired you to commit a random act of kindness', 'encouraged more regular flossing of the teeth', 'helped organize your life in the small ways that matter', 'saved your minutes--if not tens of minutes--writing your own solution', 'brightened your day... or darkened if if you are trying to sleep in', 'caused you to dance a little jig of joy and joyousness', 'inspired you to tweet a little @twinpictues social love', 'tasted great, while also being less filling');
		$rand_key = array_rand($like_it_arr);
		$like_it = $like_it_arr[$rand_key];
	?>
		<div class="wrap">
			<div class="icon32" id="icon-options-custom" style="background:url( <?php echo plugins_url( 'images/collapse-o-matic-icon.png', __FILE__ ) ?> ) no-repeat 50% 50%"><br></div>
			<h2>Collapse-Pro-Matic</h2>
		</div>
		
		<div class="postbox-container metabox-holder meta-box-sortables" style="width: 69%">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'colomat' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'Collapse-Pro-Matic Settings', 'colomat' ) ?></h3>
					<div class="inside">
						<form method="post" action="options.php">
							<?php
								settings_fields( $this->domain );
								$this->_set_options();
								$options = $this->options;
							?>
							<fieldset class="options">
								<table class="form-table">
								<tr>
									<th><?php _e( 'Style', 'colomat' ) ?>:</th>
									<td><label><select id="<?php echo $this->options_name ?>[style]" name="<?php echo $this->options_name ?>[style]">
										<?php
											if(empty($options['style'])){
												$options['style'] = 'light';
											}
											$st_array = array(
												__('Light', 'colomat') => 'light',
												__('Dark', 'colomat') => 'dark'
											);
											foreach( $st_array as $key => $value){
												$selected = '';
												if($options['style'] == $value){
													$selected = 'SELECTED';
												}
												echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
											}
										?>
										</select>
										<br /><span class="description"><?php _e('Select Light for sites with lighter backgrounds. Select Dark for sites with darker backgrounds.', 'colomat'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Tag Attribute', 'colomat' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[tag]" name="<?php echo $this->options_name ?>[tag]" value="<?php echo $options['tag']; ?>" />
										<br /><span class="description"><?php printf(__('HTML tag use to wrap the trigger text. See %sTag Attribute%s in the documentation for more info.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#tag" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Offset Attribute', 'colomat' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[offset]" name="<?php echo $this->options_name ?>[offset]" value="<?php echo $options['offset']; ?>" />
										<br /><span class="description"><?php _e('Default offset value for Findme Attribute.', 'colomat'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Wrap Tag Attribute', 'colomat' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[wraptag]" name="<?php echo $this->options_name ?>[wraptag]" value="<?php echo $options['wraptag']; ?>" />
										<br /><span class="description"><?php printf(__('HTML tag use to wrap the entire collapse element. See %sElwraptag Attribute%s in the documentation for more info.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#elwraptag" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Wrap Class Attribute', 'colomat' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[wrapclass]" name="<?php echo $this->options_name ?>[wrapclass]" value="<?php echo $options['wrapclass']; ?>" />
										<br /><span class="description"><?php printf(__('Class used to wrap the entire collapse element. See %sElwrapclass Attribute%s in the documentation for more info.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#elwrapclass" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Targtag Attribute', 'colomat' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[targtag]" name="<?php echo $this->options_name ?>[targtag]" value="<?php echo $options['targtag']; ?>" />
										<br /><span class="description"><?php printf(__('HTML tag use for the target element. See %sTargtag Attribute%s in the documentation for more info.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#targtag" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Trigpos Attribute', 'colomat' ) ?>:</th>
									<td><label><select id="<?php echo $this->options_name ?>[trigpos]" name="<?php echo $this->options_name ?>[trigpos]">
										<?php
											$tp_array = array(
												__('Above', 'colomat') => 'above',
												__('Below', 'colomat') => 'below'
											);
											foreach( $tp_array as $key => $value){
												$selected = '';
												if($options['trigpos'] == $value){
													$selected = 'SELECTED';
												}
												echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
											}
										?>
										</select>
										<br /><span class="description"><?php printf(__('Position the title trigger above or below the target. See %sTrigpos Attribute%s in the documentation for more info.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#trigpos" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Targpos Attribute', 'colomat' ) ?>:</th>
									<td><label><select id="<?php echo $this->options_name ?>[targpos]" name="<?php echo $this->options_name ?>[targpos]">
										<?php
											$tap_array = array(
												__('Block', 'colomat') => '',
												__('Inline', 'colomat') => 'inline'
											);
											foreach( $tap_array as $key => $value){
												$selected = '';
												if($options['targpos'] == $value){
													$selected = 'SELECTED';
												}
												echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
											}
										?>
										</select>
										<br /><span class="description"><?php printf(__('Position the target as an inline or block element. See %sTargpos Attribute%s in the documentation for more info.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#targpos" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<?php
										if(empty($options['duration'])){
												$options['duration'] = 'fast';
										}
									?>
									<th><?php _e( 'Collapse/Expand Duration', 'colomat' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[duration]" name="<?php echo $this->options_name ?>[duration]" value="<?php echo $options['duration']; ?>" />
										<br /><span class="description"><?php printf(__('A string or number determining how long the animation will run. See %sDuration%s in the documentation for more info.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#duration" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Animation Effect', 'colomat' ) ?>:</th>
									<td><label><select id="<?php echo $this->options_name ?>[slideEffect]" name="<?php echo $this->options_name ?>[slideEffect]">
										<?php
											if(empty($options['slideEffect'])){
												$options['slideEffect'] = 'slideFade';
											}
											$se_array = array(
												__('Slide Only', 'colomat') => 'slideToggle',
												__('Slide & Fade', 'colomat') => 'slideFade'
											);
											foreach( $se_array as $key => $value){
												$selected = '';
												if($options['slideEffect'] == $value){
													$selected = 'SELECTED';
												}
												echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
											}
										?>
										</select>
										<br /><span class="description"><?php printf(__('Animation effect to use while collapsing and expanding. See %sAnimation Effect%s in the documentation for more info.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/#animation-effect" target="_blank">', '</a>'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Cookie Expires', 'colomat' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[cookie_expire]" name="<?php echo $this->options_name ?>[cookie_expire]" value="<?php echo $options['cookie_expire']; ?>" /> <?php echo _n( 'Day', 'Days', $options['cookie_expire'], 'colomat' ); ?>
										<br /><span class="description"><?php _e('How many days before cookie expires.', 'colomat'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Nested Sub Expands', 'colomat' ) ?>:</th>
									<td><label><input type="text" id="<?php echo $this->options_name ?>[sub_expands]" name="<?php echo $this->options_name ?>[sub_expands]" value="<?php echo $options['sub_expands']; ?>" />
										<br /><span class="description"><?php _e('How many nested subexpand levels.', 'colomat'); ?></span></label>
									</td>
								</tr>
								
								<tr>
									<th><?php _e( 'Custom Style', 'colomat' ) ?>:</th>
									<td><label><textarea id="<?php echo $this->options_name ?>[custom_css]" name="<?php echo $this->options_name ?>[custom_css]" style="width: 100%; height: 150px;"><?php echo $options['custom_css']; ?></textarea>
										<br /><span class="description"><?php _e( 'Custom CSS style for <em>ultimate flexibility</em>', 'colomat' ) ?></span></label>
									</td>
								</tr>
								
								</table>
							</fieldset>
							
							<p class="submit" style="margin-bottom: 20px;">
								<input class="button-primary" type="submit" value="<?php _e( 'Save Changes' ) ?>" style="float: right;" />
							</p>
						<!--</form>-->
					</div>
				</div>
			</div>
		</div>
		
		<div class="postbox-container side metabox-holder meta-box-sortables" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'colomat' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'About' ) ?></h3>
					<div class="inside">
						<h4><img src="<?php echo plugins_url( 'images/collapse-o-matic-icon.png', __FILE__ ) ?>" width="16" height="16"/> Collapse-Pro-Matic Version <?php echo $this->version; ?></h4>
						<p><?php _e( 'Remove clutter, save space. Display and hide additional content in a SEO friendly way. Wrap any content&mdash;including other shortcodes&mdash;into a lovely jQuery expanding and collapsing element.', 'colomat') ?></p>
						<ul>
							<li>
								<?php printf( __( '%sDetailed documentation%s, complete with working demonstrations of all shortcode attributes, is available for your instructional enjoyment.', 'colomat'), '<a href="http://plugins.twinpictures.de/plugins/collapse-o-matic/documentation/" target="_blank">', '</a>'); ?>
								<?php printf( __( 'A %scomplete list of pro features%s is also available.', 'colomat'), '<a href="http://wordpress.org/support/plugin/jquery-collapse-o-matic" target="_blank">', '</a>', '<a href="http://plugins.twinpictures.de/plugins/collapse-pro-matic/documentation/" target="_blank">', '</a>'); ?>
							</li>
							<li><?php printf( __('If this plugin %s, please consider %ssharing your story%s with others.', 'colomat'), $like_it, '<a href="http://www.facebook.com/twinpictures" target="_blank">', '</a>' ) ?></li>
							<li>Your comments, bug-reports, feedback and cocktail recipes are always welcomed at the <a href="http://plugins.twinpictures.de/premium-plugins/collapse-pro-matic/" target="_blank">Twinpictues Plugin Oven</a>.</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="postbox-container side metabox-holder meta-box-sortables" style="width:29%;">
			<div style="margin:0 5px;">
				<div class="postbox">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'colomat' ) ?>"><br/></div>
					<h3 class="handle"><?php _e( 'Register Plugin', 'colomat') ?></h3>
					<div class="inside">
					<p><?php _e('To receive plugin updates you must register your plugin. Enter your Receipt ID and email address used to purchase the plugin below.', 'colomat'); ?></p>
							<fieldset>
								<table>
									<tr>
										<th><?php _e( 'Receipt ID', 'colomat' ) ?>:</th>
										<td><label><input type="text" id="<?php echo $this->options_name ?>[download_key]" name="<?php echo $this->options_name ?>[download_key]" value="<?php echo $options['download_key']; ?>" style="width: 100%" />
											<br /><span class="description"><?php _e('Receipt ID is found in the Purchase Receipt', 'colomat'); ?></span></label>
										</td>
									</tr>
									
									<tr>
										<th><?php _e( 'Email', 'colomat' ) ?>:</th>
										<td><label><input type="text" id="<?php echo $this->options_name ?>[email]" name="<?php echo $this->options_name ?>[email]" value="<?php echo $options['email']; ?>" style="width: 100%" />
										<br /><span class="description"><?php _e('Email address used to purchase plugin', 'colomat'); ?></span></label>
										</td>
									</tr>
								</table>
							</fieldset>
							<p class="submit" style="margin-bottom: 20px;">
								<input class="button-primary" type="submit" style="float: right;" value="<?php _e( 'Register' ) ?>" />
							</p>
						</form>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	<?php
	}

	/**
	 * Set options from save values or defaults
	 */
	function _set_options() {
		// set options
		$saved_options = get_option( $this->options_name );

		// backwards compatible (old values)
		if ( empty( $saved_options ) ) {
			$saved_options = get_option( $this->domain . 'options' );
		}
		
		// set all options
		if ( ! empty( $saved_options ) ) {
			foreach ( $this->options AS $key => $option ) {
				$this->options[ $key ] = ( empty( $saved_options[ $key ] ) ) ? '' : $saved_options[ $key ];
			}
		}
	}

} // end class WP_Collapse_Pro_Matic


/**
 * Create instance
 */
$WP_Collapse_Pro_Matic = new WP_Collapse_Pro_Matic;

?>