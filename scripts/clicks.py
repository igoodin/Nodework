import urllib
import urllib2
import random
import sys

requests = 100
try:
	requests = int(sys.argv[1])
except(IndexError):
	print "Defaulting to %s requests" % requests

print "Submitting %s requests..." % requests

app_id = 12345
base_url = "http://localhost:8124"

def generateRandomLocation():
	return "%s/%s" % (base_url, random.random() * 100)

for req in range(requests):
	url = base_url + "/click?app=%s&loc=%s&x=%s&y=%s&w=%s" % (
		app_id,
		urllib.quote(generateRandomLocation()),
		random.randrange(0, 1250),
		random.randrange(0, 1080),
		random.randrange(800, 2000)
	)

	r = urllib2.Request(url)
	f = urllib2.urlopen(r)
	f.read()
