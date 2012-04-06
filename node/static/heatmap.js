function __nw_render_heatmap(data){
	this.render = function(x, y){
		var canvas = document.getElementById("c");
		if(! canvas.getContext){
			canvas = G_vmlCanvasManager.initElement(canvas);
		}
		var ctx = canvas.getContext("2d");
		
		ctx.beginPath();
		ctx.arc(x, y, 10, 0, Math.PI*2, true);
		ctx.closePath();
		ctx.fillStyle = 'rgba(255,144,0,0.5)';
		ctx.fill();
	}

	var canvas = document.getElementById("c");
	canvas.width = document.body.offsetWidth;
	canvas.height = getDocHeight();
	for(var i = 0; i < data.clicks.length; i++){		
		if(data.scale){
			var ratio = canvas.width/data.clicks[i].w;
			this.render(data.clicks[i].x*ratio,data.clicks[i].y);
		}else{
			this.render(data.clicks[i].x,data.clicks[i].y);
		}
	}
}

function __nw_construct_heatmap(Store, Globals){
	var url_params = {};
	var qs = window.location.toString().split("?")[1];
	qs = qs.split("&");
	for(var i =0; i < qs.length;i++){
		var entry = qs[i].split("=");
		url_params[entry[0]] = entry[1];
	}

	var new_head = document.createElement("div");
	new_head.setAttribute("style",
		"background-color:#232323;width:100%;height:70px;font-size:20px;position:absolute; z-index:1000; left:0; top:0;"
	);

	var dummy_div = document.createElement("div");
	dummy_div.setAttribute("style", "height: 70px; width: 100%;");

	new_head.innerHTML = "<img src=\""+unescape(url_params["_nwhost"])+"nodework/views/static/images/nodeworklogo.png\"/>";
	new_head.innerHTML += "<a style=\"color:#dcdcdc;\" href=\"javascript:window.close()\">Close</a>";

	var c = document.getElementsByTagName("body")[0].childNodes[0];
	document.body.insertBefore(dummy_div, c);
	document.body.insertBefore(new_head, c);

	var here = window.location.toString();
	here = here.replace(/(\?|&)_nwheatmap=[^&]+/, '');
	here = here.replace(/(\?|&)_nwkey=[^&]+/, '');
	here = here.replace(/(\?|&)_nwhost=[^&]+/, '');
	here = here.replace(/(\?|&)_nwstart_date=[^&]+/, '');
	here = here.replace(/(\?|&)_nwend_date=[^&]+/, '');

	var float_div = document.createElement("canvas");
	float_div.setAttribute("id","c");
	float_div.style.position = "absolute";
	float_div.style.top = "70px";
	float_div.style.left = "0px";
	float_div.style.width = "100%";
	float_div.style.zIndex = "1000";
	float_div.style.background = "#5c5c5c";
	float_div.style.height = getDocHeight() + "px";
	float_div.style.opacity = "0.6";
	float_div.style.filter = "alpha(opacity=60)";

	c = document.getElementsByTagName("body")[0].childNodes[0];
	document.body.insertBefore(float_div, c);
	
	//page needs to come last
	var click_url = unescape(url_params["_nwhost"])+"index.php/heatmap/clicks?app_id="+Store.AppID+"&key="+escape(url_params["_nwkey"])+"&w="+escape(Globals.WindowWidth())+"&start_date="+escape(url_params['_nwstart_date'])+"&end_date="+escape(url_params['_nwend_date'])+"&page="+escape(here);

	var clickscript  = document.createElement("script");
	clickscript.id ="clickrequest";
	clickscript.setAttribute("src", click_url + "&q=" + Math.random());
	clickscript.setAttribute("type","text/javascript");
	document.body.appendChild(clickscript);
}

function getDocHeight() {
	var D = document;
		return Math.max(
			Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
			Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
			Math.max(D.body.clientHeight, D.documentElement.clientHeight)
	);
}
