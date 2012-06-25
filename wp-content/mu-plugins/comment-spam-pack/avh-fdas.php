<?php
/*
Plugin Name: AVH First Defense Against Spam - WPMU DEV Version
Plugin URI: http://premium.wpmudev.org/
Description: This plugin gives you the ability to block spammers before content is served.
Version: 2.0.2
Author: Ulrich Sossou
Author URI: http://ulrichsossou.com/
Site Wide Only: true
Network: true
Text Domain: avh-fdas

Based on AVH First Defense Against Spam by Peter van der Does  (email : peter@avirtualhome.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Define Message Numbers
define( 'AVHFDAS_REPORTED_DELETED', '100' );
define( 'AVHFDAS_ADDED_BLACKLIST', '101' );
define( 'AVHFDAS_REPORTED', '102' );
define( 'AVHFDAS_ERROR_INVALID_REQUEST', '200' );
define( 'AVHFDAS_ERROR_NOT_REPORTED', '201' );
define( 'AVHFDAS_ERROR_EXISTS_IN_BLACKLIST', '202' );

require_once( dirname( __FILE__ ) . '/avh-files/avh-fdas.client.php' );
