<?php
/**
 * Copyright (C) 2014 Questionmark Computing Limited.
 * License GNU GPL version 2 or later (see LICENSE.TXT file)
 * There is NO WARRANTY, to the extent permitted by law.
 */

require_once('PHPCoreWrapper.inc');
require_once('SigningTask.inc');
require_once('SiteSettings.inc');

class CoreFactory {

  /**
   * Get a Signed Request Data Generator object
   *
   * @return \Questionmark\qm_core\SignedRequestDataGenerator
   */
  public static function getSignedRequestDataGenerator() {
    return new SignedRequestDataGenerator(
      self::getPHPCoreWrapper(),
      self::getSigningTask(),
      self::getSiteSettings());
  }

}

class SignedRequestDataGenerator {
  private $phpWrapper;
  private $signingTask;
  private $siteSettings;

  /**
   * @param Wrappers\PHPCoreWrapperInterface $phpWrapper
   * @param Cryptography\SigningTaskInterface $signingTask
   * @param SiteSettingsInterface $siteSettings
   */
  public function __construct(
      PHPCoreWrapper $phpWrapper,
      SigningTask $signingTask,
      SiteSettings $siteSettings) {
    $this->phpWrapper = $phpWrapper;
    $this->signingTask = $signingTask;
    $this->siteSettings = $siteSettings;
  }

  /**
   * Get a PHP Core Wrapper object
   *
   * @return \Questionmark\qm_core\Wrappers\PHPCoreWrapperInterface
   */
  public static function getPHPCoreWrapper() {
    return new PHPCoreWrapper();
  }

  /**
   * Get a Signing Task object
   *
   * @return \Questionmark\qm_core\Cryptography\SigningTaskInterface
   */
  public static function getSigningTask() {
    return new SigningTask();
  }

  /**
   * Get a Site Settings object
   *
   * @return \Questionmark\qm_core\SiteSettingsInterface
   */
  public static function getSiteSettings() {
    return new SiteSettings();
  }

}
