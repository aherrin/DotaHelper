#!/usr/bin/env python
import dota2api
import sys
api = dota2api.Initialise("848471C48E75211005D2D7A958924178")

userAccountId = sys.argv[1]

match = api.get_match_history(account_id = userAccountId)

print (match.json)