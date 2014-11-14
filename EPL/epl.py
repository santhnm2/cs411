#!/usr/bin/python

import unirest
from bs4 import BeautifulSoup

base_player_url = 'http://www.premierleague.com/en-gb/players/profile.statistics.html/'
url_goals = 'http://www.premierleague.com/ajax/player/index/BY_STAT/TOP_SCORERS/null/null/null/null/null/2014-2015/null/null/100/4/2/2/1/null.json'
url_assists = 'http://www.premierleague.com/ajax/player/index/BY_STAT/ASSISTS/null/null/null/null/null/2014-2015/null/null/100/4/2/2/1/null.json'

PLAYERS = []
NAMES = {}

def process_request(req, param):
	result = req['playerIndexSection']['index']['resultsList']
	for res in result:
		player = {}
		name = res['fullName'].encode('utf-8')
		player['NAME'] = name
		player['URL'] = base_player_url + res['cmsAlias'][0]
		player['TEAM'] = res['club']['clubFullName'].encode('utf-8')
		player[param] = res['value']
		NAMES[name] = True
		PLAYERS.append(player)
	

def get_top_players():
	process_request(unirest.get(url_goals).body, 'GOALS')
	process_request(unirest.get(url_assists).body, 'ASSISTS')
	print len(NAMES)
	

def main():
	get_top_players()

if __name__=='__main__':
	main()
