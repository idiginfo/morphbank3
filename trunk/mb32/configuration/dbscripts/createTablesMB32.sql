
-- --------------------------------------------------------

--
-- Table structure for table 'Annotation'
--

create table Annotation (
    id int primary key not null,
    objectId int, 
    objectTypeId varchar(255), 
    typeAnnotation varchar(255), 
    xLocation int, 
    yLocation int, 
    areaHeight int, 
    areaWidth int, 
    areaRadius int, 
    annotationQuality varchar(10), 
    title varchar(255), 
    keywords varchar(255), 
    `comment` text, 
    xmlData text, 
    externalURL varchar(255), 
    annotationLabel varchar(255), 
    annotationMarkup varchar(255), 
    key annotObjfk (objectId)
) engine=InnoDB ;



-- --------------------------------------------------------
--
-- Table structure for table 'AnnotationType'
--

create table AnnotationType (
    annotationType varchar(255) primary key not null, 
    annotationSchema text
) engine=InnoDB ;


-- --------------------------------------------------------

--
-- Table structure for table 'BaseObject'
--

create table BaseObject (
    id int primary key not null, 
    userId int not null, 
    groupId int not null, 
    dateCreated timestamp not null default current_timestamp, 
    dateLastModified datetime,
    dateToPublish date not null, 
    objectTypeId varchar(255) not null, 
    `name` varchar(255), 
    description varchar(255), 
    submittedBy int not null, 
    objectLogo varchar(255), 
    keywords text, 
    summaryHTML text, 
    thumbURL varchar(255), 
    published varchar(255) not null default 'private_mb', 
    geolocated tinyint, 
    imageAltText text, 
    xmlKeywords text, 
    hostServer varchar(500),
    imagesCount int,
    key userId (userId), 
    key groupId (groupId), 
    key baseSubmittedByfk (submittedBy), 
    key dateLastModified (dateLastModified),
    key dateCreated (dateCreated)
) engine=InnoDB ;


-- --------------------------------------------------------

--
-- Table structure for table 'BasisOfRecord'
--

create table BasisOfRecord (
    description varchar(255) default 'Not provided', 
    `name` char(2) primary key not null
) engine=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'CharacterState'
--

create table CharacterState (
    id int primary key not null, 
    charStateValue varchar(255) not null
) engine=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'Collection'
--

create table Collection (
    id int primary key not null, 
    userId int not null, 
    groupId int not null, 
    `name` varchar(255) not null default 'New collection', 
    key colluserfk (userId), 
    key collgroupfk (groupId)
) engine=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'CollectionObjects'
--

create table CollectionObjects (
    identifier int primary key not null auto_increment, 
    collectionId int not null, 
    objectId int not null, 
    objectOrder int, 
    objectTypeId varchar(255), 
    objectRole varchar(255), 
    objectTitle varchar(255), 
    startSubCollection tinyint, 
    parentId varchar(18), 
    key collectionObjMain (collectionId, objectId), 
    key collectionObjOrder (collectionId, objectOrder), 
    key collObjObjfk (objectId), 
    key collObjCollfk (collectionId)
) engine=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'ContinentOcean'
--

create table ContinentOcean (
    description varchar(255), 
    `name` char(2) primary key not null
) engine=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'Country'
--

create table Country (
    continentOcean char(2) not null, 
    description varchar(255) not null default 'Not provided', 
    `name` char(2) not null,
    key `name` (`name`),
    key continentId (continentOcean),
    key `description` (`description`),
) engine=InnoDB;


-- --------------------------------------------------------

create table CurrentIds (
 minId int not null,
 maxId int not null,
 `type` varchar(255) primary key not null
) engine=InnoDB;


--
-- Table structure for table 'DeterminationAnnotation'
--

create table DeterminationAnnotation (
    annotationId int primary key not null, 
    specimenId int, 
    tsnId bigint, 
    rankId smallint, 
    kingdomId int, 
    rankName varchar(140), 
    typeDetAnnotation varchar(255), 
    sourceOfId varchar(255), 
    collectionId int, 
    materialsUsedInId varchar(255), 
    prefix varchar(255), 
    suffix varchar(255), 
    resourcesused varchar(255), 
    altTaxonName varchar(255), 
    key DetAnnSpecimenIdfk (specimenId), 
    key DetAnntsnIdfk (tsnId), 
    key DetAnnrankIdfk (rankId), 
    key DetAnnkingdomIdfk (kingdomId)
) engine=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'DevelopmentalStage'
--

create table DevelopmentalStage (
    `name` varchar(255) primary key not null default 'Not provided', 
    description varchar(255) default 'Not provided'
) engine=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'ExternalLinkObject'
--

create table ExternalLinkObject (
    linkId int unsigned primary key not null auto_increment,
    mbId int not null, 
    extLinkTypeId int,
    label varchar(255), 
    urlData varchar(255), 
    description varchar(2000),
    externalId varchar(512) charset latin1,
    unique key ExternalIdUnique (externalId),
    key baseObjectFk (mbId),
    key extTypeFk (extLinkTypeId)
) engine=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'ExternalLinkType'
--

create table ExternalLinkType (
    linkTypeId int primary key not null auto_increment, 
    `name` varchar(255) not null default '',
    description varchar(255)
) engine=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'Form'
--

create table Form (
    `name` varchar(255) primary key not null, 
    description varchar(255)
) engine=InnoDB;



create table Geolocated (
    id int not null primary key
) engine=InnoDB;



create table Groups (
    id int primary key not null, 
    groupName varchar(255), 
    tsn bigint, 
    groupManagerId int, 
    `status` tinyint, 
    dateCreated datetime, 
    unique key uniquegroupName (groupName), 
    key groupstsnfk (tsn), 
    key groupsGrpMngIdfk (groupManagerId)
) engine=InnoDB;



create table History (
    id int not null, 
    userId int not null, 
    groupId int not null, 
    dateModified datetime, 
    modifiedFrom text not null, 
    modifiedTo text not null, 
    tableName varchar(255) not null 
) engine=InnoDB;



create table Image (
    id int primary key not null, 
    userId int, 
    groupId int, 
    dateToPublish date, 
    specimenId int, 
    viewId int, 
    imageHeight int, 
    imageWidth int, 
    resolution int, 
    magnification float, 
    imageType varchar(8), 
    accessNum int, 
    copyrightText varchar(255), 
    originalFileName varchar(127), 
    creativeCommons text, 
    photographer text, 
    eol int,
    key imgspecimentfk (specimenId), 
    key imgviewfk (viewId), 
    key imgaccessnumind (accessNum), 
    key imguserfk (userId), 
    key imggroupfk (groupId)
) engine=InnoDB;



create table ImagingPreparationTechnique (
    `name` varchar(255) primary key not null, 
    description varchar(255)
) engine=InnoDB;



create table ImagingTechnique (
    `name` varchar(255) primary key not null, 
    description varchar(255)
) engine=InnoDB;



create table ITISCounter (
    counter int not null primary key
) engine=InnoDB;



create table Keywords (
    id int primary key not null, 
    userId int, 
    groupId int, 
    dateToPublish datetime,
    objectTypeId varchar(255), 
    keywords text,
    submittedBy int, 
    geolocated tinyint, 
    xmlKeywords text, 
    dateCreated datetime,
    key objectTypeId (objectTypeId), 
    fulltext key `keywords` (keywords),
    key dateCreated(dateCreated),
    key dateToPublish(dateToPublish)
    ) engine=MyISAM;


    
create table  KeywordsTemp (
  id int not null primary key,
  keywords text,
  xmlKeywords text,
  imageAltText text
) engine=InnoDB;



create table Kingdoms (
    kingdom_id int primary key not null, 
    kingdom_name char(10) not null default '', 
    update_date date
) engine=InnoDB;


create table Locality (
    id int primary key not null, 
    continentOcean varchar(255), 
    country varchar(255), 
    locality text,
    latitude double, 
    longitude double, 
    coordinatePrecision int, 
    minimumElevation smallint, 
    maximumElevation smallint, 
    minimumDepth smallint, 
    maximumDepth smallint, 
    imagesCount bigint default 0, 
    county varchar(255), 
    state varchar(255), 
    paleoGroup varchar(255), 
    paleoFormation varchar(255), 
    paleoMember varchar(255), 
    paleoBed varchar(255), 
    continent varchar(255), 
    ocean varchar(255), 
    informationWithheld text, 
    key locationctryfk (country), 
    key locationoceanfk (continentOcean)
) engine=InnoDB;



create table Matrix (
    id int primary key not null, 
    numRows int, 
    numChars int, 
    gap char(5) default '-', 
    missing char(5) default '?'
) engine=InnoDB;



create table MatrixCell (
    id int primary key not null, 
    matrixId int, 
    rowNum int, 
    columnNum int, 
    charStateList text, 
    `value` varchar(255), 
    key MatrixCell_ibfk_1 (matrixId)
) engine=InnoDB;



create table MatrixCellValue (
    id int primary key not null auto_increment, 
    matrixId int, 
    `row` int not null, 
    col int not null, 
    `value` varchar(255) not null, 
    key Index_3 (matrixId, `row`, col)
) engine=InnoDB;



create table MbCharacter (
    id int primary key not null, 
    label varchar(8), 
    characterNumber varchar(255), 
    discrete tinyint, 
    ordered tinyint, 
    publicationId int, 
    pubComment text, 
    key characterpubfk (publicationId)
) engine=InnoDB;



create table MBTeam (
    id int not null default 0, 
    fname varchar(20), 
    lname varchar(255), 
    category varchar(255), 
    categoryOrder int, 
    peopleOrder int, 
    image_name varchar(255), 
    telephone varchar(20), 
    office varchar(255), 
    email varchar(255) not null default '', 
    web varchar(60), 
    description longtext,
    notes longtext
) engine=InnoDB;



create table MirrorInfo (
    serverId int not null default 1, 
    imageId int not null default 1, 
    primary key (serverId, imageId)
) engine=InnoDB;



create table MissingImages (
    id int not null, 
    accessNum int, 
    problems text,
    primary key (id)
) engine=InnoDB;



create table  MissingLinks (
  id int not null auto_increment primary key,
  linkType varchar(255) not null,
  sourceId int not null,
  targetId int not null,
  objectOrder int default null,
  objectRole varchar(255) default null,
  objectTitle varchar(255) default null,
  dateRecorded datetime,
  remoteDetailUrl varchar(512)
) engine=InnoDB;



create table News (
    id int primary key not null, 
    title varchar(255), 
    body text,
    image varchar(255), 
    imageText varchar(255), 
    dateCreated datetime, 
    `status` tinyint
) engine=InnoDB;



create table Otu (
    id int primary key not null, 
    label varchar(255)
) engine=InnoDB;



create table Publication (
    id int primary key not null,
    doi varchar(255), 
    publicationType varchar(18), 
    address varchar(255), 
    annote varchar(255), 
    author varchar(255), 
    publicationTitle varchar(255), 
    chapter varchar(255), 
    edition varchar(255), 
    editor varchar(255), 
    howPublished varchar(255), 
    institution varchar(255), 
    `key` varchar(255), 
    `month` varchar(10), 
    `day` tinyint, 
    note tinytext,
    number varchar(11), 
    organization varchar(255), 
    pages varchar(255), 
    publisher varchar(255), 
    school varchar(255), 
    series varchar(255), 
    title varchar(255), 
    volume varchar(11), 
    `year` varchar(255), 
    isbn varchar(255), 
    issn varchar(255)
) engine=InnoDB;



create table RecentlyModified (
    id int primary key not null, 
    dateLastModified datetime, 
    objectTypeId varchar(255)
) engine=InnoDB;



create table RecentlyModifiedTemp (
    id int, 
    dateLastModified datetime, 
    objectTypeId varchar(255), 
    dependentTypeId varchar(255) 
) engine=InnoDB;



create table sequence (
    SEQ_NAME varchar(255), 
    SEQ_COUNT decimal(15, 0) 
) engine=InnoDB;



create table ServerInfo (
    serverId int not null auto_increment, 
    url varchar(255) not null default 'http://morphbank.net/', 
    logo varchar(255), 
    contact varchar(255), 
    admin int not null, 
    mirrorGroup int not null, 
    dateCreated date not null, 
    updatedDate date not null, 
    basePath varchar(255) not null default '/ftp', 
    login varchar(255) not null default 'test', 
    passwd varchar(255) not null default 'test', 
    port int not null, 
    imageURL varchar(255) not null, 
    locality varchar(255), 
    tsns text not null, 
    primary key (serverId), 
    key MirrorGroupfk (mirrorGroup), 
    key ServerUser (admin)
) engine=InnoDB;


-- --------------------------------------------------------

--
-- Table structure for table 'Sex'
--

create table Sex (
    `name` varchar(255) primary key not null, 
    description varchar(255) not null default 'Not provided'
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'Spam'
--

create table Spam (
    id int primary key not null auto_increment, 
    `code` varchar(255), 
    graphic varchar(255)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'Specimen'
--

create table Specimen (
    id int primary key not null,
    basisOfRecordId char(2), 
    sex varchar(255), 
    form varchar(255), 
    developmentalStage varchar(255), 
    preparationType varchar(255), 
    individualCount int, 
    tsnId bigint, 
    typeStatus varchar(255), 
    `name` text,
    dateIdentified datetime, 
    `comment` text,
    institutionCode varchar(255), 
    collectionCode varchar(255), 
    catalogNumber varchar(255), 
    previousCatalogNumber varchar(255), 
    relatedCatalogItem varchar(255), 
    relationshipType varchar(255), 
    collectionNumber varchar(255), 
    collectorName text, 
    dateCollected datetime, 
    localityId int, 
    notes text,
    taxonomicNames text,
    imagesCount bigint default 0, 
    standardImageId int, 
    ocr text, 
    barCode varchar(255), 
    labelData text, 
    earliestDateCollected datetime, 
    latestDateCollected datetime, 
    key specsexfk (sex), 
    key specformfk (form), 
    key specdstgfk (developmentalStage), 
    key spectsnfk (tsnId), 
    key speclocfk (localityId), 
    key spectstfk (typeStatus), 
    key specbofrfk (basisOfRecordId)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'SpecimenPart'
--

create table SpecimenPart (
    `name` varchar(255) primary key not null, 
    description varchar(255) not null default 'Not provided'
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'synonym_links'
--

create table synonym_links (
    tsn int not null, 
    tsn_accepted int not null, 
    update_date date not null, 
    key syntsnfk (tsn), 
    key syntsnacceptedfk (tsn_accepted)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'Taxa'
--

create table Taxa (
    id int primary key not null auto_increment, 
    boId int, 
    tsn bigint, 
    scientificName varchar(255) not null, 
    taxon_author_id int, 
    taxon_author_name varchar(255), 
    `status` varchar(255), 
    parent_tsn bigint, 
    parent_name text, 
    kingdom_id smallint, 
    kingdom_name varchar(255), 
    rank_id smallint, 
    rank_name varchar(255), 
    imagesCount bigint default 0, 
    nameType varchar(255), 
    nameSource varchar(255), 
    publicationId int, 
    userId int, 
    groupId int, 
    dateToPublish datetime not null, 
    keywords text,
    objectTypeId varchar(255), 
    taxonomicNames varchar(255),
    unique key (tsn), 
    unique key (boId), 
    fulltext key (keywords),
    key (scientificName),
    key (taxon_author_id)
) engine=MyISAM;



CREATE TABLE TaxaTemp (
  tsn int(11) not null,
  keywords varchar(4000) default null,
  PRIMARY KEY  (tsn)
) ENGINE=MyISAM ;



-- --------------------------------------------------------

--
-- Table structure for table 'TaxonAuthors'
--

create table TaxonAuthors (
    taxon_author_id int primary key not null, 
    taxon_author varchar(255) not null, 
    update_date datetime not null, 
    kingdom_id smallint not null
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'TaxonConcept'
--

create table TaxonConcept (
    id int primary key not null, 
    tsn bigint not null, 
    nameSpace varchar(255), 
    `status` varchar(255), 
    key (tsn)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'TaxonomicUnits'
--

create table TaxonomicUnits (
    tsn bigint primary key not null, 
    unit_ind1 char(1), 
    unit_name1 varchar(255) not null default '', 
    unit_ind2 char(1), 
    unit_name2 varchar(255), 
    unit_ind3 varchar(255), 
    unit_name3 varchar(255), 
    unit_ind4 varchar(255), 
    unit_name4 varchar(255), 
    unnamed_taxon_ind char(1), 
    `usage` varchar(255) not null default '', 
    unaccept_reason varchar(255), 
    credibility_rtng varchar(255) not null default '', 
    completeness_rtng varchar(255), 
    currency_rating varchar(255), 
    phylo_sort_seq smallint, 
    initial_time_stamp datetime not null, 
    parent_tsn bigint, 
    taxon_author_id int, 
    hybrid_author_id int, 
    kingdom_id int not null default 0, 
    rank_id smallint not null default 0, 
    update_date date not null, 
    uncertain_prnt_ind char(3), 
    key parttsntsnfk (parent_tsn), 
    key kingdomfk (kingdom_id), 
    key rankidfk (rank_id)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'TaxonUnitTypes'
--

create table TaxonUnitTypes (
    kingdom_id int not null, 
    rank_id smallint not null, 
    rank_name char(15) not null, 
    dir_parent_rank_id smallint not null, 
    req_parent_rank_id smallint not null, 
    update_date date not null, 
    primary key (kingdom_id,rank_id),
    key rankIdKey (rank_id)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'Tree'
--

create table Tree (
    tsn bigint primary key not null, 
    unit_ind1 char(1) default '', 
    unit_name1 varchar(255) default '', 
    unit_ind2 char(1) default '', 
    unit_name2 varchar(255) default '', 
    unit_ind3 varchar(255) default '', 
    unit_name3 varchar(255) default '', 
    unit_ind4 varchar(255) default '', 
    unit_name4 varchar(255) default '', 
    scientificName varchar(255), 
    taxon_author_id int, 
    letter char(1) default '', 
    `usage` varchar(255) not null default '', 
    unaccept_reason varchar(255), 
    credibility_rtng varchar(255) not null default '', 
    completeness_rtng varchar(255), 
    currency_rating varchar(255), 
    parent_tsn bigint, 
    kingdom_id smallint, 
    rank_id smallint, 
    lft int, 
    rgt int, 
    imagesCount bigint default 0, 
    myImagesCount bigint default 0, 
    nameType varchar(255), 
    nameSource varchar(255), 
    comments text, 
    tradeDesignationName varchar(255), 
    pages varchar(255), 
    publicationId int, 
    key treeparttsntsnfk (parent_tsn), 
    key letter (letter), 
    key lft (lft), 
    key rgt (rgt), 
    key authorId (taxon_author_id),
    key sciname (scientificName)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'TypeStatus'
--

create table TypeStatus (
    `name` varchar(255) primary key not null, 
    description varchar(255)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'User'
--

create table `User` (
    id int primary key not null, 
    privilegeTSN bigint, 
    uin varchar(255), 
    pin varchar(255), 
    `name` varchar(255), 
    email varchar(255), 
    affiliation varchar(255), 
    address varchar(255), 
    last_Name varchar(255), 
    first_Name varchar(255), 
    suffix varchar(255), 
    middle_init char(1), 
    street1 varchar(255), 
    street2 varchar(255), 
    city varchar(255), 
    country varchar(255), 
    state char(2), 
    zipcode varchar(255), 
    `status` tinyint default 0, 
    primaryTSN bigint, 
    secondaryTSN bigint, 
    isContributor int default 0, 
    dateCreated datetime, 
    preferredServer int, 
    preferredGroup int, 
    userLogo varchar(255), 
    logoURL varchar(255), 
    unique key unique_user_name (uin), 
    key privilegeTSN (privilegeTSN), 
    key primarytsn (primaryTSN), 
    key secondarytsn (secondaryTSN)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'UserGroup'
--

create table UserGroup (
    `user` int not null, 
    groups int not null, 
    userId int not null, 
    dateLastModified timestamp not null default current_timestamp on update current_timestamp, 
    dateCreated timestamp null, 
    dateToPublish timestamp null, 
    userGroupRole char(20) not null, 
    primary key (`user`, groups), 
    key usergroupgroupfk (groups), 
    key userId (userId)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'UserGroupKey'
--

create table UserGroupKey (
    userId int, 
    groupId int, 
    keyString varchar(255) not null, 
    primary key using btree (keyString), 
    unique key Index_user_group using btree (userId, groupId), 
    key Index_group using btree (groupId)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'UserProperty'
--

create table UserProperty (
    id int primary key not null auto_increment, 
    objectId int not null,
    `name` varchar(255) not null, 
    `value` text not null, 
    namespaceURI varchar(255), 
    key Index_name (`name`), 
    key Index_obj_name (objectId, `name`)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'Vernacular'
--

create table Vernacular (
    tsn bigint not null, 
    vernacular_name varchar(255) not null default '', 
    `language` varchar(255) not null default '', 
    approved_ind char(1), 
    update_date date not null, 
    vern_id bigint not null default 0
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'View'
--

create table `View` (
    id int primary key not null,
    viewName text,
    imagingTechnique varchar(255), 
    imagingPreparationTechnique varchar(255), 
    specimenPart varchar(255), 
    viewAngle varchar(255), 
    developmentalStage varchar(255), 
    sex varchar(255), 
    form varchar(255), 
    viewTSN bigint, 
    isStandardView int, 
    standardImageId int, 
    imagesCount int default 0, 
    key viewimgtechfk (imagingTechnique), 
    key viewimgpreptechfk (imagingPreparationTechnique), 
    key viewspecpartfk (specimenPart), 
    key viewviewanglefk (viewAngle), 
    key viewdevstagefk (developmentalStage), 
    key viewsexfk (sex), 
    key standardImageId (standardImageId), 
    key viewTSN (viewTSN), 
    key formIdkey (form)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Table structure for table 'ViewAngle'
--

create table ViewAngle (
    `name` varchar(255) not null, 
    description varchar(255), 
    primary key (`name`)
) engine=InnoDB;



-- --------------------------------------------------------

--
-- Structure for view 'Taxon'
--

create view Taxon as
    select p.tsn as tsn, 
        p.scientificName as scientificName, 
        k.kingdom_name as kingdom, 
        t.rank_name as rank, 
        p.taxon_author_id as taxonAuthorId 
    from (Tree p left join TaxonUnitTypes t 
            on((p.rank_id = t.rank_id) and (p.kingdom_id = t.kingdom_id)) 
        left join Kingdoms k on(t.kingdom_id = k.kingdom_id));



-- --------------------------------------------------------

--
-- Structure for view 'TaxonBranch'
--

create view TaxonBranch as
    select c.tsn as child, 
        p.tsn as tsn, p.rank_id as rankId, 
        p.kingdom_id as kingdomId 
    from (Tree c join Tree p) 
    where (p.lft <= c.lft) and (p.rgt >= c.rgt) and (p.tsn <> 0);


    
-- --------------------------------------------------------

--
-- Structure for view 'TaxonBranchNode'
--

create view TaxonBranchNode as
    select b.child as child, t.tsn as tsn,
        t.scientificName as scientificName,
        t.kingdom as kingdom, t.rank as rank, b.rankId as rankId
    from (TaxonBranch b join Taxon t on(b.tsn = t.tsn));



-- --------------------------------------------------------

--
-- Foreign Key Constraints
--

alter table Annotation
    add foreign key (id) references BaseObject(id),
    add foreign key (objectId) references BaseObject(id);

alter table BaseObject
    add foreign key (userId) references User(id), 
    add foreign key (groupId) references Groups(id), 
    add foreign key (submittedBy) references User(id);

alter table CharacterState
    add foreign key (id) references BaseObject(id);

alter table Collection
    add foreign key (id) references BaseObject(id), 
    add foreign key (userId) references User(id), 
    add foreign key (groupId) references Groups(id);

alter table CollectionObjects
    add foreign key (collectionId) references BaseObject(id), 
    add foreign key (objectId) references BaseObject(id);

alter table DeterminationAnnotation
    add foreign key (annotationId) references Annotation(id), 
    add foreign key (specimenId) references Specimen(id), 
    add foreign key (tsnId) references Tree(tsn), 
    add foreign key (rankId) references TaxonUnitTypes(rank_id), 
    add foreign key (kingdomId) references Kingdoms(kingdom_id);

alter table ExternalLinkObject
    add foreign key (mbId) references BaseObject(id), 
    add foreign key (extLinkTypeId) references ExternalLinkType(linkTypeId);

alter table Groups
    add foreign key (id) references BaseObject(id),
    add foreign key (tsn) references Tree(tsn),
    add foreign key (groupManagerId) references User(id);

alter table Image
    add foreign key (id) references BaseObject(id), 
    add foreign key (userId) references User(id), 
    add foreign key (groupId) references Groups(id), 
    add foreign key (specimenId) references Specimen(id), 
    add foreign key (viewId) references View(id);

alter table Locality
    add foreign key (id) references BaseObject(id);
alter table Matrix
    add foreign key (id) references BaseObject(id);

alter table MatrixCell
    add foreign key (id) references BaseObject(id), 
    add foreign key (matrixId) references Matrix(id);

alter table MatrixCellValue
    add foreign key (matrixId) references Matrix(id);

alter table MbCharacter
    add foreign key (id) references BaseObject(id), 
    add foreign key (publicationId) references Publication(id);

alter table News
    add foreign key (id) references BaseObject(id);

alter table Otu
    add foreign key (id) references BaseObject(id);

alter table Publication
    add foreign key (id) references BaseObject(id);

alter table Specimen
    add foreign key (id) references BaseObject(id), 
    add foreign key (basisOfRecordId) references BasisOfRecord(name), 
    add foreign key (tsnId) references Tree(tsn), 
    add foreign key (localityId) references Locality(id),
    add foreign key (standardImageId) references Image(id);

alter table Taxa
    add foreign key (tsn) references Tree(tsn),
    add foreign key (parent_tsn) references Tree(tsn);

alter table TaxonConcept
    add foreign key (id) references BaseObject(id), 
    add foreign key (tsn) references Tree(tsn);

alter table TaxonomicUnits
    add foreign key (tsn) references Tree(tsn),
    add foreign key (parent_tsn) references Tree(tsn);

alter table TaxonUnitTypes
    add foreign key (kingdom_id) references Kingdoms(kingdom_id);

alter table Tree
    add foreign key (parent_tsn) references Tree(tsn);

alter table `User`
    add foreign key (id) references BaseObject(id), 
    add foreign key (privilegeTSN) references Tree(tsn), 
    add foreign key (primaryTSN) references Tree(tsn), 
    add foreign key (secondaryTSN) references Tree(tsn);

alter table UserGroup
    add foreign key (`user`) references User(id), 
    add foreign key (groups) references Groups(id),
    add foreign key (userId) references User(id);

alter table UserGroupKey
    add foreign key (userId) references User(id), 
    add foreign key (groupId) references Groups(id);

alter table UserProperty
    add foreign key (objectId) references BaseObject(id);

alter table Vernacular
    add foreign key (tsn) references Tree(tsn);

alter table `View`
    add foreign key (id) references BaseObject(id), 
    add foreign key (viewTSN) references Tree(tsn), 
    add foreign key (standardImageId) references Image(id);
