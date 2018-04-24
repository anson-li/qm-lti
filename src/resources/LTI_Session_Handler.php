<?php

class LTI_Session_Handler {

  private $dbTableNamePrefix = '';
  private $db = NULL;

/*
 * Class constructor
 */
  function __construct($db, $dbTableNamePrefix = '') {
    $this->db = $db;
    $this->dbTableNamePrefix = $dbTableNamePrefix;
  }

/*
 * Setup session handler details to work through DB
 */
  public function process_session_handlers() {
    session_set_save_handler('_open',
                             '_close',
                             '_read',
                             '_write',
                             '_destroy',
                             '_clean');
  }

  function _open() {
    return true;
  }

  function _close() {
    return true;
  }

  function _read() {
    return true;
  }

  function _write() {
    return true;
  }

  function _destroy() {
    return true;
  }

  function _clean() {
    return true;
  }

}

?>
