=== Islam Companion ===
Contributors: nadirlatif
Tags: islam,quran,sunnat,hadith,religion
Requires at least: 3.0
Tested up to: 4.7.4
Stable tag: 3.0.6
License: GPLV2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The goal of the Islam Companion plugin is to help users understand Islam

== Description ==

The Islam Companion plugin has following features:

1. The plugin provides Holy Quran translation in following languages: Amharic, Arabic, Bosnian, Bengali, Bulgarian, Amazigh, Czech, German, Divehi, Spanish, English, Persian, French, Hindi, Hausa, Indonesian, Italian, Japanese, Korean, Kurdish, Malayalam, Malay, Dutch, Norwegian, Portuguese, Polish, Russian, Romanian, Swedish, Somali, Sindhi, Albanian, Swahili, Turkish, Tajik, Tamil, Tatar, Thai, Uzbek, Urdu, Uyghur and Chinese.
2. It provides a dashboard widget that displays Holy Quran Navigator. It also allows listening to Holy Quran verses in Arabic and Urdu.
3. It provides a dashboard widget that displays Hadith Navigator. It allows reading hadith in English language from following Hadith collection: Sahih Muslim, Sahih Bukhari, Abu Dawud, Authentic Supplications of the Prophet, Hadith Qudsi, An Nawawi's Fourty Hadiths, Maliks Muwatta and Shamaa-il Tirmidhi.
4. It provides a settings page from where the user can set the language, translator and division for Holy Quran navigator. The settings page also allows importing Holy Quran and Hadith data to Wordpress.
5. It allows organizing Holy Quran reading by the following divisions: Sura, Hizb, Juz, Page and Manzil.
6. It allows searching Holy Quran and Hadith text. It also allows looking up the meaning of a word using an online dictionary
8. It provides shortcodes for embedding Holy Quran and Hadith text
9. It provides shortcodes for embedding Holy Quran and Hadith navigators on the website frontend
10. It provides sidebar widgets that display Holy Quran and Hadith text

Plugin information:

1. Quranic Translation provided by: http://tanzil.net
2. Quranic Audio provided by: http://www.quranurdu.com/
3. Hadith Data provided by: http://hadithcollection.com/
4. Urdu fonts provided by: http://www.cle.org.pk/software/localization/Fonts/nafeesWebNaskh.html
5. Arabic fonts provided by: http://www.amirifont.org/
5. Learn Arabic online: http://www.madinaharabic.com/Arabic_Reading_Course/Lessons/L000_001.html
6. Discuss Islam on Facebook: https://www.facebook.com/Holyquran.Islam786/
7. Visit the plugin author's website: http://www.pakjiddat.pk/islam-companion
8. Support the development of the plugin by making a donation. Please contact nadir@pakjiddat.pk for more information

== Installation ==
Login to your wordpress blog and go to Plugins then Add New and then search for Islam Companion and then click Install

== Frequently Asked Questions ==
1. What does this plugin do. The plugin allows reading and searching Holy Quran and Hadith. It also allows listening to Holy Quran audio in Arabic and Urdu

2. Where does the plugin get its data. The plugin uses Holy Quran translations from http://tanzil.net/trans/ and Holy Quran audio from http://www.quranurdu.com/. It uses Hadith data from http://hadithcollection.com/

3. How does the dictionary option work. On the Holy Quran and Hadith navigator widgets, you have to move your mouse over a word for about 3 seconds. The word will change color to green. Then click on the word. This will open a link to an online dictionary in a new browser tab. The online dictionary will show the meaning of the word.

4. How does the data import option work. The data import option allows importing Holy Quran and Hadith data to the Wordpress. The plugin will not work until the data has been imported. We can import either Holy Quran data or Hadith data or both Holy Quran and Hadith data. The data import option can take a few hours to complete.

5. How to use shortcodes in pages and posts. To add Holy Quran and Hadith to your pages and posts, you can use shortcodes. To get the text of the shortcode, you have to click on the small yellow start at the bottom of a verse or hadith. This will copy the shortcode text to clipboard. You can then paste this text into your wordpress post or page. You can change the sura number, start ayat, end ayat and other options.

6. How to display Holy Quran and Hadith navigators on your website frontend. To display Holy Quran navigator use following shortcode: [get-holy-quran-navigator narrator="Abul A'ala Maududi" language="Urdu" css_classes="your-css-class1 your-css-class2"]. Change narrator and language to your choice. Possible values for narrator and language is same as the values on settings page of the plugin. To display Hadith navigator widget use following shortcode: [get-hadith-navigator css_classes="your-css-class1 your-css-class2"].

7. How to use sidebar widgets. The plugin provides two widgets that display Holy Quran verses and Hadith text. To add a widget to your widget area, you have to go to appearance then widgets and then drag and drop the widget to the widget area. Then you have to configure the widget settings. The Holy Quran widget has following settings: Title (The widget title), Narrator (the translator name), Language (the translation language), Ayas (a list of suras and ayas of the form: sura1:ayat-number1, sura2:ayat-number2), Transformation (it has two options. random and slideshow. random changes the text after one week. slideshow changes the text after 3 seconds). Container (it allows displaying the text as a single paragraph or as a list), Css Classes (a list of css classes separated by space). The Hadith widget has following settings: Title (The widget title), Hadith Ids (The comma separated list of Hadith ids).

8. How to search the Holy Quran and Hadith text. Click on the big plus button. This will open a search box. Enter the search terms and press enter or click on the search button. You should then see the search results.

== Changelog ==

= 3.0.6 =
* Corrected minor errors

= 3.0.5 =
* Corrected minor errors

= 3.0.4 =
* Updated url of audio files. Corrected error in changing plugin from development mode to production mode

= 3.0.3 =
* Added option to Holy Quran sidebar widget for setting text transformation. Added two transformation options. random and slideshow

= 3.0.2 =
* Updated plugin screenshots

= 3.0.1 =
* Corrected minor bugs

= 3.0.0 =
* Removed option for fetching Holy Quran and Hadith data from remote source. Improved layout of frontend navigator widgets. Corrected minor bugs. Improved plugin performance

= 2.4.8 =
* Corrected compatibility problem with WooCommerce. Removed the hidden css class which was causing problems with WooCommerce

= 2.4.7 =
* Corrected error in displaying loading image

= 2.4.6 =
* Corrected errors in clicking on dashboard widget icons

= 2.4.5 =
* Corrected minor errors in plugin. Updated plugin so it uses new Islam Companion API url

= 2.4.4 =
* Updated plugin so it uses new Islam Companion API url

= 2.4.3 =
* Updated plugin so it works with latest version of Islam Companion API

= 2.4.2 =
* Corrected syntax error in Api.php file

= 2.4.1 =
* Corrected error in displaying overlay div on navigator widgets
* Added width and style parameters to Holy Quran and Hadith shortcodes
* Added caching to Islam Companion API

= 2.4.0 =
* Added shortcode for displaying Holy Quran navigator widget on the website frontend
* Added shortcode for displaying Hadith navigator widget on the website frontend
* Added button to Holy Quran and Hadith navigators for scrolling to the top

= 2.3.3 =
* Corrected error in selecting sura from sura dropdown
* Corrected error in displaying search results
* Updated plugin so it works with php 7

= 2.3.2 =
* Updated plugin author contact information
* Updated information about external scripts used by plugin

= 2.3.1 =
* Updated api server url
* Corrected audio player bug in internet explorer

= 2.3.0 =
* Added search feature to Holy Quran dashboard widget
* Added search feature to Hadith dashboard widget
* Added copy to clipboard and copy shortcode buttons to Holy Quran and Hadith dashboard widgets
* Added mouse over dictionary feature to Holy Quran and Hadith dashboard widgets

= 2.2.2 =
* Updated islamcompanion api url to new server url

= 2.2.1 =
* Corrected text formatting error in Hadith Dashboard Widget

= 2.2.0 =
* Added Hadith Navigator widget
* Added Shortcodes for Hadith
* Added Frontend widget for Hadith
* Corrected error in auto populating narrator dropdown on widgets admin page

= 2.1.4 =
* Corrected layout errors on Firefox browser
* Updated url of audio files so it points to audio files hosted on pakjiddat.pk website
* Updated font file for Urdu and Arabic text

= 2.1.3 =
* Corrected minor bugs

= 2.1.2 =
* Corrected minor bugs in settings page

= 2.1.1 =
* Added dropdown fields to Holy Quran widget
* Corrected minor bugs

= 2.1.0 =
* Added shortcode for displaying Holy Quran verses and audio on pages and posts
* Added widget for displaying Holy Quran verses and audio on widget areas
* Added an icon to Holy Quran navigator for copying shortcode text

= 2.0.2 =
* Corrected error in audio player

= 2.0.1 =
* Corrected error in data import

= 2.0.0 =
* Moved the Islam Companion plugin to Pak Php framework
* Added option for importing all plugin data to local wordpress database
* Updated layout of the Holy Quran dashboard widget so it shows verses in both Arabic and the Translated language
* Updated layout of the dropdown option on the Holy Quran dashboard widget 

= 1.2.3 =
* Corrected minor bugs
* Added error handling to API functions
* Simplified API functions

= 1.2.2 =
* Corrected session_start error

= 1.2.1 =
* Corrected html closing tag error
* Corrected error in saving settings from Dashboard widget

= 1.2.0 =
* Added options for navigating the Holy Quran from the Dashboard widget
* Removed some of the navigation options from the settings page
* Updated the remote API
* Changed verse number to decimal format for all left to right languages that do not have browser support for numbering
* Added remote API to version control

= 1.1.0 =
* Added option for selecting Holy Quran divisions on the settings page
* Updated the Holy Quran Dashboard widget layout
* Updated remote API to object oriented class based format
* Unit tested the remote API

= 1.0.8 =
* Update api server url

= 1.0.7 =
* Update online dictionary icon so it gets the dictionary link from database
* Removed option for entering online dictionary url

= 1.0.6 =
* Added option for searching for a word using an online dictionary
* Added option to the settings page for setting online dictionary url

= 1.0.5 =
* Replaced option for selecting ayat with option for selecting ruku
* Added audio player for listening to Quranic Verses
* Added multi user and multi site support. It allows each user to have his own plugin settings
* Added internationalization and localization to the plugin. The plugin text is displayed in the users own language. Currently the plugin only contains translations in Urdu language. Translations in other languages can easily be created
* Added css classes for displaying verses with bullet numbering in the language of the user. If the user language is not supported then a default numbered bullet is displayed
* Uploaded media files to content delivery network
* Secured the plugin code by adding try/catch statements, exception throwing, exception handling and error logging
* Removed addslashes function on line 246 in file class-islam-companion-settings.php
* Corrected PayPal donation link
* Updated plugin description
* Renamed "Message of the day" feature to "Holy Quran Dashboard Widget" 

= 1.0.4 =
* Corrected layout bug in admin dashboard widget 
* Added default settings for plugin

= 1.0.3 =
* Added next and previous links to admin dashboard widget. the user can browser Quranic verses using these links
* Added meta information of the Quranic verses to the admin dashboard widget

= 1.0.2 =
* Added option under settings for saving the surah, verse and verse count
* Updated dashboard widget so it displays Quranic verses according to the settings

= 1.0.1 =
* Moved message for the day text to admin dashboard widget
* Corrected bugs in displaying message for the day

= 1.0.0 =
* Added option for configuring language
* Added function that displays Quranic verse at top of the admin page

== Upgrade Notice ==

= 3.0.6 =
* Corrected minor errors

= 3.0.5 =
* Corrected errors in AyatFormatter.php file

= 3.0.4 =
* Updated url of audio files. Corrected minor errors

= 3.0.3 =
* Added option to Holy Quran sidebar widget for setting text transformation. Added two transformation options. random and slideshow

= 3.0.2 =
* Updated plugin screenshots

= 3.0.1 =
* Corrected minor bugs

= 3.0.0 =
* Removed option for fetching Holy Quran and Hadith data from remote source. Data must be imported to Wordpress first. Improved plugin performance

= 2.4.8 =
* Corrected compatibility problem with WooCommerce

= 2.4.7 =
* Corrected minor bugs

= 2.4.6 =
* Corrected errors in clicking on dashboard widget icons

= 2.4.5 =
* Corrected minor errors in plugin. Updated plugin so it uses new Islam Companion API url

= 2.4.4 =
* Updated plugin so it uses new Islam Companion API url

= 2.4.3 =
* Updated plugin so it works with lastest version of Islam Companion API

= 2.4.2 =
* Corrected minor bug in source code

= 2.4.1 =
* Corrected error in displaying overlay div on navigator widgets
* Added width and style parameters to Holy Quran and Hadith shortcodes
* Added caching to Islam Companion API

= 2.4.0 =
* Added shortcode for displaying Holy Quran navigator widget on the website frontend
* Added shortcode for displaying Hadith navigator widget on the website frontend
* Added button to Holy Quran and Hadith navigators for scrolling to the top

= 2.3.3 =
* Corrected error in selecting sura from sura dropdown
* Corrected error in displaying search results
* Updated plugin so it works with php 7

= 2.3.2 =
* Updated plugin author contact information
* Updated information about external scripts used by plugin

= 2.3.1 =
* Updated api server url
* Corrected audio player bug in internet explorer

= 2.3.0 =
* Added search feature to Holy Quran dashboard widget
* Added search feature to Hadith dashboard widget
* Added copy to clipboard and copy shortcode buttons to Holy Quran and Hadith dashboard widgets
* Added mouse over dictionary feature to Holy Quran and Hadith dashboard widgets

= 2.2.2 =
* Updated islamcompanion api url to new server url

= 2.2.1 =
* Corrected text formatting error in Hadith Dashboard Widget

= 2.2.0 =
* Added Hadith Navigator widget
* Added Shortcodes for Hadith
* Added Frontend widget for Hadith
* Corrected error in auto populating narrator dropdown on widgets admin page
* Corrected layout error in displaying widget on frontend of wordpress twentysixteen theme

= 2.1.4 =
* Corrected layout errors on Firefox browser

= 2.1.3 =
* Corrected minor bugs

= 2.1.2 =
* Corrected minor bugs in settings page

= 2.1.1 =
* Added dropdown fields to Holy Quran widget
* Corrected minor bugs

= 2.1.0 =
* Added shortcode for displaying Holy Quran verses and audio on pages and posts
* Added widget for displaying Holy Quran verses and audio on widget areas
* Added an icon to Holy Quran navigator for copying shortcode text

= 2.0.2 =
* Corrected error in audio player

= 2.0.1 =
* Corrected error in data import

= 2.0.0 =
* Added option for importing all plugin data to local wordpress database
* Updated layout of the Holy Quran dashboard widget so it shows verses in both Arabic and the Translated language

= 1.2.3 =
* Corrected minor bugs
* Organized source code

= 1.2.2 =
* Corrected minor bugs

= 1.2.1 =
* Corrected minor bugs

= 1.2.0 =
* Added options for navigating the Holy Quran from the Dashboard widget
* Removed some of the navigation options from the settings page
* Updated the remote API
* Changed verse number to decimal format for all left to right languages that do not have browser support for numbering

= 1.1.0 =
* Added option for selecting Holy Quran divisions
* Updated the Holy Quran Dashboard widget layout

= 1.0.8 =
* Update api server url

= 1.0.7 =
* Update online dictionary icon so it gets the dictionary link from database
* Removed option for entering online dictionary url

= 1.0.6 =
* Added option for searching for a word using an online dictionary
* Added option to the settings page for setting online dictionary url

= 1.0.5 =
* Added audio player for listening to Quranic Verses in Arabic and Urdu languages
* Updated settings page and replaced option for selecting ayat with option for selecting ruku
* Added multi user and multi site support. It allows each user to have his own plugin settings
* Added internationalization and localization to the plugin. The plugin text is displayed in the users own language. Currently the plugin only contains translations in Urdu language. Translations in other languages can easily be created
* Secured the plugin code by adding error handling and logging

= 1.0.4 =
* Corrected layout bug in admin dashboard widget 
* Added default settings for plugin

= 1.0.3 =
* Updated Holy Quran dashboard widget so it allows user to browse the Quranic verses using next,prev links
* Add verse information to the Holy Quran dashboard widget

= 1.0.2 =
* Updated Holy Quran dashboard widget so it displays the verses according to the configured settings
* Added option under settings for saving the surah, verse and verse count

= 1.0.1 =
* Message for the day text is now displayed in admin dashboard widget
* Corrected bugs in displaying message for the day

== Screenshots ==

1. This screenshot shows how the plugin displays verses from the Holy Quran and Hadith on the admin dashboard. The user can browse the verses using navigation links. The audio player recites the Holy Quran verses in Arabic and Urdu. The star button allows copying shortcode. The copy button allows copying the Verse or Hadith text.
2. This screenshot shows how to change the settings for the plugin. The language, narrator and division can be configured from here
3. This screenshot shows the shortcode text and the widget text on the website frontend
4. This screenshot shows the configuration page for the widgets
5. This screenshot shows the hadith navigator on the website frontend
6. This screenshot shows the holy quran navigator on the website frontend
