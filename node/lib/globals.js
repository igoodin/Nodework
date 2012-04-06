/*
 * Copyright (c) 2010 Base62 LLC.
 *
 * Do not distribute or modify without explicit permission from Base62 LLC.
 * This software is "as is" with no warrenty.
*/

/* String functions {{{*/
exports.empty = function(candidate){
	if(
		candidate == false ||
		isNull(candidate) ||
		isUndefined(candidate) ||
		trim(candidate) == "" ||
		candidate == 0 ||
		candidate.length == 0
		){
		return true;
	}
}

function trim(str){
	if(typeof(str) == "undefined"){
		return false;
	}
	return str.replace(/^\s+|\s+$/g, "");
}
exports.trim = trim;/*}}}*/

/*Type Checking {{{*/
function isNull(val){
	if(val == null){
		return true;
	}
	return false;
}
exports.isNull = isNull;

function isUndefined(val){
	if(typeof(val) == "undefined"){
		return true;
	}
	return false;
}
exports.isUndefined = isUndefined;

function isBool(val){
	if(typeof(val) == "boolean"){
		return true;
	}
	return false;
}
exports.isBool = isBool;

function isNumber(val){
	if(typeof(val) == "number"){
		return true;
	}
	return false;
}
exports.isNumber = isNumber;

function isString(val){
	if(typeof(val) == "string"){
		return true;
	}
	return false;
}
exports.isString = isString;

function isFunction(val){
	if(typeof(val) == "function"){
		return true;
	}
	return false;
}
exports.isFunction = isFunction;/*}}}*/

