=== Category Post Info Control plugin ===
Contributors: HusbandHunterDad
Donate link: http://www.pspsoftware.co.za/catpic-buy-me-a-beer/
Tags: category,post info,byline,post meta,author and date
Requires at least: 3.0.0
Tested up to: 3.4.2
Stable tag: trunk

Allows the user to specify whether post info, aka byline or Post meta (author and date etc), from posts in each category is displayed or not. 

== Description ==

Category Post Info Control is a simple plugin that enables you to hide the byline (the line below the title heading where the author and date of the post is displayed) on posts within certain 
categories while still showing the byline in other categories. For example, if you have a portfolio page or product pages, something that's meant to look professional, you probably don't want 
to show the usual "posted by HusbandHunterDad on 05/10/2012". But you do want to show that information on blog pages on the same site. With this plugin you can put the portfolio or product posts into 
one or more categories and mark those categories so that the byline is not displayed for them.

Plugin homepage [Hide WordPress byline](http://www.pspsoftware.co.za/catpic-category-post-info-control/ "PSP Software")

== Installation ==

1. Unzip into the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In WP Admin, edit an existing category and tick the "Hide Post Info Top" checkbox under "Category Post Info Control"

If you're using the Geneis Framework (Studiopress), that's all you need to do. If you view posts within that category now, they should not display the byline.
If you're using any theme other than a Genesis child theme, you need to add the following into your theme's content-single.php page.

4. Follow these instructions to make it work on non-Genesis themes http://www.pspsoftware.co.za/catpic-ad-code-to-theme/

== Changelog ==

= 1.1 =
Workaround for bug in Windows 7 / Chrome where the checkbox was not showing in Chrome.