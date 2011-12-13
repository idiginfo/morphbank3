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

	
	include_once('head.inc.php');
	$title =  'About - Manual';
	initHtml( $title, NULL, NULL);
	echoHead( false, $title);
	?>

	
		<div class="mainGenericContainer" width="100%">
		<!--change the header below -->
		<h1>Mapping: Morphbank - Darwin Core - ABCD</h1>
<div id=footerRibbon></div>
			<!--<table class="manualContainer" cellspacing="0" width="100%">
			<tr>-->
			<td width="100%">
<br />
<br />

<TABLE border="1" cellspacing="1" cellpadding="10" ><TBODY>
<TR><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>Morphbank Table.fieldname</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA"><h3>Darwin Core 1.2 (Classic)-<br />Darwin Core 1.2 Element</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA"><h3>ABCD xpath</h3></TH></TR>

<TR><TD>BaseObject.dateLastModified</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DateLastModified-2003-06-13">DateLastModified</a></TD>	<TD>DataSets/DataSet/Units/Unit/DateLastEdited</TD>
<TR><TD>Specimen.institutionCode</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#InstitutionCode-2003-06-13">InstitutionCode</a></TD>	<TD>DataSets/DataSet/Units/Unit/SourceInstitutionID</TD>
<TR><TD>Specimen.collectionCode</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CollectionCode-2003-06-13">CollectionCode</a></TD>	<TD>DataSets/DataSet/Units/Unit/SourceID</TD>
<TR><TD>Specimen.catalogNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CatalogNumber-2003-06-13">CatalogNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/UnitID</TD>

<TR><TD>Tree.scientificName</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#ScientificName-2003-06-13">ScientificName</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/FullScientificNameString</TD>
<TR><TD>Specimen.basisOfRecord</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#BasisOfRecord-2003-06-13">BasisOfRecord</a></TD>	<TD>DataSets/DataSet/Units/Unit/RecordBasis</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Kingdom-2003-06-13">Kingdom</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = regnum</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Phylum-2003-06-13">Phylum</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = phylum</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Class-2003-06-13">Class</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = classis</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Order-2003-06-13">Order</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = ordo</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Family-2003-06-13">Family</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = familia</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Genus-2003-06-13">Genus</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/GenusOrMonomial or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/Genus
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Species-2003-06-13">Species</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/SpeciesEpithet or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/FirstE
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Subspecies-2003-06-13">Subspecies</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/SubspeciesEpithet or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/Sec

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#ScientificNameAuthor-2003-06-13">ScientificNameAuthor</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/ParentheticalAuthorTeamAndYear + DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/B
<TR><TD>Specimen.name</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#IdentifiedBy-2003-06-13">IdentifiedBy</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/Identifiers/IdentifiersText</TD>
<TR><TD>Specimen.dateIdentified (as yyyy-mm-dd)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#YearIdentified-2003-06-13">YearIdentified</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Identifications/Identification/Date/DateText</TD>
<TR><TD>Specimen.dateIdentified (as yyyy-mm-dd)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MonthIdentified-2003-06-13">MonthIdentified</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Identifications/Identification/Date/DateText</TD>
<TR><TD>Specimen.dateIdentified (as yyyy-mm-dd)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DayIdentified-2003-06-13">DayIdentified</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Identifications/Identification/Date/DateText</TD>

<TR><TD>Specimen.typeStatus</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#TypeStatus-2003-06-13">TypeStatus</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/NomenclaturalTypeDesignations/NomenclaturalTypeText</TD>
<TR><TD>Specimen.collectionNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CollectorNumber-2003-06-13">CollectorNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/CollectorsFieldNumber</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#FieldNumber-2003-06-13">FieldNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Code</TD>
<TR><TD>Specimen.collectorName</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Collector-2003-06-13">Collector</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/GatheringAgents/GatheringAgentsText</TD>
<TR><TD>Specimen.earliestDateCollected (as yyyy-mm-dd 00:00:00)<br />Specimen.latestDateCollected (as yyyy-mm-dd 00:00:00:)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#YearCollected-2003-06-13">YearCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>

<TR><TD>Specimen.earliestDateCollected (as yyyy-mm-dd 00:00:00)<br />Specimen.latestDateCollected (as yyyy-mm-dd 00:00:00:)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MonthCollected-2003-06-13">MonthCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>
<TR><TD>Specimen.earliestDateCollected (as yyyy-mm-dd 00:00:00)<br />Specimen.latestDateCollected (as yyyy-mm-dd 00:00:00:)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DayCollected-2003-06-13">DayCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#JulianDay-2003-06-13">JulianDay</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/DayNumberBegin</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#TimeOfDay-2003-06-13">TimeOfDay</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/TimeOfDayBegin</TD>
<TR><TD>Specimen.continentOcean</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#ContinentOcean-2003-06-13">ContinentOcean</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Continent</TD>

<TR><TD>Specimen.country</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Country-2003-06-13">Country</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Country/Name</TD>
<TR><TD>Specimen.state</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#StateProvince-2003-06-13">StateProvince</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= State or = Province (etc.)</TD>
<TR><TD>Specimen.county</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#County-2003-06-13">County</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= County</TD>
<TR><TD>Locality.locality</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Locality-2003-06-13">Locality</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/AreaDetail</TD>
<TR><TD>Locality.longitude</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Longitude-2003-06-13">Longitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/LongitudeDecimal</TD>

<TR><TD>Locality.latitude</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Latitude-2003-06-13">Latitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/LatitudeDecimal</TD>
<TR><TD>Locality.coordinatePrecision</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CoordinatePrecision-2003-06-13">CoordinatePrecision</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLong/ISOAccuracy or DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLong/AccuracyStatement</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#BoundingBox-2003-06-13">BoundingBox</a></TD>	<TD>not covered by ABCD</TD>
<TR><TD>Locality.minimumElevation</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MinimumElevation-2003-06-13">MinimumElevation</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactAtomised/LowerValue</TD>
<TR><TD>Locality.maximumElevation</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MaximumElevation-2003-06-13">MaximumElevation</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactAtomised/UpperValue</TD>

<TR><TD>Locality.minimumDepth</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MinimumDepth-2003-06-13">MinimumDepth</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactAtomised/LowerValue</TD>
<TR><TD>Locality.minimumDepth</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MaximumDepth-2003-06-13">MaximumDepth</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactAtomised/UpperValue</TD>
<TR><TD>Specimen.sex</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Sex-2003-06-13">Sex</a></TD>	<TD>DataSets/DataSet/Units/Unit/Sex</TD>
<TR><TD>Specimen.preparationType</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#PreparationType-2003-06-13">PreparationType</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/Preparations/PreparationsText</TD>
<TR><TD>Specimen.individualCount</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#IndividualCount-2003-06-13">IndividualCount</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/SiteMeasurementOrFact/MeasurementOrFactAtomised/LowerValue</TD>

<TR><TD>Specimen.previousCatalogNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#PreviousCatalogNumber-2003-06-13">PreviousCatalogNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/History/PreviousUnitsText</TD>
<TR><TD>Specimen.relationshipType</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#RelationshipType-2003-06-13">RelationshipType</a></TD>	<TD>DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociationType</TD>
<TR><TD>Specimen.relatedCatalogItem</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#RelatedCatalogItem-2003-06-13">RelatedCatalogItem</a></TD>	<TD>DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitSourceInstitutionCode + DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitSourceName + DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitI
<TR><TD>Specimen.notes</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Notes-2003-06-13">Notes</a></TD>	<TD>DataSets/DataSet/Units/Unit/Notes</TD>

</TBODY></TABLE>

<br />
<br />

<TABLE border="1" cellspacing="1" cellpadding="3"><TBODY>
<TR><TH colspan="1" align="left" bgcolor="#C5C2BA"><h3>Morphbank Table.fieldname</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>OBIS extension-<br />OBIS Element</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>ABCD xpath</h3></TH></TR>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#RecordURL-2005-07-10">RecordURL</a></TD>	<TD>DataSets/DataSet/Units/Unit/RecordURI</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Source-2005-07-10">Source</a></TD>	<TD>DataSets/DataSet/Units/Unit/IPRStatements/Citations/Citation/Text</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Citation-2005-07-10">Citation</a></TD>	<TD>DataSets/DataSet/Units/Unit/IPRStatements/Citations/Citation/Text</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Subgenus-2005-07-10">Subgenus</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Zoological/Subgenus</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#StartYearCollected-2005-07-10">StartYearCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EndYearCollected-2005-07-10">EndYearCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeEnd</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#StartMonthCollected-2005-07-10">StartMonthCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EndMonthCollected-2005-07-10">EndMonthCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeEnd</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#StartDayCollected-2005-07-10">StartDayCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EndDayCollected-2005-07-10">EndDayCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeEnd</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#StartJulianDay-2005-07-10">StartJulianDay</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/DayNumberBegin</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EndJulianDay-2005-07-10">EndJulianDay</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/DayNumberEnd</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#StartTimeOfDay-2005-07-10">StartTimeOfDay</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/TimeOfDayBegin</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EndTimeOfDay-2005-07-10">EndTimeOfDay</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/TimeOfDayEnd</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#TimeZone-2005-07-10">TimeZone</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/TimeZone</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#StartLongitude-2005-07-10">StartLongitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/CoordinateSets/SiteCoordinates/CoordinatesLatLon/LongitudeDecimal with attribute "begin" set to true</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EndLongitude-2005-07-10">EndLongitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/CoordinateSets/SiteCoordinates/CoordinatesLatLon/LongitudeDecimal with attribute "begin" set to true</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#StartLatitude-2005-07-10">StartLatitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/CoordinateSets/SiteCoordinates/CoordinatesLatLon/LatitudeDecimal with attribute "begin" set to true</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EndLatitude-2005-07-10">EndLatitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/CoordinateSets/SiteCoordinates/CoordinatesLatLon/LatitudeDecimal with attribute "begin" set to true</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Start_EndCoordinatePrecision-2005-07-10">Start_EndCoordinatePrecision</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLong/CoordinateErrorDistanceInMeters</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DepthRange-2005-07-10">DepthRange</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactText</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Temperature-2005-07-10">Temperature</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/LowerValue + constant</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Lifestage-2005-07-10">Lifestage</a></TD>	<TD>DataSets/DataSet/Units/Unit/MycologicalUnit/MycologicalSexualStage or DataSets/DataSet/Units/Unit/MycologicalUnit/MycologicalLiveStages/MycologicalLiveStage (Note DwC spec uses "MycologicalLifeStage" or DataSets/DataSet/Units/Unit/ZoologicalUnit/Phase
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#ObservedIndividualCount-2005-07-10">ObservedIndividualCount</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/LowerValue + constant</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#SampleSize-2005-07-10">SampleSize</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/LowerValue + constant</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#ObservedWeight-2005-07-10">ObservedWeight</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/LowerValue + constant</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#GMLFeature-2005-07-10">GMLFeature</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/GML</TD>

</TBODY></TABLE>

<br />
<br />

<TABLE border="1" cellspacing="1" cellpadding="3"><TBODY>
<TR><TH colspan="1" align="left" bgcolor="#C5C2BA"><h3>Morphbank Table.fieldname</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>Darwin Core 1.21 (MaNIS/HerpNet/ORNIS/FishNet2)-<br />Darwin Core 1.21 Element</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>ABCD xpath</h3></TH></TR>

<TR><TD>BaseObject.dateLastModified</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DateLastModified-2003-06-17">DateLastModified</a></TD>	<TD>DataSets/DataSet/Units/Unit/DateLastEdited</TD>
<TR><TD>Specimen.basisOfRecord</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#BasisOfRecord-2003-06-17">BasisOfRecord</a></TD>	<TD>DataSets/DataSet/Units/Unit/RecordBasis</TD>

<TR><TD>Specimen.institutionCode</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#InstitutionCode-2003-06-17">InstitutionCode</a></TD>	<TD>DataSets/DataSet/Units/Unit/SourceInstitutionID</TD>
<TR><TD>Specimen.collectionCode</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CollectionCode-2003-06-17">CollectionCode</a></TD>	<TD>DataSets/DataSet/Units/Unit/SourceID</TD>
<TR><TD>Specimen.catalogNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CatalogNumberText-2003-06-17">CatalogNumberText</a></TD>	<TD>DataSets/DataSet/Units/Unit/UnitID</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CatalogNumberNumeric-2003-06-17">CatalogNumberNumeric</a></TD>	<TD>DataSets/DataSet/Units/Unit/UnitIDNumeric</TD>
<TR><TD>Specimen.collectorName</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Collector-2003-06-17">Collector</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/GatheringAgents/GatheringAgentsText</TD>

<TR><TD>Specimen.collectionNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CollectorNumber-2003-06-17">CollectorNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/CollectorsFieldNumber</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#FieldNumber-2003-06-17">FieldNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Code</TD>
<TR><TD>Specimen.earliestDateCollected (as yyyy-mm-dd 00:00:00)<br />Specimen.latestDateCollected (as yyyy-mm-dd 00:00:00:)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#YearCollected-2003-06-17">YearCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>
<TR><TD>Specimen.earliestDateCollected (as yyyy-mm-dd 00:00:00)<br />Specimen.latestDateCollected (as yyyy-mm-dd 00:00:00:)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MonthCollected-2003-06-17">MonthCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>
<TR><TD>Specimen.earliestDateCollected (as yyyy-mm-dd 00:00:00)<br />Specimen.latestDateCollected (as yyyy-mm-dd 00:00:00:)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DayCollected-2003-06-17">DayCollected</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#TimeCollected-2003-06-17">TimeCollected</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/TimeOfDayBegin</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimCollectingDate-2003-06-17">VerbatimCollectingDate</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/DateText</TD>
<TR><TD>Specimen.notes</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#FieldNotes-2003-06-17">FieldNotes</a></TD>	<TD>DataSets/DataSet/Units/Unit/FieldNotes</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#JulianDay-2003-06-17">JulianDay</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/DayNumberBegin</TD>
<TR><TD>Locality.locality</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#HigherGeography-2003-06-17">HigherGeography</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Gathering/LocalityText or DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName}</TD>

<TR><TD>Locality.continentOcean</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#ContinentOcean-2003-06-17">ContinentOcean</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Continent</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#IslandGroup-2003-06-17">IslandGroup</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Island group</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Island-2003-06-17">Island</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Island</TD>
<TR><TD>Locality.country</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Country-2003-06-17">Country</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Country/Name</TD>
<TR><TD>Locality.state</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#StateProvince-2003-06-17">StateProvince</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= State or = Province (etc.)</TD>

<TR><TD>Locality.county</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#County-2003-06-17">County</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= County</TD>
<TR><TD>Locality.locality</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Locality-2003-06-17">Locality</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/AreaDetail</TD>
<TR><TD>Locality.latitude</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DecimalLatitude-2003-06-17">DecimalLatitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/LatitudeDecimal</TD>
<TR><TD>Locality.longitude</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DecimalLongitude-2003-06-17">DecimalLongitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/LongitudeDecimal</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#HorizontalDatum-2003-06-17">HorizontalDatum</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/SpatialDatum</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#OriginalCoordinateSystem-2003-06-17">OriginalCoordinateSystem</a></TD>	<TD>(partly) DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesGrid/GridCellSystem</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimLatitude-2003-06-17">VerbatimLatitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/VerbatimLatitude</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimLongitude-2003-06-17">VerbatimLongitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/VerbatimLongitude</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#GeorefMethod-2003-06-17">GeorefMethod</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinateMethod</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CoordinateUncertaintyInMeters-2003-06-17">CoordinateUncertaintyInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/CoordinateErrorDistanceInMeters</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#LatLongComments-2003-06-17">LatLongComments</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/GeoreferenceRemarks</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#BoundingBox-2003-06-17">BoundingBox</a></TD>	<TD>not covered by ABCD</TD>
<TR><TD>Locality.minimumElevation</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MinimumElevationInMeters-2003-06-17">MinimumElevationInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactAtomised/LowerValue</TD>
<TR><TD>Locality.maximumElevation</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MaximumElevationInMeters-2003-06-17">MaximumElevationInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactAtomised/UpperValue</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimElevation-2003-06-17">VerbatimElevation</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactText</TD>

<TR><TD>Locality.minimumDepth</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MinimumDepthInMeters-2003-06-17">MinimumDepthInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactAtomised/LowerValue</TD>
<TR><TD>Locality.maximumDepth</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MaximumDepthInMeters-2003-06-17">MaximumDepthInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactAtomised/UpperValue</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimDepth-2003-06-17">VerbatimDepth</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactText</TD>
<TR><TD>Tree.scientificName</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#ScientificName-2003-06-17">ScientificName</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/FullScientificNameString</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#HigherTaxon-2003-06-17">HigherTaxon</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Kingdom-2003-06-17">Kingdom</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = regnum</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Phylum-2003-06-17">Phylum</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = phylum</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Class-2003-06-17">Class</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = classis</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Order-2003-06-17">Order</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = ordo</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Family-2003-06-17">Family</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = familia</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Genus-2003-06-17">Genus</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/GenusOrMonomial or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/Genus
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Species-2003-06-17">Species</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/SpeciesEpithet or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/FirstE
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Subspecies-2003-06-17">Subspecies</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/SubspeciesEpithet or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/Sec
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#ScientificNameAuthor-2003-06-17">ScientificNameAuthor</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/ParentheticalAuthorTeamAndYear + DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/B
<TR><TD>Specimen.name</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#IdentifiedBy-2003-06-17">IdentifiedBy</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/Identifiers/IdentifiersText</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#IdentificationModifier-2003-06-17">IdentificationModifier</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/IdentificationQualifier</TD>

<TR><TD>Specimen.dateIdentified (as yyyy-mm-dd)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#YearIdentified-2003-06-17">YearIdentified</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Identifications/Identification/Date/DateText</TD>
<TR><TD>Specimen.dateIdentified (as yyyy-mm-dd)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MonthIdentified-2003-06-17">MonthIdentified</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Identifications/Identification/Date/DateText</TD>
<TR><TD>Specimen.dateIdentified (as yyyy-mm-dd)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DayIdentified-2003-06-17">DayIdentified</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Identifications/Identification/Date/DateText</TD>
<TR><TD>Specimen.typeStatus</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#TypeStatus-2003-06-17">TypeStatus</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/NomenclaturalTypeDesignations/NomenclaturalTypeText</TD>
<TR><TD>Specimen.sex</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Sex-2003-06-17">Sex</a></TD>	<TD>DataSets/DataSet/Units/Unit/Sex</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Preparations-2003-06-17">Preparations</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/Preparations/PreparationsText</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Tissues-2003-06-17">Tissues</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/Preparations/PreparationsText</TD>
<TR><TD>Specimen.individualCount</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#IndividualCount-2003-06-17">IndividualCount</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/SiteMeasurementOrFact/MeasurementOrFactAtomised/LowerValue</TD>
<TR><TD>Specimen.developmentalStage</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#AgeClass-2003-06-17">AgeClass</a></TD>	<TD>{DataSets/DataSet/Units/Unit/ZoologicalUnit/PhasesOrStages/PhaseOrStage or DataSets/DataSet/Units/Unit/MycologicalUnit/MycologicalLifeStages/MycologicalLifeStage or DataSets/DataSet/Units/Unit/MycologicalUnit/MycologicalSexualStage}</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#GenBankNum-2003-06-17">GenBankNum</a></TD>	<TD>DataSets/DataSet/Units/Unit/Sequences/Sequence/ID-in-Database + constant</TD>

<TR><TD>Specimen.previousCatalogNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#OtherCatalogNumbers-2003-06-17">OtherCatalogNumbers</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/History/PreviousUnitsText</TD>
<TR><TD>Specimen.relatedCatalogItem</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#RelatedCatalogedItems-2003-06-17">RelatedCatalogedItems</a></TD>	<TD>DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitSourceInstitutionCode + DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitSourceName + DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitI
<TR><TD>Specimen.notes</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Remarks-2003-06-17">Remarks</a></TD>	<TD>DataSets/DataSet/Units/Unit/Notes</TD>
 
</TBODY></TABLE>

<br	/>
<br />

<TABLE border="1" cellspacing="1" cellpadding="3"><TBODY>
<TR><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>Morphbank Table.fieldname</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>Darwin Core 1.4 (Draft Standard)-<br />Darwin Core 1.4 Element</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>ABCD xpath</h3></TH></TR>

<TR><TD>http://morphbank.net/?id=[morphbankid]</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#GlobalUniqueIdentifier-2007-04-17">GlobalUniqueIdentifier</a></TD>	<TD>DataSets/DataSet/Units/Unit/UnitGUID</TD>
<TR><TD>BaseObject.dateLastModified</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DateLastModified-2007-04-17">DateLastModified</a></TD>	<TD>DataSets/DataSet/Units/Unit/DateLastEdited</TD>
<TR><TD>Specimen.basisOfRecord</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#BasisOfRecord-2007-04-17">BasisOfRecord</a></TD>	<TD>DataSets/DataSet/Units/Unit/RecordBasis</TD>

<TR><TD>Specimen.institutionCode</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#InstitutionCode-2007-04-17">InstitutionCode</a></TD>	<TD>DataSets/DataSet/Units/Unit/SourceInstitutionID</TD>
<TR><TD>Specimen.collectionCode</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CollectionCode-2007-04-17">CollectionCode</a></TD>	<TD>DataSets/DataSet/Units/Unit/SourceID</TD>
<TR><TD>Specimen.catalogNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CatalogNumber-2007-04-17">CatalogNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/UnitID</TD>
<TR><TD>Locality.informationWithheld</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#InformationWithheld-2007-04-17">InformationWithheld</a></TD>	<TD>DataSets/DataSet/Units/Unit/InformationWithheld</TD>
<TR><TD>Specimen.notes</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Remarks-2007-04-17">Remarks</a></TD>	<TD>DataSets/DataSet/Units/Unit/Notes</TD>

<TR><TD>Tree.scientificName</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#ScientificName-2007-04-17">ScientificName</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/FullScientificNameString</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#HigherTaxon-2007-04-17">HigherTaxon</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Kingdom-2007-04-17">Kingdom</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = regnum</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Phylum-2007-04-17">Phylum</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = phylum</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Class-2007-04-17">Class</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = classis</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Order-2007-04-17">Order</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = ordo</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Family-2007-04-17">Family</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = familia</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Genus-2007-04-17">Genus</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/GenusOrMonomial or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/Genus
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#SpecificEpithet-2007-04-17">SpecificEpithet</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/SpeciesEpithet or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/FirstE
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#InfraspecificRank-2007-04-17">InfraspecificRank</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/Rank</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#InfraspecificEpithet-2007-04-17">InfraspecificEpithet</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/SubspeciesEpithet or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/Sec
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#AuthorYearOfScientificName-2007-04-17">AuthorYearOfScientificName</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/ParentheticalAuthorTeamAndYear + DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/B
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#NomenclaturalCode-2007-04-17">NomenclaturalCode</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/Code</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#IdentificationQualifier-2007-04-17">IdentificationQualifier</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/IdentificationQualifier</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#HigherGeography-2007-04-17">HigherGeography</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Gathering/LocalityText or DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName}</TD>

<TR><TD>Locality.continent</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Continent-2007-04-17">Continent</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Continent</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#WaterBody-2007-04-17">WaterBody</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Water body</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#IslandGroup-2007-04-17">IslandGroup</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Island group</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Island-2007-04-17">Island</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Island</TD>
<TR><TD>Locality.country</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Country-2007-04-17">Country</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Country/Name</TD>

<TR><TD>Locality.state</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#StateProvince-2007-04-17">StateProvince</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= State or = Province (etc.)</TD>
<TR><TD>Locality.county</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#County-2007-04-17">County</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= County</TD>
<TR><TD>Locality.locality</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Locality-2007-04-17">Locality</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/AreaDetail</TD>
<TR><TD>Locality.minimumElevation</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MinimumElevationInMeters-2007-04-17">MinimumElevationInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactAtomised/LowerValue</TD>
<TR><TD>Locality.maximumElevation</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MaximumElevationInMeters-2007-04-17">MaximumElevationInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactAtomised/UpperValue</TD>

<TR><TD>Locality.minimumDepth</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MinimumDepthInMeters-2007-04-17">MinimumDepthInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactAtomised/LowerValue</TD>
<TR><TD>Locality.maximumDepth</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#MaximumDepthInMeters-2007-04-17">MaximumDepthInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactAtomised/UpperValue</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CollectingMethod-2007-04-17">CollectingMethod</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Method</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#ValidDistributionFlag-2007-04-17">ValidDistributionFlag</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/ValidDistributionFlag</TD>
<TR><TD>Specimen.earliestDateCollected (as yyyy-mm-dd 00:00:00)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EarliestDateCollected-2007-04-17">EarliestDateCollected</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>

<TR><TD>Specimen.latestDateCollected (as yyyy-mm-dd 00:00:00)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#LatestDateCollected-2007-04-17">LatestDateCollected</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/ISODateTimeEnd</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DayOfYear-2007-04-17">DayOfYear</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/DayNumberBegin</TD>
<TR><TD>Specimen.collectorName</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Collector-2007-04-17">Collector</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/GatheringAgents/GatheringAgentsText</TD>
<TR><TD>Specimen.sex</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Sex-2007-04-17">Sex</a></TD>	<TD>DataSets/DataSet/Units/Unit/Sex</TD>
<TR><TD>Specimen.developmentalStage</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#LifeStage-2007-04-17">LifeStage</a></TD>	<TD>DataSets/DataSet/Units/Unit/MycologicalUnit/MycologicalSexualStage or DataSets/DataSet/Units/Unit/MycologicalUnit/MycologicalLiveStages/MycologicalLiveStage (Note DwC spec uses "MycologicalLifeStage" or DataSets/DataSet/Units/Unit/ZoologicalUnit/Phase

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Attributes-2007-04-17">Attributes</a></TD>	<TD>DataSets/DataSet/Units/Unit/MeasurementsOrFacts</TD>
<TR><TD>http://morphbank.net/?id=[mbimageid]</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#ImageURL-2007-04-17">ImageURL</a></TD>	<TD>DataSets/DataSet/Units/Unit/MultimediaObjects</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#RelatedInformation-2007-04-17">RelatedInformation</a></TD>	<TD>DataSets/DataSet/Units/Unit/RecordURI</TD>

</TBODY></TABLE>

<br />
<br />

<TABLE border="1" cellspacing="1" cellpadding="3"><TBODY>
<TR><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>Morphbank Table.fieldname</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>Darwin Core 1.4 Curatorial extension -<br />Darwin Core 1.4 Curatorial Element</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>ABCD xpath</h3></TH></TR>

<TR><TD>Specimen.catalogNumber (varchar)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CatalogNumberNumeric-2007-04-17">CatalogNumberNumeric</a></TD>	<TD>DataSets/DataSet/Units/Unit/UnitIDNumeric</TD>
<TR><TD>Specimen.name</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#IdentifiedBy-2007-04-17">IdentifiedBy</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/Identifiers/IdentifiersText</TD>
<TR><TD>Specimen.dateIdentified (as yyyy-mm-dd 00:00:00)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DateIdentified-2007-04-17">DateIdentified</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/Date/DateText</TD>

<TR><TD>Specimen.collectionNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CollectorNumber-2007-04-17">CollectorNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/GatheringAgents/GatheringAgentsText</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#FieldNumber-2007-04-17">FieldNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Code</TD>
<TR><TD>Specimen.notes</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#FieldNotes-2007-04-17">FieldNotes</a></TD>	<TD>DataSets/DataSet/Units/Unit/FieldNotes</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimCollectingDate-2007-04-17">VerbatimCollectingDate</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/DateText</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimElevation-2007-04-17">VerbatimElevation</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactText</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimDepth-2007-04-17">VerbatimDepth</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactText</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Preparations-2007-04-17">Preparations</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/Preparations/PreparationsText</TD>
<TR><TD>Specimen.typeStatus</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#TypeStatus-2007-04-17">TypeStatus</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/NomenclaturalTypeDesignations/NomenclaturalTypeText</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#GenBankNumber-2007-04-17">GenBankNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/Sequences/Sequence/ID-in-Database + constant</TD>
<TR><TD>Specimen.previousCatalogNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#OtherCatalogNumbers-2007-04-17">OtherCatalogNumbers</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/History/PreviousUnitsText</TD>

<TR><TD>Specimen.relatedCatalogItem</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#RelatedCatalogedItems-2007-04-17">RelatedCatalogedItems</a></TD>	<TD>DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitSourceInstitutionCode + DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitSourceName + DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitI
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Disposition-2007-04-17">Disposition</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/Disposition</TD>
<TR><TD>Specimen.individualCount</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#IndividualCount-2007-04-17">IndividualCount</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/SiteMeasurementOrFact/MeasurementOrFactAtomised/LowerValue</TD>

</TBODY></TABLE>


<br />
<br	/>

<TABLE border="1" cellspacing="1" cellpadding="3"><TBODY>
<TR><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>Morphbank Table.fieldname</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>Darwin Core 1.4 Geospatial extension -<br />Darwin Core 1.4 Geospatial Element</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>ABCD xpath</h3></TH></TR>

<TR><TD>Locality.latitude</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DecimalLatitude-2007-04-17">DecimalLatitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/LatitudeDecimal</TD>
<TR><TD>Locality.longitude</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#DecimalLongitude-2007-04-17">DecimalLongitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/LongitudeDecimal</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#GeodeticDatum-2007-04-17">GeodeticDatum</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/SpatialDatum</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#CoordinateUncertaintyInMeters-2007-04-17">CoordinateUncertaintyInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/CoordinateErrorDistanceInMeters</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#PointRadiusSpatialFit-2007-04-17">PointRadiusSpatialFit</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/PointRadiusSpatialFit</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimCoordinates-2007-04-17">VerbatimCoordinates</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/CoordinatesText or DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesUTM/UTMText}</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimLatitude-2007-04-17">VerbatimLatitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/VerbatimLatitude</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimLongitude-2007-04-17">VerbatimLongitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/VerbatimLongitude</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#VerbatimCoordinateSystem-2007-04-17">VerbatimCoordinateSystem</a></TD>	<TD>(partly) DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesGrid/GridCellSystem</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#GeoreferenceProtocol-2007-04-17">GeoreferenceProtocol</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinateMethod</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#GeoreferenceSources-2007-04-17">GeoreferenceSources</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/GeoreferenceSources</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#GeoreferenceVerificationStatus-2007-04-17">GeoreferenceVerificationStatus</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/GeoreferenceVerificationStatus</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#GeoreferenceRemarks-2007-04-17">GeoreferenceRemarks</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/GeoreferenceRemarks</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#FootprintWKT-2007-04-17">FootprintWKT</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/FootprintWKT (ABCD v2.06b)</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#FootprintSpatialFit-2007-04-17">FootprintSpatialFit</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/FootprintSpatialFit (ABCD v2.06b)</TD>

</TBODY></TABLE>

<br />
<br	/>

<TABLE border="1" cellspacing="1" cellpadding="3"><TBODY>
<TR><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>Morphbank Table.fieldname</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>Darwin Core 1.4 Paleontology Extension -<br />Darwin Core 1.4 Paleontology Element</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>ABCD xpath</h3></TH></TR>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EarliestEonOrLowestEonothem-2005-07-03">EarliestEonOrLowestEonothem</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#LatestEonOrHighestEonothem-2005-07-03">LatestEonOrHighestEonothem</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EarliestEraOrLowestErathem-2005-07-03">EarliestEraOrLowestErathem</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#LatestEraOrHighestErathem-2005-07-03">LatestEraOrHighestErathem</a></TD>	<TD>not in ABCD</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EarliestPeriodOrLowestSystem-2005-07-03">EarliestPeriodOrLowestSystem</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#LatestPeriodOrHighestSystem-2005-07-03">LatestPeriodOrHighestSystem</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EarliestEpochOrLowestSeries-2005-07-03">EarliestEpochOrLowestSeries</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#LatestEpochOrHighestSeries-2005-07-03">LatestEpochOrHighestSeries</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#EarliestAgeOrLowestStage-2005-07-03">EarliestAgeOrLowestStage</a></TD>	<TD>not in ABCD</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#LatestAgeOrHighestStage-2005-07-03">LatestAgeOrHighestStage</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#LowestBiostratigraphicZone-2005-07-03">LowestBiostratigraphicZone</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#HighestBiostratigraphicZone-2005-07-03">HighestBiostratigraphicZone</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#LithostratigraphicTerms-2005-07-03">LithostratigraphicTerms</a></TD>	<TD>not in ABCD</TD>
<TR><TD>Locality.paleoGroup</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Group-2005-07-03">Group</a></TD>	<TD>not in ABCD</TD>

<TR><TD>Locality.paleoFormation</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Formation-2005-07-03">Formation</a></TD>	<TD>not in ABCD</TD>
<TR><TD>Locality.paleoMember</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Member-2005-07-03">Member</a></TD>	<TD>not in ABCD</TD>
<TR><TD>Locality.paleoBed</TD><TD><a href="http://rs.tdwg.org/dwc/terms/history/index.htm#Bed-2005-07-03">Bed</a></TD>	<TD>not in ABCD</TD>

</TBODY></TABLE>


<br />
<br />

<TABLE border="1" cellspacing="1" cellpadding="3"><TBODY>
<TR><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>Morphbank Table.fieldname</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>2.8 Standard Darwin Core (Recommended)<br />Darwin Core Recommended Term</h3></TH><TH colspan="1" align="left" bgcolor="#C5C2BA" ><h3>ABCD xpath</h3></TH></TR>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#Occurrence">Occurrence</a></TD>	<TD>DataSets/DataSet/Units/Unit</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#Event">Event</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering</TD>
<TR><TD>Locality.locality</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#dcterms:Location">dcterms:Location</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/LocalityText</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#GeologicalContext">GeologicalContext</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Stratigraphy</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#Identification">Identification</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#Taxon">Taxon</a></TD>	<TD>no simple equivalent in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#ResourceRelationship">ResourceRelationship</a></TD>	<TD>DataSets/DataSet/Units/Unit/Associations</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#MeasurementOrFact">MeasurementOrFact</a></TD>	<TD>Datasets/Dataset/Units/Unit/MeasurementsOrFacts or DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#dcterms:type">dcterms:type</a></TD>	<TD>DataSets/DataSet/Units/Unit/RecordBasis</TD>
<TR><TD>BaseObject.dateLastModified</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#dcterms:modified">dcterms:modified</a></TD>	<TD>DataSets/DataSet/Units/Unit/DateLastEdited</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#dcterms:language">dcterms:language</a></TD>	<TD>some ABCD elements have a datatype extended with a language attribute, no language element at the Unit level</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#dcterms:rights">dcterms:rights</a></TD>	<TD>DataSets/DataSet/Units/Unit/IPRStatements</TD>
<TR><TD>as Contributor from Morphbank userId</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#dcterms:rightsHolder">dcterms:rightsHolder</a></TD>	<TD>DataSets/DataSet/Units/Unit/Owner/Organisation/Name/Representation/Text or DataSets/DataSet/Units/Unit/Owner/Person/FullName or DataSets/DataSet/Metadata/Owners/Owner/Organisation/Name/Representation/Text or DataSets/DataSet/Metadata/Owners/Owner/Pers

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#dcterms:accessRights">dcterms:accessRights</a></TD>	<TD>DataSets/DataSet/Units/Unit/IPRStatements</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#dcterms:bibliographicCitation">dcterms:bibliographicCitation</a></TD>	<TD>DataSets/DataSet/Units/Unit/IPRStatements/Citations/Citation/Text</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#institutionID">institutionID</a></TD>	<TD>DataSets/DataSet/Units/Unit/SourceID</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#collectionID">collectionID</a></TD>	<TD>DataSets/DataSet/Units/Unit/SourceID</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#institutionCode">institutionCode</a></TD>	<TD>DataSets/DataSet/Units/Unit/SourceInstitutionID</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#collectionCode">collectionCode</a></TD>	<TD>DataSets/DataSet/Units/Unit/SourceID</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#datasetName">datasetName</a></TD>	<TD>DataSets/DataSet/Units/Unit/SourceID</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#ownerInstitutionCode">ownerInstitutionCode</a></TD>	<TD>not in ABCD</TD>
<TR><TD>Specimen.basisOfRecord</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#basisOfRecord">basisOfRecord</a></TD>	<TD>DataSets/DataSet/Units/Unit/RecordBasis</TD>
<TR><TD>Locality.informationWithheld</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#informationWithheld">informationWithheld</a></TD>	<TD>DataSets/DataSet/Units/Unit/InformationWithheld</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#dataGeneralizations">dataGeneralizations</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#dynamicProperties">dynamicProperties</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#datasetID">datasetID</a></TD>	<TD>DataSets/DataSet/DataSetGUID</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#occurrenceID">occurrenceID</a></TD>	<TD>DataSets/DataSet/Units/Unit/UnitGUID</TD>
<TR><TD>Specimen.catalogNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#catalogNumber">catalogNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/UnitID</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#occurrenceDetails">occurrenceDetails</a></TD>	<TD>DataSets/DataSet/Units/Unit/RecordURI</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#occurrenceRemarks">occurrenceRemarks</a></TD>	<TD>DataSets/DataSet/Units/Unit/Notes</TD>
<TR><TD>Specimen.collectionNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#recordNumber">recordNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/CollectorsFieldNumber</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#recordedBy">recordedBy</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/GatheringAgents/GatheringAgentsText</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#individualID">individualID</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/Result/TaxonIdentified/ScientificName/NameAtomised/Zoological/NamedIndividual or DataSets/DataSet/Units/Unit/ObservationUnit/ObservationUnitIdentifiers/ObservationUnitIdentifier or DataSets/Da

<TR><TD>Specimen.individualCount</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#individualCount">individualCount</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/SiteMeasurementOrFact/MeasurementOrFactAtomised/LowerValue</TD>
<TR><TD>Specimen.sex</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#sex">sex</a></TD>	<TD>DataSets/DataSet/Units/Unit/Sex</TD>
<TR><TD>Specimen.developmentalStage</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#lifeStage">lifeStage</a></TD>	<TD>DataSets/DataSet/Units/Unit/MycologicalUnit/MycologicalSexualStage or DataSets/DataSet/Units/Unit/MycologicalUnit/MycologicalLiveStages/MycologicalLiveStage (Note DwC spec uses "MycologicalLifeStage" or DataSets/DataSet/Units/Unit/ZoologicalUnit/Phase
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#reproductiveCondition">reproductiveCondition</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#behavior">behavior</a></TD>	<TD>not in ABCD</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#establishmentMeans">establishmentMeans</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/EstablishmentMeans</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#occurrenceStatus">occurrenceStatus</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#preparations">preparations</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/Preparations/PreparationsText</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#disposition">disposition</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/Disposition</TD>
<TR><TD>Specimen.previousCatalogNumber</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#otherCatalogNumbers">otherCatalogNumbers</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/History/PreviousUnitsText</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#previousIdentifications">previousIdentifications</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification with PreferredFlag = false</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#associatedMedia">associatedMedia</a></TD>	<TD>DataSets/DataSet/Units/Unit/MultimediaObjects</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#associatedReferences">associatedReferences</a></TD>	<TD>DataSets/DataSet/Units/Unit/UnitReferences</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#associatedOccurrences">associatedOccurrences</a></TD>	<TD>DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitSourceInstitutionCode + DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitSourceName + DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitI
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#associatedSequences">associatedSequences</a></TD>	<TD>DataSets/DataSet/Units/Unit/Sequences/Sequence/ID-in-Database + constant</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#associatedTaxa">associatedTaxa</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Synecology/AssociatedTaxa</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#eventID">eventID</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Code</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#samplingProtocol">samplingProtocol</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Method</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#samplingEffort">samplingEffort</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#eventDate">eventDate</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#eventTime">eventTime</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin and DataSets/DataSet/Units/Unit/Gathering/ISODateTimeEnd</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#startDayOfYear">startDayOfYear</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/DayNumberBegin</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#endDayOfYear">endDayOfYear</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/DayNumberEnd</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#year">year</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#month">month</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#day">day</a></TD>	<TD>accessible from DataSets/DataSet/Units/Unit/Gathering/ISODateTimeBegin</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#verbatimEventDate">verbatimEventDate</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/DateTime/DateText</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#habitat">habitat</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#fieldNumber">fieldNumber</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Code</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#fieldNotes">fieldNotes</a></TD>	<TD>DataSets/DataSet/Units/Unit/FieldNotes</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#eventRemarks">eventRemarks</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#locationID">locationID</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#higherGeographyID">higherGeographyID</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#higherGeography">higherGeography</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Gathering/LocalityText or DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName}</TD>
<TR><TD>Locality.continent</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#continent">continent</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Continent</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#waterBody">waterBody</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Water body</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#islandGroup">islandGroup</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Island group</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#island">island</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= Island</TD>
<TR><TD>Locality.country</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#country">country</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Country/Name</TD>
<TR><TD>Country.name</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#countryCode">countryCode</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Country/ISO3166Code</TD>

<TR><TD>Locality.state</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#stateProvince">stateProvince</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= State or = Province (etc.)</TD>
<TR><TD>Locality.county</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#county">county</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName with NamedAreas/NamedArea/AreaClass= County</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#municipality">municipality</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName</TD>
<TR><TD>Locality.locality</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#locality">locality</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#verbatimLocality">verbatimLocality</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/NamedAreas/NamedArea/AreaName</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#verbatimElevation">verbatimElevation</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactText</TD>
<TR><TD>Locality.minimumElevation</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#minimumElevationInMeters">minimumElevationInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactAtomised/LowerValue</TD>
<TR><TD>Locality.maximumElevation</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#maximumElevationInMeters">maximumElevationInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactAtomised/UpperValue</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#verbatimDepth">verbatimDepth</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactText</TD>

<TR><TD>Locality.minimumDepth</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#minimumDepthInMeters">minimumDepthInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactAtomised/LowerValue</TD>
<TR><TD>Locality.maximumDepth/TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#maximumDepthInMeters">maximumDepthInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrFactAtomised/UpperValue</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#minimumDistanceAboveSurfaceInMeters">minimumDistanceAboveSurfaceInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Height/MeasurementOrFactAtomised/LowerValue</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#maximumDistanceAboveSurfaceInMeters">maximumDistanceAboveSurfaceInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/Height/MeasurementOrFactAtomised/UpperValue</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#locationAccordingTo">locationAccordingTo</a></TD>	<TD>not in ABCD</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#locationRemarks">locationRemarks</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/AreaDetail</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#verbatimCoordinates">verbatimCoordinates</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/CoordinatesText or DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesUTM/UTMText}</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#verbatimLatitude">verbatimLatitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/VerbatimLatitude</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#verbatimLongitude">verbatimLongitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/VerbatimLongitude</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#verbatimCoordinateSystem">verbatimCoordinateSystem</a></TD>	<TD>(partly) DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesGrid/GridCellSystem</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#verbatimSRS">verbatimSRS</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#decimalLatitude">decimalLatitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/LatitudeDecimal</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#decimalLongitude">decimalLongitude</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/LongitudeDecimal</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#geodeticDatum">geodeticDatum</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/SpatialDatum</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#coordinateUncertaintyInMeters">coordinateUncertaintyInMeters</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLon/CoordinateErrorDistanceInMeters</TD>

<TR><TD>Locality.coordinatePrecision</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#coordinatePrecision">coordinatePrecision</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLong/ISOAccuracy or DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinatesLatLong/AccuracyStatement</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#pointRadiusSpatialFit">pointRadiusSpatialFit</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/PointRadiusSpatialFit</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#footprintWKT">footprintWKT</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/FootprintWKT (ABCD v2.06b)</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#footprintSRS">footprintSRS</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#footprintSpatialFit">footprintSpatialFit</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/FootprintSpatialFit (ABCD v2.06b)</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#georeferencedBy">georeferencedBy</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#georeferenceProtocol">georeferenceProtocol</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/CoordinateMethod</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#georeferenceSources">georeferenceSources</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/GeoreferenceSources</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#georeferenceVerificationStatus">georeferenceVerificationStatus</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/GeoreferenceVerificationStatus</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#georeferenceRemarks">georeferenceRemarks</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteCoordinateSets/SiteCoordinates/GeoreferenceRemarks</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#geologicalContextID">geologicalContextID</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#earliestEonOrLowestEonothem">earliestEonOrLowestEonothem</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#latestEonOrHighestEonothem">latestEonOrHighestEonothem</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#earliestEraOrLowestErathem">earliestEraOrLowestErathem</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#latestEraOrHighestErathem">latestEraOrHighestErathem</a></TD>	<TD>not in ABCD</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#earliestPeriodOrLowestSystem">earliestPeriodOrLowestSystem</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#latestPeriodOrHighestSystem">latestPeriodOrHighestSystem</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#earliestEpochOrLowestSeries">earliestEpochOrLowestSeries</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#latestEpochOrHighestSeries">latestEpochOrHighestSeries</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#earliestAgeOrLowestStage">earliestAgeOrLowestStage</a></TD>	<TD>not in ABCD</TD>

<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#latestAgeOrHighestStage">latestAgeOrHighestStage</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#lowestBiostratigraphicZone">lowestBiostratigraphicZone</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#highestBiostratigraphicZone">highestBiostratigraphicZone</a></TD>	<TD>not in ABCD</TD>
<TR><TD>not in mb</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#lithostratigraphicTerms">lithostratigraphicTerms</a></TD>	<TD>not in ABCD</TD>
<TR><TD>Locality.paleoGroup</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#group">group</a></TD>	<TD>not in ABCD</TD>

<TR><TD>Locality.paleoFormation</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#formation">formation</a></TD>	<TD>not in ABCD</TD>
<TR><TD>Locality.paleoMember</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#member">member</a></TD>	<TD>not in ABCD</TD>
<TR><TD>Locality.paleoBed</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#bed">bed</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#identificationID">identificationID</a></TD>	<TD>not in ABCD</TD>
<TR><TD>Specimen.name</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#identifiedBy">identifiedBy</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/Identifiers/IdentifiersText</TD>

<TR><TD>Specimen.dateIdentified (as yyyy-mm-dd 00:00:00)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#dateIdentified">dateIdentified</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/Date/DateText</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#identificationReferences">identificationReferences</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/References</TD>
<TR><TD>Specimen.comment</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#identificationRemarks">identificationRemarks</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/Notes</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#identificationQualifier">identificationQualifier</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/IdentificationQualifier</TD>
<TR><TD>Specimen.typeStatus</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#typeStatus">typeStatus</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/NomenclaturalTypeDesignations/NomenclaturalTypeText</TD>

<TR><TD>Morphbank/ITIS tsn</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#taxonID">taxonID</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#scientificNameID">scientificNameID</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#acceptedNameUsageID">acceptedNameUsageID</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#parentNameUsageID">parentNameUsageID</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#originalNameUsageID">originalNameUsageID</a></TD>	<TD>not in ABCD</TD>

<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#nameAccordingToID">nameAccordingToID</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#namePublishedInID">namePublishedInID</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#taxonConceptID">taxonConceptID</a></TD>	<TD>not in ABCD</TD>
<TR><TD>Tree.scientificName (retrieve by tsn)</TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#scientificName">scientificName</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/FullScientificNameString</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#acceptedNameUsage">acceptedNameUsage</a></TD>	<TD>not in ABCD</TD>

<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#parentNameUsage">parentNameUsage</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#originalNameUsage">originalNameUsage</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#nameAccordingTo">nameAccordingTo</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#namePublishedIn">namePublishedIn</a></TD>	<TD>DataSets/DataSet/Units/Unit/SpecimenUnit/NomenclaturalTypeDesignations/NomenclaturalTypeDesignation/NomenclaturalReference/TitleCitation</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#higherClassification">higherClassification</a></TD>	<TD>not in ABCD</TD>

<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#kingdom">kingdom</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = regnum</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#phylum">phylum</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = phylum</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#class">class</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = classis</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#order">order</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = ordo</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#family">family</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/HigherTaxa/HigherTaxon/HigherTaxonName with HigherTaxa/HigherTaxon/HigherTaxonRank = familia</TD>

<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#genus">genus</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/GenusOrMonomial or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/Genus
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#subgenus">subgenus</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Zoological/Subgenus</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#specificEpithet">specificEpithet</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/SpeciesEpithet or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/FirstE
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#infraspecificEpithet">infraspecificEpithet</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/SubspeciesEpithet or DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/Sec
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#taxonRank">taxonRank</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Botanical/Rank</TD>

<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#verbatimTaxonRank">verbatimTaxonRank</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#scientificNameAuthorship">scientificNameAuthorship</a></TD>	<TD>{DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/Bacterial/ParentheticalAuthorTeamAndYear + DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/ScientificName/NameAtomised/B
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#vernacularName">vernacularName</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#nomenclaturalCode">nomenclaturalCode</a></TD>	<TD>DataSets/DataSet/Units/Unit/Identifications/Identification/TaxonIdentified/Code</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#taxonomicStatus">taxonomicStatus</a></TD>	<TD>not in ABCD</TD>

<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#nomenclaturalStatus">nomenclaturalStatus</a></TD>	<TD>(DataSets/DataSet/Units/Unit/SpecimenUnit/NomenclaturalTypeDesignations/NomenclaturalTypeDesignation/NomenclaturalReference/TitleCitation)
pro parte</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#taxonRemarks">taxonRemarks</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#resourceRelationshipID">resourceRelationshipID</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#resourceID">resourceID</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#relatedResourceID">relatedResourceID</a></TD>	<TD>DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitSourceInstitutionCode + DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitSourceName + DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociatedUnitI

<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#relationshipOfResource">relationshipOfResource</a></TD>	<TD>DataSets/DataSet/Units/Unit/Associations/UnitAssociation/AssociationType</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#relationshipAccordingTo">relationshipAccordingTo</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#relationshipEstablishedDate">relationshipEstablishedDate</a></TD>	<TD>not in ABCD</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#relationshipRemarks">relationshipRemarks</a></TD>	<TD>DataSets/DataSet/Units/Unit/Associations/UnitAssociation/Comments</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#measurementID">measurementID</a></TD>	<TD>not in ABCD</TD>

<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#measurementType">measurementType</a></TD>	<TD>DataSets/DataSet/Units/Unit/MeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/Parameter or DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/Parameter</TD>
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#measurementValue">measurementValue</a></TD>	<TD>DataSets/DataSet/Units/Unit/MeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/LowerValue or DataSets/DataSet/Units/Unit/MeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/UpperValue or DataSets/DataSet/Units/Unit/Gathering/S
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#measurementAccuracy">measurementAccuracy</a></TD>	<TD>DataSets/DataSet/Units/Unit/MeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/Accuracy or DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/SiteMeasurementOrFact/MeasurementOrFactAtomised/Accuracy or DataSets/DataSet/Units/Unit/Gather
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#measurementUnit">measurementUnit</a></TD>	<TD>DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/UnitOfMeasurement or DataSets/DataSet/Units/Unit/Gathering/SiteMeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/UnitOfMeasurement<
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#measurementDeterminedDate">measurementDeterminedDate</a></TD>	<TD>DataSets/DataSet/Units/Unit/MeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/MeasurementDateTime or DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactAtomised/MeasurementDateTime or DataSets/DataSet/Units/Unit/Gathering/De

<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#measurementDeterminedBy">measurementDeterminedBy</a></TD>	<TD>DataSets/DataSet/Units/Unit/MeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/MeasuredBy or DataSets/DataSet/Units/Unit/Gathering/Altitude/MeasurementOrFactAtomised/MeasuredBy or DataSets/DataSet/Units/Unit/Gathering/Depth/MeasurementOrF
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#measurementMethod">measurementMethod</a></TD>	<TD>/DataSets/DataSet/Units/Unit/MeasurementsOrFacts/MeasurementOrFact/MeasurementOrFactAtomised/Method or /DataSets/DataSet/Units/Unit/Gathering/Biotope/MeasurementsOrFacts/MeasurementOrFactAtomised/Method or /DataSets/DataSet/Units/Unit/Gathering/SiteMe
<TR><TD></TD><TD><a href="http://rs.tdwg.org/dwc/terms/index.htm#measurementRemarks">measurementRemarks</a></TD>	<TD>not in ABCD</TD>

</TBODY></TABLE>
<br />
<br />
<p>The Darwin Core - ABCD Mapping above comes from the following <a href="http://rs.tdwg.org/dwc/terms/history/dwctoabcd/index.htm">Biodiversity Information Standards (TDWG)</a> page.
</p>

	        <br />
			<br />
			<div id=footerRibbon></div>
			<table align="right">
<td><a href="<?php echo $config->domain; ?>About/Manual/gettingStarted.php" class="button smallButton"><div>Next</div></a></td>
<td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
</table>
			</div>
		
			<?php
//Finish with end of HTML	
finishHtml();
?>	

