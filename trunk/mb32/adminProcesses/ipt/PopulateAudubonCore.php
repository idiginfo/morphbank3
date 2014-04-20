<?php


echo "truncate table IptAudubonCore" . $dataSetName . ";

 insert into IptAudubonCore" . $dataSetName . "
(
id, 
occurrenceID,
identifier,
type,
title,
modified,
metadataLanguage,
providerManagedID,
available,
rights,
owner,
webStatement,
licenseLogoURL,
creditLine,
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
thumbNailAccessURI,
thumbNailFormat,
mediumQualityAccessURI,
mediumQualityFormat,
goodQualityAccessURI,
goodQualityFormat,
goodQualityExtent,
bestQualityAccessURI,
bestQualityFormat,
bestQualityExtent,
bestQualityFurtherInformationURL)

select
i.id,
s.occurrenceID,
eo.externalId AS identifier, 
'StillImage' AS type,
concat ('image of ',s.scientificname) AS title,
b.dateLastModified AS modified,
'en' AS metadataLanguage,
concat('http://www.morphbank.net/',i.id) AS providerManagedID,
i.dateToPublish AS available,
cc.rights,
i.copyrightText AS Owner,
i.creativeCommons as webStatement,
cc.licenseLogoURL,
i.copyrightText AS creditLine,
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

#thumbnail access point
concat('http://www.morphbank.net?id=',i.id,'&imgType=thumb') AS thumbNailAccessURI,
'image/jpeg' AS thumbNailFormat,

# 400 pixel wide as medium quality
concat('http://www.morphbank.net?id=',i.id,'&imgType=jpg') AS mediumQualityAccessURI,
'image/jpeg' AS mediumQualityFormat,

#jpeg image as good quality access point
concat('http://www.morphbank.net?id=',i.id,'&imgType=jpeg') AS goodQualityAccessURI,
'image/jpeg' AS goodQualityFormat,
concat(i.imageHeight,' x ',i.imageWidth) AS goodQualityExtent,

# original image as best quality access point
concat('http://www.morphbank.net?id=',i.id,'&imgType=',replace(i.imageType,'jpg','jpeg')) AS bestQualityAccessURI,
concat('image/',replace(i.imageType,'jpg','jpeg')) AS bestQualityFormat,
concat(i.imageHeight,' x ',i.imageWidth) AS bestQualityExtent,
concat('http://www.morphbank.net/',i.id) AS bestQualityFurtherInformationURL

from Image i 
join IptOccurrence" . $dataSetName . " s on (i.specimenid=s.id)
join BaseObject b on(i.id = b.id) 
left join View v on(i.viewId = v.id) 
join User u on(b.userId = u.id) 
join ExternalLinkObject eo on (s.id = eo.mbid and eo.description =  'dcterms:identifier')
left join CreativeCommons cc on  cc.idCreativeCommons = 32;

";
 ?>
