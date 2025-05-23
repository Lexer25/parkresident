/******************************************************************************/
/***               Generated by IBExpert 31.03.2025 12:13:23                ***/
/******************************************************************************/

SET SQL DIALECT 3;

SET NAMES WIN1251;



/******************************************************************************/
/***                                 Tables                                 ***/
/******************************************************************************/



CREATE GENERATOR GEN_HL_PARAM_ID;

CREATE TABLE HL_PARAM (
    ID          INTEGER NOT NULL,
    TABLO_IP    STR_50 /* STR_50 = VARCHAR(50) */,
    TABLO_PORT  INTEGER,
    BOX_IP      STR_50 /* STR_50 = VARCHAR(50) */,
    BOX_PORT    INTEGER,
    ID_GATE     SMALLINT,
    ID_CAM      SMALLINT,
    ID_DEV      SMALLINT,
    MODE        SMALLINT DEFAULT  0,
    NAME        STR_250 /* STR_250 = VARCHAR(250) */,
    ID_PARKING  SMALLINT,
    IS_ENTER    SMALLINT DEFAULT 1,
    CREATED     TIMESTAMP DEFAULT 'now',
    CHANNEL     INTEGER
);





/******************************************************************************/
/***                              Primary Keys                              ***/
/******************************************************************************/

ALTER TABLE HL_PARAM ADD CONSTRAINT PK_HL_PARAM PRIMARY KEY (ID);


/******************************************************************************/
/***                              Foreign Keys                              ***/
/******************************************************************************/

ALTER TABLE HL_PARAM ADD CONSTRAINT FK_HL_PARAM_1 FOREIGN KEY (ID_PARKING) REFERENCES HL_PARKING (ID) ON DELETE SET NULL;


/******************************************************************************/
/***                                Triggers                                ***/
/******************************************************************************/


SET TERM ^ ;




/* Trigger: HL_PARAM_BI */
CREATE TRIGGER HL_PARAM_BI FOR HL_PARAM
ACTIVE BEFORE INSERT POSITION 0
as
begin
  if (new.id is null) then
    new.id = gen_id(gen_hl_param_id,1);
end
^


SET TERM ; ^



/* Fields descriptions */

DESCRIBE FIELD MODE TABLE HL_PARAM
'Режим работы шлюза
0 - режим шлюза
1 - реле 0 ворота
2 - реле 1 шлагбаум
3 - и реле 0 ,и реле 1';



/******************************************************************************/
/***                               Privileges                               ***/
/******************************************************************************/


/* Privileges of procedures */
GRANT SELECT ON HL_PARAM TO PROCEDURE VALIDATEPASS_HL_PARKING_2;
GRANT SELECT ON HL_PARAM TO PROCEDURE VALIDATEPASS_HL_PARKING_3;