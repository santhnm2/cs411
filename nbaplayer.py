#!/usr/bin/python

import unirest
from bs4 import BeautifulSoup

def get_stats(player):
	unirest.timeout(5)
	try:
		response = unirest.get(player['URL']).body;
	except Exception as e:
		print 'ERROR: %s for url: %s' % (str(e), player['URL'])
		return player
	soup = BeautifulSoup(response);

	player['NAME'] = soup.find_all('h1')[1].contents[0].encode('utf-8')
	results = soup.find_all('tr', {'class': 'evenrow'}) + soup.find_all('tr', {'class': 'oddrow'})

	count = 0
	i = 0
	while (count < 2 and i < len(results)):
		if results[i].contents[0].contents[0] == '\'14-\'15':
			count += 1
		i += 1
	i -= 1

	try:
		stats = results[i].contents
	except IndexError as e:
		player['PTS'] = 0
		player['AST'] = 0
		player['REB'] = 0
		return player
	
	
	try:
		player['PTS'] = int(stats[16].contents[0].replace(',', ''))
	except Exception as e:
		player['PTS'] = 0
	try:
		player['AST'] = int(stats[11].contents[0].replace(',', ''))
	except Exception as e:
		player['AST'] = 0
	try:
		player['REB'] = int(stats[10].contents[0].replace(',', ''))
	except Exception as e:
		player['REB'] = 0

	return player

