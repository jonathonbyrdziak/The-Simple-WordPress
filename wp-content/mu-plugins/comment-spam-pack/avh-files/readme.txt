Plugin Name: AVH First Defense Against Spam - WPMU DEV Version
Author: Ulrich Sossou

== Changelog ==
= Version 2.3.2 =
* Bugfix: Commenting didn't work anymore.

= Version 2.3.1 =
* Stop Forum Spam is only checked when a comment is posted. All other checks are still done before content is served. The reason for this change is the amount of traffic this plugin caused at the server of Stop Forum Spam.

= Version 2.3 =
* Better error messages when a problem occurs with a call to Stop Forum Spam.
* Disabled Stop Forum Spam until a better solution is created.

= Version 2.2 =
* Changed initial settings for email to not send E-Mail. This is better for busy sites.
* Option for using a honey pot page by Project Honey Pot.
* Change in IP caching system. Added the field lastseen. This field will be updated if an IP returns which was previously identified as spam. The daily cleaning of the IP cache database will use this field to determine if the record will be deleted.
* Bugfix: Database version was never saved.
* Bugfix: When HTP connection failed, IP was added as no-spam in cache when cache is active.
* Bugfix: Uninstall didn't work.
* Bugfix: Validate admin fields.

= Version 2.1.2 =
* Bugfix: Settings link on plugin page was incorrect.

= Version 2.1.1 =
* Bugfix: Menu Option FAQ threw an error.

= Version 2.1 =
* Added an IP caching system.
* Administrative layout changes.
* Optional email can be send with information about the cron jobs of the plugin.
* Bugfix: The default setting to terminate the connection for Project Honey Pot was unrealistic.

= Version 2.0.1 =
* Bugfix: The function comment_footer_die was undefined.

= Version 2.0 =
* RFC: Optionally check the visitor at Project Honey Pot.
* RFC: Optionally receive error emails for failed calls to Stop Forum Spam. Error mails were always received.
* The plugin has a separate menu page.
* Added very simple statistics.
* Bugfix: Check Trackbacks/Pingbacks for spammers as well.
* Bugfix: Reporting a spammer without an email address failed. Stop Forum Spam changed their policy about reporting spammers without an email.

= Version 1.3 =
* Updated determination of users IP. Now also detects right IP if the server is running Apache with nginx proxy.

= Version 1.2.3 =
* Bugfix: HTTP Error messages didn't work properly
* Refactoring of some of the code.

= Version 1.2.2 =
* Bugfix: Trackback and Pingback comments were blocked as well

= Version 1.2.1 =
* Better implementation for getting the remote IP.

= Version 1.2 =
 * Added security to protect against spammers directly posting comments by accessing wp-comments-post.php.
 * An email can be received of a spammer trying posting directly. The email holds a link to report the spammer at Stop Forum Spam ( an API key is required).
 * The black and white list can now hold ranges besides single IP addresses.
 * Some small improvements and bug fixes.

= Version 1.1 =
* Ability to report a spammer to Stop Forum Spam if you sign up on their website and get an API key (it's free).
* Added a link in the emails to add an IP to the local blacklist.
* Bugfix: Uninstall did not work.
* RFC: A white list was added.

= Version 1.0 =
* Initial version

= Glossary =
* RFC: Request For Change. This indicates a new or improved function requested by one or more users.

2104-1340424462