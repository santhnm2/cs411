#!/usr/bin/python

import unirest
from bs4 import BeautifulSoup
import json

base_player_url = 'http://www.premierleague.com/en-gb/players/profile.statistics.html/'
url_goals = 'http://www.premierleague.com/ajax/player/index/BY_STAT/TOP_SCORERS/null/null/null/null/null/2014-2015/null/null/100/4/2/2/1/null.json'
url_assists = 'http://www.premierleague.com/ajax/player/index/BY_STAT/ASSISTS/null/null/null/null/null/2014-2015/null/null/100/4/2/2/1/null.json'

PLAYERS = []
ALIAS = {}

def print_players():
	global PLAYERS
	for player in PLAYERS:
		print '%s has %d goals and %d assists' % (player['NAME'], player['GOALS'], player['ASSISTS'])

def process_request(req, param):
	global PLAYERS, ALIAS
	result = req['playerIndexSection']['index']['resultsList']
	for res in result:
		player = {}
		name = res['fullName'].encode('utf-8')
		alias = res['cmsAlias'][0]
		try:
			player = ALIAS[alias]
			player[param] = res['value']
		except KeyError as e:
			player['NAME'] = name
			player['URL'] = base_player_url + alias
			player['TEAM'] = res['club']['clubFullName'].encode('utf-8')
			player[param] = res['value']
			PLAYERS.append(player)
		ALIAS[alias] = player

def get_composite_scores():
	global PLAYERS
	for player in PLAYERS:
		goals = 0
		assists = 0
		total = 0
		try:
			goals = player['GOALS']
		except KeyError as e:
			player['GOALS'] = 0
		try:
			assists = player['ASSISTS']
		except KeyError as e:
			player['ASSISTS'] = 0
		player['TOTAL'] = (2*assists + goals)

def rank_players():
	global PLAYERS	
	PLAYERS = sorted(PLAYERS, key=lambda k: k['TOTAL'], reverse=True)[0:120] 
	
def write_list_to_file():
	global PLAYERS
	myfile = open('epl_top_120_players.json', 'w+') 
	myfile.write(json.dumps(PLAYERS))
	myfile.close()

def get_top_players():
	global PLAYERS
	process_request(unirest.get(url_goals).body, 'GOALS')
	process_request(unirest.get(url_assists).body, 'ASSISTS')

def main():
	get_top_players()
	get_composite_scores()
	rank_players()
	write_list_to_file()

if __name__=='__main__':
	main()
