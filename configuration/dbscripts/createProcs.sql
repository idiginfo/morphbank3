DELIMITER ;;

DROP PROCEDURE IF EXISTS ImageUpdate ;;
DROP PROCEDURE IF EXISTS LocalityUpdate ;;
DROP PROCEDURE IF EXISTS SpecimenUpdate ;;
DROP PROCEDURE IF EXISTS ViewUpdate ;;
DROP PROCEDURE IF EXISTS PublicationUpdate ;;

DROP PROCEDURE IF EXISTS BaseObjectUpdate;;

CREATE PROCEDURE BaseObjectUpdate(OUT oMsg TEXT, 
	IN iId INT, IN iUpdates TEXT, IN iGroupId INT, IN iUserId INT, 
	IN iModifiedFrom TEXT, IN iModifiedTo TEXT, IN iTableName VARCHAR(30))
BEGIN
	SET @sql = CONCAT("UPDATE BaseObject SET " , iUpdates, " WHERE id = ", iId);
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	INSERT INTO History (Id, userId, groupId, dateModified, modifiedFrom,
		modifiedTo, tableName) VALUES (iId, iUserId, iGroupId, NOW(), iModifiedFrom, 
		iModifiedTo, iTableName );
	SELECT ROW_COUNT();
END ;;

DROP PROCEDURE IF EXISTS DevelopmentalStageInsert ;;

CREATE PROCEDURE DevelopmentalStageInsert(IN iName VARCHAR(64), 
	IN iDescription VARCHAR(128))
BEGIN
	DECLARE countRecs INT;
	SELECT COUNT(*) INTO countRecs FROM DevelopmentalStage 
		WHERE name = iName AND description = iDescription;
	IF countRecs = 0 THEN
		INSERT INTO DevelopmentalStage (name, description) 
			VALUES (iName, iDescription);
	END IF;
END ;;

DROP PROCEDURE IF EXISTS DevelopmentalStageUpdate ;;

CREATE PROCEDURE DevelopmentalStageUpdate(OUT oMsg TEXT, IN iId VARCHAR(128), 
	IN iUpdates TEXT, IN iGroupId INT, IN iUserId INT, IN iModifiedFrom TEXT, 
	IN iModifiedTo TEXT, IN iTableName VARCHAR(30))
BEGIN
	SET @sql = CONCAT("UPDATE DevelopmentalStage SET " , iUpdates, " WHERE name = '", iId, "'");
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	INSERT INTO History (Id, userId, groupId, dateModified, modifiedFrom, modifiedTo, tableName) 
		VALUES (iId, iUserId, iGroupId, NOW(), iModifiedFrom, iModifiedTo, iTableName );
END ;;

DROP PROCEDURE IF EXISTS ExternalLinkObjectInsert;;
CREATE PROCEDURE ExternalLinkObjectInsert(IN iMbId INT, IN iExtLinkTypeId INT, IN iLabel VARCHAR(64), 
				IN iURLData VARCHAR(255), IN iDescription VARCHAR(128),	IN iExtId VARCHAR(512))
BEGIN
	DECLARE MbId, linkType INT;
	SELECT COUNT(*) INTO MbId FROM BaseObject WHERE id = iMbId;
	SELECT COUNT(*) INTO linktype FROM ExternalLinkType WHERE linkTypeId = iExtLinktypeId;
	IF MbId > 0 and linkType > 0 THEN
		INSERT INTO ExternalLinkObject (mbId, extLinkTypeId, Label, urlData, description, externalId) 
			VALUES (iMbId, iExtLinkTypeId, iLabel, iURLData, iDescription, iExtId); 
		SELECT LAST_INSERT_ID();
	END IF;	
END;;


DROP PROCEDURE IF EXISTS ExternalLinkObjectUpdate ;;

CREATE PROCEDURE ExternalLinkObjectUpdate(IN iLinkId INT, IN iMBId INT, IN iExtLinkTypeID INT,
	IN iLabel TEXT, IN iUrlData TEXT, IN iDescription TEXT, IN iExternalId TEXT)
BEGIN
	UPDATE ExternalLinkObject SET mbId = iMBId, extLinkTypeId = iExtLinkTypeID, label = iLabel,
		urlData = iUrlData, description = iDescription, externalId = iExternalId 
		WHERE linkId = iLinkId AND mbId = iMBId;
	SELECT ROW_COUNT();
END ;;

DROP PROCEDURE IF EXISTS ExternalLinkTypeUpdate ;;

CREATE PROCEDURE ExternalLinkTypeUpdate(OUT oMsg TEXT, IN iId VARCHAR(128), 
	IN iUpdates TEXT, IN iGroupId INT, IN iUserId INT, IN iModifiedFrom TEXT, 
	IN iModifiedTo TEXT, IN iTableName VARCHAR(30))
BEGIN
	SET @sql = CONCAT("UPDATE ExternalLinkType SET " , iUpdates, " WHERE name = '", iId, "'");
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	INSERT INTO History (Id, userId, groupId, dateModified, modifiedFrom, modifiedTo, tableName) 
		VALUES (iId, iUserId, iGroupId, NOW(), iModifiedFrom, iModifiedTo, iTableName );
END ;;

DROP PROCEDURE IF EXISTS FormInsert ;;

CREATE PROCEDURE FormInsert(IN iName VARCHAR(64), IN iDescription VARCHAR(128))
BEGIN
	DECLARE countRecs INT;
	SELECT COUNT(*) INTO countRecs FROM Form WHERE name = iName AND description = iDescription;
	IF countRecs = 0 THEN
		INSERT INTO Form (name, description) VALUES (iName, iDescription);
	END IF;
END ;;

DROP PROCEDURE IF EXISTS FormUpdate ;;

CREATE PROCEDURE FormUpdate(OUT oMsg TEXT, IN iId VARCHAR(128), IN iUpdates TEXT, 
	IN iGroupId INT, IN iUserId INT, IN iModifiedFrom TEXT, IN iModifiedTo TEXT, 
	IN iTableName VARCHAR(30))
BEGIN
	SET @sql = CONCAT("UPDATE Form SET " , iUpdates, " WHERE name = '", iId, "'");
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	INSERT INTO History (Id, userId, groupId, dateModified, modifiedFrom, modifiedTo, tableName) 
		VALUES (iId, iUserId, iGroupId, NOW(), iModifiedFrom, iModifiedTo, iTableName );
END ;;

DROP PROCEDURE IF EXISTS HistoryInsert ;;

CREATE PROCEDURE HistoryInsert(IN iId INT, IN iUserId INT, IN iGroupId INT, 
	IN iDateModified DATETIME, IN iModifiedFrom TEXT, IN iModifiedTo TEXT, 
	IN iTableName VARCHAR(30))
BEGIN
	DECLARE userRec, groupRec, baseRec INT;
	SELECT COUNT(*) INTO userRec FROM User WHERE id = iUserId;
	SELECT COUNT(*) INTO groupRec FROM Groups WHERE id = iGroupId;
	SELECT COUNT(*) INTO baseRec FROM BaseObject WHERE id = iId;
	IF userRec > 0 and groupRec > 0 and baseRec > 0 THEN
		INSERT INTO History (Id, userId, groupId, dateModified, modifiedFrom, 
			modifiedTo, tableName) 
			VALUES (iId, iUserId, iGroupId, iDateModified, iModifiedFrom, 
			iModifiedTo, iTableName );
	END IF;
END ;;

DROP PROCEDURE IF EXISTS ImageDelete ;;

CREATE PROCEDURE ImageDelete(OUT oMsg TEXT, IN iId INT)
BEGIN
	DECLARE Count, CountColl INT;
	SELECT COUNT(*) INTO Count FROM Annotation WHERE objectId = iId;
	SELECT COUNT(*) INTO CountColl FROM CollectionObjects WHERE objectId = iId;
	IF Count = 0 AND CountColl = 0 THEN
		DELETE FROM Image WHERE id = iId;
		DELETE FROM ExternalLinkObject WHERE mbId = iId; 
		DELETE FROM BaseObject WHERE id = iId;
		DELETE FROM Keywords WHERE id = iId;
		SET oMsg = "Successfully Deleted Image";
	ELSE
		SET oMsg = "Deletion Failed";
	END IF;
END ;;


DROP PROCEDURE IF EXISTS ImagingPreparationTechniqueInsert ;;

CREATE PROCEDURE ImagingPreparationTechniqueInsert(IN iName VARCHAR(64), IN iDescription VARCHAR(128))
BEGIN
	DECLARE countRecs INT;
	SELECT COUNT(*) INTO countRecs FROM ImagingPreparationTechnique WHERE name = iName AND description = iDescription;
	IF countRecs = 0 THEN
		INSERT INTO ImagingPreparationTechnique (name, description) VALUES (iName, iDescription);
	END IF;
END ;;

DROP PROCEDURE IF EXISTS ImagingPreparationTechniqueUpdate ;;

CREATE PROCEDURE ImagingPreparationTechniqueUpdate(OUT oMsg TEXT, IN iId VARCHAR(128), 
	IN iUpdates TEXT, IN iGroupId INT, IN iUserId INT, IN iModifiedFrom TEXT, 
	IN iModifiedTo TEXT, IN iTableName VARCHAR(30))
BEGIN
	SET @sql = CONCAT("UPDATE ImagingPreparationTechnique SET " , iUpdates, " WHERE name = '", iId, "'");
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	INSERT INTO History (Id, userId, groupId, dateModified, modifiedFrom, modifiedTo, tableName) 
		VALUES (iId, iUserId, iGroupId, NOW(), iModifiedFrom, iModifiedTo, iTableName );
END ;;

DROP PROCEDURE IF EXISTS ImagingTechniqueInsert ;;

CREATE PROCEDURE ImagingTechniqueInsert(IN iName VARCHAR(64), IN iDescription VARCHAR(128))
BEGIN
	DECLARE countRecs INT;
	SELECT COUNT(*) INTO countRecs FROM ImagingTechnique WHERE name = iName AND description = iDescription;
	IF countRecs = 0 THEN
		INSERT INTO ImagingTechnique (name, description) VALUES (iName, iDescription);
	END IF;
END ;;

DROP PROCEDURE IF EXISTS ImagingTechniqueUpdate ;;

CREATE PROCEDURE ImagingTechniqueUpdate(OUT oMsg TEXT, IN iId VARCHAR(128), 
	IN iUpdates TEXT, IN iGroupId INT, IN iUserId INT, IN iModifiedFrom TEXT, 
	IN iModifiedTo TEXT, IN iTableName VARCHAR(30))
BEGIN
	SET @sql = CONCAT("UPDATE ImagingTechnique SET " , iUpdates, " WHERE name = '", iId, "'");
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	INSERT INTO History (Id, userId, groupId, dateModified, modifiedFrom, modifiedTo, tableName) 
		VALUES (iId, iUserId, iGroupId, NOW(), iModifiedFrom, iModifiedTo, iTableName );
END ;;

DROP PROCEDURE IF EXISTS Keywords ;;

CREATE PROCEDURE Keywords(IN iBaseSql TEXT, IN iKeywordSql TEXT)
BEGIN
	SET @sql = iBaseSql;
	PREPARE stmt FROM @sql;EXECUTE stmt;
	SET @sqlKey = iKeywordSql;
	PREPARE stmtKey FROM @sqlKey;
	EXECUTE stmtKey;
END ;;

DROP PROCEDURE IF EXISTS LocalityDelete ;;

CREATE PROCEDURE LocalityDelete(OUT oMsg TEXT, IN iId INT)
BEGIN 
	DECLARE Count INT; 
	SELECT COUNT(*) INTO Count FROM Specimen WHERE localityId = iId; 
	IF Count = 0 THEN 
		DELETE FROM Locality WHERE id = iId;
		DELETE FROM ExternalLinkObject WHERE mbId = iId;
		DELETE FROM BaseObject WHERE id = iId; 
		DELETE FROM Keywords WHERE id = iId;
		SET oMsg = "Successfully Deleted Locality";
	ELSE
		SET oMsg = "Deletion Failed";
	END IF;
END ;;

DROP PROCEDURE IF EXISTS MassTaxa ;;

CREATE PROCEDURE MassTaxa(IN iTree TEXT, IN iTaxon TEXT, IN iBase TEXT)
BEGIN 
	SET @sql = iTree; 
	PREPARE stmt FROM @sql; 
	EXECUTE stmt; 
	SET @sqlTaxa= iTaxon; 
	PREPARE stmtTaxa FROM @sqlTaxa; 
	EXECUTE stmtTaxa; 
	SET @sqlTaxa= iBase; 
	PREPARE stmtBase FROM @sqlBase; 
	EXECUTE stmtBase; 
END ;;

DROP PROCEDURE IF EXISTS SetGeolocated ;;

CREATE PROCEDURE SetGeolocated()
begin
	delete from Geolocated;
	insert into Geolocated select id from Locality where not longitude is null;
	insert into Geolocated select id from Specimen where localityid in (select id from Geolocated);
	insert into Geolocated select id from Image where specimenid in (select id from Geolocated);
	update BaseObject set geolocated = false;
	update BaseObject b join Geolocated g on b.id=g.id
    set b.geolocated = true;
	update Keywords set geolocated = false;
	update Keywords k join Geolocated g on k.id=g.id 
    set k.geolocated = true where k.id in (select id from Geolocated);
    select row_count();
end ;;

DROP PROCEDURE IF EXISTS SexInsert ;;

CREATE PROCEDURE SexInsert(IN iName VARCHAR(64), IN iDescription VARCHAR(128))
BEGIN
	DECLARE countRecs INT;
	SELECT COUNT(*) INTO countRecs FROM Sex WHERE name = iName AND description = iDescription;
	IF countRecs = 0 THEN
		INSERT INTO Sex (name, description) VALUES (iName, iDescription);
	END IF;
END ;;

DROP PROCEDURE IF EXISTS SexUpdate ;;

CREATE PROCEDURE SexUpdate(OUT oMsg TEXT, IN iId VARCHAR(128), IN iUpdates TEXT, 
	IN iGroupId INT, IN iUserId INT, IN iModifiedFrom TEXT, IN iModifiedTo TEXT, 
	IN iTableName VARCHAR(30))
BEGIN
	SET @sql = CONCAT("UPDATE Sex SET " , iUpdates, " WHERE name = '", iId, "'");
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	INSERT INTO History (Id, userId, groupId, dateModified, modifiedFrom, modifiedTo, tableName) 
		VALUES (iId, iUserId, iGroupId, NOW(), iModifiedFrom, iModifiedTo, iTableName );
END ;;


DROP PROCEDURE IF EXISTS SpecimenDelete ;;

CREATE PROCEDURE SpecimenDelete(OUT oMsg TEXT, IN iId INT)
BEGIN 
		DECLARE Count INT; 
		SELECT COUNT(*) INTO Count FROM Image WHERE specimenId = iId; 
		IF Count = 0 THEN DELETE FROM Specimen WHERE id = iId; 
			DELETE FROM ExternalLinkObject WHERE mbId = iId; 
			DELETE FROM BaseObject WHERE id = iId; 
			DELETE FROM Keywords WHERE id = iId; 
			SET oMsg = "Successfully Deleted Specimen"; 
		ELSE 
			SET oMsg = "Deletion Failed"; 
	END IF; 
END ;;

DROP PROCEDURE IF EXISTS SpecimenPartInsert ;;

CREATE PROCEDURE SpecimenPartInsert(IN iName VARCHAR(64), IN iDescription VARCHAR(128))
BEGIN
	DECLARE countRecs INT;
	SELECT COUNT(*) INTO countRecs FROM SpecimenPart 
		WHERE name = iName AND description = iDescription;
	IF countRecs = 0 THEN
		INSERT INTO SpecimenPart (name, description) VALUES (iName, iDescription);
	END IF;
END ;;

DROP PROCEDURE IF EXISTS SpecimenPartUpdate ;;

CREATE PROCEDURE SpecimenPartUpdate(OUT oMsg TEXT, IN iId VARCHAR(128), IN iUpdates TEXT, 
	IN iGroupId INT, IN iUserId INT, IN iModifiedFrom TEXT, IN iModifiedTo TEXT, IN iTableName VARCHAR(30))
BEGIN
	SET @sql = CONCAT("UPDATE SpecimenPart SET " , iUpdates, " WHERE name = '", iId, "'");
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	INSERT INTO History (Id, userId, groupId, dateModified, modifiedFrom, modifiedTo, tableName) 
		VALUES (iId, iUserId, iGroupId, NOW(), iModifiedFrom, iModifiedTo, iTableName );
END ;;


DROP PROCEDURE IF EXISTS UpdateKeywordsTable ;;

CREATE PROCEDURE UpdateKeywordsTable()
BEGIN
	delete from Keywords where id in (select id from RecentlyModified);
	insert into Keywords(id, userId, groupId, dateToPublish, objectTypeId,
		keywords, submittedBy, geolocated, xmlKeywords)
	select B.id, userId, groupId, dateToPublish, B.objectTypeId,
		keywords, submittedBy, geolocated, xmlKeywords
		from BaseObject B join RecentlyModified R on R.id=B.id;
	repair table Keywords quick;
END ;;

DROP PROCEDURE IF EXISTS UserGroupInsert ;;

CREATE PROCEDURE UserGroupInsert(IN iUser INT(11), IN iGroups INT(11), IN iUserId INT(11), 
	IN iUserGroupRole CHAR(20), OUT oError TEXT)
BEGIN 
	main: begin 
		DECLARE countRecs INT; 
		SELECT COUNT(*) INTO countRecs FROM UserGroup WHERE user = iUser AND groups = iGroups; 
		IF countRecs > 0 THEN 
			SET oError = 'User exists in the same group'; 
			LEAVE main; 
		END IF; 
		INSERT INTO UserGroup (user, groups, userId, dateLastModified, dateCreated, dateToPublish, userGroupRole) 
			VALUES (iUser, iGroups, iUserId, NOW(),NOW(), NOW(), iUserGroupRole);
	END main; 
END ;;


DROP PROCEDURE IF EXISTS ViewAngleInsert ;;

CREATE PROCEDURE ViewAngleInsert(IN iName VARCHAR(64), IN iDescription VARCHAR(128))
BEGIN
	DECLARE countRecs INT;
	SELECT COUNT(*) INTO countRecs FROM ViewAngle WHERE name = iName AND description = iDescription;
	IF countRecs = 0 THEN
		INSERT INTO ViewAngle (name, description) VALUES (iName, iDescription);
	END IF;
END ;;

DROP PROCEDURE IF EXISTS ViewAngleUpdate ;;

CREATE PROCEDURE ViewAngleUpdate(OUT oMsg TEXT, IN iId VARCHAR(128), IN iUpdates TEXT, 
	IN iGroupId INT, IN iUserId INT, IN iModifiedFrom TEXT, IN iModifiedTo TEXT, 
	IN iTableName VARCHAR(30))
BEGIN
	SET @sql = CONCAT("UPDATE ViewAngle SET " , iUpdates, " WHERE name = '", iId, "'");
	PREPARE stmt FROM @sql;
	EXECUTE stmt;
	INSERT INTO History (Id, userId, groupId, dateModified, modifiedFrom, modifiedTo, tableName) 
		VALUES (iId, iUserId, iGroupId, NOW(), iModifiedFrom, iModifiedTo, iTableName );
END ;;

DROP PROCEDURE IF EXISTS ViewDelete ;;

CREATE PROCEDURE ViewDelete(OUT oMsg TEXT, IN iId INT)
BEGIN 
	DECLARE Count INT; 
	SELECT COUNT(*) INTO Count FROM Image WHERE viewId = iId; 
	IF Count = 0 THEN 
		DELETE FROM View WHERE id = iId; 
		DELETE FROM ExternalLinkObject WHERE mbId = iId; 
		DELETE FROM BaseObject WHERE id = iId; 
		DELETE FROM Keywords WHERE id = iId; 
		SET oMsg = "Successfully Deleted View";
	ELSE
		SET oMsg = "Deletion Failed";
	END IF; 
END ;;


DELIMITER ;


