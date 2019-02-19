#!/usr/bin/env python
import dota2api
import sys
api = dota2api.Initialise("0010961CE9FD53CCAC05B5BDD6082495")

matchID = sys.argv[1]
playerID = sys.argv[2]
playerID = int(playerID) - 76561197960265728


match = api.get_match_details(match_id = matchID)

heroName = ("")
counter = 0

while (counter<10):
   if (match["players"][counter]["account_id"] == playerID):
       heroName = (match["players"][counter]["hero_name"])
       break

   else:
       counter += 1

print (heroName)