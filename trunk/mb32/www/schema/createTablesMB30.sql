xxx command to skip the error at the beginning;
-- --------------------------------------------------------

--
-- Table structure for table 'Annotation'
--

CREATE  TABLE  Annotation (
	id int(11) NOT NULL,
	objectId int(11) NOT NULL, 
	objectTypeId varchar(50) NOT NULL, 
	typeAnnotation varchar(25) NOT NULL, 
	xLocation int(11), 
	yLocation int(11), 
	areaHeight int(11), 
	areaWidth int(11), 
	areaRadius int(11), 
	annotationQuality enum('0', '1', '2', '3', '4', '5'), 
	title varchar(50), 
	keywords varchar(40), 
	`comment` text, 
	xmlData text, 
	externalURL varchar(255), 
	annotationLabel varchar(25), 
	annotationMarkup varchar(25), 
	PRIMARY KEY (id), 
	KEY annotObjfk (objectId)
) ENGINE=InnoDB ;

-- --------------------------------------------------------
--
-- Table structure for table 'AnnotationType'
--

CREATE  TABLE  AnnotationType (
	annotationType varchar(25) NOT NULL default '0', 
	annotationSchema text, 
	PRIMARY KEY (annotationType)
) ENGINE=InnoDB ;

-- --------------------------------------------------------

--
-- Table structure for table 'BaseObject'
--

CREATE  TABLE  BaseObject (
	id int(11) NOT NULL, 
	userId int(11) NOT NULL, 
	groupId int(11) NOT NULL, 
	dateCreated datetime NOT NULL, 
	dateLastModified datetime NOT NULL, 
	dateToPublish datetime NOT NULL, 
	objectTypeId varchar(50) NOT NULL default '', 
	`name` varchar(64), 
	description varchar(255), 
	submittedBy int(11) NOT NULL, 
	objectLogo varchar(25), 
	keywords text, 
	summaryHTML text, 
	thumbURL varchar(30), 
	published varchar(15) NOT NULL default 'private_mb', 
	geolocated tinyint(1), 
	imageAltText text, 
	xmlKeywords text, 
	PRIMARY KEY (id), 
	KEY userId (userId), 
	KEY groupId (groupId), 
	KEY baseSubmittedByfk (submittedBy), 
	KEY dateLastModified (dateLastModified)
) ENGINE=InnoDB ;

-- --------------------------------------------------------

--
-- Table structure for table 'BasisOfRecord'
--

CREATE  TABLE  BasisOfRecord (
	description varchar(64) default 'Not provided', 
	`name` char(2) NOT NULL, 
	PRIMARY KEY (`name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'CharacterState'
--

CREATE  TABLE  CharacterState (
	id int(11) NOT NULL, 
	charStateValue varchar(32) NOT NULL, 
	PRIMARY KEY (id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Collection'
--

CREATE  TABLE  Collection (
	id int(11) NOT NULL, 
	userId int(11) NOT NULL, 
	groupId int(11) NOT NULL, 
	`name` varchar(50) NOT NULL default 'New collection', 
	PRIMARY KEY (id), 
	KEY colluserfk (userId), 
	KEY collgroupfk (groupId)
) ENGINE=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'CollectionObjects'
--

CREATE  TABLE  CollectionObjects (
	identifier int(11) NOT NULL auto_increment, 
	collectionId int(11) NOT NULL, 
	objectId int(11) NOT NULL, 
	objectOrder int(11), 
	objectTypeId varchar(50), 
	objectRole varchar(128), 
	objectTitle varchar(25), 
	startSubCollection tinyint(1), 
	parentId varchar(18), 
	PRIMARY KEY (identifier), 
	KEY collectionObjMain (collectionId, objectId), 
	KEY collectionObjOrder (collectionId, objectOrder), 
	KEY collObjObjfk (objectId), 
	KEY collObjCollfk (collectionId)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'ContinentOcean'
--

CREATE  TABLE  ContinentOcean (
	description varchar(64), 
	`name` char(2) NOT NULL, 
	PRIMARY KEY (`name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Country'
--

CREATE  TABLE  Country (
	continentOcean char(2) NOT NULL, 
	description varchar(128) NOT NULL default 'Not provided', 
	`name` char(2) NOT NULL, 
	PRIMARY KEY (`name`), 
	KEY continentId (continentOcean)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'DeterminationAnnotation'
--

CREATE  TABLE  DeterminationAnnotation (
	annotationId int(11) NOT NULL, 
	specimenId int(11) NOT NULL, 
	tsnId bigint(20) NOT NULL, 
	rankId smallint(6) NOT NULL, 
	kingdomId int(11) NOT NULL, 
	rankName varchar(140), 
	typeDetAnnotation varchar(10), 
	sourceOfId varchar(128), 
	collectionId int(11) NOT NULL, 
	materialsUsedInId varchar(25), 
	prefix varchar(25), 
	suffix varchar(25), 
	resourcesused varchar(128), 
	altTaxonName varchar(35), 
	PRIMARY KEY (annotationId), 
	KEY DetAnnSpecimenIdfk (specimenId), 
	KEY DetAnntsnIdfk (tsnId), 
	KEY DetAnnrankIdfk (rankId), 
	KEY DetAnnkingdomIdfk (kingdomId)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'DevelopmentalStage'
--

CREATE  TABLE  DevelopmentalStage (
	`name` varchar(128) NOT NULL default 'Not provided', 
	description varchar(128) default 'Not provided', 
	PRIMARY KEY (`name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'ExternalLinkObject'
--

CREATE  TABLE  ExternalLinkObject (
	linkId int(10) unsigned NOT NULL auto_increment,
	mbId int(11) NOT NULL, 
	extLinkTypeId int(5),
	label varchar(64), 
	urlData varchar(255), 
	description varchar(128),
	externalId varchar(512) charset latin1,
	PRIMARY KEY (linkId),
	UNIQUE KEY ExternalIdUnique (externalId),
	KEY baseObjectFk (mbId),
	KEY extTypeFk (extLinkTypeId),
	KEY ExtLinkObjExtLinkTypefk (extLinkTypeId)
) ENGINE=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'ExternalLinkType'
--

CREATE  TABLE  ExternalLinkType (
	linkTypeId int(5) NOT NULL auto_increment, 
	`name` varchar(128) NOT NULL default '',
	description varchar(255), 
	PRIMARY KEY (linkTypeId)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Form'
--

CREATE  TABLE  Form (
	`name` varchar(128) NOT NULL, 
	description varchar(128), 
	PRIMARY KEY (`name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Geolocated'
--

CREATE  TABLE  Geolocated (
	id int(11) NOT NULL, 
	PRIMARY KEY (id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Groups'
--

CREATE  TABLE  Groups (
	id int(11) NOT NULL, 
	groupName varchar(128) NOT NULL, 
	tsn bigint(20), 
	groupManagerId int(11), 
	`status` tinyint(1), 
	dateCreated datetime, 
	PRIMARY KEY (id), 
	UNIQUE KEY uniquegroupName (groupName), 
	KEY groupstsnfk (tsn), 
	KEY groupsGrpMngIdfk (groupManagerId)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'History'
--

CREATE  TABLE  History (
	id int(11) NOT NULL, 
	userId int(11) NOT NULL, 
	groupId int(11) NOT NULL, 
	dateModified datetime, 
	modifiedFrom text NOT NULL, 
	modifiedTo text NOT NULL, 
	tableName varchar(30) NOT NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Image'
--

CREATE  TABLE  Image (
	id int(11) NOT NULL, 
	userId int(11), 
	groupId int(11), 
	dateToPublish date, 
	specimenId int(11), 
	viewId int(11), 
	imageHeight int(11), 
	imageWidth int(11), 
	resolution int(11), 
	magnification float, 
	imageType varchar(8), 
	accessNum int(18), 
	copyrightText varchar(255), 
	originalFileName varchar(127), 
	creativeCommons text, 
	photographer text, 
	PRIMARY KEY (id), 
	KEY imgspecimentfk (specimenId), 
	KEY imgviewfk (viewId), 
	KEY imgaccessnumind (accessNum), 
	KEY imguserfk (userId), 
	KEY imggroupfk (groupId)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'ImagingPreparationTechnique'
--

CREATE  TABLE  ImagingPreparationTechnique (
	`name` varchar(128) NOT NULL, 
	description varchar(128), 
	PRIMARY KEY (`name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'ImagingTechnique'
--

CREATE  TABLE  ImagingTechnique (
	`name` varchar(128) NOT NULL, 
	description varchar(128), 
	PRIMARY KEY (`name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'ITISCounter'
--

CREATE  TABLE  ITISCounter (
	counter int(11) NOT NULL, 
	PRIMARY KEY (counter)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Keywords'
--

CREATE  TABLE  Keywords (
	id int(11) NOT NULL, 
	userId int(11), 
	groupId int(11), 
	dateToPublish datetime, 
	objectTypeId varchar(50), 
	keywords text,
	submittedBy int(11), 
	geolocated tinyint(1), 
	xmlKeywords text, 
	PRIMARY KEY (id), 
	KEY objectTypeId (objectTypeId), 
	FULLTEXT KEY keywords (keywords)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table 'Kingdoms'
--

CREATE  TABLE  Kingdoms (
	kingdom_id int(11) NOT NULL default '0', 
	kingdom_name char(10) NOT NULL default '', 
	update_date date, 
	PRIMARY KEY (kingdom_id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Locality'
--

CREATE  TABLE  Locality (
	id int(11) NOT NULL, 
	continentOcean varchar(128), 
	country varchar(128), 
	locality text,
	latitude double, 
	longitude double, 
	coordinatePrecision int(11), 
	minimumElevation smallint(6), 
	maximumElevation smallint(6), 
	minimumDepth smallint(6), 
	maximumDepth smallint(6), 
	imagesCount bigint(20) default '0', 
	county varchar(128), 
	state varchar(128), 
	paleoGroup varchar(128), 
	paleoFormation varchar(128), 
	paleoMember varchar(128), 
	paleoBed varchar(128), 
	continent varchar(128), 
	ocean varchar(128), 
	informationWithheld text, 
	PRIMARY KEY (id), 
	KEY locationctryfk (country), 
	KEY locationoceanfk (continentOcean)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Matrix'
--

CREATE  TABLE  Matrix (
	id int(11) NOT NULL, 
	numRows int(11), 
	numChars int(11), 
	gap char(5) default '-', 
	missing char(5) default '?', 
	PRIMARY KEY (id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'MatrixCell'
--

CREATE  TABLE  MatrixCell (
	id int(11) NOT NULL, 
	matrixId int(11) NOT NULL, 
	rowNum int(5), 
	columnNum int(5), 
	charStateList text, 
	`value` varchar(45), 
	PRIMARY KEY (id), 
	KEY MatrixCell_ibfk_1 (matrixId)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'MatrixCellValue'
--

CREATE  TABLE  MatrixCellValue (
	id int(11) NOT NULL auto_increment, 
	matrixId int(11), 
	`row` int(11) NOT NULL, 
	col int(11) NOT NULL, 
	`value` varchar(12) NOT NULL, 
	PRIMARY KEY (id), 
	KEY Index_3 (matrixId, `row`, col)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'MbCharacter'
--

CREATE  TABLE  MbCharacter (
	id int(11) NOT NULL, 
	label varchar(8), 
	characterNumber varchar(32), 
	discrete tinyint(1) NOT NULL, 
	ordered tinyint(1), 
	publicationId int(11), 
	pubComment text, 
	PRIMARY KEY (id), 
	KEY characterpubfk (publicationId)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'MBTeam'
--

CREATE  TABLE  MBTeam (
	id int(8) NOT NULL default '0', 
	fname varchar(20), 
	lname varchar(40), 
	category varchar(40), 
	categoryOrder int(2), 
	peopleOrder int(2), 
	image_name varchar(80), 
	telephone varchar(20), 
	office varchar(40), 
	email varchar(40) NOT NULL default '', 
	web varchar(60), 
	description longtext,
	notes longtext
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'MirrorInfo'
--

CREATE  TABLE  MirrorInfo (
	serverId int(5) NOT NULL default '1', 
	imageId int(11) NOT NULL default '1', 
	PRIMARY KEY (serverId, imageId)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'MissingImages'
--

CREATE  TABLE  MissingImages (
	id int(11) NOT NULL, 
	accessNum int(11), 
	PRIMARY KEY (id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'News'
--

CREATE  TABLE  News (
	id int(11) NOT NULL, 
	title varchar(64), 
	body text,
	image varchar(64), 
	imageText varchar(128), 
	dateCreated datetime, 
	`status` tinyint(1), 
	PRIMARY KEY (id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Otu'
--

CREATE  TABLE  Otu (
	id int(11) NOT NULL, 
	label varchar(32) NOT NULL, 
	PRIMARY KEY (id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Publication'
--

CREATE  TABLE  Publication (
	id int(11) NOT NULL, 
	doi varchar(128), 
	publicationType varchar(18), 
	address varchar(64), 
	annote varchar(255), 
	author varchar(128), 
	publicationTitle varchar(256), 
	chapter varchar(256), 
	edition varchar(64), 
	editor varchar(256), 
	howPublished varchar(128), 
	institution varchar(128), 
	`key` varchar(64) , 
	`month` enum('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'), 
	`day` tinyint(5), 
	note tinytext ,
	number varchar(11), 
	organization varchar(128), 
	pages varchar(64), 
	publisher varchar(128), 
	school varchar(128), 
	series varchar(128), 
	title varchar(256), 
	volume varchar(11), 
	`year` varchar(10), 
	isbn varchar(64), 
	issn varchar(64), 
	PRIMARY KEY (id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'RecentlyModified'
--

CREATE  TABLE  RecentlyModified (
	id int(11) NOT NULL, 
	dateLastModified datetime, 
	objectTypeId varchar(156), 
	PRIMARY KEY (id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'RecentlyModifiedTemp'
--

CREATE  TABLE  RecentlyModifiedTemp (
	id int(11), 
	dateLastModified datetime, 
	objectTypeId varchar(156), 
	dependentTypeId varchar(156) 
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'sequence'
--

CREATE  TABLE  sequence (
	SEQ_NAME varchar(50), 
	SEQ_COUNT decimal(15, 0) 
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'ServerInfo'
--

CREATE  TABLE  ServerInfo (
	serverId int(5) NOT NULL auto_increment, 
	url varchar(128) NOT NULL default 'http://morphbank.net/', 
	logo varchar(128), 
	contact varchar(128), 
	admin int(11) NOT NULL, 
	mirrorGroup int(11) NOT NULL, 
	dateCreated date NOT NULL, 
	updatedDate date NOT NULL, 
	basePath varchar(128) NOT NULL default '/ftp', 
	login varchar(41) NOT NULL default 'test', 
	passwd varchar(41) NOT NULL default 'test', 
	port int(5) NOT NULL, 
	imageURL varchar(128) NOT NULL, 
	locality varchar(128), 
	tsns text NOT NULL, 
	PRIMARY KEY (serverId), 
	KEY MirrorGroupfk (mirrorGroup), 
	KEY ServerUser (admin)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Sex'
--

CREATE  TABLE  Sex (
	`name` varchar(128) NOT NULL default 'Not provided', 
	description varchar(128) NOT NULL default 'Not provided', 
	PRIMARY KEY (`name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Spam'
--

CREATE  TABLE  Spam (
	id int(11) NOT NULL auto_increment, 
	`code` varchar(8), 
	graphic varchar(30), 
	PRIMARY KEY (id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Specimen'
--

CREATE  TABLE  Specimen (
	id int(11) NOT NULL, 
	basisOfRecordId char(2), 
	sex varchar(128), 
	form varchar(128), 
	developmentalStage varchar(128), 
	preparationType varchar(255), 
	individualCount int(11), 
	tsnId bigint(20), 
	typeStatus varchar(64), 
	`name` text,
	dateIdentified datetime, 
	`comment` text,
	institutionCode varchar(128), 
	collectionCode varchar(128), 
	catalogNumber varchar(128), 
	previousCatalogNumber varchar(128), 
	relatedCatalogItem varchar(128), 
	relationshipType varchar(128), 
	collectionNumber varchar(128), 
	collectorName text, 
	dateCollected datetime, 
	localityId int(11), 
	notes text,
	taxonomicNames text,
	imagesCount bigint(20) default '0', 
	standardImageId int(11), 
	ocr text, 
	barCode varchar(45), 
	labelData text, 
	earliestDateCollected datetime, 
	latestDateCollected datetime, 
	PRIMARY KEY (id), 
	KEY specsexfk (sex), 
	KEY specformfk (form), 
	KEY specdstgfk (developmentalStage), 
	KEY spectsnfk (tsnId), 
	KEY speclocfk (localityId), 
	KEY spectstfk (typeStatus), 
	KEY specbofrfk (basisOfRecordId)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'SpecimenPart'
--

CREATE  TABLE  SpecimenPart (
	`name` varchar(255) NOT NULL, 
	description varchar(128) NOT NULL default 'Not provided', 
	PRIMARY KEY (`name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'synonym_links'
--

CREATE  TABLE  synonym_links (
	tsn int(11) NOT NULL default '0', 
	tsn_accepted int(11) NOT NULL default '0', 
	update_date date NOT NULL, 
	KEY syntsnfk (tsn), 
	KEY syntsnacceptedfk (tsn_accepted)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Taxa'
--

CREATE  TABLE  Taxa (
	id int(11) NOT NULL auto_increment, 
	boId int(11), 
	tsn bigint(20), 
	scientificName text NOT NULL, 
	taxon_author_id int(11), 
	taxon_author_name varchar(100), 
	`status` varchar(12), 
	parent_tsn bigint(20), 
	parent_name text, 
	kingdom_id smallint(6), 
	kingdom_name varchar(10), 
	rank_id smallint(6), 
	rank_name varchar(15), 
	imagesCount bigint(20) default '0', 
	nameType varchar(32), 
	nameSource varchar(64), 
	publicationId int(11), 
	userId int(11), 
	groupId int(11), 
	dateToPublish datetime NOT NULL, 
	keywords text,
	objectTypeId varchar(50), 
	PRIMARY KEY (id), 
	UNIQUE KEY tsn (tsn), 
	UNIQUE KEY boId (boId), 
	FULLTEXT KEY keywords (keywords)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table 'Taxa1'
--

CREATE  TABLE  Taxa1 (
	id int(11) NOT NULL auto_increment, 
	boId int(11), 
	tsn bigint(20), 
	scientificName text NOT NULL, 
	taxon_author_id int(11), 
	taxon_author_name varchar(100), 
	`status` varchar(12), 
	parent_tsn bigint(20), 
	parent_name text, 
	kingdom_id smallint(6), 
	kingdom_name varchar(10), 
	rank_id smallint(6), 
	rank_name varchar(15), 
	imagesCount bigint(20) default '0', 
	nameType varchar(32), 
	nameSource varchar(64), 
	publicationId int(11), 
	userId int(11), 
	groupId int(11), 
	dateToPublish datetime NOT NULL, 
	keywords text,
	objectTypeId varchar(50), 
	PRIMARY KEY (id), 
	FULLTEXT KEY keywords (keywords)
) ENGINE=MyISAM;

-- --------------------------------------------------------

--
-- Stand-in structure for view 'Taxon'
--
CREATE  TABLE  Taxon (
`tsn` bigint(20)
, `scientificName` text
, `kingdom` char(10)
, `rank` char(15)
, `taxonAuthorId` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table 'TaxonAuthors'
--

CREATE  TABLE  TaxonAuthors (
	taxon_author_id int(11) NOT NULL, 
	taxon_author varchar(100) NOT NULL, 
	update_date datetime NOT NULL, 
	kingdom_id smallint(6) NOT NULL, 
	PRIMARY KEY (taxon_author_id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Stand-in structure for view 'TaxonBranch'
--
CREATE  TABLE  TaxonBranch (
`child` bigint(20)
, tsn bigint(20)
, rankId smallint(6)
, kingdomId smallint(6)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view 'TaxonBranchNode'
--
CREATE  TABLE  TaxonBranchNode (
`child` bigint(20)
, tsn bigint(20)
, scientificName text
, kingdom char(10)
, rank char(15)
);
-- --------------------------------------------------------

--
-- Table structure for table 'TaxonConcept'
--

CREATE  TABLE  TaxonConcept (
	id int(11) NOT NULL, 
	tsn bigint(20) NOT NULL, 
	nameSpace varchar(32), 
	`status` varchar(32), 
	PRIMARY KEY (id), 
	KEY tsn (tsn)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'TaxonomicUnits'
--

CREATE  TABLE  TaxonomicUnits (
	tsn bigint(20) NOT NULL default '0', 
	unit_ind1 char(1), 
	unit_name1 varchar(35) NOT NULL default '', 
	unit_ind2 char(1), 
	unit_name2 varchar(35), 
	unit_ind3 varchar(7), 
	unit_name3 varchar(35), 
	unit_ind4 varchar(7), 
	unit_name4 varchar(35), 
	unnamed_taxon_ind char(1), 
	`usage` varchar(12) NOT NULL default '', 
	unaccept_reason varchar(50), 
	credibility_rtng varchar(40) NOT NULL default '', 
	completeness_rtng varchar(10), 
	currency_rating varchar(7), 
	phylo_sort_seq smallint(6), 
	initial_time_stamp datetime NOT NULL, 
	parent_tsn bigint(20), 
	taxon_author_id int(11), 
	hybrid_author_id int(11), 
	kingdom_id int(11) NOT NULL default '0', 
	rank_id smallint(6) NOT NULL default '0', 
	update_date date NOT NULL, 
	uncertain_prnt_ind char(3), 
	PRIMARY KEY (tsn), 
	KEY parttsntsnfk (parent_tsn), 
	KEY kingdomfk (kingdom_id), 
	KEY rankidfk (rank_id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'TaxonUnitTypes'
--

CREATE  TABLE  TaxonUnitTypes (
	kingdom_id int(11) NOT NULL default '0', 
	rank_id smallint(6) NOT NULL default '0', 
	rank_name char(15) NOT NULL default '', 
	dir_parent_rank_id smallint(6) NOT NULL default '0', 
	req_parent_rank_id smallint(6) NOT NULL default '0', 
	update_date date NOT NULL, 
	KEY rank_id (rank_id), 
	KEY rankIdKey (rank_id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Tree'
--

CREATE  TABLE  Tree (
	tsn bigint(20) NOT NULL default '0', 
	unit_ind1 char(1) default '', 
	unit_name1 varchar(35) default '', 
	unit_ind2 char(1) default '', 
	unit_name2 varchar(35) default '', 
	unit_ind3 varchar(7) default '', 
	unit_name3 varchar(35) default '', 
	unit_ind4 varchar(7) default '', 
	unit_name4 varchar(35) default '', 
	scientificName text, 
	taxon_author_id int(11), 
	letter char(1) default '', 
	`usage` varchar(12) NOT NULL default '', 
	unaccept_reason varchar(50), 
	credibility_rtng varchar(40) NOT NULL default '', 
	completeness_rtng varchar(10), 
	currency_rating varchar(7), 
	parent_tsn bigint(20), 
	kingdom_id smallint(6), 
	rank_id smallint(6), 
	lft int(11), 
	rgt int(11), 
	imagesCount bigint(20) default '0', 
	nameType varchar(32), 
	nameSource varchar(64), 
	comments text, 
	tradeDesignationName varchar(64), 
	pages varchar(32), 
	publicationId int(11), 
	PRIMARY KEY (tsn), 
	KEY treeparttsntsnfk (parent_tsn), 
	KEY letter (letter), 
	KEY lft (lft), 
	KEY rgt (rgt), 
	KEY authorId (taxon_author_id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'TypeStatus'
--

CREATE  TABLE  TypeStatus (
	`name` varchar(64) NOT NULL, 
	description varchar(255), 
	PRIMARY KEY (`name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'User'
--

CREATE  TABLE  `User` (
	id int(11) NOT NULL, 
	privilegeTSN bigint(20), 
	uin varchar(41) NOT NULL default '', 
	pin varchar(41) NOT NULL default '', 
	`name` varchar(128) NOT NULL, 
	email varchar(128), 
	affiliation varchar(255), 
	address varchar(255), 
	last_Name varchar(35), 
	first_Name varchar(35), 
	suffix varchar(10), 
	middle_init char(1), 
	street1 varchar(35), 
	street2 varchar(35), 
	city varchar(25), 
	country varchar(25), 
	state char(2), 
	zipcode varchar(10), 
	`status` tinyint(1) default '0', 
	primaryTSN bigint(20), 
	secondaryTSN bigint(20), 
	isContributor int(1) default '0', 
	dateCreated datetime, 
	preferredServer int(5) NOT NULL default '1', 
	preferredGroup int(11), 
	userLogo varchar(128), 
	logoURL varchar(128), 
	PRIMARY KEY (id), 
	UNIQUE KEY unique_user_name (uin), 
	KEY privilegeTSN (privilegeTSN), 
	KEY primarytsn (primaryTSN), 
	KEY secondarytsn (secondaryTSN)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'UserGroup'
--

CREATE  TABLE  UserGroup (
	`user` int(11) NOT NULL, 
	groups int(11) NOT NULL, 
	userId int(11) NOT NULL, 
	dateLastModified timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP, 
	dateCreated timestamp NULL, 
	dateToPublish timestamp NULL, 
	userGroupRole char(20) NOT NULL, 
	PRIMARY KEY (`user`, groups), 
	KEY usergroupgroupfk (groups), 
	KEY userId (userId)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'UserGroupKey'
--

CREATE  TABLE  UserGroupKey (
	userId int(11), 
	groupId int(11), 
	keyString varchar(45) NOT NULL, 
	PRIMARY KEY USING BTREE (keyString), 
	UNIQUE KEY Index_user_group USING BTREE (userId, groupId), 
	KEY Index_group USING BTREE (groupId)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'UserProperty'
--

CREATE  TABLE  UserProperty (
	id int(11) NOT NULL auto_increment, 
	objectId int(11) NOT NULL, 
	`name` varchar(255) NOT NULL, 
	`value` text NOT NULL, 
	namespaceURI varchar(255), 
	PRIMARY KEY (id), 
	KEY Index_name (`name`), 
	KEY Index_obj_name (objectId, `name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'Vernacular'
--

CREATE  TABLE  Vernacular (
	tsn bigint(20) NOT NULL default '0', 
	vernacular_name varchar(80) NOT NULL default '', 
	`language` varchar(15) NOT NULL default '', 
	approved_ind char(1), 
	update_date date NOT NULL, 
	vern_id bigint(20) NOT NULL default '0'
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table 'View'
--

CREATE  TABLE  `View` (
	id int(11) NOT NULL, 
	viewName text,
	imagingTechnique varchar(128), 
	imagingPreparationTechnique varchar(128), 
	specimenPart varchar(255), 
	viewAngle varchar(128), 
	developmentalStage varchar(128), 
	sex varchar(128), 
	form varchar(128), 
	viewTSN bigint(20), 
	isStandardView int(1), 
	standardImageId int(11), 
	imagesCount int(20) default '0', 
	PRIMARY KEY (id), 
	KEY viewimgtechfk (imagingTechnique), 
	KEY viewimgpreptechfk (imagingPreparationTechnique), 
	KEY viewspecpartfk (specimenPart), 
	KEY viewviewanglefk (viewAngle), 
	KEY viewdevstagefk (developmentalStage), 
	KEY viewsexfk (sex), 
	KEY standardImageId (standardImageId), 
	KEY viewTSN (viewTSN), 
	KEY formIdkey (form)
) ENGINE=InnoDB;


-- Constraints for table Annotation
--
ALTER TABLE Annotation
	ADD CONSTRAINT Annotation_ibfk_1 FOREIGN KEY (id) REFERENCES BaseObject (id) ON UPDATE CASCADE,
	ADD CONSTRAINT Annotation_ibfk_2 FOREIGN KEY (objectId) REFERENCES BaseObject (id) ON UPDATE CASCADE;

--
--
-- Constraints for table BaseObject
--
ALTER TABLE BaseObject
	ADD CONSTRAINT BaseObject_ibfk_1 FOREIGN KEY (userId) REFERENCES `User` (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT BaseObject_ibfk_2 FOREIGN KEY (groupId) REFERENCES Groups (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT BaseObject_ibfk_3 FOREIGN KEY (submittedBy) REFERENCES `User` (id) ON UPDATE CASCADE;

--
-- Constraints for table CharacterState
--
ALTER TABLE CharacterState
	ADD CONSTRAINT PhyloCharState_ibfk_1 FOREIGN KEY (id) REFERENCES BaseObject (id) ON UPDATE CASCADE;

--
-- Constraints for table Collection
--
ALTER TABLE Collection
	ADD CONSTRAINT Collection_ibfk FOREIGN KEY (id) REFERENCES BaseObject (id) ON UPDATE CASCADE,
	ADD CONSTRAINT Collection_ibfk_1 FOREIGN KEY (userId) REFERENCES `User` (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT Collection_ibfk_2 FOREIGN KEY (groupId) REFERENCES Groups (id) ON UPDATE CASCADE;

--
-- Constraints for table CollectionObjects
--
ALTER TABLE CollectionObjects
	ADD CONSTRAINT CollectionObjects_ibfk_1 FOREIGN KEY (collectionId) REFERENCES BaseObject (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT CollectionObjects_ibfk_2 FOREIGN KEY (objectId) REFERENCES BaseObject (id) ON UPDATE CASCADE;

--
-- Constraints for table Country
--
ALTER TABLE Country
	ADD CONSTRAINT Country_ibfk_1 FOREIGN KEY (continentOcean) REFERENCES ContinentOcean (`name`) ON UPDATE CASCADE;

--
-- Constraints for table DeterminationAnnotation
--
ALTER TABLE DeterminationAnnotation
	ADD CONSTRAINT DeterminationAnnotation_ibfk_1 FOREIGN KEY (annotationId) REFERENCES Annotation (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT DeterminationAnnotation_ibfk_2 FOREIGN KEY (specimenId) REFERENCES Specimen (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT DeterminationAnnotation_ibfk_3 FOREIGN KEY (tsnId) REFERENCES Tree (tsn) ON UPDATE CASCADE, 
	ADD CONSTRAINT DeterminationAnnotation_ibfk_4 FOREIGN KEY (rankId) REFERENCES TaxonUnitTypes (rank_id) ON UPDATE CASCADE, 
	ADD CONSTRAINT DeterminationAnnotation_ibfk_5 FOREIGN KEY (kingdomId) REFERENCES Kingdoms (kingdom_id) ON UPDATE CASCADE;

--
-- Constraints for table ExternalLinkObject
--
ALTER TABLE ExternalLinkObject
	ADD CONSTRAINT ExternalLinkObject_ibfk_1 FOREIGN KEY (mbId) REFERENCES BaseObject (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT ExternalLinkObject_ibfk_2 FOREIGN KEY (extLinkTypeId) REFERENCES ExternalLinkType (linkTypeId) ON UPDATE CASCADE;

--
-- Constraints for table Groups
--
ALTER TABLE Groups
	ADD CONSTRAINT Groups_ibfk_1 FOREIGN KEY (id) REFERENCES BaseObject (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT Groups_ibfk_3 FOREIGN KEY (groupManagerId) REFERENCES `User` (id) ON UPDATE CASCADE;

--
-- Constraints for table Image
--
ALTER TABLE Image
	ADD CONSTRAINT Image_ibfk_1 FOREIGN KEY (id) REFERENCES BaseObject (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT Image_ibfk_2 FOREIGN KEY (userId) REFERENCES `User` (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT Image_ibfk_3 FOREIGN KEY (groupId) REFERENCES Groups (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT Image_ibfk_4 FOREIGN KEY (specimenId) REFERENCES Specimen (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT Image_ibfk_5 FOREIGN KEY (viewId) REFERENCES `View` (id) ON UPDATE CASCADE;

--
-- Constraints for table Locality
--
ALTER TABLE Locality
	ADD CONSTRAINT Locality_ibfk_1 FOREIGN KEY (id) REFERENCES BaseObject (id);

--
-- Constraints for table Matrix
--
ALTER TABLE Matrix
	ADD CONSTRAINT Matrix_ibfk_1 FOREIGN KEY (id) REFERENCES BaseObject (id);

--
-- Constraints for table MatrixCellValue
--
ALTER TABLE MatrixCellValue
	ADD CONSTRAINT MatrixCellValue_ibfk_1 FOREIGN KEY (id) REFERENCES BaseObject (id),
	ADD CONSTRAINT FK_MatrixCellValue_1 FOREIGN KEY (matrixId) REFERENCES Matrix (id);

--
-- Constraints for table MbCharacter
--
ALTER TABLE MbCharacter
	ADD CONSTRAINT PhyloCharacter_ibfk_5 FOREIGN KEY (publicationId) REFERENCES Publication (id), 
	ADD CONSTRAINT PhyloCharacter_ibfk_6 FOREIGN KEY (id) REFERENCES BaseObject (id) ON UPDATE CASCADE;

--
-- Constraints for table News
--
ALTER TABLE News
	ADD CONSTRAINT News_ibfk_1 FOREIGN KEY (id) REFERENCES BaseObject (id) ON UPDATE CASCADE;

--
-- Constraints for table Otu
--
ALTER TABLE Otu
	ADD CONSTRAINT Otu_ibfk_1 FOREIGN KEY (id) REFERENCES BaseObject (id) ON UPDATE CASCADE;

--
-- Constraints for table ServerInfo
--
ALTER TABLE ServerInfo
	ADD CONSTRAINT ServerInfo_ibfk_2 FOREIGN KEY (admin) REFERENCES `User` (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT ServerInfo_ibfk_3 FOREIGN KEY (mirrorGroup) REFERENCES Groups (id) ON UPDATE CASCADE;

--
-- Constraints for table Specimen
--
ALTER TABLE Specimen
	ADD CONSTRAINT Specimen_ibfk_1 FOREIGN KEY (id) REFERENCES BaseObject (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT Specimen_ibfk_5 FOREIGN KEY (tsnId) REFERENCES Tree (tsn) ON UPDATE CASCADE, 
	ADD CONSTRAINT Specimen_ibfk_6 FOREIGN KEY (localityId) REFERENCES Locality (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT Specimen_ibfk_8 FOREIGN KEY (basisOfRecordId) REFERENCES BasisOfRecord (`name`) ON UPDATE CASCADE;

--
-- Constraints for table `User`
--
ALTER TABLE `User`
	ADD CONSTRAINT User_ibfk_1 FOREIGN KEY (id) REFERENCES BaseObject (id) ON UPDATE CASCADE;

--
-- Constraints for table UserGroup
--
ALTER TABLE UserGroup
	ADD CONSTRAINT UserGroup_ibfk_1 FOREIGN KEY (`user`) REFERENCES `User` (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT UserGroup_ibfk_2 FOREIGN KEY (groups) REFERENCES Groups (id) ON UPDATE CASCADE, 
	ADD CONSTRAINT UserGroup_ibfk_3 FOREIGN KEY (userId) REFERENCES `User` (id) ON UPDATE CASCADE;

--
-- Constraints for table UserGroupKey
--
ALTER TABLE UserGroupKey
	ADD CONSTRAINT FK_UserGroupKey_1 FOREIGN KEY (userId) REFERENCES `User` (id), 
	ADD CONSTRAINT FK_UserGroupKey_2 FOREIGN KEY (groupId) REFERENCES Groups (id);

--
-- Constraints for table UserProperty
--
ALTER TABLE UserProperty
	ADD CONSTRAINT UserProperty_FK FOREIGN KEY (objectId) REFERENCES BaseObject (id);


--
-- Constraints for table `View`
--
ALTER TABLE `View`
	ADD CONSTRAINT View_FK_OBJ FOREIGN KEY (id) REFERENCES BaseObject (id) ON UPDATE CASCADE,
	ADD CONSTRAINT View_ibfk_8 FOREIGN KEY (viewTSN) REFERENCES Tree (tsn) ON UPDATE CASCADE;

-- --------------------------------------------------------

--
-- Table structure for table 'ViewAngle'
--

CREATE  TABLE  ViewAngle (
	`name` varchar(128) NOT NULL, 
	description varchar(128), 
	PRIMARY KEY (`name`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Structure for view 'Taxon'
--
DROP TABLE IF EXISTS Taxon;

CREATE  or replace VIEW Taxon AS
	select p.tsn AS tsn, 
		p.scientificName AS scientificName, 
		k.kingdom_name AS kingdom, 
		t.rank_name AS rank, 
		p.taxon_author_id AS taxonAuthorId 
	from ((Tree p left join TaxonUnitTypes t 
			on(((p.rank_id = t.rank_id) and (p.kingdom_id = t.kingdom_id)))) 
		left join Kingdoms k on((t.kingdom_id = k.kingdom_id)));

-- --------------------------------------------------------

--
-- Structure for view 'TaxonBranch'
--
DROP TABLE IF EXISTS TaxonBranch;

CREATE  or replace VIEW TaxonBranch AS
	select c.tsn AS child, 
		p.tsn AS tsn, p.rank_id AS rankId, 
		p.kingdom_id AS kingdomId 
	from (Tree c join Tree p) 
	where ((p.lft <= c.lft) and (p.rgt >= c.rgt) and (p.tsn <> 0));

-- --------------------------------------------------------

--
-- Structure for view 'TaxonBranchNode'
--
DROP TABLE IF EXISTS TaxonBranchNode;

CREATE  or replace VIEW TaxonBranchNode AS
	select b.child AS child, t.tsn AS tsn,
		t.scientificName AS scientificName,
		t.kingdom AS kingdom, t.rank AS rank
	from (TaxonBranch b join Taxon t on((b.tsn = t.tsn)));



