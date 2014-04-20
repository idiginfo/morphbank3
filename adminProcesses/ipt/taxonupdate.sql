


update IptOccurrence o join Specimen s on o.id=s.id 
join TaxonBranches n on (n.tsn = s.tsnId)
set o.kingdom = n.kingdom,o.phylum = n.phylum, o.class =n.class,
o.order = n.order, o.family = n.family, o.genus=n.genus,
o.subgenus=n.subgenus
;


