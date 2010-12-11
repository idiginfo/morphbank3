CREATE TABLE `Taxa` (
  `id` int(11) NOT NULL auto_increment,
  `boId` int(11) default NULL,
  `tsn` bigint(20) default NULL,
  `scientificName` text NOT NULL,
  `taxon_author_id` int(11) default NULL,
  `taxon_author_name` varchar(100) default NULL,
  `status` varchar(12) default NULL,
  `parent_tsn` bigint(20) default NULL,
  `parent_name` text,
  `kingdom_id` smallint(6) default NULL,
  `kingdom_name` varchar(10) default NULL,
  `rank_id` smallint(6) default NULL,
  `rank_name` varchar(15) default NULL,
  `imagesCount` bigint(20) default '0',
  `nameType` varchar(32) default NULL,
  `nameSource` varchar(64) default NULL,
  `publicationId` int(11) default NULL,
  `userId` int(11) default NULL,
  `groupId` int(11) default NULL,
  `dateToPublish` datetime NOT NULL,
  `keywords` text character set utf8,
  `objectTypeId` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `keywords` (`keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

insert into Taxa(tsn, scientificName, taxon_author_id, status, parent_tsn, kingdom_id, rank_id, imagesCount, nameType, publicationId) SELECT tsn, scientificName, taxon_author_id, `usage`, parent_tsn, kingdom_id, rank_id, imagesCount, nameType, publicationId from Tree;

update Taxa inner join Kingdoms on Taxa.kingdom_id=Kingdoms.kingdom_id set Taxa.kingdom_name=Kingdoms.kingdom_name;

update Taxa inner join TaxonUnitTypes on Taxa.rank_id=TaxonUnitTypes.rank_id set Taxa.rank_name=TaxonUnitTypes.rank_name;

update Taxa inner join Tree on Taxa.parent_tsn=Tree.tsn set Taxa.parent_name=Tree.scientificName;

update Taxa join Tree on Taxa.tsn=Tree.tsn set Taxa.nameType=Tree.nameType;

update Taxa join Tree on Taxa.tsn=Tree.tsn set Taxa.publicationId=Tree.publicationId;

update Taxa inner join TaxonConcept on Taxa.tsn=TaxonConcept.tsn inner join BaseObject on BaseObject.id=TaxonConcept.id set boId = BaseObject.id, Taxa.userId = BaseObject.userId, Taxa.groupId = BaseObject.groupId, Taxa.dateToPublish = BaseObject.dateToPublish;

 update Taxa inner join TaxonAuthors on Taxa.taxon_author_id=TaxonAuthors.taxon_author_id set Taxa.taxon_author_name=TaxonAuthors.taxon_author;
