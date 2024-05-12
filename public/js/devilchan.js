function ajax_processing(){
    
    if(document.querySelector(".ajax-processing")){
        return false;
    }

    var ajax_processing = dom.createStr(`<div class="ajax-processing" ><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgb(255, 255, 255) none repeat scroll 0% 0%; display: block; shape-rendering: auto;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"> <g transform="rotate(0 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.9166666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(30 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.8333333333333334s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(60 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.75s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(90 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.6666666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(120 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5833333333333334s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(150 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(180 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.4166666666666667s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(210 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.3333333333333333s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(240 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.25s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(270 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.16666666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(300 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.08333333333333333s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(330 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#000000"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animate> </rect> </g> </svg></div>`)[0];
    document.body.appendChild(ajax_processing);

    return ajax_processing;
}

function Devilchan(e){

	this.elements = this.slice(e);
}

function slice(e){

	var array = [];

	for (var i = 0 ; i < e.length ; i++){

	    array[i] = e[i];
	}

	return array;
}

function on(elSelector, eventName, selector, fn) {

    var element = document.querySelector(elSelector);

    element.addEventListener(eventName, function(event) {
        var possibleTargets = element.querySelectorAll(selector);
        var target = event.target;

        for (var i = 0, l = possibleTargets.length; i < l; i++) {
            var el = target;
            var p = possibleTargets[i];

            while(el && el !== element) {
                if (el === p) {
                    return fn.call(p, event);
                }

                el = el.parentNode;
            }
        }
    });
}

function forEach(e,c){

	for (var i = 0 ; i < e.length ; i++){

	    c.call(e[i],e[i],i);
	}
}

function extend(obj, src){

	for (var key in src){

        if (src.hasOwnProperty(key)){

        	obj[key] = src[key];
    	}
    }

    return obj;
}

function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

var dom = {
	create : function(s){
		return document.createElement(s);
	},
	createStr : function(s){
		var t = this.create("div");
		t.innerHTML = s;

		return slice(t.childNodes);
	}
}

function removeElement(el){
    el.parentElement.removeChild(el);
}

function replaceElement(newChild,oldChild){
    oldChild.parentElement.replaceChild(newChild, oldChild);
}

function ajax(o){

    var n = new FormData;

    if(o.data){

        for (var s in o.data){
        	n.append(s, o.data[s]);
        }
    }

    var a = new XMLHttpRequest;

    a.open(o.type, o.url);

    a.onreadystatechange = function(){

    	if (4 == a.readyState){

    		var rs = a.responseText;

            if (200 == a.status){

	            if(o.success){

                    if(o.dataType){
                        
                        try {

                            rs = o.dataType == "json" ? JSON.parse(rs) : rs;

                        } catch (e){

                            rs = false;
                        }
                    }
                        
	                o.success(rs);
	            }

            }else{

            	if(o.error){

                    if(o.dataType){
                        
                        try {

                            rs = o.dataType == "json" ? JSON.parse(rs) : rs;

                        } catch (e){

                            rs = false;
                        }
                    }
                        
                    o.error(rs);
                }
            }
        }
    }
    
    a.send(n);
}

function Animate(o){

    var start = new Date();

    o = extend({
    	duration : 1000,
    	step : function(){},
    	callback : function(){}
    },o);

    function decrease(){

        var timePassed = new Date - start,
            progress = timePassed / o.duration;

        if (progress >= 1) {
            progress = 1;
        }

        var t = progress;

        o.step(1 - (--t)*t*t*t);

        if(progress >= 1){
        	o.callback();
        	return;
        }

        requestAnimationFrame(decrease);
    }

    decrease();
}

function fadeIN(e,d,c=function(){}){

	Animate({
		step : function(p){
			e.style.opacity = p
		},
		duration : d || 1000,
		callback : c
	});
}

function fadeOUT (e, t, n = function() {}) {
    Animate({
        step: function(t) {
            e.style.opacity = 1 - t
        },
        duration: t || 1000,
        callback: n
    })
}

var EvEmitter = (function(){

		function EvEmitter() {}

	    var proto = EvEmitter.prototype;

	    proto.on = function(eventName, listener) {

	    	if (!eventName || !listener) {

	            return;
	        }

	        var events = this._events = this._events || {};

	        var listeners = events[eventName] = events[eventName] || [];

	        if (listeners.indexOf(listener) == -1) {

	            listeners.push(listener);
	        }
	        return this;
	    }

	    proto.once = function(eventName, listener) {

	        if (!eventName || !listener) {
	            return;
	        }
	        this.on(eventName, listener);

	        var onceEvents = this._onceEvents = this._onceEvents || {};

	        var onceListeners = onceEvents[eventName] = onceEvents[eventName] || {};

	        onceListeners[listener] = true;

	        return this;
	    }

	    proto.off = function(eventName, listener) {

	        var listeners = this._events && this._events[eventName];

	        if (!listeners || !listeners.length) {

	            return;
	        }
	        var index = listeners.indexOf(listener);

	        if (index != -1) {

	            listeners.splice(index, 1);
	        }

	        return this;
	    }

	    proto.emitEvent = function(eventName, args){

	        var listeners = this._events && this._events[eventName];
	        if (!listeners || !listeners.length) {
	            return;
	        }
	       
	        listeners = listeners.slice(0);
	        args = args || [];

	        var onceListeners = this._onceEvents && this._onceEvents[eventName];

	        for (var i = 0; i < listeners.length; i++) {

	            var listener = listeners[i]
	            var isOnce = onceListeners && onceListeners[listener];

	            if (isOnce) {

	                this.off(eventName, listener);
	                delete onceListeners[listener];
	            }

	            listener.apply(this, args);
	        }

	        return this;
	    }

	    proto.allOff = function() {

	        delete this._events;
	        delete this._onceEvents;
	    }

	    return EvEmitter;

	}()),
	IMGsLoaded = (function(EventIMG){

		var IMGsLoaded = function(e,o){

			var _ = this;

			this.IMGs = [];

			this.o = extend({

				progress : function(){},
				always : function(){}

			},o || {});

			this.getIMGs(e,function(){

				_.check();
			});
		}	

		IMGsLoaded.prototype.getIMGs = function(e,t) {

			this.addIMGs(e);
			t();
		}

		IMGsLoaded.prototype.addIMGs = function(e){

			if (e.nodeName == 'IMG') {

	            this.IMGs.push(new loadingIMGs(e));
	        }

	        var c = e.getElementsByTagName("IMG");

	        for( var i = 0 ; i < c.length ; i++ ){

	        	this.IMGs.push(new loadingIMGs(c[i]));
	        }
		}

		IMGsLoaded.prototype.check = function(){

			this.count = 0;

			for( var i = 0 ; i < this.IMGs.length ; i++ ){

				this.IMGs[i].once("progress",(function(obj,img){

					this.handle(obj,img);
					
				}).bind(this));

	        	this.IMGs[i].check();
	        }
		}

		IMGsLoaded.prototype.handle = function(obj,img){

			this.count++;

			this.o.progress(img,obj.isLoaded);

			if(this.count == this.IMGs.length){
	            
				this.complete();
			}	
		}

		IMGsLoaded.prototype.complete = function(){

			this.o.always(this);
		}

		var loadingIMGs = function(img){

			this.IMG = img;
		}

		loadingIMGs.prototype = Object.create(EventIMG.prototype);

		loadingIMGs.prototype.check = function(){

			var img = new Image(),
				_ = this;

			img.onload = function(){

				_.confirm(true);
			}

			img.onerror = function(){

				_.confirm(false);
			}

			img.src = this.IMG.src;
		}

		loadingIMGs.prototype.confirm = function(isloaded){

			this.isLoaded = isloaded;
			this.emitEvent("progress",[this,this.IMG]);
		}

	   return IMGsLoaded;
	        
	}(EvEmitter));
	
var tags_input = function() {
        var e = function(e,options) {

            var _this = this;
            this.options = options || {};
            this.real_tags_input = e;
            this.parent = this.real_tags_input.parentNode;

            this.create_dom();
            this.create_from_string(e.value);
        };
        return e.prototype.create_dom = function(){

            var _this = this

            _this.tags_holder = dom.create("div");
            _this.tags_holder.classList.add("tags-holder");

            _this.type_input = dom.create("input");
            _this.type_input.classList.add("i-tags");

            _this.type_input.addEventListener("keydown", function(e) {
                var n = this.value;
                13 == e.keyCode && (_this.create(this.value), this.value = ""); 
                if( e.key == "Backspace" && (n == "" || n.charCodeAt(0) == 8203) ){
                    _this.remove();
                }
            });

            this.parent.appendChild(this.type_input);
            this.parent.appendChild(this.tags_holder);

        }, e.prototype.remove = function(){

            if(0 != this.tags_holder.getElementsByTagName("span").length){
                    
                var lastChild = this.tags_holder.lastChild;

                if(this.options.confirmRemove){

                    if (!confirm(this.options.confirmRemove(lastChild))){
                        return;
                    }
                }
                this.tags_holder.removeChild(lastChild);
                this.real_tags_input.value = this.get_tags();
                this.options.onRemove && this.options.onRemove(lastChild,this);
            }
            
        },e.prototype.create = function(e,tr = true){

            if (e.replace(/\s/g, "").length){

                var cur_val = this.real_tags_input.value.toLowerCase(),
                    tag = cur_val.split(",");

                if (tag.includes(e.toLowerCase().replace(/\s\s+/g, ' '))){
                    return;
                }

                var t = this,
                    n = dom.create("span");

                e = e.replace(/[\u00A0-\u9999<>\&]/gim, function(e) {
                    return "&#" + e.charCodeAt(0) + ";"
                })

                n.innerHTML = e;

                n.style.opacity = 0;

                t.tags_holder.append(n);

                fadeIN(n, 400);

                t.real_tags_input.value = t.get_tags();

                n.addEventListener("click", function(){

                    if(t.options.confirmRemove){

                        if (confirm(t.options.confirmRemove(this))){

                            t.tags_holder.removeChild(this);
                            t.options.onRemove && t.options.onRemove(this,t);
                            t.real_tags_input.value = t.get_tags();
                        }

                    }else{

                        t.tags_holder.removeChild(this);
                        t.options.onRemove && t.options.onRemove(this,t);
                        t.real_tags_input.value = t.get_tags();
                    }
                });

                tr && t.options.onCreate && t.options.onCreate(n,this);
            }
        }, e.prototype.create_from_string = function(e){

            var t = this,
                c = e.split(",");

            this.real_tags_input.value = "";

            c.forEach(function(e) {
                t.create(e,false);
            });

        }, e.prototype.get_tags = function() {

            var e = this.tags_holder.getElementsByTagName("SPAN");

            return e ? (str_tags = "", forEach(e, function(e) {

                str_tags += e.innerHTML + ","
            }), str_tags.slice(0, -1)) : ""
        }, e
    }(),
    closeAllSelect = function(e){

        var t = document.getElementsByClassName("select-items"),
            n = document.getElementsByClassName("select-selected");

        forEach(t, function(e, t) {

            if (e.offsetWidth > 0) {

                if (1 == n[t].isShowing) return;

                n[t].isShowing = !0;

                fadeOUT(e, 200, function() {

                    e.style.display = "none";
                    n[t].isShowing = !1;
                });

                n[t].classList.remove("select-arrow-active");
            }
        })
    },
    custom_select = function(e) {
        var t, n, i, s, r, o;
        for (e = extend({
                parent: document,
                callback: function() {}
            }, e || {}), t = e.parent.querySelector(".custom-select"), n = t.getElementsByTagName("select")[0], this.i = n, e.value && forEach(n.options, function(t, i) {
                t.value == e.value && (n.selectedIndex = i)
            }), (s = document.createElement("DIV")).setAttribute("class", "select-selected"), s.innerHTML = n.options[n.selectedIndex].innerHTML, t.appendChild(s), (r = document.createElement("DIV")).setAttribute("class", "select-items select-hide"), i = 0; i < n.length; i++)(o = document.createElement("DIV")).innerHTML = n[i].innerHTML, o.addEventListener("click", function(e) {
            var t, n, i, s, r;
            for (s = this.parentNode.parentNode.getElementsByTagName("select")[0], r = this.parentNode.previousSibling, n = 0; n < s.length; n++)
                if (s.options[n].innerHTML == this.innerHTML) {
                    for (s.selectedIndex = n, r.innerHTML = this.innerHTML, t = this.parentNode.getElementsByClassName("same-as-selected"), i = 0; i < t.length; i++) t[i].removeAttribute("class");
                    this.setAttribute("class", "same-as-selected");
                    break
                }
            r.click()
        }), r.appendChild(o);
        t.appendChild(r), s.addEventListener("click", function(e) {
            e.stopPropagation();
            var n = t.querySelector(".select-items"),
                i = document.querySelectorAll(".select-items");
            if (forEach(i, function(e, t) {
                    e.offsetWidth > 0 && e != n && fadeOUT(e, 200, function() {
                        e.style.display = "none", e.parentNode.querySelector(".select-selected").classList.remove("select-arrow-active")
                    })
                }), !this.isShowing) {
                var s = this;
                n.offsetWidth > 0 ? (this.isShowing = !0, fadeOUT(n, 200, function() {
                    n.style.display = "none", s.isShowing = !1
                })) : (n.style.display = "block", fadeIN(n, 200)), this.classList.toggle("select-arrow-active")
            }
        })
    };

function triggerEvent(el, type){

    if ('createEvent' in document) {
           
            var e = document.createEvent('HTMLEvents');
            e.initEvent(type, false, true);
            el.dispatchEvent(e);

        } else {
           
            var e = document.createEventObject();
            e.eventType = type;
            el.fireEvent('on'+e.eventType, e);
        }
    }

function autocomplete(options) {

    var selector = options.selector,
        contEl   = options.container,
        event    = options.event || {},
        ul_class = options.class || "",
        event_type = "",
        ul = dom.create("ul"),
        parent = options.parent || "",
        selector_offset = getOffset(selector),
        handlers = {
            enter: function(e){

                e.preventDefault();

                var el = ul.querySelector(".select") || "",
                    val = el.innerText || "";

                ul.innerHTML = "";
                options.onSelect && options.onSelect(el,val);
                
            },
            up: function(e){

                e.preventDefault();
                event_type = "up";
                triggerEvent(ul,"keydown");
            },
            down: function(e){

                e.preventDefault();
                event_type = "down";
                triggerEvent(ul,"keydown");

            }
        };

    if (!parent){
        body.append(ul);
        set_position(ul);
    }else{
        parent.append(ul);
    }

    function multiHandler(e) {

        var k = e.keyCode,
        	meth = k === 13 ? 'enter' : k === 38 ? 'up' : k === 40 ? 'down' : e.type === 'input' ? 'input' : null;

        return meth ? handlers[meth](e) : null;
    }

    function close(e) {
        ul.style.display = "none";
    }

    function open(e) {
        ul.style.display = "block";
    }

    function getOffset( el ) {
        var _x = 0;
        var _y = 0;
        while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
            _x += el.offsetLeft - el.scrollLeft;
            _y += el.offsetTop - el.scrollTop;
            el = el.offsetParent;
        }
        return { top: _y, left: _x };
    }

    function set_position(el){
        el.style.top = selector_offset.top + "px";
        el.style.left = selector_offset.left + "px";
    }

    var click = function(el){

        el.addEventListener("mousedown",function(){

            ul.innerHTML == "";
            options.onSelect(el);
        })
    }

    var response = function(arr){

        forEach(arr,function(e,i){

            var li = dom.create("li");

            click(li);

            li.innerHTML = e;
            ul.append(li);
        });



        ul_class && ul.classList.add(ul_class);
    }

    var typingTimer,
        doneTypingInterval = 500;

    selector.addEventListener('input',function(){

        ul.style.display = "block";
        ul.innerHTML = "";

        clearTimeout(typingTimer);

        var input = this;

        if (input.value){

            typingTimer = setTimeout(function(){
                
                options.source(input.value,response);

            }, doneTypingInterval);
        }
        
    });

    selector.addEventListener("keydown",multiHandler);

    ul.addEventListener("keydown",function(e){

        var child = this.children;

        var select = ul.querySelector(".select") || "";

        if (event_type === "down") {

            if(!select){

                this.firstChild.classList.add("select");
                event.down ? event.down(this.firstChild) : ""
                return;
            }

            if (!select.nextElementSibling){
                select.classList.remove("select");
                return;
            }

            select.classList.remove("select");
            select.nextElementSibling.classList.add("select");
            
            event.down ? event.down(select.nextElementSibling,e.target) : ""

        }else if(event_type === "up"){

            if(!select){

                this.lastChild.classList.add("select");
                event.down ? event.down(e.target.children[0]) : ""
                return;
            }

            if(!select.previousElementSibling){
                select.classList.remove("select");
                return;
            }

            select.classList.remove("select");
            select.previousElementSibling.classList.add("select");

            event.up ? event.up(select.previousElementSibling,e.target) : "";
        }
    });

    window.addEventListener("scroll",close);
    selector.addEventListener("blur",function(e){
        ul.style.display = "none";
    });

    selector.addEventListener("click",function(e){
        ul.style.display = "block";
    });
};

Devilchan.prototype = {

	slice : function(e){

		return slice(e);
	},
	each : function(t){

		for (var i = 0 ; i < this.elements.length ; i++){

		    t.call(this,this.elements[i],i);
		}
	},
	addEventListener : function(c,t){

		this.each(function(e){

			e.addEventListener(c,t);

			e._e = e._e || {};
		    e._e[c] = e._e[c] || [];
		    e._e[c].push(t);
		});

        return this;
	},
	removeEventListener : function(c,v){

		this.each(function(e){

			for(var i = 0 ; i < e._e[c].length;i++){

				e.removeEventListener(c,e._e[c][i]);
			}
		});

        return this;
	},
	IMGsLoaded : function(o){

		this.each(function(e){

			new IMGsLoaded(e,o);
		});
	},
	fadeIN : function(d){

		this.each(function(e){
			fadeIN(e,d);
		})
	}
}
var imgViewer = function (o) {
    var t = this;
    t.E = {
        close: function (){
            t.viewerFrame.parentNode.removeChild(t.viewerFrame);
            body.setAttribute("style","");
            o.onClose && o.onClose();
            t.destroy();
        },
        resize: function () {
            t.calculateImgSize(), t.zoom(1);
        },
        keydown: function (e) {
            27 == e.keyCode && t.E.close();
        },
        easeOutQuart: function (t, e, a, n) {
            return (t /= n), a * (1 - --t * t * t * t) + e;
        }
    };
};
(imgViewer.prototype.init = function () {
    var t = document.getElementById("body"),
        e = dom.createStr(
            "<div id='viewer-frame'><div id='viewer'><img id='hq-img' src='' ></div><div id='is-loading-img'><img src='/public/icons/loading/loading_2.svg' alt='loading'></div><div id='hide-viewer'><img src='/public/icons/cancel.png' alt='cancel'></div></div>"
        )[0];
    t.append(e),
        (this.body = t),
        (this.isLoading = document.getElementById("is-loading-img")),
        (this.viewerFrame = document.getElementById("viewer-frame")),
        (this.viewer = document.getElementById("viewer")),
        (this.IMG = document.getElementById("hq-img")),
        (this.close = document.getElementById("hide-viewer"));
}),
    (imgViewer.prototype.view = function (t, e) {
        var a = this;

        a.close.addEventListener("click",a.E.close);
        window.addEventListener("keydown",a.E.keydown);

        (a.complete = !1),
            (a.viewerFrame.style.display = "block"),
            (a.body.style.overflow = "hidden"),
            (a.IMG.src = t),
           
            new IMGsLoaded(a.IMG, {
                always: function () {
                    a.addEvent(), a.calculateImgSize(), a.zoom(1), (a.isLoading.style.display = "none"), (a.complete = !0);
                },
            });
    }),
    (imgViewer.prototype.calculateImgSize = function (t) {
        var e = this,
            a = e.viewerFrame.offsetWidth,
            n = e.viewerFrame.offsetHeight,
            i = e.IMG.naturalWidth,
            o = e.IMG.naturalHeight;
        if (i < a && o < n) {
            var d = i;
            r = o;
        } else
            var l = i / o,
                r = (d = (l > 1 && n >= a) || l * n > a ? a : l * n) / l;
        var m = (a - d) / 2,
            s = (n - r) / 2;
        i < d && o < r && ((d = i), (r = o));
        var c = e.IMG.style;
        (c.width = d + "px"),
            (c.height = r + "px"),
            (c.left = m + "px"),
            (c.top = s + "px"),
            (e.dataImg = { w: d, h: r, l: m, t: s }),
            (e.dataCont = { w: a, h: n }),
            (e.dataMaxScale = i / parseFloat(e.IMG.style.width)),
            (e.IMG.style.display = "block");
    }),
    (imgViewer.prototype.zoom = function (t, e) {
        var a = this;
        cancelAnimationFrame(a.momentum), (t = Math.max(t, 1)), (t = Math.min(t, a.dataMaxScale)), (e = e || { x: a.dataCont.w / 2, y: a.dataCont.h / 2 });
        var n = parseFloat(a.IMG.style.left),
            i = parseFloat(a.IMG.style.top),
            o = a.dataImg.l,
            d = a.dataImg.t,
            l = a.dataImg.w + a.dataImg.l,
            r = a.dataImg.h + a.dataImg.t,
            m = a.curScale,
            s = new Date(),
            c = function () {
                var t = new Date() - s;
                if (!(t > 200)) {
                    var h = a.E.easeOutQuart(t, m, a.exc - m, 200),
                        y = (a.dataImg.w * h) / 1,
                        v = (a.dataImg.h * h) / 1,
                        x = -(((e.x - n) * h) / m - e.x),
                        M = -(((e.y - i) * h) / m - e.y);
                    (x = Math.min(x, o)),
                        (M = Math.min(M, d)),
                        x + y < l && (x = l - y),
                        M + v < r && (M = r - v),
                        (y = Math.round(y)),
                        (v = Math.round(v)),
                        (x = Math.round(x)),
                        (M = Math.round(M)),
                        (a.IMG.style.width = y + "px"),
                        (a.IMG.style.height = v + "px"),
                        (a.IMG.style.left = x + "px"),
                        (a.IMG.style.top = M + "px"),
                        (a.curScale = h),
                        requestAnimationFrame(c);
                }
            };
        c();
    }),
    (imgViewer.prototype.addEvent = function () {
        var t = this;

        window.addEventListener("resize", t.E.resize), (t.scalePW = 0.25), (t.curScale = 1), (t.exc = 1);
        t.viewerFrame.addEventListener("wheel", function (e) {
            e.preventDefault();
            var a = e.deltaY < 0 ? Math.max(1, e.deltaY) : Math.min(-1, e.deltaY),
                n = t.curScale + a * t.scalePW;
            (t.exc += a * t.scalePW), (t.exc = Math.max(t.exc, 1)), (t.exc = Math.min(t.exc, t.dataMaxScale)), t.zoom(n, { x: e.clientX, y: e.clientY });
        }),
            t.IMG.addEventListener("mousedown", function (e) {
                if (0 == e.button) {
                    e.preventDefault();
                    var a = parseFloat(t.IMG.style.left),
                        n = a,
                        i = parseFloat(t.IMG.style.top),
                        o = i,
                        d = { x: e.clientX, y: e.clientY };
                    cancelAnimationFrame(t.momentum),
                        clearInterval(t.s),
                        (t.p = [d, d]),
                        (t.s = setInterval(function () {
                            t.cp && (t.p.shift(), t.p.push({ x: t.cp.x, y: t.cp.y }));
                        }, 50));
                    var l = e.clientX,
                        r = e.clientY,
                        m = t.dataImg.h * t.curScale,
                        s = t.dataImg.w * t.curScale;
                    t.viewerFrame.addEventListener("wheel", c),
                        new Devilchan([window]).addEventListener("mousemove", function (e) {
                            if (0 == e.button) {
                                e.preventDefault();
                                var c = { x: e.clientX, y: e.clientY };
                                t.cp = c;
                                var h = (t.dataCont.w - s) / 2;
                                t.oo = a - h;
                                var y = 0;
                                if (s <= t.dataCont.w)
                                    t.oo >= 0 ? (c.x < l ? (l = c.x) : ((d.x += c.x - l), (l = c.x)), (y = l - d.x), (y = Math.max(-t.oo, y))) : (c.x > l ? (l = c.x) : ((d.x += c.x - l), (l = c.x)), (y = l - d.x), (y = Math.min(-t.oo, y)));
                                else if (n <= 0 && n >= t.dataCont.w - s) {
                                    var v = 0;
                                    -(v += (l = c.x) - d.x) <= a && (d.x = l + a), -v >= s - (t.dataCont.w - a) && (d.x = l + (s - (t.dataCont.w - a))), (y = l - d.x);
                                } else
                                    a > 0 && a > t.dataCont.w - s
                                        ? (c.x < l ? (l = c.x) : ((d.x += c.x - l), (l += c.x - l)), (y = l - d.x), (y = Math.max(t.dataCont.w - s - a, y)))
                                        : (c.x > l ? (l = c.x) : ((d.x += c.x - l), (l += c.x - l)), (y = l - d.x), (y = Math.min(-a, y))),
                                        (n = a + l - d.x);
                                t.jj = y;
                                var x = (t.dataCont.h - m) / 2;
                                t.nn = o - x;
                                var M = "";
                                if (m <= t.dataCont.h)
                                    t.nn >= 0
                                        ? (c.y < r ? (r = c.y) : ((d.y += c.y - r), (r += c.y - r)), (M = r - d.y), (M = Math.max(-t.nn, M)))
                                        : (c.y > r ? (r = c.y) : ((d.y += c.y - r), (r += c.y - r)), (M = r - d.y), (M = Math.min(-t.nn, M)));
                                else {
                                    if (o <= 0 && o >= t.dataCont.h - m) {
                                        v = 0;
                                        -(v += (r = c.y) - d.y) <= i && (d.y = r + i), -v >= m - (t.dataCont.h - i) && (d.y = r + (m - (t.dataCont.h - i))), (M = r - d.y);
                                    } else
                                        o > 0 && o > t.dataCont.h - m
                                            ? (c.y < r ? (r = c.y) : ((d.y += c.y - r), (r += c.y - r)), (M = r - d.y), (M = Math.max(t.dataCont.h - m - i, M)))
                                            : (c.y > r ? (r = c.y) : ((d.y += c.y - r), (r += c.y - r)), (M = r - d.y), (M = Math.min(-i, M)));
                                    o = i + r - d.y;
                                }
                                t.ii = M;
                                var w = a + y,
                                    u = i + M;
                                (t.ll = w), (w = Math.round(w)), (u = Math.round(u)), (t.IMG.style.top = u + "px"), (t.IMG.style.left = w + "px");
                                v = 0;
                                s + w > t.dataCont.w && (v = (100 * (s + w - t.dataCont.w)) / s), w < 0 && (100 * -w) / s;
                                m + u > t.dataCont.h && (100 * (m + u - t.dataCont.h)) / m, u < 0 && (100 * -u) / m;
                            }
                        }),
                        new Devilchan([window]).addEventListener("mouseup", function () {
                            new Devilchan([window]).removeEventListener("mousemove"), new Devilchan([window]).removeEventListener("mouseup"), t.viewerFrame.removeEventListener("wheel", c);
                            var e = t.p[1].x - t.p[0].x,
                                d = t.p[1].y - t.p[0].y;
                            if (Math.abs(e) > 3 || Math.abs(d) > 3) {
                                t.cp = null;
                                var l = new Date(),
                                    r = t.jj,
                                    h = t.ii;
                                !(function c() {
                                    var y = new Date() - l;
                                    y < 1e3 && ((t.momentum = requestAnimationFrame(c)), clearInterval(t.s)), (r += t.E.easeOutQuart(y, e / 3, -e / 3, 1e3)), (h += t.E.easeOutQuart(y, d / 3, -d / 3, 1e3));
                                    var v = a + r,
                                        x = i + h;
                                    s <= t.dataCont.w
                                        ? t.oo >= 0
                                            ? ((v = Math.min(v, parseFloat(t.IMG.style.left))), (v = Math.max(v, (t.dataCont.w - s) / 2)))
                                            : ((v = Math.max(v, parseFloat(t.IMG.style.left))), (v = Math.min(v, (t.dataCont.w - s) / 2)))
                                        : n <= 0 && n >= t.dataCont.w - s
                                        ? ((v = Math.min(0, v)), (v = Math.max(t.dataCont.w - s, v)))
                                        : (v = a > 0 && a > t.dataCont.w - s ? Math.min(v, parseFloat(t.IMG.style.left)) : Math.max(v, parseFloat(t.IMG.style.left))),
                                        m <= t.dataCont.h
                                            ? t.nn >= 0
                                                ? ((x = Math.min(x, parseFloat(t.IMG.style.top))), (x = Math.max(x, (t.dataCont.h - m) / 2)))
                                                : ((x = Math.max(x, parseFloat(t.IMG.style.top))), (x = Math.min(x, (t.dataCont.h - m) / 2)))
                                            : o <= 0 && o >= t.dataCont.h - m
                                            ? ((x = Math.min(0, x)), (x = Math.max(t.dataCont.h - m, x)))
                                            : (x = o > 0 && o > t.dataCont.h - m ? Math.min(x, parseFloat(t.IMG.style.top)) : Math.max(x, parseFloat(t.IMG.style.top))),
                                        (v = Math.round(v)),
                                        (x = Math.round(x)),
                                        (t.IMG.style.left = v + "px"),
                                        (t.IMG.style.top = x + "px");
                                })();
                            }
                        });
                }
                function c(e) {
                    var c = e.deltaY < 0 ? Math.max(1, e.deltaY) : Math.min(-1, e.deltaY),
                        h = (t.curScale * (1 + c * t.scalePW)) / 1;
                    (h = Math.max(1, h)), (h = Math.min(t.dataMaxScale, h));
                    var y = { x: e.clientX, y: e.clientY },
                        v = parseFloat(t.IMG.style.left),
                        x = parseFloat(t.IMG.style.top),
                        M = t.dataImg.l,
                        w = t.dataImg.t,
                        u = t.dataImg.w + t.dataImg.l,
                        p = t.dataImg.h + t.dataImg.t,
                        I = (t.dataImg.w * t.exc) / 1,
                        g = (t.dataImg.h * t.exc) / 1,
                        f = -(((y.x - v) * t.exc) / t.curScale - y.x),
                        C = -(((y.y - x) * t.exc) / t.curScale - y.y);
                    (f = Math.min(f, M)) + I < u && (f = u - I),
                        (C = Math.min(C, w)) + g < p && (C = p - g),
                        (a = Math.round(f)),
                        (n = a),
                        (i = Math.round(C)),
                        (o = i),
                        (d.x = y.x),
                        (d.y = y.y),
                        (l = y.x),
                        (r = y.y),
                        (s = Math.round(I)),
                        (m = Math.round(g));
                }
            });
    });
imgViewer.prototype.destroy = function(){

    var _this = this;
    window.removeEventListener("resize",_this.E.resize);
    window.removeEventListener("keydown",_this.E.keydown);
}

const eventify = (self) => {
    self.events = {}

    self.on = function (event, listener) {
        if (typeof self.events[event] !== 'object') {
            self.events[event] = []
        }

        self.events[event].push(listener)
    }

    self.removeListener = function (event, listener) {
        let idx

        if (typeof self.events[event] === 'object') {
            idx = self.events[event].indexOf(listener)

            if (idx > -1) {
                self.events[event].splice(idx, 1)
            }
        }
    }

    self.emit = function (event) {
        var i, listeners, length, args = [].slice.call(arguments, 1);

        if (typeof self.events[event] === 'object') {
            listeners = self.events[event].slice()
            length = listeners.length

            for (i = 0; i < length; i++) {
                listeners[i].apply(self, args)
            }
        }
    }

    self.once = function (event, listener) {
        self.on(event, function g () {
            self.removeListener(event, g)
            listener.apply(self, arguments)
        })
    }
}

function parse_query_string(query) {
    var vars = query.split("&");
    var query_string = {};
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        var key = decodeURIComponent(pair[0]);
        var value = decodeURIComponent(pair[1]);

        if (typeof query_string[key] === "undefined") {
            query_string[key] = decodeURIComponent(value);

        }else if (typeof query_string[key] === "string") {
          var arr = [query_string[key], decodeURIComponent(value)];
          query_string[key] = arr;

        } else {
          query_string[key].push(decodeURIComponent(value));
        }
    }
    return query_string;
}

function Waifu_Kikyo (route,spa = true){

    var index = function(){

        this.spa = true;

        this.route(window.location.pathname,true);

        if (route.google_update){

            this.google_update = route.google_update;

        }else{

            this.google_update = true;
        } 
    }

    index.prototype.route = function(path,action = false){

        var _this = this,
            pathname = path;

        for (var i = 0 ; i < route.length; i++){

            var re = new RegExp("^" + route[i].path + "(|\\/)(|\\?(.*?))$","i");
            
            if (re.test(pathname)){

                var params = re.exec(pathname).slice(1);
                _this.ctrl = window[route[i].module](params);
                _this.pathname = pathname;
                action && _this.ctrl.script();
                return true;
            }
        }

        return false;
    }
    
    index.prototype.init = function(){

        var _this = this;

        if(this.spa == true){
            return;
        }

        on("body","click",".__link",function(e){
            e.preventDefault();

            var pathname = this.getAttribute("href");
            _this.push(pathname);

            var op = document.getElementsByClassName("open-sidebar");

            if(op.length){
                op[0].classList.remove("open-sidebar");
            }
        });

        window.addEventListener("popstate",function(e){

            var html = e.state.html,
                title = e.state.title,
                path = window.location.pathname;

            if(window.location.hash){
                path = window.location.pathname +"#viewer";
            }

            _this.route(path);
            _this.removeEvent();

            _app_.innerHTML = html;
            document.title = title + " - Devilchan";
            _this.ctrl.script();

            if(_this.google_update){
                google_update();
            }
        });
    }

    index.prototype.removeEvent = function(){

        var w = window;

        if (w._imgViewer){
            w._imgViewer.destroy();
        }
    }

    index.prototype.fetch = function(m){

        var _this = this,
            _module = _this.ctrl,
            pathname = _this.pathname,
            o = {
                main : _this.main,
                set_state : function(o){

                    var hil = _hi_.length;

                    if (hil > 1 ){

                        var cur_url = _hi_[hil - 1];
                        
                        if(pathname == cur_url){

                            _this.create(o,pathname);

                            window._s = false;
                            _hi_ = [];
                        }

                        return;
                    }

                    _this.create(o,pathname);

                    _hi_ = [];
                    window._s = false;
                }
            }

        _module.fetch && _module.fetch(o);
    }

    index.prototype.push = function(pathname,o){

        var _this = this;

        if(_hi_.indexOf(pathname) > -1){
            return;
        }

        _hi_.push(pathname);

        if(window._s){

            history.replaceState(o,"",pathname);

        }else{

            history.pushState(o,"",pathname);
        }

        window._s = true;

        var t = _this.route(pathname);


        if (t == false){

            window.location.href = pathname;
        }


        _this.fetch(pathname);
    }

    index.prototype.create = function(o,url){

        var _this = this;

        _app_.innerHTML = "";

        setTimeout(function(){
            _app_.innerHTML = o.html;
            document.title = o.title + " - Devilchan";
            _this.ctrl.script();

            if(_this.google_update){
                google_update();
            }

        },25);              
        
        history.replaceState(o,"",url);
    }

    return new index;
}