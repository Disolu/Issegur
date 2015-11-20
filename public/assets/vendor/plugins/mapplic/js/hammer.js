(function(window,undefined){'use strict';var Hammer=function(element,options){return new Hammer.Instance(element,options||{});};Hammer.defaults={stop_browser_behavior:{userSelect:'none',touchAction:'none',touchCallout:'none',contentZooming:'none',userDrag:'none',tapHighlightColor:'rgba(0,0,0,0)'}};Hammer.HAS_POINTEREVENTS=window.navigator.pointerEnabled||window.navigator.msPointerEnabled;Hammer.HAS_TOUCHEVENTS=('ontouchstart'in window);Hammer.MOBILE_REGEX=/mobile|tablet|ip(ad|hone|od)|android|silk/i;Hammer.NO_MOUSEEVENTS=Hammer.HAS_TOUCHEVENTS&&window.navigator.userAgent.match(Hammer.MOBILE_REGEX);Hammer.EVENT_TYPES={};Hammer.UPDATE_VELOCITY_INTERVAL=16;Hammer.DOCUMENT=window.document;var DIRECTION_DOWN=Hammer.DIRECTION_DOWN='down';var DIRECTION_LEFT=Hammer.DIRECTION_LEFT='left';var DIRECTION_UP=Hammer.DIRECTION_UP='up';var DIRECTION_RIGHT=Hammer.DIRECTION_RIGHT='right';var POINTER_MOUSE=Hammer.POINTER_MOUSE='mouse';var POINTER_TOUCH=Hammer.POINTER_TOUCH='touch';var POINTER_PEN=Hammer.POINTER_PEN='pen';var EVENT_START=Hammer.EVENT_START='start';var EVENT_MOVE=Hammer.EVENT_MOVE='move';var EVENT_END=Hammer.EVENT_END='end';Hammer.plugins=Hammer.plugins||{};Hammer.gestures=Hammer.gestures||{};Hammer.READY=false;function setup(){if(Hammer.READY){return;}
Event.determineEventTypes();Utils.each(Hammer.gestures,function(gesture){Detection.register(gesture);});Event.onTouch(Hammer.DOCUMENT,EVENT_MOVE,Detection.detect);Event.onTouch(Hammer.DOCUMENT,EVENT_END,Detection.detect);Hammer.READY=true;}
var Utils=Hammer.utils={extend:function extend(dest,src,merge){for(var key in src){if(dest[key]!==undefined&&merge){continue;}
dest[key]=src[key];}
return dest;},each:function(obj,iterator,context){var i,o;if('forEach'in obj){obj.forEach(iterator,context);}
else if(obj.length!==undefined){for(i=-1;(o=obj[++i]);){if(iterator.call(context,o,i,obj)===false){return;}}}
else{for(i in obj){if(obj.hasOwnProperty(i)&&iterator.call(context,obj[i],i,obj)===false){return;}}}},hasParent:function(node,parent){while(node){if(node==parent){return true;}
node=node.parentNode;}
return false;},getCenter:function getCenter(touches){var valuesX=[],valuesY=[];Utils.each(touches,function(touch){valuesX.push(typeof touch.clientX!=='undefined'?touch.clientX:touch.pageX);valuesY.push(typeof touch.clientY!=='undefined'?touch.clientY:touch.pageY);});return{pageX:(Math.min.apply(Math,valuesX)+ Math.max.apply(Math,valuesX))/ 2,
pageY:(Math.min.apply(Math,valuesY)+ Math.max.apply(Math,valuesY))/ 2
};},getVelocity:function getVelocity(delta_time,delta_x,delta_y){return{x:Math.abs(delta_x/delta_time)||0,y:Math.abs(delta_y/delta_time)||0};},getAngle:function getAngle(touch1,touch2){var y=touch2.pageY- touch1.pageY,x=touch2.pageX- touch1.pageX;return Math.atan2(y,x)*180/Math.PI;},getDirection:function getDirection(touch1,touch2){var x=Math.abs(touch1.pageX- touch2.pageX),y=Math.abs(touch1.pageY- touch2.pageY);if(x>=y){return touch1.pageX- touch2.pageX>0?DIRECTION_LEFT:DIRECTION_RIGHT;}
return touch1.pageY- touch2.pageY>0?DIRECTION_UP:DIRECTION_DOWN;},getDistance:function getDistance(touch1,touch2){var x=touch2.pageX- touch1.pageX,y=touch2.pageY- touch1.pageY;return Math.sqrt((x*x)+(y*y));},getScale:function getScale(start,end){if(start.length>=2&&end.length>=2){return this.getDistance(end[0],end[1])/ this.getDistance(start[0], start[1]);
}
return 1;},getRotation:function getRotation(start,end){if(start.length>=2&&end.length>=2){return this.getAngle(end[1],end[0])- this.getAngle(start[1],start[0]);}
return 0;},isVertical:function isVertical(direction){return direction==DIRECTION_UP||direction==DIRECTION_DOWN;},toggleDefaultBehavior:function toggleDefaultBehavior(element,css_props,toggle){if(!css_props||!element||!element.style){return;}
Utils.each(['webkit','moz','Moz','ms','o',''],function(vendor){Utils.each(css_props,function(value,prop){if(vendor){prop=vendor+ prop.substring(0,1).toUpperCase()+ prop.substring(1);}
if(prop in element.style){element.style[prop]=!toggle&&value;}});});var false_fn=function(){return false;};if(css_props.userSelect=='none'){element.onselectstart=!toggle&&false_fn;}
if(css_props.userDrag=='none'){element.ondragstart=!toggle&&false_fn;}}};Hammer.Instance=function(element,options){var self=this;setup();this.element=element;this.enabled=true;this.options=Utils.extend(Utils.extend({},Hammer.defaults),options||{});if(this.options.stop_browser_behavior){Utils.toggleDefaultBehavior(this.element,this.options.stop_browser_behavior,false);}
this.eventStartHandler=Event.onTouch(element,EVENT_START,function(ev){if(self.enabled){Detection.startDetect(self,ev);}});this.eventHandlers=[];return this;};Hammer.Instance.prototype={on:function onEvent(gesture,handler){var gestures=gesture.split(' ');Utils.each(gestures,function(gesture){this.element.addEventListener(gesture,handler,false);this.eventHandlers.push({gesture:gesture,handler:handler});},this);return this;},off:function offEvent(gesture,handler){var gestures=gesture.split(' '),i,eh;Utils.each(gestures,function(gesture){this.element.removeEventListener(gesture,handler,false);for(i=-1;(eh=this.eventHandlers[++i]);){if(eh.gesture===gesture&&eh.handler===handler){this.eventHandlers.splice(i,1);}}},this);return this;},trigger:function triggerEvent(gesture,eventData){if(!eventData){eventData={};}
var event=Hammer.DOCUMENT.createEvent('Event');event.initEvent(gesture,true,true);event.gesture=eventData;var element=this.element;if(Utils.hasParent(eventData.target,element)){element=eventData.target;}
element.dispatchEvent(event);return this;},enable:function enable(state){this.enabled=state;return this;},dispose:function dispose(){var i,eh;if(this.options.stop_browser_behavior){Utils.toggleDefaultBehavior(this.element,this.options.stop_browser_behavior,true);}
for(i=-1;(eh=this.eventHandlers[++i]);){this.element.removeEventListener(eh.gesture,eh.handler,false);}
this.eventHandlers=[];Event.unbindDom(this.element,Hammer.EVENT_TYPES[EVENT_START],this.eventStartHandler);return null;}};var last_move_event=null;var enable_detect=false;var touch_triggered=false;var Event=Hammer.event={bindDom:function(element,type,handler){var types=type.split(' ');Utils.each(types,function(type){element.addEventListener(type,handler,false);});},unbindDom:function(element,type,handler){var types=type.split(' ');Utils.each(types,function(type){element.removeEventListener(type,handler,false);});},onTouch:function onTouch(element,eventType,handler){var self=this;var bindDomOnTouch=function(ev){var srcEventType=ev.type.toLowerCase();if(srcEventType.match(/mouse/)&&touch_triggered){return;}
else if(srcEventType.match(/touch/)||srcEventType.match(/pointerdown/)||(srcEventType.match(/mouse/)&&ev.which===1)){enable_detect=true;}
else if(srcEventType.match(/mouse/)&&!ev.which){enable_detect=false;}
if(srcEventType.match(/touch|pointer/)){touch_triggered=true;}
var count_touches=0;if(enable_detect){if(Hammer.HAS_POINTEREVENTS&&eventType!=EVENT_END){count_touches=PointerEvent.updatePointer(eventType,ev);}
else if(srcEventType.match(/touch/)){count_touches=ev.touches.length;}
else if(!touch_triggered){count_touches=srcEventType.match(/up/)?0:1;}
if(count_touches>0&&eventType==EVENT_END){eventType=EVENT_MOVE;}
else if(!count_touches){eventType=EVENT_END;}
if(count_touches||last_move_event===null){last_move_event=ev;}
handler.call(Detection,self.collectEventData(element,eventType,self.getTouchList(last_move_event,eventType),ev));if(Hammer.HAS_POINTEREVENTS&&eventType==EVENT_END){count_touches=PointerEvent.updatePointer(eventType,ev);}}
if(!count_touches){last_move_event=null;enable_detect=false;touch_triggered=false;PointerEvent.reset();}};this.bindDom(element,Hammer.EVENT_TYPES[eventType],bindDomOnTouch);return bindDomOnTouch;},determineEventTypes:function determineEventTypes(){var types;if(Hammer.HAS_POINTEREVENTS){types=PointerEvent.getEvents();}
else if(Hammer.NO_MOUSEEVENTS){types=['touchstart','touchmove','touchend touchcancel'];}
else{types=['touchstart mousedown','touchmove mousemove','touchend touchcancel mouseup'];}
Hammer.EVENT_TYPES[EVENT_START]=types[0];Hammer.EVENT_TYPES[EVENT_MOVE]=types[1];Hammer.EVENT_TYPES[EVENT_END]=types[2];},getTouchList:function getTouchList(ev){if(Hammer.HAS_POINTEREVENTS){return PointerEvent.getTouchList();}
if(ev.touches){return ev.touches;}
ev.identifier=1;return[ev];},collectEventData:function collectEventData(element,eventType,touches,ev){var pointerType=POINTER_TOUCH;if(ev.type.match(/mouse/)||PointerEvent.matchType(POINTER_MOUSE,ev)){pointerType=POINTER_MOUSE;}
return{center:Utils.getCenter(touches),timeStamp:new Date().getTime(),target:ev.target,touches:touches,eventType:eventType,pointerType:pointerType,srcEvent:ev,preventDefault:function(){if(this.srcEvent.preventManipulation){this.srcEvent.preventManipulation();}
if(this.srcEvent.preventDefault){this.srcEvent.preventDefault();}},stopPropagation:function(){this.srcEvent.stopPropagation();},stopDetect:function(){return Detection.stopDetect();}};}};var PointerEvent=Hammer.PointerEvent={pointers:{},getTouchList:function(){var touchlist=[];Utils.each(this.pointers,function(pointer){touchlist.push(pointer);});return touchlist;},updatePointer:function(type,pointerEvent){if(type==EVENT_END){delete this.pointers[pointerEvent.pointerId];}
else{pointerEvent.identifier=pointerEvent.pointerId;this.pointers[pointerEvent.pointerId]=pointerEvent;}
return Object.keys(this.pointers).length;},matchType:function(pointerType,ev){if(!ev.pointerType){return false;}
var pt=ev.pointerType,types={};types[POINTER_MOUSE]=(pt===POINTER_MOUSE);types[POINTER_TOUCH]=(pt===POINTER_TOUCH);types[POINTER_PEN]=(pt===POINTER_PEN);return types[pointerType];},getEvents:function(){return['pointerdown MSPointerDown','pointermove MSPointerMove','pointerup pointercancel MSPointerUp MSPointerCancel'];},reset:function(){this.pointers={};}};var Detection=Hammer.detection={gestures:[],current:null,previous:null,stopped:false,startDetect:function startDetect(inst,eventData){if(this.current){return;}
this.stopped=false;this.current={inst:inst,startEvent:Utils.extend({},eventData),lastEvent:false,lastVelocityEvent:false,velocity:false,name:''};this.detect(eventData);},detect:function detect(eventData){if(!this.current||this.stopped){return;}
eventData=this.extendEventData(eventData);var inst_options=this.current.inst.options;Utils.each(this.gestures,function(gesture){if(!this.stopped&&inst_options[gesture.name]!==false){if(gesture.handler.call(gesture,eventData,this.current.inst)===false){this.stopDetect();return false;}}},this);if(this.current){this.current.lastEvent=eventData;}
if(eventData.eventType==EVENT_END&&!eventData.touches.length- 1){this.stopDetect();}
return eventData;},stopDetect:function stopDetect(){this.previous=Utils.extend({},this.current);this.current=null;this.stopped=true;},extendEventData:function extendEventData(ev){var cur=this.current,startEv=cur.startEvent;if(ev.touches.length!=startEv.touches.length||ev.touches===startEv.touches){startEv.touches=[];Utils.each(ev.touches,function(touch){startEv.touches.push(Utils.extend({},touch));});}
var delta_time=ev.timeStamp- startEv.timeStamp,delta_x=ev.center.pageX- startEv.center.pageX,delta_y=ev.center.pageY- startEv.center.pageY,interimAngle,interimDirection,velocityEv=cur.lastVelocityEvent,velocity=cur.velocity;if(velocityEv&&ev.timeStamp- velocityEv.timeStamp>Hammer.UPDATE_VELOCITY_INTERVAL){velocity=Utils.getVelocity(ev.timeStamp- velocityEv.timeStamp,ev.center.pageX- velocityEv.center.pageX,ev.center.pageY- velocityEv.center.pageY);cur.lastVelocityEvent=ev;cur.velocity=velocity;}
else if(!cur.velocity){velocity=Utils.getVelocity(delta_time,delta_x,delta_y);cur.lastVelocityEvent=ev;cur.velocity=velocity;}
if(ev.eventType==EVENT_END){interimAngle=cur.lastEvent&&cur.lastEvent.interimAngle;interimDirection=cur.lastEvent&&cur.lastEvent.interimDirection;}
else{interimAngle=cur.lastEvent&&Utils.getAngle(cur.lastEvent.center,ev.center);interimDirection=cur.lastEvent&&Utils.getDirection(cur.lastEvent.center,ev.center);}
Utils.extend(ev,{deltaTime:delta_time,deltaX:delta_x,deltaY:delta_y,velocityX:velocity.x,velocityY:velocity.y,distance:Utils.getDistance(startEv.center,ev.center),angle:Utils.getAngle(startEv.center,ev.center),interimAngle:interimAngle,direction:Utils.getDirection(startEv.center,ev.center),interimDirection:interimDirection,scale:Utils.getScale(startEv.touches,ev.touches),rotation:Utils.getRotation(startEv.touches,ev.touches),startEvent:startEv});return ev;},register:function register(gesture){var options=gesture.defaults||{};if(options[gesture.name]===undefined){options[gesture.name]=true;}
Utils.extend(Hammer.defaults,options,true);gesture.index=gesture.index||1000;this.gestures.push(gesture);this.gestures.sort(function(a,b){if(a.index<b.index){return-1;}
if(a.index>b.index){return 1;}
return 0;});return this.gestures;}};Hammer.gestures.Drag={name:'drag',index:50,defaults:{drag_min_distance:10,correct_for_drag_min_distance:true,drag_max_touches:1,drag_block_horizontal:false,drag_block_vertical:false,drag_lock_to_axis:false,drag_lock_min_distance:25},triggered:false,handler:function dragGesture(ev,inst){if(Detection.current.name!=this.name&&this.triggered){inst.trigger(this.name+'end',ev);this.triggered=false;return;}
if(inst.options.drag_max_touches>0&&ev.touches.length>inst.options.drag_max_touches){return;}
switch(ev.eventType){case EVENT_START:this.triggered=false;break;case EVENT_MOVE:if(ev.distance<inst.options.drag_min_distance&&Detection.current.name!=this.name){return;}
if(Detection.current.name!=this.name){Detection.current.name=this.name;if(inst.options.correct_for_drag_min_distance&&ev.distance>0){var factor=Math.abs(inst.options.drag_min_distance/ev.distance);Detection.current.startEvent.center.pageX+=ev.deltaX*factor;Detection.current.startEvent.center.pageY+=ev.deltaY*factor;ev=Detection.extendEventData(ev);}}
if(Detection.current.lastEvent.drag_locked_to_axis||(inst.options.drag_lock_to_axis&&inst.options.drag_lock_min_distance<=ev.distance)){ev.drag_locked_to_axis=true;}
var last_direction=Detection.current.lastEvent.direction;if(ev.drag_locked_to_axis&&last_direction!==ev.direction){if(Utils.isVertical(last_direction)){ev.direction=(ev.deltaY<0)?DIRECTION_UP:DIRECTION_DOWN;}
else{ev.direction=(ev.deltaX<0)?DIRECTION_LEFT:DIRECTION_RIGHT;}}
if(!this.triggered){inst.trigger(this.name+'start',ev);this.triggered=true;}
inst.trigger(this.name,ev);inst.trigger(this.name+ ev.direction,ev);var is_vertical=Utils.isVertical(ev.direction);if((inst.options.drag_block_vertical&&is_vertical)||(inst.options.drag_block_horizontal&&!is_vertical)){ev.preventDefault();}
break;case EVENT_END:if(this.triggered){inst.trigger(this.name+'end',ev);}
this.triggered=false;break;}}};Hammer.gestures.Hold={name:'hold',index:10,defaults:{hold_timeout:500,hold_threshold:1},timer:null,handler:function holdGesture(ev,inst){switch(ev.eventType){case EVENT_START:clearTimeout(this.timer);Detection.current.name=this.name;this.timer=setTimeout(function(){if(Detection.current.name=='hold'){inst.trigger('hold',ev);}},inst.options.hold_timeout);break;case EVENT_MOVE:if(ev.distance>inst.options.hold_threshold){clearTimeout(this.timer);}
break;case EVENT_END:clearTimeout(this.timer);break;}}};Hammer.gestures.Release={name:'release',index:Infinity,handler:function releaseGesture(ev,inst){if(ev.eventType==EVENT_END){inst.trigger(this.name,ev);}}};Hammer.gestures.Swipe={name:'swipe',index:40,defaults:{swipe_min_touches:1,swipe_max_touches:1,swipe_velocity:0.7},handler:function swipeGesture(ev,inst){if(ev.eventType==EVENT_END){if(ev.touches.length<inst.options.swipe_min_touches||ev.touches.length>inst.options.swipe_max_touches){return;}
if(ev.velocityX>inst.options.swipe_velocity||ev.velocityY>inst.options.swipe_velocity){inst.trigger(this.name,ev);inst.trigger(this.name+ ev.direction,ev);}}}};Hammer.gestures.Tap={name:'tap',index:100,defaults:{tap_max_touchtime:250,tap_max_distance:10,tap_always:true,doubletap_distance:20,doubletap_interval:300},has_moved:false,handler:function tapGesture(ev,inst){var prev,since_prev,did_doubletap;if(ev.eventType==EVENT_START){this.has_moved=false;}
else if(ev.eventType==EVENT_MOVE&&!this.moved){this.has_moved=(ev.distance>inst.options.tap_max_distance);}
else if(ev.eventType==EVENT_END&&ev.srcEvent.type!='touchcancel'&&ev.deltaTime<inst.options.tap_max_touchtime&&!this.has_moved){prev=Detection.previous;since_prev=prev&&prev.lastEvent&&ev.timeStamp- prev.lastEvent.timeStamp;did_doubletap=false;if(prev&&prev.name=='tap'&&(since_prev&&since_prev<inst.options.doubletap_interval)&&ev.distance<inst.options.doubletap_distance){inst.trigger('doubletap',ev);did_doubletap=true;}
if(!did_doubletap||inst.options.tap_always){Detection.current.name='tap';inst.trigger(Detection.current.name,ev);}}}};Hammer.gestures.Touch={name:'touch',index:-Infinity,defaults:{prevent_default:false,prevent_mouseevents:false},handler:function touchGesture(ev,inst){if(inst.options.prevent_mouseevents&&ev.pointerType==POINTER_MOUSE){ev.stopDetect();return;}
if(inst.options.prevent_default){ev.preventDefault();}
if(ev.eventType==EVENT_START){inst.trigger(this.name,ev);}}};Hammer.gestures.Transform={name:'transform',index:45,defaults:{transform_min_scale:0.01,transform_min_rotation:1,transform_always_block:false,transform_within_instance:false},triggered:false,handler:function transformGesture(ev,inst){if(Detection.current.name!=this.name&&this.triggered){inst.trigger(this.name+'end',ev);this.triggered=false;return;}
if(ev.touches.length<2){return;}
if(inst.options.transform_always_block){ev.preventDefault();}
if(inst.options.transform_within_instance){for(var i=-1;ev.touches[++i];){if(!Utils.hasParent(ev.touches[i].target,inst.element)){return;}}}
switch(ev.eventType){case EVENT_START:this.triggered=false;break;case EVENT_MOVE:var scale_threshold=Math.abs(1- ev.scale);var rotation_threshold=Math.abs(ev.rotation);if(scale_threshold<inst.options.transform_min_scale&&rotation_threshold<inst.options.transform_min_rotation){return;}
Detection.current.name=this.name;if(!this.triggered){inst.trigger(this.name+'start',ev);this.triggered=true;}
inst.trigger(this.name,ev);if(rotation_threshold>inst.options.transform_min_rotation){inst.trigger('rotate',ev);}
if(scale_threshold>inst.options.transform_min_scale){inst.trigger('pinch',ev);inst.trigger('pinch'+(ev.scale<1?'in':'out'),ev);}
break;case EVENT_END:if(this.triggered){inst.trigger(this.name+'end',ev);}
this.triggered=false;break;}}};if(typeof define=='function'&&define.amd){define(function(){return Hammer;});}
else if(typeof module=='object'&&module.exports){module.exports=Hammer;}
else{window.Hammer=Hammer;}})(window);if(ev.touches){return ev.touches;}
ev.identifier=1;return[ev];},collectEventData:function collectEventData(element,eventType,touches,ev){var pointerType=POINTER_TOUCH;if(ev.type.match(/mouse/)||PointerEvent.matchType(POINTER_MOUSE,ev)){pointerType=POINTER_MOUSE;}
return{center:Utils.getCenter(touches),timeStamp:new Date().getTime(),target:ev.target,touches:touches,eventType:eventType,pointerType:pointerType,srcEvent:ev,preventDefault:function(){if(this.srcEvent.preventManipulation){this.srcEvent.preventManipulation();}
if(this.srcEvent.preventDefault){this.srcEvent.preventDefault();}},stopPropagation:function(){this.srcEvent.stopPropagation();},stopDetect:function(){return Detection.stopDetect();}};}};var PointerEvent=Hammer.PointerEvent={pointers:{},getTouchList:function(){var touchlist=[];Utils.each(this.pointers,function(pointer){touchlist.push(pointer);});return touchlist;},updatePointer:function(type,pointerEvent){if(type==EVENT_END){delete this.pointers[pointerEvent.pointerId];}
else{pointerEvent.identifier=pointerEvent.pointerId;this.pointers[pointerEvent.pointerId]=pointerEvent;}
return Object.keys(this.pointers).length;},matchType:function(pointerType,ev){if(!ev.pointerType){return false;}
var pt=ev.pointerType,types={};types[POINTER_MOUSE]=(pt===POINTER_MOUSE);types[POINTER_TOUCH]=(pt===POINTER_TOUCH);types[POINTER_PEN]=(pt===POINTER_PEN);return types[pointerType];},getEvents:function(){return['pointerdown MSPointerDown','pointermove MSPointerMove','pointerup pointercancel MSPointerUp MSPointerCancel'];},reset:function(){this.pointers={};}};var Detection=Hammer.detection={gestures:[],current:null,previous:null,stopped:false,startDetect:function startDetect(inst,eventData){if(this.current){return;}
this.stopped=false;this.current={inst:inst,startEvent:Utils.extend({},eventData),lastEvent:false,lastVelocityEvent:false,velocity:false,name:''};this.detect(eventData);},detect:function detect(eventData){if(!this.current||this.stopped){return;}
eventData=this.extendEventData(eventData);var inst_options=this.current.inst.options;Utils.each(this.gestures,function(gesture){if(!this.stopped&&inst_options[gesture.name]!==false){if(gesture.handler.call(gesture,eventData,this.current.inst)===false){this.stopDetect();return false;}}},this);if(this.current){this.current.lastEvent=eventData;}
if(eventData.eventType==EVENT_END&&!eventData.touches.length- 1){this.stopDetect();}
return eventData;},stopDetect:function stopDetect(){this.previous=Utils.extend({},this.current);this.current=null;this.stopped=true;},extendEventData:function extendEventData(ev){var cur=this.current,startEv=cur.startEvent;if(ev.touches.length!=startEv.touches.length||ev.touches===startEv.touches){startEv.touches=[];Utils.each(ev.touches,function(touch){startEv.touches.push(Utils.extend({},touch));});}
var delta_time=ev.timeStamp- startEv.timeStamp,delta_x=ev.center.pageX- startEv.center.pageX,delta_y=ev.center.pageY- startEv.center.pageY,interimAngle,interimDirection,velocityEv=cur.lastVelocityEvent,velocity=cur.velocity;if(velocityEv&&ev.timeStamp- velocityEv.timeStamp>Hammer.UPDATE_VELOCITY_INTERVAL){velocity=Utils.getVelocity(ev.timeStamp- velocityEv.timeStamp,ev.center.pageX- velocityEv.center.pageX,ev.center.pageY- velocityEv.center.pageY);cur.lastVelocityEvent=ev;cur.velocity=velocity;}
else if(!cur.velocity){velocity=Utils.getVelocity(delta_time,delta_x,delta_y);cur.lastVelocityEvent=ev;cur.velocity=velocity;}
if(ev.eventType==EVENT_END){interimAngle=cur.lastEvent&&cur.lastEvent.interimAngle;interimDirection=cur.lastEvent&&cur.lastEvent.interimDirection;}
else{interimAngle=cur.lastEvent&&Utils.getAngle(cur.lastEvent.center,ev.center);interimDirection=cur.lastEvent&&Utils.getDirection(cur.lastEvent.center,ev.center);}
Utils.extend(ev,{deltaTime:delta_time,deltaX:delta_x,deltaY:delta_y,velocityX:velocity.x,velocityY:velocity.y,distance:Utils.getDistance(startEv.center,ev.center),angle:Utils.getAngle(startEv.center,ev.center),interimAngle:interimAngle,direction:Utils.getDirection(startEv.center,ev.center),interimDirection:interimDirection,scale:Utils.getScale(startEv.touches,ev.touches),rotation:Utils.getRotation(startEv.touches,ev.touches),startEvent:startEv});return ev;},register:function register(gesture){var options=gesture.defaults||{};if(options[gesture.name]===undefined){options[gesture.name]=true;}
Utils.extend(Hammer.defaults,options,true);gesture.index=gesture.index||1000;this.gestures.push(gesture);this.gestures.sort(function(a,b){if(a.index<b.index){return-1;}
if(a.index>b.index){return 1;}
return 0;});return this.gestures;}};Hammer.gestures.Drag={name:'drag',index:50,defaults:{drag_min_distance:10,correct_for_drag_min_distance:true,drag_max_touches:1,drag_block_horizontal:false,drag_block_vertical:false,drag_lock_to_axis:false,drag_lock_min_distance:25},triggered:false,handler:function dragGesture(ev,inst){if(Detection.current.name!=this.name&&this.triggered){inst.trigger(this.name+'end',ev);this.triggered=false;return;}
if(inst.options.drag_max_touches>0&&ev.touches.length>inst.options.drag_max_touches){return;}
switch(ev.eventType){case EVENT_START:this.triggered=false;break;case EVENT_MOVE:if(ev.distance<inst.options.drag_min_distance&&Detection.current.name!=this.name){return;}
if(Detection.current.name!=this.name){Detection.current.name=this.name;if(inst.options.correct_for_drag_min_distance&&ev.distance>0){var factor=Math.abs(inst.options.drag_min_distance/ev.distance);Detection.current.startEvent.center.pageX+=ev.deltaX*factor;Detection.current.startEvent.center.pageY+=ev.deltaY*factor;ev=Detection.extendEventData(ev);}}
if(Detection.current.lastEvent.drag_locked_to_axis||(inst.options.drag_lock_to_axis&&inst.options.drag_lock_min_distance<=ev.distance)){ev.drag_locked_to_axis=true;}
var last_direction=Detection.current.lastEvent.direction;if(ev.drag_locked_to_axis&&last_direction!==ev.direction){if(Utils.isVertical(last_direction)){ev.direction=(ev.deltaY<0)?DIRECTION_UP:DIRECTION_DOWN;}
else{ev.direction=(ev.deltaX<0)?DIRECTION_LEFT:DIRECTION_RIGHT;}}
if(!this.triggered){inst.trigger(this.name+'start',ev);this.triggered=true;}
inst.trigger(this.name,ev);inst.trigger(this.name+ ev.direction,ev);var is_vertical=Utils.isVertical(ev.direction);if((inst.options.drag_block_vertical&&is_vertical)||(inst.options.drag_block_horizontal&&!is_vertical)){ev.preventDefault();}
break;case EVENT_END:if(this.triggered){inst.trigger(this.name+'end',ev);}
this.triggered=false;break;}}};Hammer.gestures.Hold={name:'hold',index:10,defaults:{hold_timeout:500,hold_threshold:1},timer:null,handler:function holdGesture(ev,inst){switch(ev.eventType){case EVENT_START:clearTimeout(this.timer);Detection.current.name=this.name;this.timer=setTimeout(function(){if(Detection.current.name=='hold'){inst.trigger('hold',ev);}},inst.options.hold_timeout);break;case EVENT_MOVE:if(ev.distance>inst.options.hold_threshold){clearTimeout(this.timer);}
break;case EVENT_END:clearTimeout(this.timer);break;}}};Hammer.gestures.Release={name:'release',index:Infinity,handler:function releaseGesture(ev,inst){if(ev.eventType==EVENT_END){inst.trigger(this.name,ev);}}};Hammer.gestures.Swipe={name:'swipe',index:40,defaults:{swipe_min_touches:1,swipe_max_touches:1,swipe_velocity:0.7},handler:function swipeGesture(ev,inst){if(ev.eventType==EVENT_END){if(ev.touches.length<inst.options.swipe_min_touches||ev.touches.length>inst.options.swipe_max_touches){return;}
if(ev.velocityX>inst.options.swipe_velocity||ev.velocityY>inst.options.swipe_velocity){inst.trigger(this.name,ev);inst.trigger(this.name+ ev.direction,ev);}}}};Hammer.gestures.Tap={name:'tap',index:100,defaults:{tap_max_touchtime:250,tap_max_distance:10,tap_always:true,doubletap_distance:20,doubletap_interval:300},has_moved:false,handler:function tapGesture(ev,inst){var prev,since_prev,did_doubletap;if(ev.eventType==EVENT_START){this.has_moved=false;}
else if(ev.eventType==EVENT_MOVE&&!this.moved){this.has_moved=(ev.distance>inst.options.tap_max_distance);}
else if(ev.eventType==EVENT_END&&ev.srcEvent.type!='touchcancel'&&ev.deltaTime<inst.options.tap_max_touchtime&&!this.has_moved){prev=Detection.previous;since_prev=prev&&prev.lastEvent&&ev.timeStamp- prev.lastEvent.timeStamp;did_doubletap=false;if(prev&&prev.name=='tap'&&(since_prev&&since_prev<inst.options.doubletap_interval)&&ev.distance<inst.options.doubletap_distance){inst.trigger('doubletap',ev);did_doubletap=true;}
if(!did_doubletap||inst.options.tap_always){Detection.current.name='tap';inst.trigger(Detection.current.name,ev);}}}};Hammer.gestures.Touch={name:'touch',index:-Infinity,defaults:{prevent_default:false,prevent_mouseevents:false},handler:function touchGesture(ev,inst){if(inst.options.prevent_mouseevents&&ev.pointerType==POINTER_MOUSE){ev.stopDetect();return;}
if(inst.options.prevent_default){ev.preventDefault();}
if(ev.eventType==EVENT_START){inst.trigger(this.name,ev);}}};Hammer.gestures.Transform={name:'transform',index:45,defaults:{transform_min_scale:0.01,transform_min_rotation:1,transform_always_block:false,transform_within_instance:false},triggered:false,handler:function transformGesture(ev,inst){if(Detection.current.name!=this.name&&this.triggered){inst.trigger(this.name+'end',ev);this.triggered=false;return;}
if(ev.touches.length<2){return;}
if(inst.options.transform_always_block){ev.preventDefault();}
if(inst.options.transform_within_instance){for(var i=-1;ev.touches[++i];){if(!Utils.hasParent(ev.touches[i].target,inst.element)){return;}}}
switch(ev.eventType){case EVENT_START:this.triggered=false;break;case EVENT_MOVE:var scale_threshold=Math.abs(1- ev.scale);var rotation_threshold=Math.abs(ev.rotation);if(scale_threshold<inst.options.transform_min_scale&&rotation_threshold<inst.options.transform_min_rotation){return;}
Detection.current.name=this.name;if(!this.triggered){inst.trigger(this.name+'start',ev);this.triggered=true;}
inst.trigger(this.name,ev);if(rotation_threshold>inst.options.transform_min_rotation){inst.trigger('rotate',ev);}
if(scale_threshold>inst.options.transform_min_scale){inst.trigger('pinch',ev);inst.trigger('pinch'+(ev.scale<1?'in':'out'),ev);}
break;case EVENT_END:if(this.triggered){inst.trigger(this.name+'end',ev);}
this.triggered=false;break;}}};if(typeof define=='function'&&define.amd){define(function(){return Hammer;});}
else if(typeof module=='object'&&module.exports){module.exports=Hammer;}
else{window.Hammer=Hammer;}})(window);