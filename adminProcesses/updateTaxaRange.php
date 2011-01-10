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
*   Katja Seltmann - initial API and implementation
*   Stephen Winner - initial API and implementation
*/

require_once('../configuration/app.server.php');

echo "Please enter the task number you would like to perform.\n 1. Update Taxa for Null keywords \n
	2. Update/Insert into Taxa table for a Taxon Number \n
	3. Update/Insert into Taxa table for a Taxon Number Range \n";
$tsn = $fromTsn = $toTsn ='';

$input = 1;
$input = fgets(STDIN);
echo "Input is $input";
if($input == 2){		
	echo "Please enter a Taxon number: \n";
	$tsn = fgets(STDIN);
	while(!is_numeric(trim($tsn))){
		echo "Please enter a numeric value for Taxon number: \n";
		$tsn = fgets(STDIN);
	}
	echo "The Taxon Number is $tsn";
}
if($input == 3){		
	echo "Please enter lower Taxon number: \n";
	$fromTsn = fgets(STDIN);
	while(!is_numeric(trim($fromTsn))){
		echo "Please enter a numeric value for lower Taxon number: \n";
		$fromTsn = fgets(STDIN);
	}
	echo "Please enter higher Taxon number: \n";
	$toTsn = fgets(STDIN);
	while(!is_numeric(trim($toTsn))){
		echo "Please enter a numeric value for higher Taxon number: \n";
		$toTsn = fgets(STDIN);
	}
	echo "The Taxon Number range is $fromTsn : $toTsn";
}

$link = Adminlogin();

$xml_pattern = array('/\"/');
$xml_replace = array('\\\"');
$text_pattern = array('/\s\s+/');
$text_replace = array(' ');


  $baseObjectFields = "BaseObject.id, BaseObject.userId, BaseObject.groupId, BaseObject.objectTypeId, BaseObject.submittedBy, User.name, Groups.groupName ";

  $taxaFields = "tsn, boId, scientificName, taxon_author_id, taxon_author_name, status, nameSource, parent_tsn, parent_name, kingdom_id, kingdom_name, rank_id, rank_name, imagesCount, nameType, publicationId";

  $publicationFields = "Publication.doi, Publication.publicationType, Publication.author, Publication.publicationTitle, Publication.month, Publication.publisher, Publication.school, Publication.series, Publication.note, Publication.organization, Publication.institution, Publication.title, Publication.volume, Publication.year, Publication.isbn, Publication.issn, Groups.groupName, User.name AS submittedBy ";

  $publicationJoin = ' Publication LEFT JOIN BaseObject ON Publication.id = BaseObject.id LEFT JOIN Groups ON BaseObject.groupId = Groups.id LEFT JOIN User ON BaseObject.userId = User.id ';

  //$taxaJoin = 'Taxa join Kingdoms on Taxa.kingdom_id=Kingdoms.kingdom_id join TaxonUnitTypes on Taxa.rank_id=TaxonUnitTypes.rank_id and Taxa.kingdom_id = TaxonUnitTypes.kingdom_id';

  $baseObjectJoin = ' BaseObject LEFT JOIN User ON BaseObject.submittedBy=User.id LEFT JOIN Groups ON BaseObject.groupId=Groups.id';

  $extLinkJoin = 'ExternalLinkObject inner join ExternalLinkType on ExternalLinkObject.extLinkTypeId =ExternalLinkType.linkTypeId';


  $dom = new DomDocument('1.0', 'utf-8');
  $morphbank = $dom->appendChild($dom->createElement("morphbank"));

  //$sqlCount = mysqli_fetch_array(mysqli_query($link, 'SELECT COUNT(*) AS count FROM Taxa WHERE keywords IS NULL'));

  if($input ==1)
  	$SQL_search = "SELECT ".$taxaFields." From Taxa WHERE keywords IS NULL ORDER BY tsn";
  if($input ==2)
  	$SQL_search = "SELECT ".$taxaFields." From Taxa WHERE tsn = " .$tsn;
  if($input ==3)
  	$SQL_search = "SELECT ".$taxaFields." From Taxa WHERE tsn >= " .$fromTsn. " AND tsn <= " .$toTsn;
echo $SQL_search. "\n";

    /*if($taxaId!=NULL)
      $SQL_search .="boId=".$taxaId;
    else
      $SQL_search .="tsn=".$tsn;
   */

  $result = mysqli_query($link,$SQL_search) or die ("Could not run query: " .$SQL_search. "\n" .mysqli_error($link));
  while( $row = mysqli_fetch_assoc($result)){

    if($row['boId'] == ''){
	echo "SELECT id FROM TaxonConcept WHERE tsn = $tsn";
	$boIdRes = mysqli_query($link, "SELECT id FROM TaxonConcept WHERE tsn =" .$tsn);
	$boId = mysqli_fetch_array($boIdRes);
	$row['boId'] = $boId['id'];
    }
    echo 'row[boId]: ' .$row['boId'];

    $keyword_entry = "";
    foreach($row as $field => $value){
      if (strcmp(trim($value), "")){
        $keyword_entry .= "<$field>".$value."</$field>";
        $keyword_entry .= " ";
      }
    }
    $keyword_entry .= "\n";

    if($row['publicationId']!=NULL && $row['publicationId']!="" && $row['publicationId']!=0){
      $SQL_extended = "SELECT ".$publicationFields. " FROM ".$publicationJoin." WHERE Publication.id=".$row['publicationId'];

      $supplementary_results = mysqli_query($link,$SQL_extended);
      if(!$supplementary_results)
        mysqli_error($link);
      else{
        $additional_keywords = mysqli_fetch_assoc($supplementary_results);
        foreach($additional_keywords as $field => $value){
          if (strcmp(trim($value), "")){
            if (strcmp(trim($value), "0000-00-00 00:00:00")){
              $keyword_entry .= "<$field>".$value."</$field>";
              $keyword_entry .= " ";
            }
          }
        }
      }
      $keyword_entry .= "\n";
    }

    $SQL_extended = "SELECT ".$baseObjectFields." FROM ".$baseObjectJoin." WHERE BaseObject.id=".$row['boId'];
    $supplementary_results = mysqli_query($link,$SQL_extended);
    if(!$supplementary_results)
      mysqli_error($link);
    else{
      $additional_keywords = mysqli_fetch_assoc($supplementary_results);
      foreach($additional_keywords as $field => $value){
        if (strcmp(trim($value), "")){
          $keyword_entry .= "<$field>".$value."</$field>";
          $keyword_entry .= " ";

        }
      }
      $keyword_entry .= "\n";
    }

    $SQL_extended = "SELECT vernacular_name, language FROM Vernacular where tsn=".$row['tsn'];
    $supplementary_results = mysqli_query($link,$SQL_extended);
    if(!$supplementary_results)
      mysqli_error($link);
    else{
      if(mysqli_num_rows($supplementary_results)>0){
        while($additional_keywords = mysqli_fetch_assoc($supplementary_results)) {
          foreach($additional_keywords as $field => $value){
            if (strcmp(trim($value), "")){
              $keyword_entry .= "<$field>".$value."</$field>";
              $keyword_entry .= " ";
            }
          }
        }
        $keyword_entry .= "\n";
      }
    }


    $SQL_extended = "SELECT name,label from ".$extLinkJoin." where mbId=".$row['boId'];
    $supplementary_results = mysqli_query($link,$SQL_extended);
    if(!$supplementary_results)
      mysqli_error($link);
    else{
      if(mysqli_num_rows($supplementary_results)>0){
        while($additional_keywords = mysqli_fetch_assoc($supplementary_results)) {
          foreach($additional_keywords as $field => $value){
            if (strcmp(trim($value), "")){
              $keyword_entry .= "<$field>".$value."</$field>";
              $keyword_entry .= " ";
            }
          }
        }
        $keyword_entry .= "\n";
      }
    }

/*
    //if the keywords are created for Otu information about
    //taxa or specimen is added to the keywords filed
    if($tsn==NULL && $taxaId!=NULL){
      $SQL_extended = "SELECT objectTypeId, objectTitle, objectId FROM CollectionObjects where collectionId=".$row['boId'];
      $supplementary_results = mysqli_query($link,$SQL_extended);
      if(!$supplementary_results)
        mysqli_error($link);
      else{
        if(mysqli_num_rows($supplementary_results)>0){
          while($additional_keywords = mysqli_fetch_assoc($supplementary_results)) {
            foreach($additional_keywords as $field => $value){
              if (strcmp(trim($value), "")){
                $keyword_entry .= "<$field>".$value."</$field>";
                $keyword_entry .= " ";
              }
            }
            if($additional_keywords['objectTypeId']=="TaxonConcept"){
              $SQL_simple = "SELECT tsn from Taxa where boId=".$additional_keywords['objectId'];
              $simple_result = mysqli_query($link,$SQL_simple);
              if(!$simple_result)
                mysqli_error($link);
              else{
                $simple_row = mysqli_fetch_assoc($simple_result);
                foreach($simple_row as $field => $value){
                    $keyword_entry .= "<$field>".$value."</$field>";
                    $keyword_entry .= " ";
                  }
              }
            }
          }
          $keyword_entry .= "\n";
        }
      }
    }
*/

    $keyword_entry = trim($keyword_entry);


    $SQL_update = "UPDATE Taxa SET keywords = \"" . preg_replace('/\"/', '\\\"',  $keyword_entry) . "\" WHERE boId = ".$row['boId'];
echo $SQL_update ."\n";
    $update_results = mysqli_query($link,$SQL_update);
    if(!$update_results)
      mysqli_error($link);

    $SQL_update = "UPDATE BaseObject SET keywords = \"" . preg_replace('/\"/', '\\\"',  $keyword_entry) . "\" WHERE id = ".$row['boId'];
echo $SQL_update ."\n ---------------------------------- \n";
    $update_results = mysqli_query($link,$SQL_update);
    if(!$update_results)
      mysqli_error($link);

  }


/*
        // get the base object fields
        $BO_search = "SELECT " . $baseObjectFields . " FROM BaseObject INNER JOIN User ON User.id = BaseObject.userId INNER JOIN Groups ON Groups.id = BaseObject.groupId WHERE BaseObject.id=".$id;

        $BOResult = mysqli_query($link, $BO_search);
        // no loop because it is only one record.

        $row = mysqli_fetch_assoc($BOResult);

        // loop throught and create nodes for each of the base object fields
        foreach($row as $field => $value){
                if (strcmp(trim($value), "")){
                  $keyword = $morphbank->appendChild($dom->createElement($field));
                  $keyword->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
                }
        }


        // get the object specific fields.  Takes the objectType passed to this function, and concatonates it to give correct variable type.
        // $var = "name";
        // ${'new_'.$var} shoule be a variable called "new_name"
        $sql = "SELECT ".${strtolower($objectType).'Fields'}." FROM ".${strtolower($objectType).'Join'}." WHERE BaseObject.id = ".$id;

        $object_type = $morphbank->appendChild($dom->createElement($objectType));

        //echo $sql;
        $results = mysqli_query($link, $sql) or die(mysqli_error($link).$sql);

        if($results){
                while($additional_keywords = mysqli_fetch_assoc($results)) {
                        foreach($additional_keywords as $field => $value){
                                if (strcmp(trim($value), "")){
                                        $keyword = $object_type->appendChild($dom->createElement($field));
                                        $keyword->appendChild($dom->createTextNode(preg_replace($text_pattern, $text_replace, $value)));
                                }
                        }
                }
        }

        $dom->formatOutput = true;
        $xml_output = $dom->saveXML();

        $SQL_update = "UPDATE BaseObject SET keywords = \"" . preg_replace($xml_pattern, $xml_replace, $xml_output) . "\" WHERE id = ".$id;
        $update_results = mysqli_query($link, $SQL_update) or die(mysqli_error($link));

        $operation = strtolower($operation);

        if ($operation == "insert")
                $keywordInsert = 'INSERT INTO Keywords SELECT id, userId, groupId, dateToPublish, objectTypeId, keywords, submittedBy FROM BaseObject WHERE id ='.$id;
        elseif ($operation == "update")
                $keywordInsert = 'UPDATE Keywords SET keywords = "'.preg_replace($xml_pattern, $xml_replace, $xml_output).'" WHERE id ='.$id;

        //echo $keywordInsert;
        $keywordResult = mysqli_query($link, $keywordInsert) or die(mysqli_error($link));

}
*/
