#!/usr/bin/python

import unirest
from bs4 import BeautifulSoup
import re
import nbaplayer
import sys, os
from multiprocessing.dummy import Process, Queue
import time
from datetime import datetime
import csv
import json

RUNDAY = datetime.utcnow().date().isoformat()
TEAM_CODES = {}
PLAYERS = []
BASE_URL = 'http://espn.go.com/nba/team/roster/_/name/'

def load_codes():
	global TEAM_CODES
	print 'Loading team names...'
	file = open('nba_teams.txt', 'r+')
	for line in file:
		line = line.strip('\n').split('|')
		TEAM_CODES[line[0]] = line[1]
	file.close()

def get_rosters_from_file(rosters_outfile):
	global PLAYERS
	print 'Loading rosters from file...'
	json_data = open(rosters_outfile, 'r+')
	PLAYERS = json.loads(json_data.read())
	json_data.close()
	print 'Finished loading rosters from file...'

def get_rosters_from_url(rosters_outfile):
	global PLAYERS, TEAM_CODES
	for team, code in TEAM_CODES.iteritems():
		print 'Fetching roster for %s...' % team
		url = BASE_URL+code
		try:		
			result = unirest.get(url).body
		except Exception as e:
			print 'ERROR: could not access %s' % url
			continue
		soup = BeautifulSoup(result)
		results = soup.find_all('tr', {'class': 'evenrow'}) + soup.find_all('tr', {'class': 'oddrow'})
		for result in results:
			player = {}
			temp = result.contents
			player['SPORT'] = 'NBA'
			player['TEAM'] = team
			player['JERSEY'] = temp[0].contents[0].encode('utf-8')
			player['URL'] = temp[1].contents[0]['href'].replace('_', 'stats/_')
			player['POSITION'] = temp[2].contents[0].encode('utf-8')
			PLAYERS.append(player)
	print 'Finished fetching rosters.'
	print 'Saving rosters to file...'
	json_data = open(rosters_outfile, 'w+')
	json_data.write(json.dumps(PLAYERS))
	json_data.close()
	print 'Finished saving rosters to file.'

def get_rosters():
	rosters_outfile = RUNDAY+'_nba_rosters.json'
	if os.path.isfile(rosters_outfile):
		get_rosters_from_file(rosters_outfile)
	else:
		get_rosters_from_url(rosters_outfile)

def queue_players(in_queue):
	global PLAYERS
	for player in PLAYERS:
		in_queue.put(player)	

def get_stats_helper(in_queue, out_queue):
	global PLAYERS
	if in_queue.empty():
		return
	player = in_queue.get()
	player = nbaplayer.get_stats(player)
	out_queue.put(player)

def get_stats():
	print 'Fetching NBA player stats...'
	stats_outfile = RUNDAY+'_nba_stats.csv'
	csvout = open(stats_outfile, 'wb')

	NUM_THREADS = 6

	in_queue = Queue()
	out_queue = Queue()
	queue_players(in_queue)

	while not in_queue.empty():	
		jobs = []

		for i in range(NUM_THREADS):
			if not in_queue.empty():
				thread = Process(target=get_stats_helper, args=(in_queue, out_queue))
				jobs.append(thread)
				thread.start()
		for thread in jobs:
			thread.join()	

		while not out_queue.empty():
			player = out_queue.get()
			try: 
				name = player['NAME']
			except KeyError as e:
				continue
			player['TIME'] = RUNDAY
			fieldnames = [
				'TIME',
				'NAME', 
				'JERSEY',
				'SPORT',
				'TEAM',
				'POSITION',
				'PTS',
				'REB',
				'AST',
				'URL'
			]
		
			csvwriter = csv.DictWriter(csvout, delimiter='|', fieldnames=fieldnames)
			csvwriter.writerow(player)
	csvout.close()

	print 'Finished fetching NBA player stats.'
	print 'Ouput saved in %s' % stats_outfile

def print_players():
	global PLAYERS
	for player in PLAYERS:
		print player
	
def main():
	load_codes()
	get_rosters()
	get_stats()
	#print_players()

if __name__=='__main__':
	main()
	
