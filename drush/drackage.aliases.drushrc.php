<?php

$aliases['drackage'] = array(
  'uri' => 'default',
  'remote-host' => 'xen.dk',
  'remote-user' => 'root',
  'path-aliases' => array(
    '%drush' => '/root/drush',
    '%drush-script' => '/root/drush/drush',
  ),
  'uri' => 'drackage.xen.dk',
  'root' => '/var/www/drackage.xen.dk',
  'deployotron' => array(
    'branch' => 'deploy',
    'no-offline' => TRUE,
    'no-dump' => TRUE,
    'no-cc-all' => TRUE,
    'no-updb' => TRUE,
    'message' => "Remember to run drush @drackage cr to rebuild caches.",
  ),
);
