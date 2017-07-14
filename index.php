<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. It is the starting point of the plugin
 *
 * @link:             http://www.islamcompanion.org
 * @since             1.0.0
 * @package           IslamCompanion
 * @wordpress-plugin
 * Plugin Name:       Islam Companion
 * Plugin URI:        http://www.islamcompanion.org
 * Description:       The goal of this plugin is to make it easier to integrate Islam in your every day life
 * Version:           3.0.6
 * Author:            Pak Jiddat
 * Author URI:        http://www.islamcompanion.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       islam-companion
 * Domain Path:       /languages
*/
namespace Framework;
/** The autoload.php file is included */
require("autoload.php");
/** The application parameters */
$parameters = (isset($argc))?$argv:$_REQUEST;
/** The application context is determined */
$context    = (isset($parameters['context'])) ? $parameters['context'] : ((isset($argc))?"command line":"browser");
/** The application request is handled */
$output     = \Framework\Frameworks\WordPress\Application::HandleRequest($context, $parameters, "IslamCompanion");
/** If the output is not suppressed then the application output is echoed back */
if (!defined("NO_OUTPUT"))echo $output;
