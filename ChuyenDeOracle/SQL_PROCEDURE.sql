CREATE OR REPLACE PROCEDURE SP_INSERT_COVID_COUNTRIES
    (CT_ID in number,CFM in number,DEATHS in NUMBER,RECOVERED IN NUMBER,ACTIVE IN NUMBER,
        DATE_TIME IN DATE)
AS
BEGIN
    INSERT INTO COVID_COUNTRIES(country_id,confirmed,deaths,recoverd,active,date_time,date_year,date_month,date_day) VALUES
    (CT_ID,CFM,DEATHS,RECOVERED,ACTIVE,
    DATE_TIME,TO_CHAR(DATE_TIME,'YYYY'),TO_CHAR(DATE_TIME,'MM'),TO_CHAR(DATE_TIME,'DD'));
END SP_INSERT_COVID_COUNTRIES;

EXEC SP_INSERT_COVID_COUNTRIES (111,1,0,0,0,TO_DATE('01-07-2020 12:20:00','DD-MM-YYYY HH24:MI:SS'));

SELECT * FROM covid_countries WHERE id = 111;
SELECT * FROM GLOBALS ORDER BY DATE_YEAR,DATE_MONTH,DATE_DAY;

DROP TRIGGER TG_AFTER_INSERT_COVID_COUNTRIES


ALTER TRIGGER TG_AFTER_INSERT_COVID_COUNTRIES ENABLE;
create or replace NONEDITIONABLE TRIGGER TG_AFTER_INSERT_COVID_COUNTRIES
AFTER INSERT ON COVID_COUNTRIES FOR EACH ROW
DECLARE  ROWCOUNT INTEGER;
         XDAY NUMBER;
         XMONTH NUMBER;
         XYEAR NUMBER;
         TODAY DATE;
         XCONFIRMED NUMBER(38,0);
         XDEATHS NUMBER(38,0);
         XRECOVERED NUMBER(38,0);
         XACTIVE NUMBER(38,0);
BEGIN
    SELECT COUNT(*) INTO ROWCOUNT 
    FROM GLOBALS
    WHERE :NEW.DATE_DAY =DATE_DAY AND :NEW.DATE_MONTH = DATE_MONTH AND :NEW.DATE_YEAR = DATE_YEAR;
    IF ROWCOUNT > 0 THEN
        UPDATE GLOBALS 
        SET CONFIRMED = CONFIRMED + :NEW.CONFIRMED,
            DEATHS = DEATHS + :NEW.DEATHS,
            RECOVERED = RECOVERED + :NEW.RECOVERD,
            ACTIVE = ACTIVE + :NEW.ACTIVE
        WHERE :NEW.DATE_DAY = DATE_DAY AND :NEW.DATE_MONTH = DATE_MONTH AND :NEW.DATE_YEAR = DATE_YEAR;
    ELSE
        TODAY := :NEW.DATE_TIME - 1;
        
        XDAY := TO_CHAR(TODAY,'DD');
        XMONTH := TO_CHAR(TODAY,'MM');
        XYEAR := TO_CHAR(TODAY,'YYYY');
        
        ROWCOUNT:=0;
        
        SELECT COUNT(*) INTO ROWCOUNT
        FROM GLOBALS
        WHERE DATE_DAY = XDAY AND DATE_MONTH = XMONTH AND DATE_YEAR = XYEAR;
        IF (ROWCOUNT > 0) THEN
        
            SELECT confirmed,deaths,recovered,active INTO XCONFIRMED,XDEATHS,XRECOVERED,XACTIVE
            FROM GLOBALS
            WHERE XDAY = date_day AND XMONTH = DATE_MONTH AND XYEAR = DATE_YEAR AND ROWNUM=1;
            
            XCONFIRMED := XCONFIRMED + :NEW.CONFIRMED;
            XDEATHS := XDEATHS + :NEW.DEATHS;
            XRECOVERED := XRECOVERED + :NEW.RECOVERD;
            XACTIVE := XACTIVE + :NEW.ACTIVE;
            
            INSERT INTO GLOBALS
            VALUES (XCONFIRMED,XDEATHS,XRECOVERED,XACTIVE,:NEW.DATE_DAY,:NEW.DATE_MONTH,:NEW.DATE_YEAR);
        ELSE
            INSERT INTO GLOBALS
            VALUES (:NEW.CONFIRMED,:NEW.DEATHS,:NEW.RECOVERD,:NEW.ACTIVE,:NEW.DATE_DAY,:NEW.DATE_MONTH,:NEW.DATE_YEAR);
        END IF;
    END IF;
END;