<?php
  
  
  // simply echos the following contents to the web browser.
  // This helps keep the main scripts simpler to read.
  function mainCheckTSN()
  {
      include("mbMenu_data.php");
      
      echo '<div class="main" style="width:850px;">      
         <div class="mainGenericContainer" style="width:750px;">
          <h1>Checking duplicated taxonomic names</h1>
          <p>This operation can take several minutes</p>';
      getDuplicatedTsnNames();
      echo '    </div>
        <div class="minHeight">&nbsp;</div>
      ';
      include('footer.inc.php');
      // main
      echo '</div>';
  }
  
  
  function getDuplicatedTsnNames()
  {
      $sql = 'SELECT MAX(tsn) as maxTsn, kingdom_id, unit_name1, unit_name2, unit_name3, rank_id, count(*) as numRecords ' . 'FROM Tree ' . 'GROUP BY kingdom_id, unit_name1, unit_name2, unit_name3 ' . 'HAVING numRecords>1 and maxTsn>=999000000 ' . 'ORDER BY tsn';
      
      $result = mysql_query($sql);
      if (!$result)
          return false;
      
      echo '<table>
      <tr>
        <td>#</td><td>tsn</td><td>kingdom</td><td>unit name 1</td><td>unit name 2</td><td>unit name 3</td><td>rank</td>
      </tr>
    ';
      if (mysql_num_rows($result) > 0) {
          $count = 0;
          while ($record = mysql_fetch_array($result, MYSQL_ASSOC)) {
              $count++;
              echo '<tr>
          <td align="right">' . $count . '</td>
          <td>' . $record['maxTsn'] . '</td>
          <td align="center">' . $record['kingdom_id'] . '</td>
          <td>' . $record['unit_name1'] . '</td>
          <td>' . $record['unit_name2'] . '</td>
          <td>' . $record['unit_name3'] . '</td>
          <td align="center">' . $record['rank_id'] . '</td>
        </tr>';
          }
      }
      echo '</table>';
  }
?>
