<?php

namespace drunomics\Composer;

use Composer\Script\Event;
use Composer\Util\Filesystem;

/**
 * Provides a clean command.
 */
class CleanCommand {

  /**
   * Removes all installed vendors.
   *
   * @param \Composer\Script\Event $event
   */
  public static function runCommand(Event $event) {
    $composer = $event->getComposer();
    $io = $event->getIO();

    $repo = $composer->getRepositoryManager()
      ->getLocalRepository();
    /** @var \Composer\Repository\RepositoryInterface $repo */

    $filesystem = new Filesystem();
    foreach ($repo->getPackages() as $package) {
      $path = $composer->getInstallationManager()->getInstallPath($package);
      if (is_dir($path) && !$filesystem->isDirEmpty($path)) {
        $io->write("Cleaning package <fg=green>" . $package->getPrettyName() . '</>');
        $filesystem->remove($path);
      }
    }
    // Remove whole vendor dir.
    // Above process cleans only packages and leads to errors with autoloader.
    // See https://drunomics.myjetbrains.com/youtrack/issue/DEV-2011.
    $vendorDir = $composer->getConfig()->get('vendor-dir');
    if (is_dir($vendorDir) && !$filesystem->isDirEmpty($vendorDir)) {
      $io->write("Removing vendor dir <fg=green>" . $vendorDir . '</>');
      $filesystem->remove($vendorDir);
    }

  }

}
