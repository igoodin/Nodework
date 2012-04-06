/*
 * Copyright (c) 2010 Base62 LLC.
 *
 * Do not distribute or modify without explicit permission from Base62 LLC.
 * This software is "as is" with no warrenty.
*/

var mongoose = require("mongoose");
var Schema = mongoose.Schema;

//////// DEFINITION
var Click = new Schema({
	app_id : { type : String, index : true },
	date : { type : Number, index : true },
	loc : {type : String, index : true },
	x : { type : Number, index : true },
	y : { type : Number, index : true },
	w : { type : Number, index : true }
});

//////// SETTERS
Click.path("loc").set(function(val){
	if(typeof(val) != "undefined"){
		return val.replace(/^\s+|\s+$/g, "");
	}
	else{
		return "";
	}
});

//register the model
mongoose.model("Click", Click);
