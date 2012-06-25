=== HTML Emails ===
Contributors: batmoo
Donate link: http://digitalize.ca/donate
Tags: email, notification, html, html emails, html email
Requires at least: 2.9
Tested up to: 3.0
Stable tag: 1.0

Converts the default plain text email notifications into fully customizable, sweet-lookin' HTML emails.

== Description ==

Converts the default plain-text email notifications into fully customizable, sweet-lookin' HTML emails. 

Multi-site support (for WordPress 3.0) will be added soon.

**Notes:**

* Emails are sent with readable plain-text versions for email clients that don't support HTML emails. Note: they're somewhat crude because they're auto-generated. I may decide to add decent-looking plain-text versions in the future, but that's low priority.
* Emails have only been tested on Gmail, Gmail on Android, and Outlook, but should work on most email clients (including clients without HTML support). If you're using a client other than the 3 I've listed, I would appreciate [an email](mailto:batmoo@gmail.com) with info on whether the email looks like it should and works correctly.

Send your questions, comments, suggestions [via email](mailto:batmoo@gmail.com).

== Upgrade Notice ==

Nothing to see here.

== Frequently Asked Questions ==

When I get some Frequently Asked Questions, I'll include them here.

== Customizing Templates ==

You can fully customize the look of any of the emails by creating your own templates. Emails have two pieces:

* Email Wrapper (content common across all emails)
* Email Message (content unique to each email)

Custom templates can be created an placed in either your theme directory or your Content directory (/wp-content/ on most sites). See utils.php for a number of useful functions that you can use in your templates.

**Email Wrapper**

The Email Wrapper includes elements and markup that are common across all email notifications. It contains your html, head and body tags and any other elements that are shared across emails.

To customize the email template, create a file called `html_email.php` and add it to either your theme or content directory.

Note: you must include the following code in the template to work:

`<?php htmlize_message_body($email_templates, $email_data); ?>`

The Email Wrapper has access to the following variables:

* $email_title: (string) title of the email
* $email_subtitle: (string) subtitle of the email
* $email_data: (array) Associative array of various data passed by the calling function

**Email Message: New Comment**

By default, new comment and comment moderation emails share the same template. You can create separate templates for both for each comment type by creating the following files:

* `notify_postauthor_comment.php`
* `notify_postauthor_trackback.php`
* `notify_postauthor_pingback.php`
* `notify_moderator_comment.php`
* `notify_moderator_trackback.php`
* `notify_moderator_pingback.php`

If you don't want to customize the emails by comment type, just create the following two templates:

* `notify_postauthor.php`
* `notify_moderator.php`

If you just want a custom template shared between the two notification types, just create the following:

* `notify_comment.php`

New Comment email messages have the following data variables available:

* $comment 				- (obj) comment object
* $post 				- (obj) post object 
* $comment_type 		- (string) slug for the comment_type
* $comment_type_text 	- (string) friendly name for the comment_type
* $comment_moderate 	- (bool) Whether the comment needs moderation or not

== Using HTML Emails in your plugin ==

It's pretty easy to use. The main call you need to make is htmlize_message() and pass the return into your wp_mail object. Details to come.

== Credits ==

* Blockquote image borrowed from the amazing [Wu Wei theme by Jeff Ngan](http://wordpress.org/extend/themes/wu-wei)
* Plain Text conversion script by [Jon Abernathy aka Chuggnutt](http://www.chuggnutt.com/html2text.php)
* Email styling inspired by [Wordpress.com Blog Subscription Notifications](http://en.support.wordpress.com/blog-subscriptions/)

== Installation ==

1. Upload and extract the plugin to plugins directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Enjoy sweet lookin' email notifications!

== Screenshots ==

1. New Comment email as viewed in Gmail
2. New User email as viewed in Gmail
3. Email as viewed in Mail.app

== Changelog ==

= 1.0 (2010-05-05) =

* Note: This version was originally released as v0.2 but users weren't being prompted with an upgrade because parsefloat is dumb.
* Feature: Support for "New User" and "Password Lost" emails
* Bug: Moderation emai not showing the correct number of emails pending
* Localization: Belarusian translation added. Thanks to [Marcis G](http://pc.de/).

= 0.2 (2010-05-04) =

* Same as 1.0

= 0.11 (2010-04-09) =

* Better localization support
* Bug: Moderation emails were not being sent because the wp_mail call was commented out
* Docs: Localization comments and addtional details on customizing templates

= 0.1 (2010-04-04) =
* First release
* Comment notification emails
