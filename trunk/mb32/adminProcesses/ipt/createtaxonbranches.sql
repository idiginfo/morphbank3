drop table TaxonBranches;

create table TaxonBranches (
	tsn bigint primary key,
	parent_tsn bigint,
	rank_id int,
	rank varchar(100),
	kingdom varchar(100),
	phylum varchar(100),
	class varchar(100),
	`order` varchar(100),
	family varchar(100),
	genus varchar(100),
	subgenus varchar(100),
	species varchar(100),
	scientificname varchar(100)
);


#1,10,Kingdom,10,10,1996-07-11
insert into TaxonBranches (tsn, rank_id, parent_tsn, rank, scientificname) (
select tsn,t.rank_id,t.parent_tsn,rank_name,scientificname from Tree t 
join TaxonUnitTypes p on t.rank_id=p.rank_id and t.kingdom_id=p.kingdom_id
);

#Kingdoms and subkingdoms ranks 10,20
#1,10,Kingdom,10,10,1996-07-11
update TaxonBranches set kingdom = scientificname where rank_id = 10;

#subkingdom
update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom
where b.rank_id =  20;


#1,30,Phylum,20,10,1996-07-11
update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = b.scientificname
where b.rank_id = 30;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum
where b.rank_id = 40;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum
where b.rank_id = 50; 

#1,60,Class,50,30,1996-07-11
update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= b.scientificname
where b.rank_id = 60;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class = p.class
where b.rank_id = 70;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class = p.class
where b.rank_id = 80;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class = p.class
where b.rank_id = 90;


#1,100,Order,90,60,1996-07-11
update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = b.scientificname
where b.rank_id = 100;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order
where b.rank_id = 110;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order
where b.rank_id = 120;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order
where b.rank_id = 130;

#1,140,Family,130,100,1996-07-11
update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order, b.family = b.scientificname
where b.rank_id = 140;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order, b.family = p.family
where b.rank_id = 150;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order, b.family = p.family
where b.rank_id = 160;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order, b.family = p.family
where b.rank_id = 170;

#1,180,Genus,170,140,1996-07-11
update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order, b.family = p.family, b.genus = b.scientificname
where b.rank_id = 180;

#1,190,Subgenus,180,180,1996-07-11
update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order, b.family = p.family, b.genus = p.genus, b.subgenus = b.scientificname
where b.rank_id = 190;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order, b.family = p.family, b.genus = p.genus, b.subgenus = p.subgenus
where b.rank_id = 200;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order, b.family = p.family, b.genus = p.genus, b.subgenus = p.subgenus
where b.rank_id = 210;

#1,220,species

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order, b.family = p.family, b.genus = p.genus, b.subgenus = p.subgenus,
b.species = b.scientificname
where b.rank_id = 220;

update TaxonBranches b join TaxonBranches p on b.parent_tsn = p.tsn
set b.kingdom = p.kingdom, b.phylum = p.phylum, b.class= p.class,
b.order = p.order, b.family = p.family, b.genus = p.genus, b.subgenus = p.subgenus,
b.species = p.species
where b.rank_id > 220;