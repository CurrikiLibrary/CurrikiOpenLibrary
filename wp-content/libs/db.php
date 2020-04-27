<?php

/*
  +--------------------------------------------------------------------------
  |   CubeCart v3
  |   ========================================
  |   by Alistair Brookbanks
  |	CubeCart is a Trade Mark of Devellion Limited
  |   Copyright Devellion Limited 2005 - 2006. All rights reserved.
  |   Devellion Limited,
  |   5 Bridge Street,
  |   Bishops Stortford,
  |   HERTFORDSHIRE.
  |   CM23 2JU
  |   UNITED KINGDOM
  |   http://www.devellion.com
  |	UK Private Limited Company No. 5323904
  |   ========================================
  |   Web: http://www.cubecart.com
  |   Date: Tuesday, 17th July 2007
  |   Email: sales (at) cubecart (dot) com
  |	License Type: CubeCart is NOT Open Source Software and Limitations Apply
  |   Licence Info: http://www.cubecart.com/site/faq/license.php
  +----------------------------------_logs----------------------------------------
  |	db.inc.php
  |   ========================================
  |	Database Class
  +--------------------------------------------------------------------------
 */

if (class_exists('db')) {
  return;
}

class db {

  var $query = "";
  var $db = NULL;
  var $dbdatabase = "";

  ////////////////////
  // CRUD Functions //
  ////////////////////
  function db($dbhost = '', $dbuser = '', $dbpwd = '', $dbdatabase = '') {
    global $global;
    $this->dbdatabase = $dbdatabase;

    $this->db = @mysqli_connect($dbhost, $dbuser, $dbpwd, $dbdatabase);
    if (!$this->db)
      die($this->debug(true));

    @mysqli_set_charset($this->db, "utf8");
  }

  function select($query, $maxRows = 0, $pageNum = 0) {
    $this->query = $query;

    // start limit if $maxRows is greater than 0
    if ($maxRows > 0) {
      $startRow = $pageNum * $maxRows;
      $query = sprintf("%s LIMIT %d, %d", $query, $startRow, $maxRows);
    }

    $result = mysqli_query($this->db, $query);
    if ($this->error())
      die($this->debug());

    $output = false;

    for ($n = 0; $n < mysqli_num_rows($result); $n++) {
      $row = mysqli_fetch_assoc($result);
      $output[$n] = $row;
    }

    return $output;
  }

  function insert($tablename, $record) {
    if (!is_array($record))
      die($this->debug("array", "Insert", $tablename));

    $count = 0;
    foreach ($record as $key => $val) {
      if ($count == 0) {
        $fields = "`" . $key . "`";
        $values = $val;
      } else {
        $fields .= ", " . "`" . $key . "`";
        $values .= ", " . $val;
      }
      $count++;
    }

    $query = "INSERT INTO " . $tablename . " (" . $fields . ") VALUES (" . $values . ")";

    $this->query = $query;
    mysqli_query($this->db, $query);

    if ($this->error())
      die($this->debug());

    if ($this->affected() > 0)
      return true;
    else
      return false;
  }

  function update($tablename, $record, $where) {
    if (!is_array($record))
      die($this->debug("array", "Update", $tablename));

    $count = 0;

    foreach ($record as $key => $val) {
      if ($count == 0)
        $set = "`" . $key . "`" . "=" . $val;
      else
        $set .= ", " . "`" . $key . "`" . "= " . $val;
      $count++;
    }

    $query = "UPDATE " . $tablename . " SET " . $set . " WHERE " . $where;

    $this->query = $query;
    mysqli_query($this->db, $query);

    $this->makeLog($query, $tablename);

    if ($this->error())
      die($this->debug());

    if ($this->affected() > 0)
      return true;
    else
      return false;
  }

  function delete($tablename, $where, $limit = "") {
    $query = "DELETE from " . $tablename . " WHERE " . $where;
    if ($limit != "")
      $query .= " LIMIT " . $limit;
    $this->query = $query;
    mysqli_query($this->db, $query);

    $this->makeLog($query, $tablename);

    if ($this->error())
      die($this->debug());

    if ($this->affected() > 0)
      return TRUE;
    else
      return FALSE;
  }

  function numrows($query) {
    $this->query = $query;
    $result = mysqli_query($this->db, $query);
    return mysqli_num_rows($result);
  }

  function misc($query) {
    $this->query = $query;
    $result = mysqli_query($this->db, $query);
    $this->makeLog($query);

    if ($this->error())
      die($this->debug());

    if ($result == TRUE)
      return TRUE;
    else
      return FALSE;
  }

  /////////////////////////////////////////////
  // Clean SQL Variables (Security Function) //
  /////////////////////////////////////////////
  function mySQLSafe($value, $quote = "'") {

    // strip quotes if already in
    $value = addslashes(stripslashes($value));
    $value = mysqli_escape_string($this->db, $value);
    $value = $quote . trim($value) . $quote;

    return $value;
  }

  function makeLog($query, $tabname = NULL) {
    
  }

  function debug($type = "", $action = "", $tablename = "") {
    switch ($type) {
      case "connect":
        $message = "MySQL Error Occured";
        $result = mysqli_errno($this->db) . ": " . mysqli_error($this->db);
        $query = "";
        $output = "Could not connect to the database. Be sure to check that your database connection settings are correct and that the MySQL server in running.";
        break;


      case "array":
        $message = $action . " Error Occured";
        $result = "Could not update " . $tablename . " as variable supplied must be an array.";
        $query = "";
        $output = "Sorry an error has occured accessing the database. Be sure to check that your database connection settings are correct and that the MySQL server in running.";

        break;


      default:
        if (mysqli_errno($this->db)) {
          $message = "MySQL Error Occured";
          $result = mysqli_errno($this->db) . ": " . mysqli_error($this->db);
          $output = "Sorry an error has occured accessing the database. Be sure to check that your database connection settings are correct and that the MySQL server in running.";
        } else {
          $message = "MySQL Query Executed Succesfully.";
          $result = $this->affected() . " Rows Affected";
          $output = "view logs for details";
        }

        $linebreaks = array("\n", "\r");
        if ($this->query != "")
          $query = "QUERY = " . str_replace($linebreaks, " ", $this->query);
        else
          $query = "";
        break;
    }

    $output = "<b style='font-family: Arial, Helvetica, sans-serif; color: #0B70CE;'>" . $message . "</b><br />\n<span style='font-family: Arial, Helvetica, sans-serif; color: #000000;'>" . $result . "</span><br />\n<p style='Courier New, Courier, mono; border: 1px dashed #666666; padding: 10px; color: #000000;'>" . $query . "</p>\n";

    $msg = $output;
    $msg .= 'Request URI:' . $_SERVER['REQUEST_URI'];
    $msg .= ($_SERVER['QUERY_STRING']) ? '<br /><br />Query String:' . $_SERVER['QUERY_STRING'] : '';
    $msg .= '<br /><br />Script File Name:' . $_SERVER['SCRIPT_FILENAME'];

    if (isset($_REQUEST['test'])) {
      echo $output;
      echo "<pre>";
      print_r(debug_backtrace());
      echo "</pre>";
      return $output;
    }
  }

  function error() {
    if (mysqli_errno($this->db))
      return true;
    else
      return false;
  }

  function insertid() {
    return mysqli_insert_id($this->db);
  }

  function affected() {
    return mysqli_affected_rows($this->db);
  }

  function close() { // close conection
    mysqli_close($this->db);
  }

}

// end of db class
//    $time_start = microtime(true);
//$time_end = microtime(true);
//    $time = $time_end - $time_start;
/*
 *  $time_start = microtime(true);
  if (isset($_REQUEST['debug'])) {
  $result2 = mysql_query('EXPLAIN ' . $query, $this->db);
  if ($result2) {

  $exp = "<table border=1>";
  $exp .= "<tr><td>id</td><td>select_type</td><td>table</td><td>type</td><td>possible_keys</td><td>key</td><td>key_len</td><td>ref</td><td>rows</td><td>Extra</td></tr>";
  while ($r1 = mysql_fetch_array($result2)) {
  $exp .= "<tr>";
  $exp .= "<td>" . $r1['id'] . "</td>";
  $exp .= "<td>" . $r1['select_type'] . "</td>";
  $exp .= "<td>" . $r1['table'] . "</td>";
  $exp .= "<td>" . $r1['type'] . "</td>";
  $exp .= "<td>" . $r1['possible_keys'] . "</td>";
  $exp .= "<td>" . $r1['key'] . "</td>";
  $exp .= "<td>" . $r1['key_len'] . "</td>";
  $exp .= "<td>" . $r1['ref'] . "</td>";
  $exp .= "<td>" . $r1['rows'] . "</td>";
  $exp .= "<td>" . $r1['Extra'] . "</td>";
  $exp .= "</tr>";
  }
  $exp .= "</table>";
  }
  }
  $time_end = microtime(true);
  $EXTRA_TIME = $time_end - $time_start;
  $GLOBALS['EXTRA_TIME'] = $EXTRA_TIME;
  $GLOBALS['allqueies'] .= (($time > 0.9) ? "<span style='color:RED;'>" : "") . "<pre> Query No#" . ($GLOBALS['totalQueries'] ++) . "&nbsp;&nbsp;&nbsp;" . $query . "</pre><br/><b>Query took: $time secs</b><hr/><br/>" . (($time > 0.9) ? "</span>" : "");
  if (isset($_REQUEST['debug'])) {
  $GLOBALS['allqueies'] .= $exp;
  }
 */
?>

