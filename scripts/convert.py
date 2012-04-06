import pymongo
import json
f = open("log.txt","r")

	 	
connection = pymongo.Connection('localhost',27017)
db = connection['db']
collection = db.pings

for line in f:
	stuff=  json.loads(line)
	del stuff["_id"]
	collection.insert(stuff)
