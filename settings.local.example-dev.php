<?php

/**
* @file
* Local development override configuration feature.
*
* To activate this feature, copy and rename it such that its path plus
* filename is 'sites/default/settings.local.php'. Then, go to the bottom of
* 'sites/default/settings.php' and uncomment the commented lines that mention
* 'settings.local.php'.
*
* If you are using a site name in the path, such as 'sites/example.com', copy
* this file to 'sites/example.com/settings.local.php', and uncomment the lines
* at the bottom of 'sites/example.com/settings.php'.
*/

/** [DEV] Enable local development services. */
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

/** [DEV] Show all error messages */
$config['system.logging']['error_level'] = 'verbose';

/** [DEV] Disable CSS/JS aggregation. */
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

/** [DEV] Disable the render cache (this includes the page cache). */
$settings['cache']['bins']['render'] = 'cache.backend.null';

/** [DEV] Disable Dynamic Page Cache. */
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

/** [DEV] Allow test modules and themes to be installed. */
$settings['extension_discovery_scan_tests'] = TRUE;

/** [DEV] Enable access to rebuild.php.  */
$settings['rebuild_access'] = TRUE;

$databases['default']['default'] = array (
 'database' => 'drupal',
 'username' => 'root',
 'password' => 'root',
 'prefix' => '',
 'host' => 'db',
 'port' => '3306',
 'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
 'driver' => 'mysql',
);

$settings['trusted_host_patterns'] = array(
  'PROJECT_NAME.local',
);

#$conf['reverse_proxy'] = TRUE;
#$conf['reverse_proxy_addresses'] = array(gethostbyname('nginx'));
//if (
//    isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
//    && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
//    && !empty($conf['reverse_proxy'])
//    && in_array($_SERVER['REMOTE_ADDR'], $conf['reverse_proxy_addresses'])) {
//  $_SERVER['HTTPS'] = 'on';
//  // This is hardcoded because there is no header specifying the original port.
//  $_SERVER['SERVER_PORT'] = 443;
//}
