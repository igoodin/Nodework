/*
 * Copyright (c) 2010 Base62 LLC.
 *
 * Do not distribute or modify without explicit permission from Base62 LLC.
 * This software is "as is" with no warrenty.
*/

var mongoose = require("mongoose");
var Schema = mongoose.Schema;

//////// DEFINITION
var Ping = new Schema({
	app_id : { type : String, index : true },
	date : { type : Number, index : true },
	loc : { type : String, index : true },
	ref : String,
	browser : String,
	version : String,
	ismobile : Boolean,
	platform : String,
	platform_version : String,
	sess : { type : String, index : true },
	ip : { type : String, index : true },
	res : String,
	unique : Boolean,
	visitor : Boolean,
	ua : String
});

///////// SETTERS
Ping.path("loc").set(function(val){
	if(typeof(val) != "undefined" && val != null){
		return val.replace(/^\s+|\s+$/g, "");
	}
	else{
		return "";
	}
});

Ping.path("ref").set(function(val){
	if(typeof(val) != "undefined" && val != null){
		return val.replace(/^\s+|\s+$/g, "");
	}
	else{
		return "";
	}
});
////////

//register the model
mongoose.model("Ping", Ping);
