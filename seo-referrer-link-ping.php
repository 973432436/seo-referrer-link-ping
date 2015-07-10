<?php
/*
 *Plugin Name: SEO Referrer Link Ping
 *Plugin URI: http://bluehatseo.com/blue-hat-technique-18-link-saturation-w-log-link-matching/
 *Description: Automatically ping all referrer links for an SEO boost.
 *Version: 1.0
 *Author: Equus Assets, Inc.
 *Author URI: http://equusassets.com/
 *License: GPLv2
 *License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class SEO_Referrer_Link_Ping_Plugin
{
  static $instance = false;
  private $referrer = '';

  private function __construct() {
    add_action("parse_request", array($this, "seo_referrer_link_ping_capture"));
    add_action("wp_footer", array($this, "seo_referrer_link_ping"));
  }

  public static function getInstance() {
    if (!self::$instance)
      self::$instance = new self;
    return self::$instance;
  }

  function seo_referrer_link_ping_not_mydomain()
  {
    $mydomain = parse_url(get_bloginfo('url'));
    return preg_match("/http(s)?:\/\/(www\.)?".$mydomain['host']."\//i",$this->referrer) === 0;
  }
  
  function seo_referrer_link_ping_not_blocked()
  {
    $referdomain = parse_url($this->referrer);
    $blocked_domains_option = "google.com,bing.com,yahoo.com,msn.com,ask.com,aol.com,yandex.com,search.com";
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
      echo "<div style=\"visibility:hidden\"><iframe src=\"http://pingomatic.com/ping/?title=$referrer&blogurl=$referrer&rssurl=$referrer&chk_weblogscom=on&chk_blogs=on&chk_feedburner=on&chk_newsgator=on&chk_myyahoo=on&chk_pubsubcom=on&chk_blogdigger=on&chk_weblogalot=on&chk_newsisfree=on&chk_topicexchange=on&chk_google=on&chk_tailrank=on&chk_skygrid=on&chk_collecta=on&chk_superfeedr=on&chk_audioweblogs=on&chk_rubhub=on&chk_a2b=on&chk_blogshares=on\" border=\"0\" width=\"0\" height=\"0\" style=\"border:0px;\"></iframe></div>";
    }
  }
  
  function seo_referrer_link_ping_capture()
  {
    $this->referrer = $_SERVER['HTTP_REFERER'];
  }
}

$SEO_Referrer_Link_Ping_Plugin = SEO_Referrer_Link_Ping_Plugin::getInstance();
?>
