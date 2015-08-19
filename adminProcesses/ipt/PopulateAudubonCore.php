<?php


echo "truncate table " . $iptACTable . ";

 insert into " . $iptACTable . " (
id, 
occurrenceID,
identifier,
type,
title,
modified,
metadataLanguageLiteral,
providerManagedID,
available,
rights,
owner,
webStatement,
licenseLogoURL,
credit,
attributionLogoURL,
creator,
provider,
metadataProvider,
metadataCreator,
description,
tag,
subjectPart,
subjectOrientation,
captureDevice,
resourceCreationTechnique,

accessURI,
format,
variantLiteral,
PixelXDimension,
PixelYDimension,
furtherInformationURL)

select
i.id,
s.occurrenceID,
eo.externalId AS identifier, 
'StillImage' AS type,
concat ('image of ',s.scientificname) AS title,
b.dateLastModified AS modified,
'en' AS metadataLanguageLiteral,
concat('http://www.morphbank.net/',i.id) AS providerManagedID,
i.dateToPublish AS available,
cc.rights,
i.copyrightText AS Owner,
i.creativeCommons as webStatement,
cc.licenseLogoURL,
i.copyrightText AS credit,
u.userLogo AS attributionLogoURL,
i.photographer AS creator,
concat('http://www.morphbank.net/',b.userId) AS provider,
'morphbank.net' AS metadataProvider,
'morphbank.net' AS metadataCreator,
b.description AS description,
v.specimenPart AS tag, # tag with more info from view
v.specimenPart AS subjectPart,
v.viewAngle AS subjectOrientation,
v.imagingTechnique AS captureDevice,
v.imagingPreparationTechnique AS resourceCreationTechnique,


# original image as best quality access point
concat('http://www.morphbank.net?id=',i.id,'&imgType=jpeg') AS accessURI,
'image/jpeg' AS format,
'best quality' as variantLiteral,
i.imageWidth as PixelXDimension,
i.imageHeight as PixelYDimension,
concat('http://www.morphbank.net/',i.id) AS furtherInformationURL

from Image i 
join " . $iptOccTable . " s on (i.specimenid=s.id)
join BaseObject b on(i.id = b.id) 
left join View v on(i.viewId = v.id) 
join User u on(b.userId = u.id) 
join ExternalLinkObject eo on (i.id = eo.mbid and eo.description =  'dcterms:identifier')
left join CreativeCommons cc on  cc.idCreativeCommons = 32;

";
 ?>
