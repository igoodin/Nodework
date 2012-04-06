# encoding: utf-8
"""
log.py

Provide basic logging functionality.

Created by David Wischhusen on 2011-04-10.
Copyright (c) 2011 Base62 LLC. All rights reserved.
"""

class Log:
	debug_on = False

	def __init__(self, log_level="DEBUG"):
		""" Init. Set log level """
		self.log_level = log_level

		if log_level == "DEBUG":
			self.debug_on = True

	def debug(self, msg):
		""" Output a debug-level message """
		if self.debug_on:
			print "DEBUG::%s" % msg