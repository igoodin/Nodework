function __Monitor(){/*{{{*/
	var Store = {/*{{{*/
		SessID : "",
		Host : null,
		Unique : null,
		Visitor : null,
		Refresh: null
	};/*}}}*/

	var Globals = {/*{{{*/
		MakeScript : function(src, params){
			var script = document.createElement("script");
			script.src = src;

			script.src += "?app=" + escape(Store.AppID);
			if(params.length > 0){
				for(var i = 0; i < params.length; i++){
					script.src += "&" + params[i].join("=");
				}
			}

			return script;
		},

		WindowWidth : function(){
			var win_w = null;
			if(parseInt(navigator.appVersion) > 3){
				if(/Microsoft/i.test(navigator.appName)){
					win_w = document.body.offsetWidth;
				}
				else{
					win_w = window.innerWidth;
				}
			}
			return win_w;
		},

		WindowHeight : function(){
			var win_h = null;
			if(parseInt(navigator.appVersion) > 3){
				if(/Microsoft/i.test(navigator.appName)){
					win_h = document.body.offsetHeight;
				}
				else{
					win_h = window.innerHeight;
				}
			}
			return win_h;
		},

		GenerateSessionID : function(){
			var chars = "";
			var ranges = [[48,57], [97,122], [65,90]];
			for(var r in ranges){
				for(var i = ranges[r][0]; i <= ranges[r][1]; i++){
					chars += String.fromCharCode(i);
				}
			}
			var sess_id = "";
			for(var i = 0; i < 26; i++){
				sess_id += chars.charAt(Math.floor(Math.random() * chars.length));
			}
			return sess_id;
		},

		Trim : function(val){
			if(typeof(val) == "string"){
				return val.replace(/^\s+|\s+$/g, "");
			}
			return val;
		}
	};/*}}}*/

	var Cookie = {/*{{{*/
		Set : function(name, value, expires, path, domain){
			var cookie_str = escape(name)+"="+value+";";
			if(Globals.Trim(expires) != ""){
				cookie_str += " expires="+expires.toGMTString()+";";
			}
			if(Globals.Trim(expires) != ""){
				cookie_str += " path="+escape(path)+";";
			}
			if(Globals.Trim(domain) != ""){
				cookie_str += " domain="+escape(domain)+";";
			}

			document.cookie = cookie_str;
		},

		Get : function(cookie_name){
			var cookies = document.cookie.split(";");
			for(var i = 0; i < cookies.length; i++){
				var c = cookies[i].split("=");
				if(Globals.Trim(c[0]) == cookie_name){
					return c[1];
				}
			}
			return false;
		},

		ToObj : function(cookie){
			obj = {};
			segments = cookie.split(",");
			for(var i = 0; i < segments.length; i++){
				values = segments[i].split(":");
				obj[values[0]] = unescape(values[1]);
			}
			return obj;
		},

		ToSafe : function(obj){
			var str = "";
			for(var prop in obj){
				str += prop + ":" + escape(obj[prop])+ ",";
			}
			return str;
		}
	};/*}}}*/

	var Container = {/*{{{*/
		ContainerBase : "__analytics",
		Containter : "__analytics",
		EnsureContainerAvailability : function(){
			while(document.getElementById(Container.ContainerBase) != null){
				Container.Container = Container.ContainerBase + "_" + ("" + Math.random()*10).replace(".", "");
			}

			var div = document.createElement("div");
			div.setAttribute("style", "display: none;");
			div.id = Container.Container;

			var body = document.getElementsByTagName("body")[0];
			body.appendChild(div);
		},

		AddElement : function(element){
			document.getElementById(this.container).appendChild(element);
		}
	};/*}}}*/

	var Operations = {/*{{{*/
		ClickEvent : function(e){
			var x = null;
			var y = null;

			if(navigator.appName != "Microsoft Internet Explorer"){
				x = e.pageX;
				y = e.pageY;
			}
			else{
				x = event.clientX + document.body.scrollLeft;
				y = event.clientY + document.body.scrollTop;
			}

			if(typeof(x) == "number" && typeof(y) == "number"){
				var vars = [
						["loc", escape(document.location.href)],
						["x", escape(x)],
						["y", escape(y)],
						["q", Math.random()]
				];

				var win_w = Globals.WindowWidth();

				if(typeof(win_w) == "number"){
					vars[vars.length] = ["w", escape(win_w)];
				}

				var script = Globals.MakeScript(
					Store.Host + "/click",
					vars
				);

				Container.AddElement(script);
			}
		},

		LinkUp : function(){
			var elements = document.getElementsByTagName("a");
			for(var i = 0; i < elements.length; i++){
				if(elements[i].getAttribute("onclick") == null){
					elements[i].onclick = function(e){
						Operations.ClickEvent(e);
					}
				}
				else{
					elements[i].onclick = function(e){
						Operations.ClickEvent(e);
						this.ret = (eval("this.ret = function(){"+e.target.getAttribute("onclick")+"}();"));
						if(typeof(this.ret) == "boolean"){
							return this.ret;
						}
						else{
							return true;
						}
					}
				}
			}

			if(document.onclick == null){
				document.onclick = function(e){
					Operations.ClickEvent(e);
				}
			}
			else{
				document.onclick = function(e){
					Operations.ClickEvent(e);
					this.ret = (eval("this.ret = function(){"+e.target.getAttribute("onclick")+"}();"));
					if(typeof(this.ret) == "boolean"){
						return this.ret;
					}
					else{
						return true;
					}
				}
			}
		},

		Ping : function(){
			var vars = [
					["loc", escape(document.location.href)],
					["sessid", escape(Store.SessID)],
					["ref", escape(document.referrer)],
					["u", escape((Store.Unique) ? "true" : "false")],
					["v", escape((Store.Visitor) ? "true" : "false")],
					["r", escape((Store.Refresh) ? "true" : "false")],
					["q", Math.random()]
			];

			if(typeof(screen.width) == "number" && typeof(screen.height) == "number"){
				vars[vars.length] = ["w", escape(screen.width)];
				vars[vars.length] = ["h", escape(screen.height)];
			}


			var script = Globals.MakeScript(
				Store.Host + "/ping",
				vars
				);
			Container.AddElement(script);
		}
	};/*}}}*/

	this.Run = function(){/*{{{*/
		Container.EnsureContainerAvailability();

		Store.AppID = __app_id;
		Store.Host = __host;
		Store.CookieName = "__sess_" + Store.AppID;

		if(window.location.toString().match(/_nwheatmap/)){
			var script = document.createElement("script");
			script.src = Store.Host + "/js/heatmap.js";
			if(script.addEventListener){
				script.addEventListener("load", function(){
					__nw_construct_heatmap(Store, Globals);
				}, false);
			}
			else{
				/*
				@todo implement this
				*/
				script.attachEvent("onreadystatechange", function(){
					if(script.readyState == "loaded"){
						var excanvas = document.createElement("script");
						excanvas.type = "text/javascript";
						excanvas.src = Store.Host + "/js/excanvas.js";
						excanvas.onreadystatechange = function(){
							if(excanvas.readyState == "loaded"){
								__nw_construct_heatmap(Store, Globals);
							}
						}

						document.body.appendChild(excanvas);
					};
				});

			}
			//Container.AddElement(script);
			document.body.insertBefore(script, document.getElementsByTagName("body")[0].childNodes[0]);
		}
		else{
			if(Cookie.Get(Store.CookieName) == false){
				Store.SessID = Globals.GenerateSessionID();
				Store.Unique = true;
				Store.Refresh = false;
			}
			else{
				var cookie = Cookie.Get(Store.CookieName);
				cookie = Cookie.ToObj(cookie);

				Store.SessID = cookie["sessid"];
				Store.Unique = false;

				if(cookie["visit"] == (new Date()).getUTCDate()){
					/* this person has come here today, let's not log them */
					Store.Visitor = false;
				}
				else{
					/* this person is new today, we'll log them */
					Store.Visitor = true;
				}

				if(cookie["last"] == document.location.href){
					Store.Refresh = true;
				}
				else{
					Store.Refresh = false;
				}
			}

			var expire = new Date();
			expire.setDate(expire.getDate() + 30);
			var c_dat = {
				sessid:Store.SessID,
				visit:(new Date()).getUTCDate(),
				last:document.location.href
			};
			Cookie.Set(Store.CookieName, Cookie.ToSafe(c_dat), expire, "/", "");

			Operations.Ping();
			Operations.LinkUp();
		}
	}/*}}}*/
}/*}}}*/

/* Execution Loop {{{*/
if(/MSIE/i.test(navigator.userAgent)){
	if(/loaded|complete/i.test(document.readyState)){
		new __Monitor().Run();
	}
	else{
		window.attachEvent("onload", function(){
			new __Monitor().Run();
		});
	}
}
else{
	if(/WebKit/i.test(navigator.userAgent)){
		window.addEventListener("load", function(){
			new __Monitor().Run();
		}(), false);
	}
	else{
		window.addEventListener("load", function(){
			new __Monitor().Run();
		}, false);
	}

}
/*}}}*/
