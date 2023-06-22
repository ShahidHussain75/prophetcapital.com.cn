=== Plugins Language Switcher ===
Contributors: shinephp
Donate link: http://www.shinephp.com/donate/
Tags: language, changer, switcher
Requires at least: 3.0
Tested up to: 4.0.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

It changes WordPress back-end language according to user selection. 

== Description ==

Plugins Language Switcher allows to use different language (from WordPress default one) for WordPress back-end interface. It is useful for example if your blog front-end configured as Spanish but you or your editors and authors wish to work at WordPress back-end with other, possibly native language, e.g. English. Another variant of this plugin usage is other plugins translation testing without changing of the whole blog language WPLANG constant value.
This plugin shows in the language selection drop down list all languages which you have in blog wp-content/languages and wp-content/plugins directories.
It is possible to setup different back-end languages for different users. Plugin stores selected language value locally on user computer using cookies. 
To read more about "Plugins Language Switcher" visit this link at <a href="http://www.shinephp.com/plugins-language-switcher-wordpress-plugin/" rel="nofollow">shinephp.com</a>


== Installation ==

Installation procedure:

Attention! Starting from version 1.1 plugin works with WordPress 3.0 and higher only. For earlier WordPress versions use plugin version 1.0.2. from http://downloads.wordpress.org/plugin/plugins-language-switcher.1.0.2.zip
1. Deactivate plugin if you have the previous version installed.
2. Extract "plugins-language-switcher.x.x.x.zip" archive content to the "/wp-content/plugins/plugins-language-switcher" directory.
3. Activate "Plugins Language Switcher" plugin via 'Plugins' menu in WordPress admin menu.
4. Go to the "Tools"-"Plugins Language Switcher" menu item and select the language you wish to use with plugins at admin.

== Frequently Asked Questions ==
* How to add new language to your plugin?
* If you have in your /wp-content/plugins/ directory translation file for language you search way to add to Plugins Language Switcher drop down list, Plugins Language Switcher will show that language in the language selection drop down list automatically.
* Place the whole WordPress back-end translation additional language .mo file to wp-content/languages to get full translation of administrator back-end to needed language. Plugins settings pages could be translated if they have its own translation files.
You can find WordPress translation file for your language at the translation repository at http://svn.automattic.com/wordpress-i18n/

== Screenshots ==
1. screenshot-1.png Plugins Language Switcher Settings page. Simple and useful.


== Special Thanks to ==
You are welcome! Help me in the bug hunting, share with me new ideas about plugin further development, contribute to the source code and link to your site will appear here.

= Translations =
* Chinese: [Vincent Tay](http://www.seoservicessingapore.net/)
* Dutch: [Rene](http://wpwebshop.com)
* French: [Olivier](http://www.office-web.net/)
* Georgian: [George Popkhadze](http://www.popkha.com)
* German: [Christian](http://www.irc-junkie.org)
* Hindi: [Outshine Solutions](http://outshinesolutions.com/)
* Italian: [Maurizio](http://http://www.myfox.org) [Alessandro Mariani](http://technodin.org)
* Lithuanian: [Vincent G](http://host1free.com)
* Russian: [Vladimir Garagulya](http://shinephp.com)
* Serbian: [Web Hosting Hub](http://www.webhostinghub.com/)
* Slovenian: [Peter Kordiš](http://perro.iprom.si)
* Spanish: [David](http://www.verasoul.com)

Dear plugin User!
If you wish to help me with this plugin translation I very appreciate it. Please send your language .po file to vladimir[at-sign]shinephp.com email. Do not forget include you site link in order I can show it with greetings for the translation help at shinephp.com, plugin settings page and in this readme.txt file.
If you have better translation for some phrases or found not translated phrases yet at the already made translation files, send your translation to me and it will be taken into consideration. You are welcome!

== Changelog ==

= 1.4.4 =
* 24.12.2013
* Plugin renews automatically the cookie with selected language value. This should prevent language selection loss after the cookie expiration.
* Serbian translation was added. Thanks to [Web Hosting Hub](http://www.webhostinghub.com/).

= 1.4.3 =
* 12.12.2013
* French translation was added. Thanks to [Olivier](http://www.office-web.net/).
* WordPress compatibility information was updated to 3.8.

= 1.4.2 =
* 05.02.2013
* Settings link is fixed under plugin row at the installed plugins list from Plugins menu.

= 1.4.1 =
* 15.07.2012
* Hindi translation is added, thanks to Love Chandel.

= 1.4 =
* 22.05.2012
- I extended plugin's users base. While administrators only used this plugin earlier, any registered user with 'edit_posts' capability can use "Plugins language switcher" now. Starting from version 1.4, look for "Plugins language switcher" menu item under "Tools" submenu.

= 1.3.2 =
* 07.04.2012
- Lithuanian translation is added, thanks to Vincent G.

= 1.3.1 =
* 23.05.2011
* Files with just the language code in the file name, e.g. ru_RU.mo are detected correctly.
* Plugins Language Switcher itself uses selected language for its interface.

= 1.3 =
* 22.05.2011
* Plugin uses cookie to store selected language locally at the user computer. This way different administrator may use different languages.
* Chinese translation is added.
* shinephp.com RSS feed is removed from the plugin settings page.

Full list of changes is available at http://www.shinephp.com/plugins-languge-switcher-wordpress-plugin/


== Additional Documentation ==

You can find more information about "Plugins Language Switcher" plugin at this page
http://www.shinephp.com/plugins-languge-switcher-wordpress-plugin/

I am ready to answer on your questions about this plugin usage. Use ShinePHP forum at
http://shinephp.com/community/forum/plugins-languger-switcher/
or plugin page comments and site contact form for it please.
