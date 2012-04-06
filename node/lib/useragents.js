/*
 * Copyright (c) 2010 Base62 LLC.
 *
 * Do not distribute or modify without explicit permission from Base62 LLC.
 * This software is "as is" with no warrenty.
*/

exports.parseBrowser = function(uaString){/*{{{*/
	var browser = "Other";
	var version = null;

	//formats
	/*
	* MSIE - MSIE VERSION.NUMBER
	* Firefox - Firefox/VERSIO.N.NUMBER
	* Chrome - Chrome/V.E.RSION.NUMBER
	* Opera - Opera/VERSION.NUMBER (2f)
	* Safari - has safari kw bbut uses "Version/VERSION.NUMBER" for vn
	*/

	var browser_ua = [
		{
			"name":"Chrome",
			"key":["Chrome"],
			"version":/Chrome\/(\d)\./
			//captures only the first digit of its long version number
			//d+.d+.d+.d+ -> d
		},
		{
			"name":"Opera",
			"key":["Opera"],
			"version":/Opera\/(\d\.\d{2})/
			//Grabs the whole version ex d.dd
		},
		{
			"name":"Internet Explorer",
			"key":["MSIE"],
			"version":/MSIE\ (\d)/
			//grabs only major version ex d.d+ -> d
		},
		{
			"name":"Firefox",
			"key":["Firefox"],
			"version":/Firefox\/(\d\.\d)/
			//grabs only major/minor ex d.dd -> d.d
		},
		{
			"name":"Safari",
			"key":["Safari"],
			"version":/Version\/(\d)/
			//grabs only major version ex d.dd -> d
		}
	];

	browser_ua_loop: /*label*/
	for(var i = 0; i < browser_ua.length; i++){
		for(var j = 0; j < browser_ua[i].key.length; j++){
			if(uaString.search(browser_ua[i].key[j]) != -1){
				browser = browser_ua[i].name;
				var match = uaString.match(browser_ua[i].version);
				if(match != null){
					if(typeof(match[1]) != "undefined"){
						version = match[1];
					}
				}
				break browser_ua_loop;
			}
		}
	}

	return {"browser":browser, "version":version};
}/*}}}*/

exports.parsePlatform = function(uaString){/*{{{*/
	var platform_name = "Other";
	var platform_version = null;

	var platform_ua = [
		{"name":"Linux",	"key":["Linux"], "version":null},
		{"name":"Mac OSX",	"key":["os x", "Macintosh"], "version":/Mac OS X (\d+_\d+)/},
		{"name":"Windows",	"key":["windows", 'Windows'], "version":/Windows NT (\d+\.\d+)/}
	];

	platform_ua_loop: /*label*/
	for(var i = 0; i < platform_ua.length; i++){
		for(var j = 0; j < platform_ua[i].key.length; j++){
			if(uaString.search(platform_ua[i].key[j]) != -1){
				platform_name = platform_ua[i].name;
				if(platform_ua[i].version != null){
					var matches = uaString.match(platform_ua[i].version)
					if(matches.length > 1){
						platform_version = matches[1]
					}
				}
				break platform_ua_loop;
			}
		}
	}

	return {platform:platform_name, version:platform_version};
}/*}}}*/

exports.parseMobile = function(uaString){/*{{{*/
	var mobile = false;

	var mobile_ua = [
		{"name":"iPhone","key":["iPhone"]},
		{"name":"BlackBerry","key":["BlackBerry"]},
		{"name":"Android","key":["Android"]}
	];

	mobile_ua_loop: /*label*/
	for(var i = 0; i < mobile_ua.length; i++){
		for(var j = 0; j < mobile_ua[i].key.length; j++){
			if(uaString.search(mobile_ua[i].key[j]) != -1){
				mobile = mobile_ua[i].name;
				break mobile_ua_loop;
			}
		}
	}
	return mobile;
}/*}}}*/
