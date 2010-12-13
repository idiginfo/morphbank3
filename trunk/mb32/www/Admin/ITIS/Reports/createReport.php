<?php
  //function ti create ITIS reports
  
  function createReport()
  {
      global  $objInfo;
      
      $link = AdminLogin();
      
      if (!isset($_POST['returnUrl']))
          $returnUrl = $_SERVER['HTTP_REFERER'];
      else
          $returnUrl = $_POST['returnUrl'];
      
      $userId = $objInfo->getUserId();
      
      if (isset($_POST['selection']) && $_POST['selection'] != "" && $_POST['selection'] != null) {
          $userInput[7];
          $selection = $_POST['selection'];
          $kingdom = $_POST['kingdom'];
          $userInput[0] = $kingdom;
          
          $hrank = $_POST['highest_rank'];
          $userInput[1] = $hrank;
          
          $lrank = $_POST['lowest_rank'];
          $userInput[2] = $lrank;
          if ($hrank > $lrank && ($hrank != 0 && $hrank != 7) && ($lrank != 0 && $lrank != 7)) {
              echo '<span style="color:red"><b>The highest rank can not be below the lowest rank selected. Report can not be created.</b></span><br/><br/>';
              return 1;
          }
          $scientificName = $_POST['scientific_name'];
          $tsn = $_POST['tsn'];
          if ($scientificName == "" && $tsn == "")
              $scientificName = "Not_specified";
          else
              $scientificName = GetName($tsn, $link);
          if ($scientificName == "") {
              echo '<span style="color:red"><b>Provided tsn does not exists in the database. Report can not be created.</b></span><br/><br/>';
              return 1;
          }
          $userInput[3] = $scientificName;
          $userInput[4] = $tsn;
          $startDate = $_POST['date_from'];
          if ($startDate == "")
              $startDate = "all";
          $userInput[5] = $startDate;
          
          $endDate = $_POST['date_to'];
          if ($endDate == "")
              $endDate = "today";
          $userInput[6] = $endDate;
          
          
          //find what is the last number used for report
          $query = "SELECT counter FROM ITISCounter";
          $result = mysqli_query($link, $query);
          $row = mysqli_fetch_array($result);
          $counter = $row['counter'] + 1;
          
          
          $query = "SELECT uin FROM User WHERE id=" . $userId;
          $result = mysqli_query($link, $query);
          $row = mysqli_fetch_array($result);
          $user = $row['uin'];
          $check = CheckInput($userInput, $link);
          if ($check == 1) {
              echo '<span style="color:red"><b>The chosen tsn/name is not in the selected kingdom.Report can not be created.</b></span><br/><br/>';
              return 1;
          } elseif ($check == 2) {
              echo '<span style="color:red"><b>The selected "highest" and "lowest" rank needs to be either equal or below the rank of the specified tsn/name. Report can not be created.</b></span><br/><br/>';
              return 1;
          } elseif ($check == 3) {
              echo '<span style="color:red"><b>The highest rank can not be below the lowest rank selected. Report can not be created.</b></span><br/><br/>';
              return 1;
          } else {
              
              $newreport = $config->domain . "reports/report" . $counter . "_" . $user . ".xls";
              $reportname = "report" . $counter . "_" . $user . ".xls";
              $command1 = "javac -classpath mysql-3.1.11.jar:jxl.jar:. CreateReport.java";
              $command2 = "java -classpath mysql-3.1.11.jar:jxl.jar:. CreateReport " . $selection . " " . $kingdom . " " . $hrank;
              $command2 .= " " . $lrank . " " . $scientificName . " " . $startDate . " " . $endDate . " " . $user;
              $command = "/bin/cp /data/www/reports/output.xls /data/www/reports/report" . $counter . "_" . $user . ".xls";
              //echo "comand is ".$command2;
              
              $result = shell_exec($command1);
              $result = exec($command2, $output, $retvalue);
              if ($retvalue != 0) {
                  echo '<span style="color:#17256B"><b>There are no results meeting your search criteria.</b></span><br/><br/>';
              } else {
                  
                  $result = shell_exec($command);
                  echo '<span style="color:#17256B"><h2><b>Your report <a href="' . $newreport . '">' . $reportname . '</a> is ready for download.</h2></b></span>
          <br/><hr/><br/>';
                  $result = shell_exec("rm /data/www/reports/output.xls");
                  $query = "UPDATE ITISCounter SET counter=counter+1";
                  //echo $query;
                  $result = mysqli_query($link, $query);
                  if (!$result) {
                      mysqli_warning_count($link);
                      mysqli_error($link);
                      echo '<span style="color:red"><b>Problems updating the counter</b></span>';
                  }
                  return 0;
              }
          }
      }
  }
  //end of functon createReport
  
  //function to find scientificName from tsn
  function GetName($tsn, $link)
  {
      $name = "";
      $query = "SELECT scientificName from Tree where tsn=" . $tsn;
      $result = mysqli_query($link, $query);
      if (!result)
          echo '<span style="color:red"><b>Problems querying the database</b></span>';
      else {
          
          $numrows = mysqli_num_rows($result);
          if ($numrows > 0) {
              $row = mysqli_fetch_array($result);
              $name = $row['scientificName'];
          }
      }
      return $name;
  }
  
  //function to check the input of the user
  function CheckInput($userInput, $link)
  {
      if ($userInput[4] != "") {
          $query = "SELECT kingdom_id,rank_id from Tree where tsn=" . $userInput[4];
          $result = mysqli_query($link, $query);
          $row = mysqli_fetch_array($result);
          $kingdom = $row['kingdom_id'];
          $rank = $row['rank_id'];
          
          if ($userInput[0] != 0 && $userInput[0] != 7 && $userInput[0] != $kingdom)
              return 1;
          
          if ($userInput[1] != 0 && $userInput[1] != 7 && $userInput[1] < $rank)
              return 2;
          
          if ($userInput[2] != 0 && $userInput[2] != 7 && $userInput[2] < $rank)
              return 2;
          
          return 0;
      } else
          return 0;
  }
  //end of function CheckInput
?>
