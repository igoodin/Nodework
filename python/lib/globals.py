# encoding: utf-8
"""
globals.py

Contains general functions usefull for various tasks. Mostly formatting.

Created by David Wischhusen on 2011-04-10.
Copyright (c) 2011 Base62 LLC. All rights reserved.
"""

#Standard
import datetime
import time
import urlparse
import urllib

def get_nix_date(milliseconds):
	""" Convert milliseconds to minimim unix timestamp

	Takes date as milliseconds, converts to Python date.
	Takes Pyton date, return time as unix timestamp.

	Use of Python date (not datetime) strips the time component so when converted
	to unix timestamp, it corresponds to 12:00am on the day of the input
	timestamp. This is useful for day-based queryies in PHP as we can query
	integer values for particualr days without ignoring portions of that day.
	"""

	date = datetime.date.fromtimestamp(milliseconds / 1000)
	return int(time.mktime(date.timetuple()))

def filter_url(page, ignore_params):
	""" Remove parameters from query string

	Users have the option of ignoring certain query string parameters, this
	returns the string with all offending parameters removed.
	"""
	params = {}
	url = urlparse.urlparse(page)
	query = urlparse.parse_qs(page)
	for key, value in query.items():
		if key not in ignore_params:
				params[key] = value

	return urlparse.urlunparse((
		url.scheme,
		url.netloc,
		url.path,
		url.params,
		urllib.urlencode(params),
		url.fragment
	))

def event_at(date_in_millis):
	""" Converts a time (in milliseconds) into a time-chart friendly format

	The time chart displays hour & day-of-week data. We provide the data in the
	format of "WEEKDAY(1-7)::HOUR(0-23)"

	We perform a transformation from the regular Python weekday format.
	Dates now run from Sun-Sat numbering 1-7.
	"""

	date = datetime.datetime.fromtimestamp(date_in_millis / 1000.0)
	converted_weekday = ((date.weekday() + 1) % 7) + 1
	return "%s::%s" % (converted_weekday, date.time().hour)

def parse_platform(platform):
	""" Convert OS data from user-agent into somethign more friendly """

	if platform["name"] is None or platform["version"] is None:
		return platform

	if platform["name"] == "Linux":
		platform["version"] = "Unknown"
	elif platform["name"] == "Windows":
		if platform["version"] == "6.1":
			platform["version"] = "Windows 7"
		elif platform["version"] == "6.0":
			platform["version"] = "Windows Vista"
		elif platform["version"] == "5.1":
			platform["version"] = "Windows XP"
		else:
			platform["version"] = "Unknown"
	elif platform["name"] == "Mac OSX":
		if platform["version"] == "10_7":
			platform["version"] == "Lion"
		elif platform["version"] == "10_6":
			platform_version = "Snow Leopard"
		elif platform["version"] == "10_5":
			platform["version"] = "Leopard"
		elif platform["version"] == "10_4":
			platform["version"] = "Tiger"
		elif platform["version"] == "10_3":
			platform["version"] = "Panther"
		elif platform["version"] == "10_2":
			platform["version"] = "Jaguar"
		elif platform["version"] == "10_1":
			platform["version"] = "Puma"
		elif platform["version"] == "10_0":
			platform["version"] = "Cheetah"
		else:
			platform["version"] = "Unknown"

	return platform