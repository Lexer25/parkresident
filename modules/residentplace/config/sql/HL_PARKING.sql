/******************************************************************************/
/***               Generated by IBExpert 31.03.2025 12:14:11                ***/
/******************************************************************************/

SET SQL DIALECT 3;

SET NAMES WIN1251;



/******************************************************************************/
/***                                 Tables                                 ***/
/******************************************************************************/


CREATE GENERATOR GEN_HL_PARKING_ID;

CREATE TABLE HL_PARKING (
    ID        INTEGER NOT NULL,
    NAME      STR_50 NOT NULL /* STR_50 = VARCHAR(50) */,
    ENABLED   INTEGER DEFAULT 1 NOT NULL,
    CREATED   TIMESTAMP DEFAULT 'now',
    MAXCOUNT  SMALLINT,
    PARENT    SMALLINT
);




/******************************************************************************/
/***                              Primary Keys                              ***/
/******************************************************************************/

ALTER TABLE HL_PARKING ADD CONSTRAINT PK_HL_PARKING PRIMARY KEY (ID);


/******************************************************************************/
/***                                Triggers                                ***/
/******************************************************************************/


SET TERM ^ ;




/* Trigger: HL_PARKING_BI */
CREATE TRIGGER HL_PARKING_BI FOR HL_PARKING
ACTIVE BEFORE INSERT POSITION 0
as
begin
  if (new.id is null) then
    new.id = gen_id(gen_hl_parking_id,1);
end
^


SET TERM ; ^



/******************************************************************************/
/***                              Descriptions                              ***/
/******************************************************************************/

DESCRIBE TABLE HL_PARKING
'Перечень парковочноых комплексов (parent=0)
и перечень парковок, входяищ в комплекс (parent>0)';



/* Fields descriptions */

DESCRIBE FIELD MAXCOUNT TABLE HL_PARKING
'Количество мест на парковке согласно проектной документации';

DESCRIBE FIELD PARENT TABLE HL_PARKING
'Родительская парковка';



/******************************************************************************/
/***                               Privileges                               ***/
/******************************************************************************/
