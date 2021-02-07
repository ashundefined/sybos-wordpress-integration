# Sybos WordPress Plugin
The Sybos WordPress integration plugin allows for easy integration of the [SyBOS-API](https://www.sybos.net/)
in WordPress. Several endpoints are provided in WordPress as shortcodes.
Sybos is a web-based management application for fire departments widely deployed and
actively used in Austria. This plugin is meant to make implementation of the SyBOS API with
WordPress a straightforward process.

**Note**: This plugin is only of use to you if you/your fire department use
SyBOS for their management. It requires an API Key, which can be generated
from within SyBOS. Consult the help menu if you do not know how to do that.
## How to install
### From Wordpress
This plugin (as of now) can not be installed from the official plugin directory.
### From this repository
1. Clone this repository
1. Create a Subfolder called `syin` in the `wp-plugins` folder of your WordPress installation.
1. Move all files and folders to the `syin` folder.
For example, the path to `index.php` of this plugin should look like: `/wp-content/wp-plugins/syin/index.php`
1. Activate plugin in WordPress dashboard
1. Proceed with [configuration](#configuration).

### Configuration
1. In your WordPress dashboard a new entry called `Sybos integration` will be visible. Click on it.
1. Enter the base-url and api-key you generated in SyBOS in their respective fields. If you do not know how to do this,
   please consult the SyBOS manual.
1. Save.

## How to use
This plugin uses [WordPress' shortcodes](https://wordpress.com/support/shortcodes/) to provide data retrieved from the API to the respective
pages. Note that this plugin is still very much work in progress and new shortcodes will be added progressively.
As of now, the following shortcodes are available:
### sybos-operations
Usage example: ``[sybos-operations][/sybos-operations]``

Retrieves **all** operationes ("Einsätze") currently available in the SyBOS instance in question
and prints them to the page as formatted HTML. Styling can be achieved by editing `public/css/syin-public.css`.

Usage example: ``[sybos-operations year=2020][/sybos-operations]``

Retrieves **all** operationes ("Einsätze") currently available in the SyBOS instance in question **between 2020-12-31 23:59 and 2020-01-01 00:00**
and prints them to the page as formatted HTML. Styling can be achieved by editing `public/css/syin-public.css`. Year can
of course be changed accordingly.

Usage example: ``[sybos-operations department=City][/sybos-operations]``

**Note**: As of February 2021, Sybos does not
 offer the ability to only fetch operations for a specific fire department.
 Instead, the ``/Einsatz.php`` endpoint returns *all* operations available in the
 sysbos instance queried.
 In order to get operations for a specific department, the following (very ugly, hacky and sh***y) workaround
 is available: Set the ``department`` attribute of the shortcode to a department location
of your choice. Now only the specified department will be shown.

## Notes

## Todos
[ ] add human resources endpoint