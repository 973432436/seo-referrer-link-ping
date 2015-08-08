=== SEO Referrer Link Ping ===
Contributors: helpgeek
Donate link: http://equusassets.com/
Tags: seo
Requires at least: 3.0
Tested up to: 4.2.2
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically ping all referrer links for an SEO boost.

== Description ==

SEO Referrer Link Ping Plugin is an implementation of the SEO principle of pinging referrer links to your website based on the article originally appearing on [Blue Hat SEO](http://bluehatseo.com/blue-hat-technique-18-link-saturation-w-log-link-matching/ "Link Saturation with Log Link Matching").

The general idea is to achieve a referrer link saturation rate of 100% to boost your SEO footprint. Link saturation is the percentage of links indexed by search engines versus actual total links. For example, you may have 100 referrer links to your site coming from other websites but only 44 of those might be indexed by search engines. In such a case you would have a referrer link saturation of 44%.

Installing SEO Referrer Link Ping Plugin will cause all referrer links to any page in your WordPress site to be submitted to [Pingomatic](http://pingomatic.com "Pingomatic pinging services") pinging service automatically behind the scenes. The hope is these referring links will be indexed by the major search engines if they aren't already.

When activated, any time a web visitor clicks through to your site from an external domain, SEO Referrer Link Ping Plugin will submit that referring link to Pingomatic to make sure it gets indexed by search engines.

== Installation ==

1. Upload seo-referrer-link-ping.zip to `/wp-content/plugins/` directory and unzip.
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= What is a referrer? =

The HTTP referer (originally a misspelling of referrer) is an HTTP header field that identifies the address of the webpage that linked to the resource being requested. By checking the referrer, the new webpage can see where the request originated. (source: [Wikipedia](https://en.wikipedia.org/wiki/HTTP_referer "HTTP referer"))

= Is there anything to configure? =

The plugin works automatically behind the scenes and requires zero configuration.

= How do I verify if it is working? =

Are you able to [fake the referrer](https://addons.mozilla.org/en-us/firefox/addon/refcontrol/ "RefControl Firefox plugin")? If so you can supply a referrer and directly load any page on your site. If not, if you know of an external link from another website that links to your site you could click through from there. Once on your site right click and view the page source. Search for "pingomatic" and you should see an iframe containint HTML submitting the referrer to Pingomatic.

= Will it ping internal links? =

The plugin is smart enough to disregard referrer links from internal referrers (links from within your site on the same domain).

= Will it ping links from search engines? =

The plugin is smart enough to disregard referrer links from a few major search engines.

== Screenshots ==

== Changelog ==

= 1.1.0 =
* Added plugin options page with finer grain control of ping services so as not to overload pingomatic

= 1.0.0 =
* Initial release.
