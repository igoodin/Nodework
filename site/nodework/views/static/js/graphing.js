var graphing = {};

(function () {
	var p ={};	
	var canvas=null;
	var ctx=null;
	p.width=725;
	p.height=395;
	p.type = 'scatter';
	p.xmargin=70;
	p.ymargin=20;
	p.xoffset=30;
	p.yoffset=10;
	p.yticks=7;
	p.xticks=24;
	p.gwidth=p.width-p.xmargin-p.xoffset;
	p.gheight=p.height-p.ymargin-p.yoffset;
	
	function init(options){	
		for(o in options){
			p[o]=options[o];
		};	
	}	
	
	function makecanvas(){
		canvas = document.createElement('canvas');
		document.getElementById(p.id).appendChild(canvas);
		
		canvas.setAttribute("width",p.width);
		canvas.setAttribute("height",p.height);
		canvas.setAttribute("class","awesomegraph-canvas");
		
		//run excanvas on the dynamic div
		if (typeof G_vmlCanvasManager != 'undefined') {
		    canvas = G_vmlCanvasManager.initElement(canvas);
		}		

		if (canvas.getContext){
			ctx = canvas.getContext('2d');
		}		
	}
	
	this.chart = function(options) {
		init(options);
		makecanvas();
		processdata();
		renderChart[p.type]();
	};		
	
	function processdata(){		
		if(p.invertaxis){
			for(i in p.datasets){
				var set =p.datasets[i];
				for(i in set.data){
					var t1 =set.data[i][0];
					var t2=set.data[i][1];

					set.data[i][0]=t2;
					set.data[i][1]=t1;
				}
			}
		}	
		p['xticks'] = p.xaxis.labels.length;
		p['yticks'] = p.yaxis.labels.length;
		p["xscale"]=(p.gwidth)/(p.xticks);
		p["yscale"]=(p.gheight)/(p.yticks);		
	}
	
	function drawaxis(){
		ctx.strokeStyle = "#000000";
		ctx.fillStyle = "#000000";
		ctx.moveTo(0+p.xmargin,p.gheight)
		ctx.lineTo(p.gwidth+p.xmargin+10.0,p.gheight)
		ctx.moveTo(0+p.xmargin,0+5.0)
		ctx.lineTo(0+p.xmargin,p.gheight)
		ctx.stroke();	

		for(var i=0;i<p.xticks;i++){
			var text= p.xaxis.labels[i];

			var xpos=i*p.xscale+p.xoffset+p.xmargin;
			var ypos=p.gheight+p.ymargin;
			
			ctx.textAlign="center";
			ctx.font = '8pt Verdana';
			ctx.fillText(text,xpos,ypos);
		}		
		
		for(i=1;i<=p.yticks;i++){
			text= p.yaxis.labels[i-1];
	
			xpos=p.xmargin/2;
			ypos=(p.yoffset-p.ymargin)+p.height-i*p.yscale;
			
			ctx.textAlign="center";
			ctx.textBaseline="middle";
			ctx.font = '8pt Verdana';
			ctx.fillText(text,xpos,ypos);			
		}

	}	
	
	renderChart = {
		scatter:function(){

			for(i in p.datasets){
				var set =p.datasets[i];
				for(i in set.data){
					var point = set.data[i];
					var radius=6.7*point[2]/100;

					x =p.xoffset+p.xmargin+p.xscale*point[0];
					y=(p.height+p.yoffset-p.ymargin)-(p.yscale*point[1]);

					hex=parseInt(Math.sqrt(point[2])*15);					
					hex=256-hex;
					hex= hex.toString(16);

					color="#"+hex+hex+hex;

					ctx.fillStyle=color;					
					ctx.beginPath();
					ctx.arc(x,y,radius,0,2*Math.PI,true);
					ctx.closePath();	
					ctx.fill();
				}
				//plots empty point to clear the context
				ctx.beginPath();
				ctx.arc(x,y,0.0,0,2*Math.PI,true);
				ctx.closePath();	
				ctx.fill();			
			};
			
			drawaxis();			
		}
	}

}).apply(graphing);
