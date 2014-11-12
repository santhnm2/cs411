#!/usr/bin/python 

import unirest
from bs4 import BeautifulSoup

def get_stats(player):
	unirest.timeout(3)
	try:
		response = unirest.get(player['URL']).body;
	except Exception as e:
		print 'ERROR: %s for url: %s' % (str(e), player['URL'])
		return player
	soup = BeautifulSoup(response);
	player['NAME'] = soup.find_all('h1')[1].contents[0].encode('utf-8')
	results = soup.find_all('tr', {'class':'oddrow'})
	for result in results:
		if result.contents[0].contents[0] == '2014 Regular Season':
			qb_stats = result.contents[1:] 
			try:	
				player['YDS'] = int(qb_stats[2].contents[0].replace(',', ''))
			except Exception as e:
				player['YDS'] = 0
			try:
				player['TD'] = int(qb_stats[5].contents[0].replace(',', ''))
			except Exception as e:
				player['TD'] = 0
			return player
	player['YDS'] = 0
	player['TD'] = 0
	return player
