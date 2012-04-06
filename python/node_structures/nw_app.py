# encoding: utf-8
"""
nw_app.py

Class that converts raw ping data and logs the processed data.

Created by David Wischhusen on 2011-04-10.
Copyright (c) 2011 Base62 LLC. All rights reserved.
"""

#Standard libraries
import urlparse
import urllib
import hashlib
import re

#3rd party imports
import pygeoip

#Local imports
from lib import globals, query_builder, search_interface

class App:
	settings_collection = "app_settings"

	def __init__(self, db, app_id, logger):
		self.db = db
		self.app_id = app_id

		self.logger = logger

		self.load_settings()

	def load_settings(self):
		""" Load user-configurable settings

		Users can set the root domain for their app (used for calculating referrers)
		and any query string params that they'd like to ignore. This function
		reads those settings for use later.
		"""
		settings = self.db[self.settings_collection]

		record = settings.find_one({"app_id":self.app_id})

		self.ignore_params = record["app_params"]
		self.domain = record["app_domain"]

	def log_ping(self, nw_ping):
		""" Generic function that handles raw ping processign and logging """

		self.qb = query_builder.Query_Builder()

		date_nix = globals.get_nix_date(nw_ping.date)

		self.log_page(nw_ping.location, date_nix)
		self.log_request(date_nix)
		self.log_time(date_nix, nw_ping.date)
		if nw_ping.unique:
			self.log_unique(date_nix)
			if nw_ping.is_mobile:
				self.log_mobile(nw_ping.browser["name"])
			else:
				self.log_browser(nw_ping.browser)
			if nw_ping.platform["name"] is not None:
				self.log_platform(nw_ping.platform, date_nix)

			self.log_location(nw_ping.ip_address, date_nix)

			self.log_resolution(nw_ping.resolution)
		elif nw_ping.visitor:
			self.log_visitor(date_nix)

		if (nw_ping.referrer is not None and
			not re.search(re.escape(self.domain), nw_ping.referrer, re.IGNORECASE)):

			si = search_interface.Search_Interface()
			search_domain, search_query = si.process_url(nw_ping.referrer)

			if search_domain is not None:
				self.log_search_engine(search_domain, search_query, date_nix)
				self.log_search_term(search_query, date_nix)
				self.log_referrer("http://" + search_domain, date_nix)
			else:
				self.log_referrer(nw_ping.referrer, date_nix)

		apps = self.db["apps"]
		apps.update(
			{"app_id":self.app_id, "date_nix":date_nix},
			self.qb.build_query(),
			upsert=True
		)

	def log_page(self, location, date_nix):
		""" Log visit to a specific page

		Remove all ignored parameters and log a visit to a particualar page.
		"""
		pages_col = self.db["pages"]
		pages_qb = query_builder.Query_Builder()

		location = globals.filter_url(location, self.ignore_params)
		location_safe = hashlib.md5(location).hexdigest()

		pages_qb.append_action("set", {"pages.%s.label" % location_safe : location})
		pages_qb.append_action("inc", {"pages.%s.count" % location_safe : 1})

		pages_col.update(
			{"app_id":self.app_id, "date_nix":date_nix},
			pages_qb.build_query(),
			upsert=True
		)

	def log_request(self, date_nix):
		""" Log a visit to the site (to any page) """
		self.qb.append_action("inc",{"requests":1})

	def log_time(self, date_nix, raw_date):
		""" Log time & day of ping

		Converts time to a format friendly for the time & day chart and logs the
		result.
		"""
		event_at = globals.event_at(raw_date)
		safe_time = hashlib.md5(event_at).hexdigest()
		self.qb.append_action("set", {
			"times.%s.label" % safe_time : event_at
		})
		self.qb.append_action("inc", {
			"times.%s.count" % safe_time : 1
		})

	def log_unique(self, date_nix):
		""" Log a unique visitor to the site (to any page) """
		self.qb.append_action("inc", {"uniques":1})

	def log_visitor(self, date_nix):
		""" Log a returning visitor to the site (to any page) """
		self.qb.append_action("inc", {"visitors":1})

	def log_mobile(self, mobile):
		""" Log the use of a mobile device on the site """
		safe_mobile = hashlib.md5(mobile).hexdigest()

		self.qb.append_action("set",{"mobiles.%s.label" % safe_mobile : mobile})
		self.qb.append_action("inc",{"mobiles.%s.count" % safe_mobile : 1})

	def log_browser(self, browser):
		""" Update statistics for the web browser that was used during visit """
		browser_safe = hashlib.md5(browser["name"]).hexdigest()
		version_safe = hashlib.md5(browser["version"]).hexdigest()

		self.qb.append_action("set", {"browsers.%s.label" % browser_safe : browser["name"]})
		self.qb.append_action("inc", {"browsers.%s.count" % browser_safe : 1})
		self.qb.append_action("set", {
			"browsers.%s.versions.%s.label" % (browser_safe, version_safe) : browser["version"]
		})
		self.qb.append_action("inc", {
			"browsers.%s.versions.%s.count" % (browser_safe, version_safe) : 1
		})

	def log_platform(self, platform, date_nix):
		""" Parse and update statistics for that platform that was used """
		platform = globals.parse_platform(platform)

		platform_safe = hashlib.md5(platform["name"]).hexdigest()
		version_safe = "Unknown"
		if platform["version"] is not None:
			version_safe = hashlib.md5(platform["version"]).hexdigest()

		self.qb.append_action("set", {"platforms.%s.label" % platform_safe : platform["name"]})
		self.qb.append_action("inc", {"platforms.%s.count" % platform_safe : 1})
		self.qb.append_action("set", {
			"platforms.%s.versions.%s.label" % (platform_safe, version_safe) : platform["version"]
		})
		self.qb.append_action("inc", {
			"platforms.%s.versions.%s.count" % (platform_safe, version_safe) : 1
		})

	def log_location(self, ip_address, date_nix):
		""" Get and log the latitude/longitude of the visitor """
		loc_col = self.db["locations"]
		gic = pygeoip.GeoIP("GeoLiteCity.dat")

		record = gic.record_by_addr(ip_address)
		if record is not None:
			loc_col.insert({
				"app_id":self.app_id,
				"date_nix":date_nix,
				"long":record["longitude"],
				"lat":record["latitude"]
			})

	def log_resolution(self, resolution):
		""" Update the screen resolution stats that match the visiting user """
		safe_res = hashlib.md5(resolution).hexdigest()

		self.qb.append_action("set", {"resolutions.%s.label" % safe_res : resolution})
		self.qb.append_action("inc", {"resolutions.%s.count" % safe_res: 1})

	def log_referrer(self, referrer, date_nix):
		""" Log the referring site that the user approached the app from """
		referrer_col = self.db["referrers"]
		referrer_qb = query_builder.Query_Builder()

		parsed = urlparse.urlparse(referrer)
		safe_referrer_parsed = hashlib.md5(parsed.netloc).hexdigest()

		referrer_qb.append_action("set", {
			"referrers.%s.label" % safe_referrer_parsed : parsed.netloc
		})
		referrer_qb.append_action("inc", {
			"referrers.%s.count" % safe_referrer_parsed : 1
		})

		referrer_col.update(
			{"app_id":self.app_id, "date_nix":date_nix},
			referrer_qb.build_query(),
			upsert=True
		)

	def log_search_engine(self, search_domain, search_query, date_nix):
		""" Log the use of a particular search term on a particular search engine """
		engines_col = self.db["search_engines"]
		engines_qb = query_builder.Query_Builder()

		domain_safe = hashlib.md5(search_domain).hexdigest()
		query_safe = hashlib.md5(search_query[0]).hexdigest()

		engines_qb.append_action("set", {
			"engines.%s.label" % domain_safe : search_domain
		})
		engines_qb.append_action("inc", {
			"engines.%s.count" % domain_safe : 1
		})
		engines_qb.append_action("set", {
			"engines.%s.terms.%s.label" % (domain_safe, query_safe) : search_query[0]
		})
		engines_qb.append_action("inc", {
			"engines.%s.terms.%s.count" % (domain_safe, query_safe) : 1
		})

		engines_col.update(
			{"app_id":self.app_id, "date_nix":date_nix},
			engines_qb.build_query(),
			upsert=True
		)

	def log_search_term(self, search_query, date_nix):
		""" Log the use of a particular search term (on any search engine) """
		searches_col = self.db["search_terms"]
		searches_qb = query_builder.Query_Builder()

		query_safe = hashlib.md5(search_query[0]).hexdigest()

		searches_qb.append_action("set", {
			"terms.%s.label" % query_safe : search_query[0]
		})
		searches_qb.append_action("inc", {
			"terms.%s.count" % query_safe : 1
		})

		searches_col.update(
			{"app_id":self.app_id, "date_nix":date_nix},
			searches_qb.build_query(),
			upsert=True
		)


