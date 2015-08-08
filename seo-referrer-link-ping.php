<?php
/*
 * Plugin Name: SEO Referrer Link Ping
 * Plugin URI: http://bluehatseo.com/blue-hat-technique-18-link-saturation-w-log-link-matching/
 * Description: Automatically ping all referrer links for an SEO boost.
 * Version: 1.1.0
 * Author: Equus Assets
 * Author URI: http://equusassets.com/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class SEO_Referrer_Link_Ping_Plugin
{
  static $instance = false;
  private $referrer = '';

  private function __construct() {
    add_action("parse_request", array($this, "seo_referrer_link_ping_capture"));
    add_action("wp_footer", array($this, "seo_referrer_link_ping"));
    add_action("admin_init", array($this, "seo_referrer_link_ping_init"));
    add_action("admin_menu", array($this, "seo_referrer_link_ping_add_page"));
    register_activation_hook( __FILE__, array( 'SEO_Referrer_Link_Ping_Plugin', 'seo_referrer_link_ping_activate' ) );
  }

  public static function getInstance() {
    if (!self::$instance)
      self::$instance = new self;
    return self::$instance;
  }

  function seo_referrer_link_ping_activate()
  {
    if (!get_option('seo_referrer_link_ping_initialized')) {
      add_option('seo_referrer_link_ping_initialized', true);
  	  $seo_referrer_link_ping_strings['common']['chk_weblogscom'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_newsgator'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_blogdigger'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_topicexchange'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_skygrid'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_blogs'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_myyahoo'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_weblogalot'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_google'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_collecta'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_feedburner'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_pubsubcom'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_newsisfree'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_tailrank'] = 1;
  	  $seo_referrer_link_ping_strings['common']['chk_superfeedr'] = 1;
  	  $seo_referrer_link_ping_strings['special']['chk_audioweblogs'] = 0;
  	  $seo_referrer_link_ping_strings['special']['chk_rubhub']  = 0;
  	  $seo_referrer_link_ping_strings['special']['chk_a2b'] = 0;
  	  $seo_referrer_link_ping_strings['special']['chk_blogshares'] = 0;
      add_option('seo_referrer_link_ping_strings', $seo_referrer_link_ping_strings);
    }
  }

  function seo_referrer_link_ping_not_mydomain()
  {
    $mydomain = parse_url(get_bloginfo('url'));
    return preg_match("/http(s)?:\/\/(www\.)?".$mydomain['host']."\//i",$this->referrer) === 0;
  }
  
  function seo_referrer_link_ping_not_blocked()
  {
    $referdomain = parse_url($this->referrer);
    $blocked_domains_option = "google.com,bing.com,yahoo.com,msn.com,ask.com,aol.com,wow.com,webcrawler.com,mywebsearch.com,infospace.com,info.com,duckduckgo.com,blekko.com,contentko.com,dogpile.com,alhea.com,yandex.com,baidu.com,youtube.com,facebook.com";
    $blockeddomains = explode(",", $blocked_domains_option);
    reset($blockeddomains);
    foreach ($blockeddomains as $domain)
    {
      if (strpos($referdomain['host'], $domain) !== false)
        return false;
    }
    return true;
  }
  
  function seo_referrer_link_ping()
  {
    if($this->referrer != "" && $this->seo_referrer_link_ping_not_mydomain() && $this->seo_referrer_link_ping_not_blocked())
    {
      $referrer = urlencode($this->referrer);
      echo "<div style=\"visibility:hidden\"><iframe src=\"http://pingomatic.com/ping/?title=$referrer&blogurl=$referrer&rssurl=$referrer";
  		$options = get_option( 'seo_referrer_link_ping_strings' );
      foreach ($options['common'] as $service => $value) {
        if ($value == 1)
          echo "&$service=on";
      }
      foreach ($options['special'] as $service => $value) {
        if ($value == 1)
          echo "&$service=on";
      }
      echo "\" border=\"0\" width=\"0\" height=\"0\" style=\"border:0px;\"></iframe></div>";
    }
  }
  
  function seo_referrer_link_ping_capture()
  {
    $this->referrer = $_SERVER['HTTP_REFERER'];
  }

  // Init plugin options
  public function seo_referrer_link_ping_init() {
  	register_setting( 'seo_referrer_link_ping_options', 'seo_referrer_link_ping_strings', array($this, 'seo_referrer_link_ping_validate' ));
  }
  
  // Add menu page
  public function seo_referrer_link_ping_add_page() {
  	add_options_page( __( 'SEO Referrer Link Ping', 'seo_referrer_link_ping' ), __( 'SEO Referrer Link Ping', 'seo_referrer_link_ping' ), 'manage_options', 'seo_referrer_link_ping', array($this, 'seo_referrer_link_ping_do_page') );
  }

  // Draw the menu page itself
  public function seo_referrer_link_ping_do_page() {
  	?>
  	<div class="wrap">
  		<h2><?php _e( 'SEO Referrer Link Ping Options', 'seo_referrer_link_ping' ); ?></h2>
  
  		<form method="post" action="options.php">
  			<?php
  
  			settings_fields( 'seo_referrer_link_ping_options' );
  			$options = get_option( 'seo_referrer_link_ping_strings' );
  
  			?>
  
  			<h3><?php _e( 'Common Services (<a id="checkall" href="javascript:check_common();">Check All</a>)', 'seo_referrer_link_ping' ); ?></h3>
        <script type="text/javascript">
          function GetElementsWithClassName(_1,_2){var _3=document.getElementsByTagName(_1);var _4=new Array();for(i=0;i<_3.length;i++){if(_3[i].className==_2){_4[_4.length]=_3[i];}}return _4;}

          var allchecked = false;
          function check_common(){
            var checkall = document.getElementById("checkall");
            var _5=GetElementsWithClassName("input","common");
            if (allchecked==false) {
              for(i=0;i<_5.length;i++){
                _5[i].checked="checked";
              }
              allchecked = true;
              checkall.firstChild.nodeValue="Uncheck all"
            }
            else {
              for(i=0;i<_5.length;i++){
                _5[i].checked=false;
              }
              allchecked = false;
              checkall.firstChild.nodeValue="Check all"
            }
          }
        </script> 
  			<table class="form-table">
          <tr>
            <td>
              <label for="chk_weblogscom">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_weblogscom]" value="1" <?php checked(1, $options['common']['chk_weblogscom'], true ); ?> />
              <?php _e('Weblogs.com', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_newsgator">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_newsgator]" value="1" <?php checked(1, $options['common']['chk_newsgator'], true ); ?> />
              <?php _e('NewsGator', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_blogdigger">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_blogdigger]" value="1" <?php checked(1, $options['common']['chk_blogdigger'], true ); ?> />
              <?php _e('Blogdigger', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_topicexchange">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_topicexchange]" value="1" <?php checked(1, $options['common']['chk_topicexchange'], true ); ?> />
              <?php _e('Topic Exchange', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_skygrid">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_skygrid]" value="1" <?php checked(1, $options['common']['chk_skygrid'], true ); ?> />
              <?php _e('SkyGrid', 'seo_referrer_link_ping'); ?>
              </label>
  					</td>
  					<td>
              <label for="chk_blogs">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_blogs]" value="1" <?php checked(1, $options['common']['chk_blogs'], true ); ?> />
              <?php _e('Blo.gs', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_myyahoo">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_myyahoo]" value="1" <?php checked(1, $options['common']['chk_myyahoo'], true ); ?> />
              <?php _e('My Yahoo!', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_weblogalot">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_weblogalot]" value="1" <?php checked(1, $options['common']['chk_weblogalot'], true ); ?> />
              <?php _e('Weblogalot', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_google">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_google]" value="1" <?php checked(1, $options['common']['chk_google'], true ); ?> />
              <?php _e('Google Blog Search', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_collecta">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_collecta]" value="1" <?php checked(1, $options['common']['chk_collecta'], true ); ?> />
              <?php _e('Collecta', 'seo_referrer_link_ping'); ?>
              </label>
  					</td>
  					<td>
              <label for="chk_feedburner">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_feedburner]" value="1" <?php checked(1, $options['common']['chk_feedburner'], true ); ?> />
              <?php _e('Feed Burner', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_pubsubcom">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_pubsubcom]" value="1" <?php checked(1, $options['common']['chk_pubsubcom'], true ); ?> />
              <?php _e('PubSub.com', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_newsisfree">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_newsisfree]" value="1" <?php checked(1, $options['common']['chk_newsisfree'], true ); ?> />
              <?php _e('News Is Free', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_tailrank">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_tailrank]" value="1" <?php checked(1, $options['common']['chk_tailrank'], true ); ?> />
              <?php _e('Spinn3r', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_superfeedr">
  					  <input type="checkbox" class="common" name="seo_referrer_link_ping_strings[common][chk_superfeedr]" value="1" <?php checked(1, $options['common']['chk_superfeedr'], true ); ?> />
              <?php _e('Superfeedr', 'seo_referrer_link_ping'); ?>
              </label>
  					</td>
  				</tr>
  			</table>
  
  			<h3><?php _e( 'Specialized Services', 'seo_referrer_link_ping' ); ?></h3>
  
  			<table class="form-table">
          <tr>
            <td>
              <label for="chk_audioweblogs">
  					  <input type="checkbox" name="seo_referrer_link_ping_strings[special][chk_audioweblogs]" value="1" <?php checked(1, $options['special']['chk_audioweblogs'], true ); ?> />
              <?php _e('Weblogs.com', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_rubhub">
  					  <input type="checkbox" name="seo_referrer_link_ping_strings[special][chk_rubhub]" value="1" <?php checked(1, $options['special']['chk_rubhub'], true ); ?> />
              <?php _e('RubHub', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_a2b">
  					  <input type="checkbox" name="seo_referrer_link_ping_strings[special][chk_a2b]" value="1" <?php checked(1, $options['special']['chk_a2b'], true ); ?> />
              <?php _e('A2B GeoLocation', 'seo_referrer_link_ping'); ?>
              </label>
              <br/>
              <label for="chk_blogshares">
  					  <input type="checkbox" name="seo_referrer_link_ping_strings[special][chk_blogshares]" value="1" <?php checked(1, $options['special']['chk_blogshares'], true ); ?> />
              <?php _e('BlogShares', 'seo_referrer_link_ping'); ?>
              </label>
  					</td>
          </tr>
  			</table>
  
  			<p class="submit">
  				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save configuration', 'seo_referrer_link_ping' ); ?>" />
  			</p>
  		</form>
  	</div>
  	<?php
  }
  
  // Sanitize and validate input. Accepts an array, return a sanitized array.
  public function seo_referrer_link_ping_validate( $input ) {
    // leaving placeholder for future input checking
  
  	return $input;
  }
}

$SEO_Referrer_Link_Ping_Plugin = SEO_Referrer_Link_Ping_Plugin::getInstance();
?>
