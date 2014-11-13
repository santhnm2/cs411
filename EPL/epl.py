#!/usr/bin/python

import unirest
from bs4 import BeautifulSoup

base_player_url = 'http://www.premierleague.com/en-gb/players/profile.statistics.html/'
url_goals = 'http://www.premierleague.com/ajax/player/index/BY_STAT/TOP_SCORERS/null/null/null/null/null/2014-2015/null/null/100/4/2/2/1/null.json'
url_assists = ''

PLAYERS = []
NAMES = {}

req = unirest.get(url_goals).body
result = req['playerIndexSection']['index']['resultsList']

for res in result:
	player = {}
	name = res['fullName'].encode('utf-8')
	player['NAME'] = name
	player['URL'] = base_player_url + res['cmsAlias'][0]
	player['TEAM'] = res['club']['clubFullName'].encode('utf-8')
	player['GOALS'] = res['value']
	NAMES[name] = True
	print player
	PLAYERS.append(player)
	

