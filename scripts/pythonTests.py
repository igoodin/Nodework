required_libs = [
	"PIL",
	"pymongo",
	"json"
]

for lib in required_libs:
	try:
		exec("import %s" % lib)
		print "Success: %s loaded" % lib
	except:
		print "Error: %s NOT loaded" % lib
