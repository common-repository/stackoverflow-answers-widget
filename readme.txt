===  StackOverflow Answers Widget ===
Contributors: satrun77
Tags: stack overflow, stackoverflow, widget, stackoverflow answers
Requires at least: 2.8
Tested up to: 3.8.1
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=mohamed%2ealsharaf%40gmail%2ecom&lc=NZ&item_name=StackOverflow%20Answers%20WordPress%20Widget&item_number=2&currency_code=NZD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted

Widget to display a summary of answers you have posted in StackOverflow (http://stackoverflow.com/).

== Description ==

Widget to display a summary of answers you have posted in StackOverflow (http://stackoverflow.com/). It will display
the question's title, the answer's score, and the time the answer was last edited.

You can limit the number of questions displaying and sort the list based on:

* Highest score.
* Oldest answer.
* Newest answer.

ps. The widget uses StackOverflow API version 2.2.
    The content of this widget is cached and updated every day.

== Requirement ==

- json_decode must be available.
- PHP5 or higher

== Installation ==

1. Upload plugin folder to the wp-content/plugins/ directory.
2. Go to plugin page in Wordpress, and click "Activate"
3. Go to widgets page in Wordpress and drag StackOverflow Answers Widget to a widget area.
4. Set configuration
    a. Set widget title.
    b. User number can be found in profile URL (http://stackoverflow.com/users/[Your User Number]/[Your Username])
    c. Set number of questions to show.
    d. Set how you want to sort the list.

== Changelog ==
0.2 Use htmlspecialchars for question title
0.3 Fixed incorrect plugin folder name
0.4 Fixed bug in display.php & use h2 tag for the heading
0.5 Multiple widgets in a page can have different StackOverflow account
    Minor bug fixes
0.6 Uses StackOverflow API version 2.2.
0.7 Minor bug fixes
