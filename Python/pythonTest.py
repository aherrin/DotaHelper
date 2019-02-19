#!/usr/bin/env python
import dota2api
api = dota2api.Initialise("848471C48E75211005D2D7A958924178")

match = api.get_match_history(account_id = 76561198086741532, matches_requested = 10)
print (match.json)