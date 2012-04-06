# encoding: utf-8
"""
query_builder.py

Class for building syntactically frustrating MongoDB queries in a
more friendly way.

Created by David Wischhusen on 2011-04-11.
Copyright (c) 2011 Base62 LLC. All rights reserved.
"""

class Query_Builder:
	def __init__(self):
		self.actions = {}

	def append_action(self, action_name, action):
		""" Appends a query-action to the queue """
		if action_name not in self.actions:
			self.actions[action_name] = {}
		self.actions[action_name] = dict(self.actions[action_name], **action)

	def build_query(self):
		""" Compiles query-queue into a single coherent query """
		ret_query = {}
		for key, value in self.actions.items():
			key = "$%s" % key
			ret_query[key] = value
		return ret_query
