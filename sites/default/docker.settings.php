<?php

$databases = array();
$config_directories = array();
$settings['hash_salt'] = file_get_contents('/etc/drupal-hash-salt');

$settings['update_free_access'] = FALSE;

ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.gc_maxlifetime', 200000);
ini_set('session.cookie_lifetime', 2000000);

$databases['default']['default'] = array (
  'database' => 'drupal',
  'username' => 'drupal',
  'password' => 'drupal',
  'prefix' => '',
  'host' => 'database',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['install_profile'] = 'standard';

$config_directories['active'] = 'sites/default/files/config_C2f4CGluQEhBR5-gQuNVfuBv8EUlf8bgF29oD3wrhnCrP0YfSY5PrlQDqwCqsqrAfpwshQP06g/active';

$config_directories['staging'] = 'sites/default/files/config_C2f4CGluQEhBR5-gQuNVfuBv8EUlf8bgF29oD3wrhnCrP0YfSY5PrlQDqwCqsqrAfpwshQP06g/staging';
