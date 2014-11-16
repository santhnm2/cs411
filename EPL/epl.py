#!/usr/bin/python

import unirest
from bs4 import BeautifulSoup
import json
from multiprocessing.dummy import Process, Queue
import time
from datetime import datetime
import csv

PLAYERS = []
RUNDAY = datetime.utcnow().date().isoformat()

def queue_players_from_file(in_queue):
	global PLAYERS
	PLAYERS = [json.loads(line.strip()) for line in open('epl_top_120_players.json')]
	for player in PLAYERS:
		in_queue.put(player)

def get_stats_helper(in_queue, out_queue):
	player = in_queue.get()
	req = None
	try:
		req = unirest.get(player['URL']).body
	except Exception as e:
		in_queue.put(player)
		return
	soup = BeautifulSoup(req)
	player['NAME'] = soup.find_all('div', {'class': 'hero-name'})[0].contents[1].contents[3].contents[0].encode('utf-8')	
	print player['NAME']
	player['GOALS'] = int(soup.find_all('li', {'name': 'goals'})[0].contents[3].contents[0])
	player['ASSISTS'] = int(soup.find_all('li', {'name': 'assists'})[0].contents[3].contents[0])
	player['JERSEY'] = int(soup.find_all('div', {'class': 'hero-name'})[0].contents[1].contents[1].contents[0])
	pos = soup.find_all('ul', {'class': 'stats'})[0].contents[3].contents[1].contents[0].lower()
	pos_list = list(pos)
	pos_list[0] = pos_list[0].upper()
	player['POSITION'] = ''.join(pos_list) 
	out_queue.put(player)			

def get_stats():
	print 'Fetching EPL player stats...'
	stats_outfile = RUNDAY+'_epl_stats.csv'
	csvout = open(stats_outfile, 'wb')

	NUM_THREADS = 8

	in_queue = Queue()
	out_queue = Queue()
	queue_players_from_file(in_queue)
	
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
				'GOALS',
				'ASSISTS',
				'URL'
			]

			csvwriter = csv.DictWriter(csvout, delimiter='|', fieldnames=fieldnames)
			csvwriter.writerow(player)
	csvout.close()

	print 'Finished fetching EPL player stats.'
	print 'Ouput saved in %s' % stats_outfile

def main():
	get_stats()

if __name__=='__main__':
	main()
