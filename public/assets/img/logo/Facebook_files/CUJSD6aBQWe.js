/*!CK:3622251258!*//*1457561218,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["jud4C"]); }

__d("ShareNowCounterEvent",[],function a(b,c,d,e,f,g){c.__markCompiled&&c.__markCompiled();f.exports={SHARE_NOW_NOT_AVAILABLE:"share_now_not_available",OPEN_SHARE_NOW:"open_share_now",OPEN_DIALOG:"open_dialog",OPEN_MESSAGE_DIALOG:"open_mesage_dialog",SHARE_CREATED:"share_created",SHARE_NOW_CREATED:"share_now_created",SHARE_ERROR:"share_error",SHARE_INVOKE_ERROR:"share_invoke_error",SHARE_INVOKED:"share_invoked",SHARE_BOOTLOADED:"share_bootloaded",SHARE_POST:"share_post",SHARE_POST_ERROR:"share_post_error",SHARE_POST_SUCCESS:"share_post_success",NO_FOOTER_REF:"no_footer_ref",NO_PRIVACY_NODE:"no_privacy_node",NO_PRIVACY_REF:"no_privacy_ref",NO_PRIVACY_VALUE:"no_privacy_value",NO_PRIVACY_VALUE_ADAMA:"no_privacy_value_adama",SHARE_POST_OWN:"share_post_own",SHARE_POST_PERSON:"share_post_person",SHARE_POST_PAGE:"share_post_page",SHARE_POST_GROUP:"share_post_group",SHARE_POST_EVENT:"share_post_event",SHARE_POST_MESSAGE:"share_post_message",SHARE_ERROR_OWN:"share_error_own",SHARE_ERROR_PERSON:"share_error_person",SHARE_ERROR_PAGE:"share_error_page",SHARE_ERROR_GROUP:"share_error_group",SHARE_ERROR_EVENT:"share_error_event",SHARE_ERROR_MESSAGE:"share_error_message",SHARE_CREATED_OWN:"share_created_own",SHARE_CREATED_PERSON:"share_created_person",SHARE_CREATED_PAGE:"share_created_page",SHARE_CREATED_GROUP:"share_created_group",SHARE_CREATED_EVENT:"share_created_event",SHARE_CREATED_MESSAGE:"share_created_message",DROPDOWN_SELECT_SHARE_POST:"dropdown_select_share_post",DROPDOWN_SELECT_SHARE_LINK:"dropdown_select_share_link",SHARE_CANCEL:"share_cancel"};},null);
__d("XShareNowCounterController",["XController"],function a(b,c,d,e,f,g){c.__markCompiled&&c.__markCompiled();f.exports=c("XController").create("\/share\/share_now\/counter\/",{event:{type:"Enum",required:true,enumType:1},control:{type:"Bool",defaultValue:false}});},null);
__d('ShareNowCounter',['AsyncSignal','XShareNowCounterController'],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();var h={logEvent:function(event,i){var j=c('XShareNowCounterController').getURIBuilder().setEnum('event',event).setBool('control',i).getURI();new (c('AsyncSignal'))(j).send();}};f.exports=h;},null);
__d('Sharer',['CSS','Event','ShareModeConst','ShareNowCounter','ShareNowCounterEvent','URI','goURI'],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();function h(i,j){'use strict';this.$Sharer1=i;if(j)c('ShareNowCounter').logEvent(c('ShareNowCounterEvent').OPEN_DIALOG,true);}h.prototype.getSharerFrame=function(){'use strict';return this.$Sharer1;};h.prototype.getComponents=function(){'use strict';return this.$Sharer1.getComponents();};h.initPrivacyWarning=function(i,j){'use strict';i.subscribe('selector-change',function(k,l){c('CSS').conditionShow(j,l===c('ShareModeConst').GROUP||l===c('ShareModeConst').MESSAGE||l===c('ShareModeConst').FRIEND||l===c('ShareModeConst').SELF_POST);});};h.close=function(i){'use strict';if(typeof i=='string'&&i){c('goURI')(i);}else{var j=function(){window.close();c('goURI')(new (c('URI'))(window.location.href).setPath('/').setQueryData({}));};history.back();setTimeout(j,100);}return false;};h.listenForCancel=function(i){'use strict';c('Event').listen(i,'click',this.close);};h.reloadIfPostLogin=function(){'use strict';var i=new (c('URI'))(document.referrer);if(i.getPath()=='/login.php')window.opener.require('Plugin').reloadOtherPlugins();};f.exports=h;},null);
__d('SharerFrame',['csx','cx','ArbiterMixin','ComposerVersion','ComposerXMarauderLogger','ComposerXSessionIDs','DOM','Event','Parent','mixin'],function a(b,c,d,e,f,g,h,i){var j,k;if(c.__markCompiled)c.__markCompiled();j=babelHelpers.inherits(l,c('mixin')(c('ArbiterMixin')));k=j&&j.prototype;function l(m,n,o,p,q){'use strict';k.constructor.call(this);this.$SharerFrame1=o;this.$SharerFrame2=m;this.$SharerFrame3=this.$SharerFrame1.id;c('ComposerXSessionIDs').resetSessionID(this.$SharerFrame3);c('DOM').prependContent(this.$SharerFrame1,c('ComposerXSessionIDs').createSessionIDInput(c('ComposerXSessionIDs').getSessionID(this.$SharerFrame3)));if(p){c('ComposerXMarauderLogger').registerComposer(this.$SharerFrame1,'feed','share',c('ComposerVersion').WWW_LEGACY);c('ComposerXMarauderLogger').listenForPostEvents(this.$SharerFrame3,c('Parent').bySelector(this.$SharerFrame1,"._59s7"));c('ComposerXMarauderLogger').logEntry(this.$SharerFrame3);}this.$SharerFrame2.subscribe('change',function(t,u){this.inform('selector-change',u);if(p)c('ComposerXMarauderLogger').setShareMode(this.$SharerFrame3,u);}.bind(this));this.$SharerFrame4=n;if(q){var r=q.row,s=q.removeLink;c('Event').listen(s,'click',function(){return c('DOM').remove(r);});}}l.focusInput=function(m){'use strict';m.focus();};l.focusSelector=function(m){'use strict';var n=c('DOM').find(m,'.selectedMode input[type="text"]');if(n)n.focus();};l.prototype.getSelector=function(){'use strict';return this.$SharerFrame2;};l.prototype.getSelectedMode=function(){'use strict';return this.$SharerFrame2.getSelectedMode();};l.prototype.getComponents=function(){'use strict';return this.$SharerFrame4.getComponents();};f.exports=l;},null);
__d("XSharePrivacyAsyncController",["XController"],function a(b,c,d,e,f,g){c.__markCompiled&&c.__markCompiled();f.exports=c("XController").create("\/share\/mode\/group\/privacy\/",{current_group_id:{type:"Int",required:true},target_group_id:{type:"Int",required:true}});},null);
__d('SharerSelector',['ArbiterMixin','AsyncRequest','CSS','Event','Input','XSharePrivacyAsyncController','mixin'],function a(b,c,d,e,f,g){var h,i;if(c.__markCompiled)c.__markCompiled();h=babelHelpers.inherits(j,c('mixin')(c('ArbiterMixin')));i=h&&h.prototype;function j(k){'use strict';i.constructor.call(this);this.$SharerSelector1=k.attachments;this.$SharerSelector2=k.icons;this.$SharerSelector3=k.modeInput;this.$SharerSelector4=k.selector;this.$SharerSelector5=k.selectorGroup;this.$SharerSelector6=k.selectedMode;if(this.$SharerSelector4)this.$SharerSelector4.subscribe('change',this.$SharerSelector7.bind(this));if(this.$SharerSelector1&&this.$SharerSelector6&&this.$SharerSelector1[this.$SharerSelector6])this.$SharerSelector1[this.$SharerSelector6].showContent();}j.prototype.updateMode=function(k){'use strict';this.$SharerSelector4.setValue(k);};j.prototype.getSelectedMode=function(){'use strict';return this.$SharerSelector6;};j.prototype.hideModeSelector=function(){'use strict';c('CSS').hide(this.$SharerSelector5);};j.prototype.showModeSelector=function(){'use strict';c('CSS').show(this.$SharerSelector5);};j.prototype.$SharerSelector7=function(k,l){'use strict';var m=l.value;this.$SharerSelector3&&c('Input').setValue(this.$SharerSelector3,m);this.$SharerSelector1[this.$SharerSelector6].hideContent();this.$SharerSelector2&&c('CSS').hide(this.$SharerSelector2[this.$SharerSelector6]);this.$SharerSelector1[m].showContent();this.$SharerSelector2&&c('CSS').show(this.$SharerSelector2[m]);this.$SharerSelector6=m;if(this.$SharerSelector6==='group')this.$SharerSelector8(this.$SharerSelector1[this.$SharerSelector6]);this.inform('change',this.$SharerSelector6);};j.prototype.$SharerSelector8=function(k){'use strict';if(k.getTypeahead()){var l=k.getTypeahead();c('Event').listen(l,'keyup',function(){if(k.getTargetGroupID()){var m=k.getCurrentGroupID(),n=k.getTargetGroupID();if(k.isCurrentGroupOpenPrivacy()){var o=c('XSharePrivacyAsyncController').getURIBuilder().setInt('current_group_id',m).setInt('target_group_id',n).getURI();new (c('AsyncRequest'))(o).setMethod('GET').setReadOnly(true).send();}}});}};f.exports=j;},null);
__d('ShareMode',['csx','cx','CSS','Parent','emptyFunction'],function a(b,c,d,e,f,g,h,i){if(c.__markCompiled)c.__markCompiled();function j(k){this._content=k;}Object.assign(j.prototype,{showContent:function(){this._content&&c('CSS').show(this._content);this.show();},hideContent:function(){this._content&&c('CSS').hide(this._content);this.hide();},_getSharerRoot:function(){var k=c('Parent').bySelector(this._content,"._b-z");if(!k)k=c('Parent').bySelector(this._content,"._57xr");return k;},hideMentionsInput:function(){c('CSS').addClass(this._getSharerRoot(),"_c7f");},showMentionsInput:function(){c('CSS').removeClass(this._getSharerRoot(),"_c7f");},show:c('emptyFunction'),hide:c('emptyFunction')});f.exports=j;},null);
__d('ShareModeFriendTimeline',['Focus','ShareMode'],function a(b,c,d,e,f,g){var h,i;if(c.__markCompiled)c.__markCompiled();h=babelHelpers.inherits(j,c('ShareMode'));i=h&&h.prototype;function j(k,l){'use strict';i.constructor.call(this,k);this._typeaheadInput=l;}j.prototype.show=function(){'use strict';c('Focus').set(this._typeaheadInput);};f.exports=j;},null);
__d('ShareModeGroup',['Focus','ShareMode'],function a(b,c,d,e,f,g){var h,i;if(c.__markCompiled)c.__markCompiled();h=babelHelpers.inherits(j,c('ShareMode'));i=h&&h.prototype;function j(k,l,m,n,o){'use strict';i.constructor.call(this,k);this._typeaheadInput=l;this._hiddenInput=m;this._currentGroupID=n;this._isCurrentGroupOpenPrivacy=o;}j.prototype.show=function(){'use strict';c('Focus').set(this._typeaheadInput);};j.prototype.getTypeahead=function(){'use strict';return this._typeaheadInput;};j.prototype.getCurrentGroupID=function(){'use strict';return this._currentGroupID;};j.prototype.getTargetGroupID=function(){'use strict';if(this._hiddenInput&&this._hiddenInput.value)return this._hiddenInput.value;};j.prototype.isCurrentGroupOpenPrivacy=function(){'use strict';return this._isCurrentGroupOpenPrivacy;};f.exports=j;},null);
__d('ShareModeMessage',['Focus','ShareMode'],function a(b,c,d,e,f,g){var h,i;if(c.__markCompiled)c.__markCompiled();h=babelHelpers.inherits(j,c('ShareMode'));i=h&&h.prototype;function j(k,l){'use strict';i.constructor.call(this,k);this._typeaheadInput=l;}j.prototype.show=function(){'use strict';c('Focus').set(this._typeaheadInput);};f.exports=j;},null);
__d('ShareModeOwnTimeline',['CSS','ShareMode'],function a(b,c,d,e,f,g){var h,i;if(c.__markCompiled)c.__markCompiled();h=babelHelpers.inherits(j,c('ShareMode'));i=h&&h.prototype;function j(k){'use strict';i.constructor.call(this);this._privacyWidget=k;}j.prototype.show=function(){'use strict';c('CSS').show(this._privacyWidget);};j.prototype.hide=function(){'use strict';c('CSS').hide(this._privacyWidget);};f.exports=j;},null);