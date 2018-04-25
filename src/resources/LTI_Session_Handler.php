<?php

class LTI_Session_Handler implements SessionHandlerInterface {

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
   * Defaults to true, since opening DB is processed before the session values are opened.
   */
  public function open($save_path, $session_name) {
    return true;
  }

  /*
   * Defaults to true, since DB is set to be open passively, the _close operation shouldn't be used.
   */
  public function close() {
    return true;
  }

  /*
   * Read the session value, given the ID.
   */
  public function read($id) {
    $sql = 'SELECT data ' .
           'FROM ' . $this->dbTableNamePrefix . LTI_Data_Connector::SESSION_TABLE_NAME . ' ' .
           'WHERE (id = :id)';
    $query = $this->db->prepare($sql);
    $query->bindValue('id', $id, PDO::PARAM_STR);
    if ($query->execute()) {
      $row = $query->fetch();
      $data = $row['data'];
      if (is_null($data)) {
        return '';
      }
    } else {
      return '';
    }
    return $data;
  }

  /*
   * Write the session value.
   */
  public function write($id, $data) {
    $now = time();
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
      $query->bindValue('access', $now, PDO::PARAM_STR);
      $query->bindValue('data', $data, PDO::PARAM_STR);
    } else {
      $sql = 'UPDATE ' . $this->dbTableNamePrefix . LTI_Data_Connector::SESSION_TABLE_NAME . ' ' .
           'SET data = :data, access = :access' .
           'WHERE id = :id';
      error_log(print_r($sql, 1));
      $query = $this->db->prepare($sql);
      $query->bindValue('id', $id, PDO::PARAM_STR);
      $query->bindValue('access', $now, PDO::PARAM_STR);
      $query->bindValue('data', $data, PDO::PARAM_STR);
    }
    $ok = $query->execute();
    if (!$ok) {
      print_r($query->errorInfo(), 1);
    }
    return $ok;
  }

  /*
   * Destroy session data given ID
   */
  public function destroy($id) {
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
  public function gc($max) {
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
