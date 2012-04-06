# encoding: utf-8
"""
nw_ping.py

Object representing a document in the "pings" collection.

Created by David Wischhusen on 2011-04-10.
Copyright (c) 2011 Base62 LLC. All rights reserved.
"""

class Ping:
	def __init__(self, raw_ping):
		""" Init. Parses raw ping document into object properties. """

		self.id = raw_ping["_id"]
		self.app_id = str(raw_ping["app_id"])
		self.date = raw_ping["date"]
		self.location = raw_ping["loc"]
		self.referrer = raw_ping["ref"]
		self.browser = {
			"name":raw_ping["browser"],
			"version":raw_ping["version"]
		}
		self.is_mobile = raw_ping["ismobile"]
		self.platform = {
			"name":raw_ping["platform"],
			"version":raw_ping["platform_version"]
		}
		self.ip_address = raw_ping["ip"]
		self.resolution = raw_ping["res"]
		self.unique = raw_ping["unique"]
		self.visitor = raw_ping["visitor"]

	def __str__(self):
		return ("""
			Ping @ %(date)s
			for: %(app_id)s
			page: %(loc)s
			referrer: %(referrer)s
			browser: %(browser_name)s %(browser_version)s
			mobile: %(mobile)s
			platform: %(platform)s
			ip address: %(ip_address)s
			resolution: %(resolution)s
			unique: %(unique)s
			visitor: %(visitor)s
			""" % {
				"date":self.date,
				"app_id":self.app_id,
				"loc":self.location,
				"referrer":self.referrer,
				"browser_name":self.browser["name"],
				"browser_version":self.browser["version"],
				"mobile":self.is_mobile,
				"platform":self.platform,
				"ip_address":self.ip_address,
				"resolution":self.resolution,
				"unique":self.unique,
				"visitor":self.visitor
				}).replace("\t", "")