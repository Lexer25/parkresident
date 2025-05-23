/******************************************************************************/
/***               Generated by IBExpert 31.03.2025 12:07:14                ***/
/******************************************************************************/

SET SQL DIALECT 3;

SET NAMES WIN1251;



/******************************************************************************/
/***                                 Tables                                 ***/
/******************************************************************************/


CREATE GENERATOR GEN_HL_GARAGE_ID;

CREATE TABLE HL_GARAGE (
    ID             INTEGER NOT NULL,
    CREATED        TIMESTAMP DEFAULT 'now',
    ID_PLACE       INTEGER,
    ID_GARAGENAME  SMALLINT
);




/******************************************************************************/
/***                           Unique Constraints                           ***/
/******************************************************************************/

ALTER TABLE HL_GARAGE ADD CONSTRAINT UNQ1_HL_GARAGE UNIQUE (ID_PLACE);


/******************************************************************************/
/***                              Primary Keys                              ***/
/******************************************************************************/

ALTER TABLE HL_GARAGE ADD CONSTRAINT PK_HL_GARAGE PRIMARY KEY (ID);


/******************************************************************************/
/***                              Foreign Keys                              ***/
/******************************************************************************/

ALTER TABLE HL_GARAGE ADD CONSTRAINT FK_HL_GARAGE_1 FOREIGN KEY (ID_GARAGENAME) REFERENCES HL_GARAGENAME (ID) ON DELETE CASCADE;
ALTER TABLE HL_GARAGE ADD CONSTRAINT FK_HL_GARAGE_2 FOREIGN KEY (ID_PLACE) REFERENCES HL_PLACE (ID) ON DELETE CASCADE;


/******************************************************************************/
/***                                Triggers                                ***/
/******************************************************************************/


SET TERM ^ ;




/* Trigger: HL_GARAGE_BI */
CREATE TRIGGER HL_GARAGE_BI FOR HL_GARAGE
ACTIVE BEFORE INSERT POSITION 0
AS
BEGIN
  IF (NEW.ID IS NULL) THEN
    NEW.ID = GEN_ID(GEN_HL_GARAGE_ID,1);
END
^


SET TERM ; ^



/******************************************************************************/
/***                              Descriptions                              ***/
/******************************************************************************/

DESCRIBE TABLE HL_GARAGE
'Гараж - группировка машиномест.
В одном гараже может быть несколько машиномест.
В один гараж могут заезжать транспортные средства из нескольких организаций.';



/* Fields descriptions */

DESCRIBE FIELD CREATED TABLE HL_GARAGE
'Метка времени создания.
';

DESCRIBE FIELD ID_GARAGENAME TABLE HL_GARAGE
'Ссылка на имя гаража';

DESCRIBE FIELD ID_PLACE TABLE HL_GARAGE
'Номер или иное обозначение машиноместа';



/******************************************************************************/
/***                               Privileges                               ***/
/******************************************************************************/


/* Privileges of procedures */
GRANT SELECT ON HL_GARAGE TO PROCEDURE VALIDATEPASS_HL_PARKING_2;
GRANT SELECT ON HL_GARAGE TO PROCEDURE VALIDATEPASS_HL_PARKING_3;