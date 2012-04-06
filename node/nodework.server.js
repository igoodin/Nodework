/*
 * Copyright (c) 2010 Base62 LLC.
 *
 * Do not distribute or modify without explicit permission from Base62 LLC.
 * This software is "as is" with no warrenty.
*/

/*Imports {{{*/
var http = require("http");
var fs = require("fs");
var url = require("url");

//Custom
var useragents = require("./lib/useragents.js");
var globals = require("./lib/globals.js");

/*
* To Install mongoose:
* git clone git@github.com:LearnBoost/mongoose.git node/lib/mongoose
* cd node/lib/mongoose
* git checkout 1.0.16
*/
require.paths.unshift(__dirname + '/lib/mongoose/lib');
var mongoose = require("mongoose");

/* Models */
var PingModel = require("./models/pings.js");
var ClickModel = require("./models/clicks.js");
/*}}}*/

/*Init {{{*/

var LISTEN_PORT = 8124;
var LISTEN_HOST = "0.0.0.0";

mongoose.connect("mongodb://localhost/db", function(err){
	if(err){
		if(err.code == "ECONNREFUSED"){
			console.log("connection refused");
			node.exit(0);
		}
	}
});

server = http.createServer(serverResponse_Callback);
server.listen(LISTEN_PORT, LISTEN_HOST);
console.log("Server running at http://" + LISTEN_HOST + ":" + LISTEN_PORT +"/");

/*}}}*/

function getUTCTime(){
	date = new Date();
	return date.getTime() - (date.getTimezoneOffset() * 60000);
}

function serverResponse_Callback(request, response){/*{{{*/
	var app = extractApplicationID(request);

	var ANALYTICS_REGEX = /^\/js\/analytics\.js.*$/i;
	var HEATMAP_REGEX = /^\/js\/heatmap\.js.*$/i;
	var EXCANVAS_REGEX = /^\/js\/excanvas\.js.*$/i;
	var PING_REGEX = /^\/ping?.*$/i;
	var CLICK_REGEX = /^\/click?.*/i

	var u = url.parse(request.url).pathname;
	if(u.search( ANALYTICS_REGEX ) != -1){
		sendStaticFile(__dirname+"/static/analytics.js", request, response);
	}
	else if(u.search( HEATMAP_REGEX ) != -1){
		sendStaticFile(__dirname+"/static/heatmap.js", request, response);
	}
	else if(u.search( EXCANVAS_REGEX ) != -1){
		sendStaticFile(__dirname+"/static/excanvas.js", request, response);
	}
	else if(app != false){ /* app must be defined */
		if(u.search( PING_REGEX ) != -1){
			processPing(app, request, response);
		}
		else if(u.search( CLICK_REGEX ) != -1){
			processClickEvent(app, request, response);
		}
		else{ /* Send 404 page (invalid request) */
			logif("Invalid Request - 404")
			send404(request, response);
		}
	}

}/*}}}*/

function send404(request, response){/*{{{*/
	var body = "404: " + request.url + "not found.";
	response.writeHead(404,{
		"Content-Length": body.length,
		"Content-Type": "text/plain"
	});
	response.end(body);
}/*}}}*/

function sendStaticFile(js_file, request, response){/*{{{*/
	fs.readFile(js_file, function(err, data){
		if(err){
			logif("Error Reading File: " + js_file);
			send404(request, response);
		}
		else{
			response.writeHead(200, {
				"Content-Length" : data.length,
				"Content-Type" : "text/javascript",
				"Cache-Control" : "no-cache"
			});
			response.end(data);
		}
	});
}/*}}}*/

function processClickEvent(app_id, request, response){/*{{{*/
	var q = url.parse(request.url, true);

	var loc = q.query.loc;
	var x = q.query.x;
	var y = q.query.y;
	var w = q.query.w;

	if(globals.isUndefined(w)){
		w = -1;
	}

	var valid = true;
	if(globals.empty(loc) || globals.isUndefined(x) || globals.isUndefined(y)){
		valid = false;
	}

	if(valid){
		var Click = mongoose.model("Click");

		var c = new Click();
		c.app_id = app_id;
		c.date = getUTCTime();
		c.loc = loc;
		c.x = x;
		c.y = y;
		c.w = w;
		c.save(function(err){
			if(err){
				node.exit(0); //somehow lost connection with mongo
			}
			logif("Click for app: " + app_id + " @ (" + x + ", " + y + ") on " + loc);
		});
	}
	else{
		logif("Invalid Click Request");
	}

	//End response
	response.writeHead(200);
	response.end("");
}/*}}}*/

function processPing(app_id, request, response){/*{{{*/
	var q = url.parse(request.url, true);
	var user_agent = request.headers['user-agent'];

	var session = q.query.sessid;

	var loc = q.query.loc;
	var referrer = q.query.ref;

	var browser_data = useragents.parseBrowser(user_agent);
	var browser = browser_data.browser;
	var browser_version = browser_data.version;

	var platform_data = useragents.parsePlatform(user_agent);
	var platform = platform_data.platform;
	var platform_version = platform_data.version;
	var mobile = useragents.parseMobile(user_agent);

	var ismobile = false;
	if(mobile != false){
		ismobile=true;
		browser=mobile;
		platform=null;
	}

	var ip = request.connection.remoteAddress;

	var refresh = q.query.r;

	var unique = q.query.u;
	var visitor = q.query.v;

	if(globals.trim(unique) == "true"){
		unique = true;
	}
	else{
		unique = false;
	}

	if(globals.trim(visitor) == "true"){
		visitor = true;
	}
	else{
		visitor = false;
	}

	if(globals.trim(refresh) == "true"){
		refresh = true;
		referrer = null;
	}
	else{
		refresh = false;
	}

	var screen_width = null;
	var screen_height = null;

	if(!globals.empty(q.query.w) && !globals.empty(q.query.h)){
		screen_width = q.query.w;
		screen_height = q.query.h;
	}

	var valid = true;
	if(
		globals.empty(loc) ||
		globals.empty(user_agent) ||
		globals.empty(session) ||
		!globals.isBool(unique) ||
		!globals.isBool(visitor) ||
		!globals.isBool(refresh) ||
		globals.empty(screen_width) ||
		globals.empty(screen_height)
		){
		valid = false;
	}

	if(valid){
		var Ping = mongoose.model("Ping");

		var p = new Ping();
		var time = getUTCTime();

		p.app_id = app_id;
		//p.date = time - (60000*60*24 * Math.round((Math.random() * 2)));
		p.date = time;
		p.loc = loc;
		p.ref = referrer;
		p.browser = browser;
		p.version = browser_version;
		p.ismobile = ismobile;
		p.platform = platform;
		p.platform_version = platform_version;
		p.sess = session;
		p.ip = ip;
		p.res = screen_width + "x" + screen_height;
		p.unique = unique;
		p.visitor = visitor;
		p.ua = user_agent;
		p.save(function(err){
			if(err){
				node.exit(0); //somehow lost connectiont to mongo
			}
			logif("Ping for: " + loc + " via " + browser + " " + browser_version + " on " + platform + " " + platform_version);
		});
	}
	else{
		logif("Invalid Ping Request");
	}

	//End Response
	response.writeHead(200);
	response.end("");
}/*}}}*/

function extractApplicationID(request){/*{{{*/
	var u = url.parse(request.url, true);

	if(typeof(u.query) == "undefined" || typeof(u.query.app) == "undefined"){
		return false;
	}

	return u.query.app;
}/*}}}*/

/* Debug Functions {{{*/
DEBUG = false;
for(var i = 0; i < process.argv.length; i++){
	if(process.argv[i] == "--debug"){
		DEBUG = true;
	}
}

function logif(text){
	if(DEBUG){
		console.log(text);
	}
}

function logif(text, ignoreDebug){
	if(DEBUG || ignoreDebug){
		console.log(text);
	}
}/*}}}*/

