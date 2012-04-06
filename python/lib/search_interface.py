# encoding: utf-8
"""
search_interface.py

Extract information from queries from search engines.

Created by David Wischhusen on 2011-04-12.
Copyright (c) 2011 Base62 LLC. All rights reserved.
"""

#Standard
import urlparse
import re

class Search_Interface:
	def __init__(self):
		pass

	def process_url(self, url):
		""" Analyze url for potential search terms from Bing, Google, and Yahoo """

		parsed = urlparse.urlparse(url)

		qs = urlparse.parse_qs(parsed.query)

		#Check if using Google
		if re.search("google.com", parsed.netloc):
			if re.search("/search$", parsed.path): #regular google
				if "q" in qs:
					return re.sub("^www\.", "", parsed.netloc), qs["q"]
			elif re.search("/$", parsed.path): #google instant
				frag_qs = urlparse.parse_qs(parsed.fragment)
				if "q" in frag_qs:
					return re.sub("^www\.", "", parsed.netloc), frag_qs["q"]

		#Check if using Bing
		if (re.search("bing.com", parsed.netloc) and
			re.search("/search$", parsed.path) and
			"q" in qs):

			return re.sub("^www\.", "", parsed.netloc), qs["q"]

		#Check if using Yahoo
		if (re.search("search.yahoo.com", parsed.netloc) and
			re.search("/search$", parsed.path) and
			"p" in qs):

			return re.sub("^www\.", "", parsed.netloc), qs["p"]

		return None, None #default