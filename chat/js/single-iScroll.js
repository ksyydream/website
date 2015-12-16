(function(a,f,e){function c(){var d=[].shift.call(arguments);this.el=d.el;this.direction=d.direction||"top";this.scrollBar=d.scrollBar;this.scrollTo=d.scrollTo||0;this.prevBtn=d.prevBtn;this.nextBtn=d.nextBtn;this.speed=d.speed||10;this.barSpeed=0;this.autoScroll=d.autoScroll;this.focus=d.focus;this.smoothMode=d.smoothMode;this.init=d.init;this.ajaxBtn=d.ajaxBtn;this.done=d.done;this._callbackLock_=true;this.cache={els_pos:0,sb_pos:0,ms_pos:0,ms_lastpos:0};this.CriticalBar={low:0,high:0};this.CriticalEl={low:0,high:0};this.initialize()}c.prototype={initialize:function(){if(this.focus&&!c.isMobile){this.barFocus()}if(this.prevBtn||this.nextBtn){this.doBtnEvent()}if(this.autoScroll){}if(this.resize){this.resizeBar()}if(this.done){this.done.timmer=null;if(this.ajaxBtn){var d=this;this.activeType=0;c.addEvent(this.ajaxBtn,"click",function(g){d.doneCallback(d.done);g.preventDefault?g.preventDefault():g.returnValue=false})}else{this.activeType=1}}this.initScrollBar();this.bind()},mousewheel:function(g){var d=this;if("onmousewheel" in this.el){this.el.onmousewheel=function(h){var h=h||a.event;h._detail=h.wheelDelta/120;g.call(d,h)}}else{this.el.addEventListener("DOMMouseScroll",function(h){var h=h||a.event;h._detail=-(h.detail||0)/3;g.call(d,h)},false)}},initScrollBar:function(){this.buildBar();if(!c.isMobile){this.initDrag()}if(this.scrollTo){this.doScrollto()}},buildBar:function(){var l=this.el.parentNode,h=l.style;if(c.getStyle(l,"display")==="none"){l.tmpStyle=h.cssText;h.cssText+=";position: absolute;visibility:hidden;display:block;"}navigator.userAgent.indexOf("MSIE 8.0")!==-1&&!c.getStyle(this.scrollBar.parentNode,"z-index")&&(this.scrollBar.parentNode.style.zIndex=0);var k=this.direction==="top"?"offsetHeight":"offsetWidth",g=this.scrollBar.style;if(this.prevBtn){g[this.direction]=(this.CriticalBar.low=this.prevBtn[k])+"px"}else{g[this.direction]=0}this.elHeight=this.direction==="top"?this.el.scrollHeight:this.el.scrollWidth;var j=this.direction==="top"?"height":"width",i=this.el[k];var d=this.scrollBar.parentNode[k]-(this.prevBtn?this.prevBtn[k]:0)-(this.nextBtn?this.nextBtn[k]:0);this.cache.sb_pos=Math.floor(this.cache.els_pos*d/this.elHeight)+this.CriticalBar.low;g[this.direction]=this.cache.sb_pos+"px";g[j]=Math.floor(i*d/this.elHeight)+"px";this.CriticalBar.high=d-parseInt(g[j],10)+this.CriticalBar.low;this.barSpeed=(d-this.scrollBar[k])*this.speed/(this.CriticalEl.high=this.elHeight-i,this.CriticalEl.high)||0;typeof l.tmpStyle!=="undefined"&&(h.cssText=l.tmpStyle,l.tmpStyle=e)},initDrag:function(){var m=false,d=this.scrollBar,i=this.cache,j=this.direction==="top"?"scrollTop":"scrollLeft",h=this.direction==="top"?"clientY":"clientX",g=this.el,k=g.parentNode,l=this.scrollBar.style,n=this;d.onmousedown=function(o){var o=o||a.event;i.ms_pos=o[h];m=true;k.mouseout&&(c.detachEvent(k,"mouseover",k.mouseover),c.detachEvent(k,"mouseout",k.mouseout));f.onselectstart=function(){return false};f.onmousemove=function(s){var s=s||a.event;if(m){i.ms_lastpos=s[h];var q=i.ms_lastpos-i.ms_pos,p=i.sb_pos+q,r;p<n.CriticalBar.low&&(p=n.CriticalBar.low);p>=n.CriticalBar.high&&(p=n.CriticalBar.high);r=q*n.speed/n.barSpeed;g[j]=i.els_pos+r;l[n.direction]=p+"px"}s.preventDefault?s.preventDefault():s.returnValue=false};f.onmouseup=function(){m=false;i.els_pos=g[j];i.sb_pos=parseInt(l[n.direction],10);try{f.onselectstart=f.onmouseup=f.onmousemove=e}catch(p){}k.mouseout&&(c.addEvent(k,"mouseover",k.mouseover),c.addEvent(k,"mouseout",k.mouseout));i.els_pos===n.CriticalEl.high&&n.done&&n.activeType&&n.doneCallback(n.done)}}},bind:function(){var j=this.direction==="top"?"scrollTop":"scrollLeft";if(c.isMobile){var m=false,d=this.scrollBar,i=this.cache,h=this.direction==="top"?"pageY":"pageX",g=this.el,k=g.parentNode,l=this.scrollBar.style,n=this;this.el.addEventListener("touchstart",function(p){i.ms_pos=p.touches[0][h];m=true;f.onselectstart=function(){return false};function o(u){if(m){i.ms_lastpos=u.touches[0][h];var s=i.ms_pos-i.ms_lastpos,r=i.sb_pos+s,t;r<n.CriticalBar.low&&(r=n.CriticalBar.low);r>=n.CriticalBar.high&&(r=n.CriticalBar.high);t=s*n.speed/n.barSpeed;g[j]=i.els_pos+t;l[n.direction]=r+"px"}u.preventDefault()}function q(){m=false;i.els_pos=g[j];i.sb_pos=parseInt(l[n.direction],10);try{f.onselectstart=e;n.el.removeEventListener("touchmove",o,false);n.el.removeEventListener("touchend",q,false)}catch(r){}i.els_pos===n.CriticalEl.high&&n.done&&n.activeType&&n.doneCallback(n.done)}n.el.addEventListener("touchmove",o,false);n.el.addEventListener("touchend",q,false)},false)}else{this.mousewheel(function(o){if(o._detail<0){this.process({barSpeed:this.barSpeed,speed:this.speed,direction:j})}else{if(o._detail>0){this.process({barSpeed:-this.barSpeed,speed:-this.speed,direction:j})}}});this.unbindDocumentEvent()}this.init&&this.init()},unbindDocumentEvent:function(){var d=this;c.addEvent(this.el,"mouseover",function(h){var g=h.relateTarget||h.fromElement;if(c.isContains(d.el,g)){return}if("onmousewheel" in d.el){f.onmousewheel=function(){return false
}}else{f.tmpDOMMouseScroll=function(i){i.preventDefault()};f.addEventListener("DOMMouseScroll",f.tmpDOMMouseScroll,false)}});c.addEvent(this.el,"mouseout",function(h){var g=h.relateTarget||h.toElement;if(c.isContains(d.el,g)){return}if("onmousewheel" in d.el){f.onmousewheel=null}else{f.removeEventListener("DOMMouseScroll",f.tmpDOMMouseScroll,false)}})},doScrollto:function(){if(this.scrollTo>this.CriticalEl.high||this.scrollTo<this.CriticalEl.low){return}var d=this.direction==="top"?"scrollTop":"scrollLeft";this.cache.els_pos=this.el[d]=this.scrollTo;this.scrollBar.style[this.direction]=(this.cache.sb_pos+=this.scrollTo/this.speed*this.barSpeed)+"px"},scrollOffset:function(l,h){var g=this,k=this.direction==="top"?"scrollTop":"scrollLeft",d,j,i;if(this.scrollTo>this.CriticalEl.high||this.scrollTo<this.CriticalEl.low){return}if(h){this.offsetTimmer&&clearTimeout(g.offsetTimmer);j=(l-this.cache.els_pos)/h*24;i=+new Date;d=function(){if(+new Date-i<h){g.el[k]=g.cache.els_pos+=j;g.scrollBar.style[g.direction]=(g.cache.sb_pos=g.cache.els_pos/g.speed*g.barSpeed)+"px";g.offsetTimmer=setTimeout(d,24)}else{clearTimeout(g.offsetTimmer);g.cache.els_pos=g.el[k]=l;g.scrollBar.style[g.direction]=(g.cache.sb_pos=l/g.speed*g.barSpeed)+"px"}};d()}else{this.cache.els_pos=this.el[k]=l;this.scrollBar.style[this.direction]=(this.cache.sb_pos=l/this.speed*this.barSpeed)+"px"}},doAutoScroll:function(){},doBtnEvent:function(){var g=this,i=g.el,h;var j=this.direction==="top"?"scrollTop":"scrollLeft";function d(o,l){var k,n,m;l===1?(k=g.speed,n=g.barSpeed,m=1):(k=-g.speed,n=-g.barSpeed,m=-1);(function(){g.process({direction:j,speed:k,barSpeed:n});h=a.requestAnimationFrame(arguments.callee)})()}if(c.isMobile){this.nextBtn.addEventListener("touchstart",function(k){d(k,1);k.preventDefault()});this.nextBtn.addEventListener("touchend",function(k){a.cancelAnimationFrame(h)});this.prevBtn.addEventListener("touchstart",function(k){d(k,-1);k.preventDefault()});this.prevBtn.addEventListener("touchend",function(k){a.cancelAnimationFrame(h)})}else{this.nextBtn.onmousedown=function(k){var k=k||a.event;(k.which===1||k.button===1)&&d(k,1);k.preventDefault?k.preventDefault():k.returnValue=false};this.prevBtn.onmousedown=function(k){var k=k||a.event;(k.which===1||k.button===1)&&d(k,-1);k.preventDefault?k.preventDefault():k.returnValue=false};this.nextBtn.onmouseup=this.prevBtn.onmouseup=function(){a.cancelAnimationFrame(h)}}},barFocus:function(){var h=this,k=this.scrollBar.parentNode.style,j=this.el.parentNode,i;k.zoom=1;var g=function(n,m){var n=a.event||n;var l=n.relatedTarget||n[m];if(c.isContains(j,l)){return}m==="fromElement"?d(0,1):d(1,0)};j.mouseover=function(l){g(l,"fromElement")};j.mouseout=function(l){g(l,"toElement")};function d(n,l){var m=(l-n)/16;(function(){k.opacity=n;k.filter="alpha(opacity="+Math.floor(n*100)+")";if(n===l){if(n>=1&&k.removeAttribute){k.removeAttribute("filter")}a.cancelAnimationFrame(i)}else{n+=m;i=a.requestAnimationFrame(arguments.callee)}})()}c.addEvent(j,"mouseover",j.mouseover);c.addEvent(j,"mouseout",j.mouseout)},process:function(l){var k=l.direction,j=this.el,g=this,h=this.scrollBar.style,d=this.cache,i;d.sb_pos+=l.barSpeed||this.barSpeed;d.sb_pos>=this.CriticalBar.high&&(d.sb_pos=this.CriticalBar.high,d.els_pos=this.CriticalEl.high)&&this.done&&this.activeType&&this.doneCallback(this.done);d.sb_pos<this.CriticalBar.low&&(d.sb_pos=this.CriticalBar.low,d.els_pos=this.CriticalEl.low);j[k]=d.els_pos+=l.speed;h[this.direction]=d.sb_pos+"px"},doneCallback:function(d){var g=this;clearTimeout(d.timmer);d.timmer=setTimeout(function(){d.call(g)},50)},ajax:function(j){var h=this;if(h._callbackLock_){h._callbackLock_=false;if(j.type==="jsonp"){var g=f.createElement("script"),k=j.callbackName?j.callbackName:"iScroll"+encodeURIComponent(location.href).replace(/[^\w\s]/g,"");a[k]=function(n){g.jsonp=1;j.onsuccse&&j.onsuccse.call(h,n);h._callbackLock_=true;h.buildBar()};if(g.readyState){g.onreadystatechange=function(){if(/loaded|complete/i.test(g.readyState)){d()}else{if(g.readyState==="loading"){j.onprocess&&j.onprocess.call(h)}}}}else{g.onload=g.onerror=function(){d()}}function d(){if(typeof g.jsonp==="undefined"){j.onerror&&j.onerror.call(h)}if(g.clearAttributes){g.clearAttributes()}else{g.onload=g.onreadystatechange=g.onerror=null}g.parentNode.removeChild(g);h._callbackLock_=true}g.src=j.url+(j.url.indexOf("?")!==-1?"&callback=":"?callback=")+k;f.getElementsByTagName("head")[0].appendChild(g)}else{var m=a.XMLHttpRequest?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP"),i=j.dataType==="text/plain"||typeof j.dataType==="undefined"?"responseText":j.dataType==="text/xml"&&"responseXML",l=j.params||null;m.open(j.type?j.type:"get",j.url,true);m.onreadystatechange=function(){if(m.readyState===4){if(m.status>=200&&m.status<300||m.status===304){j.onsuccse&&j.onsuccse.call(h,m[i]);h.buildBar()}else{j.onerror&&j.onerror.call(h,m.statusText)}h._callbackLock_=true}else{if(m.readyState===3){j.onprocess&&j.onprocess.call(h)}}};j.type==="post"&&m.setRequestHeader("content-type","application/x-www-form-urlencoded");
m.send(l)}}}};c.prototype.constructor=c;c.addEvent=function(g,d,h){if(typeof addEventListener==="function"){return g.addEventListener(d,h,false)}else{return g.attachEvent("on"+d,h)}};c.detachEvent=function(g,d,h){if(typeof removeEventListener==="function"){return g.removeEventListener(d,h,false)}else{return g.detachEvent("on"+d,h)}};c.isContains=function(g,d){return g.contains?g.contains(d):g.compareDocumentPosition(d)==16};c.getStyle=function(d,g){return d.style[g]?d.style[g]:d.currentStyle?d.currentStyle[g]:a.getComputedStyle(d,null)[g]};c.isMobile="ontouchend" in f?true:false;a.requestAnimationFrame=a.requestAnimationFrame||a.mozRequestAnimationFrame||a.webkitRequestAnimationFrame||a.msRequestAnimationFrame||a.oRequestAnimationFrame||function(d){return setTimeout(d,1000/60)};a.cancelAnimationFrame=a.cancelAnimationFrame||a.mozCancelAnimationFrame||a.webkitCancelAnimationFrame||a.msCancelAnimationFrame||a.oCancelAnimationFrame||function(d){return clearTimeout(d,1000/60)};var b={init:function(d){var h=new c(d);if(typeof Object.defineProperty==="function"&&-[1,]){var g=["CriticalBar","CriticalEl","_callbackLock_","cache"];g.forEach(function(i){Object.defineProperty(h,i,{writable:true,enumerable:false,configurable:false})})}return h}};a.iScroll=b})(window,document);