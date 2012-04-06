import urllib
import urllib2
import hashlib
import random
import sys

requests = 100
try:
	requests = int(sys.argv[1])
except(IndexError):
	print "Defaulting to %s requests" % requests

print "Submitting %s requests..." % requests

app_id =1

base_url = "http://localhost:8124"

def generateSessionID():
	return hashlib.md5("%s %s %s" % (random.random(), random.random(), random.random())).hexdigest()

def generateUserAgent():
	uas = [
		"Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.127 Safari/533.4",
		"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.8) Gecko/20100723 Ubuntu/10.04 (lucid) Firefox/3.6.8",
		"Mozilla/5.0 (Windows; U; Windows NT 6.1; ja-JP) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
		"Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; el-gr) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16",
		"Opera/9.99 (Windows NT 5.1; U; pl) Presto/9.9.9",
		"Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 6.0; en-US))",
		"Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7",
" BlackBerry7250/4.0.0 Profile/MIDP-2.0 Configuration/CLDC-1.1",
" Mozilla/5.0 (Linux; U; Android 2.1-update1; en-us; DROIDX Build/VZW) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17 480X854 motorola DROIDX"
	]
	return uas[random.randrange(0,len(uas))]

def generateRandomLocation():
	#return "%s/%s" % (base_url, random.random() * 100)
	return "%s/%s?b=%s&a=%s&r=%s" % (base_url, random.random() * 100, random.random(), random.random(), random.random())

def generateRandomReferrer():
	fake_refs = [
		"http://www.google.com",
		"http://www.bing.com",
		"http://www.python.org",
		"http://www.ubuntu.com",
		"http://apple.com",
#		""
	]

	fake_refs = [
		"http://search.yahoo.com/search;_ylt=ArzwVwoPkLtDZuCaivnAxc2bvZx4?fr=yfp-t-701-s&toggle=1&cop=mss&ei=UTF8&p=stuffed%20peppers",
		"http://www.bing.com/search?q=dolhin&go=&form=QBLH&qs=n&sk=&sc=8-3",
		"http://www.google.com/#sclient=psy&hl=en&safe=off&site=&source=hp&q=stuff&aq=f&aqi=g5&aql=&oq=&pbx=1&bav=on.2,or.r_gc.r_pw.&fp=7c9f4fa6ed867460",
		"http://www.google.com/search?hl=en&source=hp&biw=1304&bih=851&q=stuff&aq=f&aqi=&aql=&oq=",
#		"http://news.google.com/nwshp?hl=en&tab=wn"
	]
	return fake_refs[random.randrange(0,len(fake_refs))] + "?q=" + str(random.random())

def generateRandomResolution():
	resolutions = [
		[640, 480],
		[800, 600],
		[1024, 768],
		[1152, 864],
		[1280, 768],
		[1280, 800],
		[1280, 1024],
		[1440, 900],
		[1600, 1200],
		[1680, 1050],
		[1920, 1080],
		[1920, 1200]
	]
	return resolutions[random.randrange(0, len(resolutions))]

for req in range(requests):
	res = generateRandomResolution()

	url = base_url + "/ping?app=%s" % (app_id)
	url += "&loc=%s" % (urllib.quote(generateRandomLocation()))
	url += "&ref=%s" % (urllib.quote(generateRandomReferrer()))
	url += "&w=%s&h=%s" % (res[0], res[1])
	url += "&sessid=%s" % (generateSessionID())
	url += "&u=%s" % ("true" if random.random() < 0.3 else "false") #unique (30%)
	url += "&v=%s" % ("true" if random.random() > 0.3 else "false") #visitor (70%)
	url += "&r=%s" % ("true" if random.random() < 0.2 else "false") #refresh (20%)

	r = urllib2.Request(url)
	r.add_header("User-Agent", generateUserAgent())
	f = urllib2.urlopen(r)
	f.read()
