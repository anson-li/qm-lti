<?php

class LTI_Session_Handler {

  private $dbTableNamePrefix = '';
  private $db = false;

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
    if ($db === false) {
      return false;
    }
    session_set_save_handler('_open',
                             '_close',
                             '_read',
                             '_write',
                             '_destroy',
                             '_clean');
  }

  /*
   * Defaults to true, since opening DB is processed before the session values are opened.
   */
  function _open() {
    return true;
  }

  /*
   * Defaults to true, since DB is set to be open passively, the _close operation shouldn't be used.
   */
  function _close() {
    return true;
  }

  /*
   * Read the session value, given the ID.
   */
  function _read($id) {
    $sql = 'SELECT data ' .
           'FROM ' . $this->dbTableNamePrefix . LTI_Data_Connector::SESSION_TABLE_NAME . ' ' .
           'WHERE (id = :id)';
    $query = $this->db->prepare($sql);
    $query->bindValue('id', $id, PDO::PARAM_STR);
    $ok = $query->execute();
    if ($ok) {
      $row = $query->fetch();
      $data = $row['data'];
    } else {
      return FALSE;
    }
    return $data;
  }

  /*
   * Write the session value.
   */
  function _write($id, $data) {
    $sql = 'SELECT count(*) ' .
           'FROM ' . $this->dbTableNamePrefix . LTI_Data_Connector::SESSION_TABLE_NAME . ' ' .
           'WHERE (id = :id)';
    $query = $this->db->prepare($sql);
    $query->bindValue('id', $id, PDO::PARAM_STR);
    $ok = $query->execute();
    $numColumns = $query->fetchColumn();
    if ($numColumns === 0) {
      $sql = 'INSERT INTO ' . $this->dbTableNamePrefix . LTI_Data_Connector::SESSION_TABLE_NAME . ' ' .
             '(id, access, data) ' .
             'VALUES (:id, :access, :data)';
      $query = $this->db->prepare($sql);
      $query->bindValue('id', $id, PDO::PARAM_STR);
      $query->bindValue('access', time(), PDO::PARAM_STR);
      $query->bindValue('data', $data, PDO::PARAM_STR);
    } else {
      $sql = 'UPDATE ' . $this->dbTableNamePrefix . LTI_Data_Connector::SESSION_TABLE_NAME . ' ' .
           'SET data = :data, access = :access' .
           'WHERE id = :id';
      $query = $this->db->prepare($sql);
      $query->bindValue('id', $id, PDO::PARAM_STR);
      $query->bindValue('access', time(), PDO::PARAM_STR);
      $query->bindValue('data', $data, PDO::PARAM_STR);
    }
    $ok = $query->execute();
    return $ok;
  }

  /*
   * Destroy session data given ID
   */
  function _destroy($id) {
    $sql = 'DELETE FROM ' . $this->dbTableNamePrefix . LTI_Data_Connector::SESSION_TABLE_NAME . ' ' .
           'WHERE (id = :id)';
    $query = $this->db->prepare($sql);
    $query->bindValue('id', $id, PDO::PARAM_STR);
    $ok = $query->execute();
    return $ok;
  }

  /*
   * Remove any expired sessions
   */
  function _clean($max) {
    $old = time() - $max;
    $sql = 'DELETE FROM ' . $this->dbTableNamePrefix . LTI_Data_Connector::SESSION_TABLE_NAME . ' ' .
           'WHERE (access < :old)';
    $query = $this->db->prepare($sql);
    $query->bindValue('old', $old, PDO::PARAM_STR);
    $ok = $query->execute();
    return $ok;
  }

}

?>
