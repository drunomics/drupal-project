<?php

$env = getenv('PHAPP_ENV');
$env_type = getenv('PHAPP_ENV_TYPE');
$env_mode = getenv('PHAPP_ENV_MODE');
$site_variant = getenv('SITE_VARIANT') ?: 'default';

// A site-specific prefix variable that can be used during configuration.
$site = getenv('SITE');

// Add in shared based settings and optionally environment specific settings.
require __DIR__ . '/../all/base.settings.php';

// Add in per-env-type settings if existing.
if (file_exists(__DIR__ . '/../all/base-' . $env_type . '.settings.php')) {
  require __DIR__ . '/../all/base-' . $env_type . '.settings.php';
}
// Add in per-site-variant settings if existing.
if (file_exists(__DIR__ . '/../all/variant.' . $site_variant . '.settings.php')) {
  require __DIR__ . '/../all/variant.' . $site_variant . '.settings.php';
}
// Add in per-env settings if existing.
if (file_exists(__DIR__ . '/../all/env.' . $env . '.settings.php')) {
  require __DIR__ . '/../all/env.' . $env . '.settings.php';
}
// Add in setting-overrides per environment mode (development or production).
if (file_exists(__DIR__ . '/../'. $env_mode . '.settings.php')) {
  require __DIR__ . '/../'. $env_mode . '.settings.php';
}

# Avoid db deadlocks. see https://www.drupal.org/project/drupal/issues/2833539
# Apply after all settings are required, so it is not overridden.
$databases['default']['default']['init_commands'] = [
  'isolation' => "SET SESSION tx_isolation='READ-COMMITTED'",
  'lock_wait_timeout' => 'SET SESSION innodb_lock_wait_timeout = 20',
  'wait_timeout' => 'SET SESSION wait_timeout = 600',
];
