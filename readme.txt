=== UserCheck ===
Contributors: usercheck
Tags: email validation, disposable email, anti-spam
Requires at least: 5.2
Tested up to: 6.6
Stable tag: 0.0.2
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Protect your WordPress site from disposable email addresses using the UserCheck API.

== Description ==

UserCheck prevents disposable or throwaway email addresses from registering or commenting on your site. This helps to protect your site from spam and maintain the quality of your user base.

= Key Features =

* Automatically checks email addresses against a constantly updated database of disposable email domains
* Works out of the box with no configuration required
* Seamlessly integrates with WordPress registration and comment forms

The plugin uses the API provided by [UserCheck](https://www.usercheck.com), which is constantly updated to include the latest disposable email domains. This ensures your site stays protected against new disposable email providers.

== Installation ==

1. Upload the `usercheck` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. (Optional) Go to Settings > UserCheck to enter your API key for increased limits

== Frequently Asked Questions ==

= Do I need an API key to use this plugin? =

No, an API key is not required to use this plugin. It works out of the box with a limit of 60 requests per hour. However, if you need higher limits, you can obtain a free API key by signing up at [https://app.usercheck.com/](https://app.usercheck.com/).

= What happens if the UserCheck API is unavailable? =

If the API is unavailable, the plugin will allow the email to be considered valid to prevent disruption to your site's functionality. An error message will be logged for your reference.

= How can I increase the request limit? =

To increase the request limit beyond 60 per hour, you can obtain an API key from UserCheck. Once you have the key, go to the UserCheck settings in your WordPress admin panel and enter the key there.

== Changelog ==

= 0.0.1 =
* Initial release

== Privacy Policy ==

UserCheck sends email domains to the UserCheck API for validation. No personal data or full email addresses are transmitted. For more information on how UserCheck handles data, please visit [https://www.usercheck.com/privacy](https://www.usercheck.com/privacy).
