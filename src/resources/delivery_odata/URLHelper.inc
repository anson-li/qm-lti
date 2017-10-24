<?php
/**
 * Copyright (C) 2014 Questionmark Computing Limited.
 * License GNU GPL version 2 or later (see LICENSE.TXT file)
 * There is NO WARRANTY, to the extent permitted by law.
 */
/**
 * @file
 * Class for URL related helper methods.
 *
 * @author Questionmark
 */

class UrlHelper {
  private $drupalWrapper;

  /**
   * @param \Questionmark\qm_core\Wrappers\DrupalWrapperInterface $drupalWrapper
   */
  public function __construct(Wrappers\DrupalWrapperInterface $drupalWrapper) {
    $this->drupalWrapper = $drupalWrapper;
  }

  /**
   * @return string Current absolute URL
   */
  public function getCurrentUrl() {
    $currentPath = $this->drupalWrapper->current_path();
    $currentUrl = $this->drupalWrapper->url($currentPath, array('absolute' => TRUE));
    return $currentUrl;
  }
}
