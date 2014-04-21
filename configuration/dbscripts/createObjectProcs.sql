SET NAMES utf8;

delimiter //

DROP PROCEDURE IF EXISTS ImageInsert; //
DROP PROCEDURE IF EXISTS LocalityInsert; //
DROP PROCEDURE IF EXISTS SpecimenInsert; //
DROP PROCEDURE IF EXISTS ViewInsert; //
DROP PROCEDURE IF EXISTS PublicationInsert; //

DROP FUNCTION IF EXISTS GetNewObjectId;//

CREATE FUNCTION GetNewObjectId () returns int
DETERMINISTIC
begin
    DECLARE oId, iMinId, iMaxId int;
    SELECT minId, maxId into iMinId, iMaxId from CurrentIds where type='object';
    SELECT max(id)+1 INTO oId FROM BaseObject where id >= iMinId and id < iMaxId;
    IF (oId > 0) THEN 
        return oId;
    END IF;
    return iMinId;
end//

DROP FUNCTION IF EXISTS GetNewTsn;//

CREATE FUNCTION GetNewTsn () returns int
DETERMINISTIC
begin
    DECLARE oTsn, iMinTsn, iMaxTsn int;
    SELECT minId, maxId into iMinTsn, iMaxTsn from CurrentIds where type='tsn';
    SELECT max(tsn+1) INTO oTsn FROM Tree where tsn >= iMinTsn and tsn < iMaxTsn;
    IF (oTsn > 0) THEN
        return oTsn;
    END IF;
    return iMinTsn;
end//

DROP PROCEDURE IF EXISTS CreateObject;//

CREATE PROCEDURE CreateObject(
  IN iTableName VARCHAR (50),
  IN iUserId INT,
  IN iGroupId INT,
  IN iSubBy INT,
  IN iDateToPublish DATE,
  IN iDescription VARCHAR(255),
  IN iName VARCHAR(64),
  IN iUUID VARCHAR (36))
BEGIN
    DECLARE userRec, groupRec, subByRec, oId INT;
    SELECT COUNT(*) INTO userRec FROM User WHERE id = iUserId;
    SELECT COUNT(*) INTO groupRec FROM Groups WHERE id = iGroupId;
    SELECT COUNT(*) INTO subByRec FROM User WHERE id = iSubBy;
    SELECT GetNewObjectId() INTO oId;

    IF userRec > 0 and groupRec > 0 and subByRec > 0 THEN
        INSERT INTO BaseObject (id, userId, groupId, submittedBy, dateCreated, dateLastModified, dateToPublish,
            objectTypeId, description, name, uuidString)
            VALUES (oId, iUserId, iGroupId, iSubBy, NOW(), NOW(), iDateToPublish, iTableName, 
            iDescription, iName, iUUID);
        set @sql = concat("insert into ", iTableName, " (id) values (", oId, ")");
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
    END IF;    
    SELECT oId;
END;//


DROP PROCEDURE IF EXISTS BaseObjectInsert;//

CREATE PROCEDURE BaseObjectInsert(IN iUserId INT, IN iGroupId INT, 
    IN iDateToPublish DATETIME, IN iObjectTypeId VARCHAR (50), 
    IN iDescription VARCHAR(255), IN iSubBy INT, IN iObjectLogo VARCHAR(25), 
    IN iKeywords TEXT, IN iSummaryHTML TEXT, IN iThumbURL VARCHAR(30),
    IN iName VARCHAR(64), OUT oId int) 
BEGIN
    DECLARE userRec, groupRec, subByRec INT;
    SELECT COUNT(*) INTO userRec FROM User WHERE id = iUserId;
    SELECT COUNT(*) INTO groupRec FROM Groups WHERE id = iGroupId;
    SELECT COUNT(*) INTO subByRec FROM User WHERE id = iSubBy;
    select GetNewObjectId() into oId;

    IF userRec > 0 and groupRec > 0 and subByRec > 0 THEN
        INSERT INTO BaseObject (id, userId, groupId, dateCreated, dateLastModified, dateToPublish,
            objectTypeId, description, submittedBy, objectLogo,keywords,summaryHTML, thumbURL, name) 
            VALUES (oId, iUserId, iGroupId, NOW(), NOW(), iDateToPublish, iObjectTypeId, 
            iDescription, iSubBy, iObjectLogo, iKeywords, iSummaryHTML, iThumbURL, iName);
    END IF;    
END;//

DROP PROCEDURE IF EXISTS AnnotationInsert;//

CREATE PROCEDURE AnnotationInsert(IN iObjectId INT(11), 
    IN iAnnotationObjectTypeId VARCHAR(50), IN iTypeAnnotation VARCHAR(25), 
    IN iXLocation INT(11), IN iYLocation INT(11), IN iAreaHeight INT(11), 
    IN iAreaWidth INT(11), IN iAreaRadius INT(11), IN iAnnotationQuality ENUM('0','1','2','3','4','5'), 
    IN iTitle VARCHAR(40), IN iAnnotationKeywords VARCHAR(40), IN iComment TEXT, IN iXmlData TEXT, 
    IN iExternalURL VARCHAR(255), IN iAnnotationLabel VARCHAR(25), IN iAnnotationMarkup VARCHAR(25), 
    IN iName VARCHAR(64), IN iObjectTypeId VARCHAR(50), IN iDescription VARCHAR(255), IN iSubBy INT, 
    IN iGroupId INT, IN iUserId INT, IN iDateToPublish DATETIME, IN iObjectLogo VARCHAR(25),
    IN iKeywords TEXT, IN iSummaryHTML TEXT, iThumbURL VARCHAR(30), 
    OUT oId INT)
BEGIN 
    DECLARE bId INT;
    SET bId = 0;
    CALL BaseObjectInsert(iUserId, iGroupId, iDateToPublish, iObjectTypeId, iDescription, 
        iSubBy, iObjectLogo,iKeywords, iSummaryHTML, iThumbURL,iName, bId);
    IF bId > 0 THEN
        SET oId = bId;
        INSERT INTO Annotation(id, objectId, objectTypeId, typeAnnotation, xLocation, yLocation, areaHeight, areaWidth, 
            areaRadius, annotationQuality, title, keywords, comment, xmlData, externalURL, annotationLabel, 
            annotationMarkup)values(bId, iObjectId, iAnnotationObjectTypeId, iTypeAnnotation, iXLocation, iYLocation, 
            iAreaHeight, iAreaWidth,iAreaRadius,iAnnotationQuality, iTitle, iAnnotationKeywords, iComment, iXmlData, 
            iExternalURL,iAnnotationLabel, iAnnotationMarkup);
    END IF;
END;//


DROP PROCEDURE IF EXISTS CollectionInsert;//

CREATE PROCEDURE CollectionInsert(IN iName VARCHAR(50),IN iSubBy INT, IN iGroupId INT, 
    IN iUserId INT, IN iDateToPublish DATE, IN iDescription VARCHAR(255))
BEGIN         
    DECLARE bId, oId INT;
    SET bId = 0;
    CALL BaseObjectInsert(iUserId, iGroupId, iDateToPublish, 'Collection', iDescription, 
        iSubBy, null, null, null, null, iName, bId);
    IF bId > 0 THEN
        SET oId = bId;
        INSERT INTO Collection (id, userId, groupId, name) VALUES (bId, iUserId, iGroupId, iName); 
    END IF;
    SELECT oId;
END;//

DROP PROCEDURE IF EXISTS GroupsInsert;//

CREATE PROCEDURE GroupsInsert(IN iGrpName VARCHAR(128), IN iTsn BIGINT(20), IN iGrpMngId INT(11), IN iStatus TINYINT(1), IN iSubBy INT, IN iGroupId INT, IN iUserId INT, IN iDescription VARCHAR(255), OUT oId INT, OUT oError TEXT)
BEGIN  
    DECLARE bId INT;
    SET bId = 0;
    CALL BaseObjectInsert(iUserId, iGroupId, iDateToPublish, 'Groups', iDescription, 
        iSubBy, null, null, null, null, null, bId);
    IF bId > 0 THEN
        SET oId = bId;
        INSERT INTO Groups (id, groupName, tsn, groupManagerId, status, dateCreated) 
            VALUES (oId, iGrpName, iTsn, iGrpMngId, iStatus, NOW()); 
    END IF; 
END;//

DROP PROCEDURE IF EXISTS NewsInsert;//

CREATE PROCEDURE NewsInsert(IN iDateToPublish DATETIME, IN iTitle VARCHAR(64), IN iBody TEXT, IN iImage VARCHAR(64), 
    IN iImgText VARCHAR(128), IN iStatus TINYINT(1), IN iSubBy INT, IN iGroupId INT, 
    IN iUserId INT, IN iDescription VARCHAR(255))
BEGIN
    DECLARE oId, bId INT;
    SET  bId = 0;
    CALL BaseObjectInsert(iUserId, iGroupId, iDateToPublish, 'News', iDescription, 
        iSubBy, null, null, null, null, null, bId);
    IF bId > 0 THEN
        SET oId = bId;
        INSERT INTO News (id, title, body, image, imageText, dateCreated, status) 
            VALUES (oId, iTitle, iBody, iImage, iImgText, NOW(), iStatus);
        SELECT oId;
    END IF;
END;//


DROP PROCEDURE IF EXISTS OtuInsert;//

CREATE PROCEDURE OtuInsert(IN iLabel VARCHAR(32), IN iName VARCHAR(64), 
    IN iObjectTypeId VARCHAR(50), IN iDescription VARCHAR(255), IN iSubBy INT, 
    IN iGroupId INT, IN iUserId INT, OUT oId INT)
BEGIN
    DECLARE bId INT;
    SET bId = 0;
    CALL BaseObjectInsert(iUserId, iGroupId, iDateToPublish, iObjectTypeId, iDescription, 
        iSubBy, iObjectLogo,iKeywords, iSummaryHTML, iThumbURL, iName, bId);
    IF bId > 0 THEN
        SET oId = bId;
        INSERT INTO Otu(id,label) VALUES(bId, iLabel);
    END IF;
END;//

DROP PROCEDURE IF EXISTS PhyloCharacterInsert;//

CREATE PROCEDURE PhyloCharacterInsert(IN iLabel VARCHAR(32),
    IN icharacterNumber VARCHAR(32), IN iDescrete INT, iOrdered INT, 
    IN iPublicationId INT, IN iPubComment TEXT, IN iName VARCHAR(64), 
    IN iObjectTypeId VARCHAR(50), IN iDescription VARCHAR(255), IN iSubBy INT, 
    IN iGroupId INT, IN iUserId INT, IN iDateToPublish DATETIME, 
    IN iObjectLogo VARCHAR(25),IN iKeywords TEXT, IN iSummaryHTML TEXT, 
    IN iThumbURL VARCHAR(30), OUT oId INT)
BEGIN 
    DECLARE bId INT;
    SET bId = 0;
    CALL BaseObjectInsert(iUserId, iGroupId, iDateToPublish, iObjectTypeId, iDescription, 
        iSubBy, iObjectLogo,iKeywords, iSummaryHTML, iThumbURL,iName, bId);
    IF bId > 0 THEN
        SET oId = bId;
        INSERT INTO MbCharacter(id, label, characterNumber, discrete, ordered, 
              publicationId, pubComment) VALUES(bId, iLabel, iCharacterNumber, 
              iDescrete, iOrdered, iPublicationId, iPubComment);
    END IF;
END;//

DROP PROCEDURE IF EXISTS PhyloCharacterStateInsert;//

CREATE PROCEDURE PhyloCharacterStateInsert(IN iCharStateValue VARCHAR(32), 
    IN iName VARCHAR(64), IN iSubBy INT, IN iGroupId INT, IN iUserId INT, 
    IN iDateToPublish DATETIME, IN iDescription VARCHAR(255), IN iObjectTypeId VARCHAR(50), 
    IN iObjectLogo VARCHAR(25), IN iKeywords TEXT, IN iSummaryHTML TEXT, 
    IN iThumbURL VARCHAR(30), OUT oId INT)
BEGIN
    DECLARE bId INT;
    SET bId = 0;
    CALL BaseObjectInsert(iUserId, iGroupId, iDateToPublish, iObjectTypeId, iDescription, iSubBy, iObjectLogo,iKeywords, iSummaryHTML, 
        iThumbURL, iName, bId);
    IF bId > 0 THEN
        SET oId = bId;
        INSERT INTO CharacterState(id, charStateValue) VALUES(bId, iCharStateValue);
    END IF;
END;//


DROP PROCEDURE IF EXISTS TaxaInsert //
CREATE PROCEDURE TaxaInsert(IN iBoId INT, IN iTsn INT, IN iScientificName TEXT, 
    IN iTaxonAuthorId INT, IN iTaxonAuthorName VARCHAR(100), IN iStatus VARCHAR(12), 
    IN iParentTsn BIGINT, IN iParentName TEXT, IN iKingdomId SMALLINT, IN iKingdomName VARCHAR(10), 
    IN iRankId SMALLINT, IN iRankName VARCHAR(15), IN iImagesCount BIGINT, IN iNameType VARCHAR(32), 
    IN iNameSource VARCHAR(64), IN iPublicationId INT, IN iGroupId INT, IN iUserId INT, 
    IN iDateToPublish DATETIME, IN iKeywords TEXT, IN iObjectTypeId VARCHAR(50))
BEGIN
    INSERT INTO Taxa (boId, tsn, scientificName, taxon_author_id, taxon_author_name, status,
        parent_tsn, parent_name, kingdom_id, kingdom_name, rank_id, rank_name, imagesCount, 
        nameType, nameSource, publicationId, userId, groupId, dateToPublish, keywords, objectTypeId) 
        VALUES (iBoId, iTsn, iScientificName, iTaxonAuthorId, iTaxonAuthorName, iStatus, iParentTsn, 
        iParentName, iKingdomId, iKingdomName, iRankId, iRankName, iImagesCount, iNameType, iNameSource, 
        iPublicationId, iUserId, iGroupId, iDateToPublish, iKeywords, iObjectTypeId);
END //

DROP PROCEDURE IF EXISTS TaxonConceptInsert;//

CREATE PROCEDURE TaxonConceptInsert(IN iTsn INT, IN iNameSpace VARCHAR(32), 
    IN iStatus VARCHAR(32), IN iSubBy INT, IN iGroupId INT, IN iUserId INT, 
    IN iDescription VARCHAR(255), IN iDateToPublish DATETIME, IN iObjectTypeId VARCHAR(50), 
    IN iObjectLogo VARCHAR(25),IN iKeywords TEXT, IN iSummaryHTML TEXT, 
    IN iThumbURL VARCHAR(30), IN iName VARCHAR(64), OUT oId INT)
BEGIN
    DECLARE bId INT; 
    DECLARE iParentTsn BIGINT; 
    DECLARE iKingdomId SMALLINT; 
    DECLARE iRankId SMALLINT;
    DECLARE iImagesCount INT; 
    DECLARE iNameType VARCHAR(32); 
    DECLARE iPublicationId INT; 
    DECLARE iNameSource VARCHAR(64);
    DECLARE iKingdomName VARCHAR(10); 
    DECLARE iRankName VARCHAR(15);
    SET bId=0; 
    SET iParentTsn=0; 
    SET iKingdomId=0; 
    SET iRankId=0; 
    SET iImagesCount=0; 
    SET iNameType="";
    SET iPublicationId=0; 
    SET iNameSource=""; 
    SET iKingdomName=""; 
    SET iRankName="";
    CALL BaseObjectInsert(iUserId, iGroupId, iDateToPublish, iObjectTypeId, iDescription, iSubBy, iObjectLogo, iKeywords, 
         iSummaryHTML, iThumbURL, iName, bId);
    IF bId > 0 THEN
        SET oId = bId;
        INSERT INTO TaxonConcept (id, tsn, nameSpace, status) 
            VALUES (bId, iTsn, iNameSpace, iStatus);
        SELECT parent_tsn INTO iParentTsn FROM Tree WHERE tsn=iTsn;
        SELECT kingdom_id INTO iKingdomId FROM Tree WHERE tsn=iTsn;
        SELECT rank_id INTO iRankId FROM Tree WHERE tsn=iTsn;
        SELECT imagesCount INTO iImagesCount FROM Tree WHERE tsn=iTsn;
        SELECT nameType INTO iNameType FROM Tree WHERE tsn=iTsn;
        SELECT publicationId INTO iPublicationId FROM Tree WHERE tsn=iTsn;
        SELECT nameSource INTO iNameSource FROM Tree WHERE tsn=iTsn;
        SELECT kingdom_name INTO iKingdomName From Kingdoms WHERE kingdom_id=iKingdomId;
        SELECT rank_name INTO iRankName From TaxonUnitTypes 
            WHERE rank_id=iRankId and kingdom_id=iKingdomId;
        IF iTsn >= 999000000 THEN
            CALL TaxaInsert(bId, iTsn, iName, iTaxonAuthorId, iTaxonAuthorName, iStatus, 
                iParentTsn, iParentName, iKingdomId, iKingdomName, iRankId, iRankName, 
                iImagesCount, iNameType, iNameSource, iPublicationId, 
                iGroupId, iUserId, iDateToPublish, iKeywords, iObjectTypeId);
        ELSE
            UPDATE Taxa SET boId = bId WHERE tsn = iTsn;                
        END IF;
    END IF;
END;//

DROP PROCEDURE IF EXISTS TreeInsert //

CREATE  PROCEDURE `TreeInsert`(IN iNameSpace VARCHAR(32),  IN iStatus VARCHAR(32),
IN iSubBy INT, IN iGroupId INT, IN iUserId INT,  
IN iDateToPublish DATETIME, IN iUnacceptReason VARCHAR(50),
IN iParentTsn BIGINT, IN iKingdomId SMALLINT,IN iRankId SMALLINT, IN iLetter VARCHAR(1),
IN iScientificName TEXT, IN iTaxonAuthorId INT, IN iPublicationId INT, IN iPages VARCHAR(32), 
IN iNameType VARCHAR(32),IN iNameSource VARCHAR(64), IN iComments TEXT)
BEGIN
DECLARE iTsn INT;
DECLARE bId INT; 
DECLARE iKingdomName VARCHAR(10);
DECLARE iRankName VARCHAR(15);
DECLARE iTaxonAuthorName VARCHAR(100);
DECLARE iParentName VARCHAR(100);
SET iTsn=0; SET bId=0;
SET iKingdomName=""; 
SET iRankName="";
SELECT GetNewTsn() INTO iTsn;

INSERT INTO Tree(tsn, `usage`, unaccept_reason, parent_tsn, kingdom_id, rank_id, letter,
scientificName, taxon_author_id, publicationId, pages, nameType, nameSource, comments) 
values (iTsn, iStatus, iUnacceptReason, iParentTsn, iKingdomId,
iRankId, iLetter, iScientificName, iTaxonAuthorId, iPublicationId, iPages, iNameType,
iNameSource, iComments);
CALL BaseObjectInsert(iUserId, iGroupId, iDateToPublish, 'TaxonConcept', 'taxon', iSubBy,
null, null, null, null, iScientificName, bId);
IF bId > 0 THEN
INSERT INTO TaxonConcept (id, tsn, nameSpace, status) VALUES (bId, iTsn, iNameSpace, iStatus);
SELECT kingdom_name INTO iKingdomName From Kingdoms WHERE kingdom_id=iKingdomId;
SELECT rank_name INTO iRankName From TaxonUnitTypes WHERE rank_id=iRankId and kingdom_id=iKingdomId;
SELECT taxon_author into iTaxonAuthorName FROM TaxonAuthors WHERE taxon_author_id=iTaxonAuthorId;
SELECT scientificName into iParentName FROM Tree where tsn=iParentTsn;
CALL TaxaInsert(bId, iTsn, iScientificName, iTaxonAuthorId, iTaxonAuthorName , iStatus, iParentTsn, iParentName, 
iKingdomId, iKingdomName, iRankId, iRankName, 0, iNameType , iNameSource, iPublicationId,
iGroupId, iUserId, iDateToPublish, NULL, 'TaxonConcept');
END IF;
select iTsn;
END//


DROP PROCEDURE IF EXISTS UserInsert;//

CREATE PROCEDURE UserInsert(IN iPrivTsn BIGINT(20), IN iuin VARCHAR(41), 
    IN ipin VARCHAR(41), IN iName VARCHAR(128), IN iEmail VARCHAR(128), 
    IN iAffiliation VARCHAR(255), IN iAddress VARCHAR(255), 
    IN iLastName VARCHAR(35), IN iFirstName VARCHAR(35), 
    IN iSuffix VARCHAR(10), IN iMiddleInit CHAR(1), IN iStreet1 VARCHAR(35), 
    IN iStreet2 VARCHAR(35), IN iCity VARCHAR(25), IN iCountry VARCHAR(25), 
    IN iState CHAR(2), IN iZipcode VARCHAR(10), IN iStatus TINYINT(1), 
    IN iPrimTsn BIGINT(20), IN iSecTsn BIGINT(20), IN iSubBy INT, IN iGroupId INT, 
    IN iUserId INT, IN iDescription VARCHAR(255), OUT oId INT, OUT oError TEXT)
BEGIN 
    main: begin 
        DECLARE bId INT;
        DECLARE userRec, groupRec, subByRec, t INT; 
        SELECT COUNT(*) INTO t FROM User WHERE uin = iuin; 
        IF t > 0 THEN  
            SET oError = 'Username exists. choose another one'; 
            LEAVE main;  
        END IF; 
        SELECT id INTO t FROM User WHERE affiliation = iaffiliation AND privilegeTSN = iPrivTsn 
            AND country = icountry AND last_name = iLastName AND first_name = iFirstName;  
        IF t > 0 THEN  
            SET oError = 'User exists';  
            SET oId = t; 
            LEAVE main; 
        END IF; 
        SET bId = 0;
        CALL BaseObjectInsert(iUserId, iGroupId, iDateToPublish, 'Groups', iDescription, 
            iSubBy, null, null, null, null, iName, bId);
        IF bId > 0 THEN
            SET oId = bId;
            INSERT INTO User (id, privilegeTSN, uin, pin, name, email, affiliation, 
                address, last_Name, first_Name, suffix, middle_init, street1, street2, 
                city, country, state, 
                zipcode, status, primaryTSN, secondaryTSN, dateCreated) 
                VALUES (oId, iPrivTsn, iuin, ipin, iName, iEmail, iAffiliation, 
                iAddress, iLastName, iFirstName, iSuffix, iMiddleInit, iStreet1, 
                iStreet2, iCity, iCountry, iState, iZipcode, iStatus, iPrimTsn, 
                iSecTsn, NOW()); 
        END IF; 
    END main; 
END;//

delimiter ;
