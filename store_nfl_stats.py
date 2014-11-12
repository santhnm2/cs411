#!/usr/bin/python

import csv
import _mysql

mydb = _mysql.connect('engr-cpanel-mysql.engr.illinois.edu', 'rmukerj2_fan', 'test1234', 'rmukerj2_fantasy')
cursor = mydb.cursor()

csv_data = csv.reader(file('2014-10-21_nfl_stats.csv'))
for row in csv_data:
    cursor.execute("INSERT INTO NFLPlayer(TIME, NAME, JERSEY, SPORT, TEAM, POSITION, TD, YDS, URL) values(%s, %s, %s, %s, %s, %s, %s, %s, %s)", row)
#close the connection to the database.
mydb.commit()
cursor.close()
