<?php
/**
* Copyright (c) 2011 Greg Riccardi, Fredrik Ronquist.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the GNU Public License v2.0
* which accompanies this distribution, and is available at
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* 
* Contributors:
*   Fredrik Ronquist - conceptual modeling and interaction design
*   Austin Mast - conceptual modeling and interaction design
*   Greg Riccardi - initial API and implementation
*   Wilfredo Blanco - initial API and implementation
*   Robert Bruhn - initial API and implementation
*   Christopher Cprek - initial API and implementation
*   David Gaitros - initial API and implementation
*   Neelima Jammigumpula - initial API and implementation
*   Karolina Maneva-Jakimoska - initial API and implementation
*   Deborah Paul - initial API and implementation implementation
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

class Explain_Queries
{
  // how many queries were executed
  var $query_count = 0;
  // which queries and their count
  var $queries = array();
  // results of EXPLAIN-ed SELECTs
  var $explains = array();
  // the MDB2 instance
  var $db = false;

  // constructor that accepts MDB2 reference
  function Explain_Queries(&$db) {
    $this->db = $db;
  }

  // this method is called on every query
  function collectInfo(
    &$db,
    $scope,
    $message,
    $is_manip = null)
  {
    // increment the total number of queries
    $this->query_count++;
    // the SQL is a key in the queries array
    // the value will be the count of how
    // many times each query was executed
    @$this->queries[$message]++;
  }

  // print the debug information
  function dumpInfo()
  {
  	echo '<div style="margin-top:300px"></div>';
    echo '<h3>Queries on this page</h3>';
    echo '<pre>';
    print_r($this->queries);
    echo '</pre>';
    echo '<h3>EXPLAIN-ed SELECTs</h3>';
    echo '<pre>';
    print_r($this->explains);
    echo '</pre>';
  }

  // the method that will execute all SELECTs
  // with and without an EXPLAIN and will
  // create $this->explains array of debug
  // information
  // SHOW WARNINGS will be called after each
  // EXPLAIN for more information
  function executeAndExplain() {

    // at this point, stop debugging
    $this->db->setOption('debug', 0);
    $this->db->loadModule('Extended');

    // take the SQL for all the unique queries
    $queries = array_keys($this->queries);
    foreach ($queries AS $sql) {

      // for all SELECTs�
      $sql = trim($sql);
      if (stristr($sql,"SELECT") !== false){
        // note the start time
        $start_time = array_sum(
            explode(" ", microtime())
        );
        // execute query
        $this->db->query($sql);
        // note the end time
        $end_time = array_sum(
            explode(" ", microtime())
        );
        // the time the query took
        $total_time = $end_time - $start_time;

        // now execute the same query with
        // EXPLAIN EXTENDED prepended
        $explain = $this->db->getAll(
          'EXPLAIN EXTENDED ' . $sql
        );

        $this->explains[$sql] = array();
        // update the debug array with the
        // new data from
        // EXPLAIN and SHOW WARNINGS
        if (!PEAR::isError($explain)) {
          $this->explains[$sql]['explain'] = $explain;
          $this->explains[$sql]['warnings'] =
               $this->db->getAll('SHOW WARNINGS');
        }

        // update the debug array with the
        // count and time
        $this->explains[$sql]['time'] = $total_time;
      }
    }
  }
}
?>
