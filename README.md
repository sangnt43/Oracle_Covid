# Oracle_Covid
Oracle 


'''
SELECT sum(confirmed) ,sum(deaths),sum(recoverd),sum(active),date_day,date_month,date_year FROM sys.covid_countries group by(date_day,date_month,date_year)
INSERT INTO globals (confirmed, deaths, recovered,active,date_day,date_month,date_year)
SELECT sum(confirmed) ,sum(deaths),sum(recoverd),sum(active),date_day,date_month,date_year FROM sys.covid_countries group by(date_day,date_month,date_year)
select * from sys.globals
'''