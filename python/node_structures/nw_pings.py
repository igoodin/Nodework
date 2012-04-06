# encoding: utf-8
"""
nw_pings.py

Basic interface to "pings" collection.

Created by David Wischhusen on 2011-04-10.
Copyright (c) 2011 Base62 LLC. All rights reserved.
"""

class Pings:
	collection_name = "pings"

	def __init__(self, db):
		self.db = db

		self.pings = self.db[self.collection_name]

	def count(self):
		""" Return the number of unprocessed pings """
		return self.pings.count()

	def reserve(self):
		""" Find, delete, and return a ping for further analysis """
		ping = self.pings.find_one()
		self.pings.remove(ping["_id"])
		return ping