// $Id: README.txt,v 1.1 2009/10/15 18:10:43 ghing Exp $

Summary

The Drupal Menu Site Map module is a plugin for the Site Map module 
(http://www.drupal.org/project/site_map) that provides greater configuration and
theming capabilities over the default handling of menus by the Site Map module.

In particular: 

* Specify the order of menus included in the site map output.
* Exclude menu items from the site map output.
* Specify the depth of menu items that will be shown in the site map.
* Theme the output of the menu in the site map.

This module was originally written to create the site map for the Center for 
Research Libraries website, http://www.crl.edu/.  

Requirements

* Drupal 6
* Menu Site Map module

Installation

Install as usual, see http://drupal.org/node/70151 for further information.

Configuration

You will probably want to disable the default Menu Site Map module's menu output 
by going to Administer >> Site Configuration >> Site map and deselecting all 
menus from the "Menus to include in the site map" lists. 

Then configure the Menu Site Map module at Administer >> Site Configuration >> 
Site map >> Menu site map

You can select which menus to include and use the arrow icons to drag the menu
names up or down to set the order that they will be included in the site map.

You can set the "Display Style" setting to determine how you want the output of
your menus to be formatted in the site map.  The default style is to output the 
top two menu levels as headings and subsequent levels as unordered lists.  You
can select the "Nested Lists" option to display the menu as only unordered lists
(similar to the output returned by the menu_tree()) function and the default 
output of the Site map module.  Finally, you can select "Theme override" which 
will output a reminder message until you override this module's theme functions
in your theme's template.php file.  Note that implementing the theme functions
in your theme will override the output of this module, regardless of which 
option is selected for the "Display Style" setting.

You can specify the maximum depth of the selected menus that will be included in
the site map using the "Menu depth" setting.

Finally, enabling the "Move second-level items without entries to the end" setting
will cause all second-level menu items without children to be displayed at the 
end of the menu branch's output rather than in it's place as specified by the 
menu.  This setting was added because the menu output of the site for which this 
module was originally developed just looked better with the childless second-level
menu items moved in this way.

Contact

Current maintainers:
* Geoffrey Hing (ghing) - http://drupal.org/user/47168

