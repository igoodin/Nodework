#!/usr/bin/env python
# encoding: utf-8
"""
server.process.py

Application launcher.

Created by David Wischhusen on 2011-04-10.
Copyright (c) 2011 Base62 LLC. All rights reserved.
"""

#Standard
import time
import os

#3rd party
import pymongo

#Local
from lib import config_parser, datastore, log, globals
from node_structures import nw_pings, nw_app, nw_ping

class Server:
	def __init__(self, config_options, logger):
		self.config_options = config_options

		self.logger = logger

		self.bootstrap()
		self.process()

	def bootstrap(self):
		""" Establish necessary connections to MongoDB """

		self.logger.debug("Starting Server Bootstrap")

		self.ds = datastore.DS(
			host=config_options["host"],
			port=config_options["port"],
			db=config_options["database"],
			logger=logger
		)
		self.ds.connect()
		self.db = self.ds.get_db()

	def process(self):
		""" Start processing loop that converts raw data into something usefull """
		pings = nw_pings.Pings(self.db)
		while True:
			while(pings.count() == 0):
				sleep_time = 3
				logger.debug("No Pings. Waiting %s seconds..." % sleep_time)
				time.sleep(sleep_time)

			ping = nw_ping.Ping(pings.reserve())

			#@todo log this ping to a log file if in debug

			app = nw_app.App(self.db, ping.app_id, logger=self.logger)
			app.log_ping(ping)


if __name__ == "__main__":
	os.environ['TZ'] = 'UTC'
	time.tzset()

	logger = log.Log("DEBUG")
	logger.debug("Hello From Debugger")

	config = config_parser.Config("config.yaml")
	config_options = config.get_options()

	try:
		while True:
			try:
				server = Server(config_options, logger)
			except pymongo.errors.AutoReconnect:
				sleep_time = 1
				logger.debug("connection lost, reconnecting in %s second" % sleep_time)
				time.sleep(sleep_time)
	except KeyboardInterrupt:
		logger.debug("Keyboard Interrupt. Exiting...")
