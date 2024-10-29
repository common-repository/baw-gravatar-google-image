=== Gravatar Google Image ===
Contributors: juliobox
Tags: gravatar, avatar, google, google image, cache, custom, photo
Requires at least: 3.4
Tested up to: 3.4.1
Stable tag: trunk
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RB7646G6NVPWU
License: GPLv2

Add a step between "no gravatar account" and "default gravatar sent back", using Google Images Search Service + 2 bonus usage

== Description ==
This plugin takes place between the gravatar picture and the default avatar return. If an email is not associated with a gravatar account, a Google Image request is sent to grab the first best relevant face picture of this member.

When a google image photo have been chosen, the user will receive a mail with 3 links, 1st to delete this avatar if not relevant enought, 2nd to refresh it, if its email is not associated with a gratavar account, 3rd an invitation to create a gravatar account.

Also, you can add you own avatar by uploading your picture, easily !

Then, you can use a gravatar cache to keep all avatar files on your server and, limit the HTTP request on gravatar.com

Here comes a (french, just watch ;p) demo video, 15mn length. (video = version 1.8 !)
http://www.youtube.com/watch?v=90mBwyNd5zU

== Installation ==

1. Extract the plugin folder from the downloaded ZIP file.
2. Upload BAW Gravatar Google image folder to your /wp-content/plugins/ directory.
3. Activate the plugin from the "Plugins" page in your Dashboard.
4. Go to discussion settings!

== Frequently Asked Questions ==

= I'm a developper, what can i hook from your plugin ? =
Here come the filters list:
1. "get_avatar" : from WP core, included in get_avatar() function, i "overwrite" it, it's a pluggable function.
1. "ggi_get_avatar" : same as above, included in get_avatar() function, same place, but instead of "$id_or_email" var, i put "$email" which is more logical
1. "default_size" : triggers every time we need to get_avatar(), default is 96 (like WP core)
1. "mail_avatar_signature" : Used to change the default email signature "Best regards - Blog_Name"

Here come the actions list:
1. "baw_google_download_img" : Triggers when a picture have to be downloaded for caching
1. "gravatar_from_cache" : Triggers at the end of grabbing the cache file
1. "gravatar_from_google" : Triggers at the end of grabbing the google file

and "get_avatar_comment_types" already and still exists too, from WP core.

== Screenshots ==
1. Discussion options with news
1. Avatars before this plugin
1. Avatars after this plugin!
1. New possible generated avatars (v1.9)
1. Comment moderation with 3 more action links
1. The mail sent to the the user for whom a google photo have been selected

== Upgrade Notice ==
Nothing.

== Changelog ==

= 1.9.3 =
* 31 jul 2012
* Bug fix when someone delete its avatar, an empty one was given.

= 1.9.2 =
* 22 jul 2012
* Bug fix : incorrect char :/
* Bug fix : Faces was not "default" in options discussion page

= 1.9.1 =
* 22 jul 2012
* Some code improvments
* Forgot to remove a comment in .js ;)
* Readme file filled :)

= 1.9 =
* 21 jul 2012
* Add a new default avatar, "tyniaf.php" for "Turn Your Name Into A Face (.com)", this is a face generator based on string.
* Add a filter on "get_avatar" for this generated avatar, it changes "height" into "data-height"
* Add a filter named "default_size" (96px), it's used on the first call of get_avatar(), so this is the height/width of the cached file!
* Add a filter named "ggi_get_avatar", same as core filter named "get_avatar" but in place of "id_or_email", i put "email" 
* Add a filter named "mail_avatar_signature" to change the default signature in the sent email
* Add an action named "baw_google_download_img", triggers when a image have to be downloaded
* Add an action named "gravatar_from_cache", triggers at the end of grabbing the cache file
* Add an action named "gravatar_from_google", triggers at the end of grabbing the google file
* Add feature : You can now empty the cache folder
* Change : Options > 2 radio button blocks now with more choices
* Change : Help improved
* Change : Much better get_avatar() overwrite with better returns and cache
* Change : Much better baw_google_download_img() with beter resize and return + security improvment
* Change : Better install script
* Change : Better uninstall script
* Fix admin_enqueue_scripts => admin_print_styles, my bad
* Info : "get_avatar_comment_types" filter from WP core still exists

= 1.8.1 =
* 20 jul 2012
* Bug fix on some installations, `global $upload_dir;` was a bad idea... ;)

= 1.8 =
* 20 jul 2012
* First release on WP repo
* Code improvment

= 1.6 =
* 19 jul 2012
* Add caching system and help

= 1.4 =
* 18 jul 2012
* Add the email stuff

= 1.2 =
* 17 jul 2012
* Add possibility to upload you own default avatar

= 1.0 =
* 16 jul 2012
* First try
