// Copyright 2007 - Michael Uyttersprot / eMich.be
// v0.1b - 07.03.01
// 2009 - Modified by Alexis Cretinoir

var gmlb_width=640;
var gmlb_height=480;
var gmOverlay;
var gmContainer;
var gmLabel;
var gm_close;
var frame_url;

function gmLb_init(){
	var linksArr=document.getElementsByTagName("a");
	for(i = 0;i<linksArr.length;i++){
		if(linksArr[i].getAttribute("rel")=="theme"){
			frame_url = linksArr[i].getAttribute("rev");
			gmLbAddClickEvent(linksArr[i],function(){gm_ShowTheme(this);return false;});
		}
	}

	gmOverlay = document.createElement("div");
	gmOverlay.setAttribute("id","gmlb_overlay");
	gmOverlay.style.display="none";
	gmOverlay.style.position=document.all?"absolute":"fixed";
	gmOverlay.style.top="0px";
	gmOverlay.style.left="0px";
	
	gmOverlay.style.width=gmlb_width+"px";
	gmOverlay.style.height=gmlb_height+"px";
	gmOverlay.style.width = getFrameWidth();
	gmOverlay.style.height = getFrameHeight();

	gmContainer = document.createElement("div");
	gmContainer.setAttribute("id","gmlb_container");
	gmContainer.style.width=gmlb_width+"px";
	gmContainer.style.position="absolute";
	gmContainer.style.display="inline";
	gmContainer.innerHTML="<iframe src ='"+frame_url+"' style='border:0px solid red;margin:0;padding:0;height:518px;width:100%;overflow-y:no-scroll;' ><p>Votre navigateur ne supporter pas les frame</p></iframe>";
	
	gmLabel = document.createElement("div");
	gmLabel.setAttribute("id","gmlb_label");
	gmLabel.setAttribute("class","gmlb_label");
	gmLabel.style.display="block";
	
	gmClose = document.createElement("div");
	gmClose.setAttribute("id","gmlb_close");
	gmClose.setAttribute("class","gmlb_close");
	gmClose.style.display="block";
	gmClose.onclick=function(){gmOverlay.style.display='none';gmLbOnClose();};
	
	gmContainer.appendChild(gmLabel);
	gmContainer.appendChild(gmClose);
	gmOverlay.appendChild(gmContainer);
	document.documentElement.ownerDocument.body.appendChild(gmOverlay);
}

function gm_ShowTheme(obj){
	if(document.all){
		gmOverlay.style.top = getScrollHeight()+"px";
		gmOverlay.style.left = getScrollWidth()+"px";
	}
	gmOverlay.style.width=getFrameWidth()+"px";
	gmOverlay.style.height=getFrameHeight()+"px";
	
	if(obj.title){
		gmLabel.innerHTML=obj.title;
	}
	else{
		gmLabel.innerHTML="Theme";
	}

	gmContainer.style.visibility="hidden";
	document.getElementById("gmlb_overlay").style.display="";
	gmContainer.style.left=((getFrameWidth()-gmContainer.offsetWidth)/2)+"px";
	gmContainer.style.top=((getFrameHeight()-gmContainer.offsetHeight)/2)+"px";
	gmContainer.style.border='1px solid #373737';
	gmContainer.style.visibility="";
}

function gmLbOnClose(){
	;
}

function getFrameWidth(){
	if (self.innerWidth)
	{
		return self.innerWidth;
	}
	else if (document.documentElement && document.documentElement.clientWidth)
	{
		return document.documentElement.clientWidth;
	}
	else if (document.body)
	{
		return document.body.clientWidth;
	}
	else return;
}

function getFrameHeight(){
	if (self.innerWidth)
	{
		return self.innerHeight;
	}
	else if (document.documentElement && document.documentElement.clientWidth)
	{
		return document.documentElement.clientHeight;
	}
	else if (document.body)
	{
		return document.body.clientHeight;
	}
	else return;
}

function getScrollWidth()
{
   var w = window.pageXOffset ||
           document.body.scrollLeft ||
           document.documentElement.scrollLeft;
           
   return w ? w : 0;
}

function getScrollHeight()
{
   var h = window.pageYOffset ||
           document.body.scrollTop ||
           document.documentElement.scrollTop;
           
   return h ? h : 0;
}

function gmLbAddLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	}
	else {
		window.onload = function() {
			oldonload();
			func();
		}
	}
}

function gmLbAddClickEvent(obj,func) {
	var oldonclick = obj.onclick;
	if (typeof obj.onclick != 'function') {
		obj.onclick = func;
	}
	else {
		obj.onclick = function() {
			oldonclick();
			func();
		}
	}
}

gmLbAddLoadEvent(gmLb_init);