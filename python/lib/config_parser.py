# encoding: utf-8
"""
config_parser.py

Class for reading the yaml-format config file that this program uses

Created by David Wischhusen on 2011-04-10.
Copyright (c) 2011 Base62 LLC. All rights reserved.
"""

#Standard
import os

#3rd Pary
import yaml

class Config:
	""" Class for parsing and making configuration options available """

	def __init__(self, config_file=None):
		""" Initialize
		Stores the target config file as an instance variable (if provided).
		"""
		self.options = {}

		if config_file:
			self.set_file(config_file)

	def set_file(self, config_file):
		""" Set the config file (if found) """
		if not os.path.exists(config_file):
			raise ConfigFileNotFound(config_file)
		self.parse(config_file)

	def parse(self, config_file):
		""" Parses the yaml config file into options """
		self.options = yaml.load(open(config_file))

	def get_option(self, option):
		""" Return an option from the config file """
		return self.options[option]

	def get_options(self):
		""" Return all options from the config file """
		return self.options


class ConfigFileNotFound(Exception):
	""" Generic error class. Returned when a config file is not found """

	def __init__(self, value):
		self.parameter = value
	def __str__(self):
		return "Config file: %s does not exist!" % self.parameter