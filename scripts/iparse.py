import pygeoip
import pymongo
import time

mongo = pymongo.Connection('localhost', 27017)
db = mongo.db
collection = db.loc

gi = pygeoip.GeoIP('/home/vpsadtwischadmin/Desktop/GeoLiteCity.dat')

file = open("iptrack.log")

projects = {}
geos = {}

def find_by_name(name):
	app_ids = {
		"rec-live":5,
		"arh-live":4,
		"dining-live":6,
		"bsc-live":7,
		"emedia-live":3
	}
	if name in app_ids:
		return app_ids[name]
	return 0

for line in file:
	line = line.strip().split(" ")
	if not line[0] in projects:
		projects[line[0]] = []
	if not line[1] in projects[line[0]]:
		projects[line[0]].append(line[1])

for p in projects:
	geos[p] = []
	for ip in projects[p]:
		r = gi.record_by_addr(ip)
		if not r is None:
			geos[p].append({
				"long": r["longitude"],
				"lat": r["latitude"]
			})

date_nix = time.time()

for g in geos:
	app_id = find_by_name(g)
	for ip in geos[g]:
		q = {
			"app_id":str(app_id),
			"date_nix":date_nix
		}
		r = collection.find_one(q)
		if r is None:
			r = {
				"app_id":str(app_id),
				"date_nix":date_nix,
				"loc":[ip]
			}
			collection.insert(r)
		else:
			loc = r["loc"]
			loc.append(ip)
			collection.update(q, {"$set":{"loc":loc}})

