# encoding: utf-8
"""
datastore.py

Manage connections to MongoDB

Created by David Wischhusen on 2011-04-10.
Copyright (c) 2011 Base62 LLC. All rights reserved.
"""

#3rd party
import pymongo

class DS:
	""" Class for managing connections to MongoDB """

	db_conn = None
	db_obj = None

	def __init__(self,host="localhost", port=27017, db="db", logger=None):
		""" Init. Sets several default options """
		self.host = host
		self.port = port
		self.db = db

		self.logger = logger

	def connect(self):
		""" Establish the connection to MongoDB """
		self.logger.debug("Connecting to MongoDB @%s:%s" % (self.host, self.port))
		self.db_conn = pymongo.Connection(self.host, self.port)
		self.logger.debug("MongoDB Connection Successful")
		return self.db_conn

	def get_db(self, db=None):
		""" Return a MongoDB Database """
		db = self.db if db == None else db
		self.logger.debug("Selecting Mongo database: %s" % db)
		self.db_obj = self.db_conn[db]
		self.logger.debug("Database Selection Successful")
		return self.db_obj

