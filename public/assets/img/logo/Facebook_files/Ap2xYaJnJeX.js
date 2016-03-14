/*!CK:2907371440!*//*1457136722,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["Hznec"]); }

__d('P2PUserEligibilityStore',['P2PActionConstants','EventEmitter','MercuryIDs','P2PAPI','P2PDispatcher','P2PGKValues'],function a(b,c,d,e,f,g){'use strict';var h,i;if(c.__markCompiled)c.__markCompiled();var j,k;h=babelHelpers.inherits(l,c('EventEmitter'));i=h&&h.prototype;function l(){i.constructor.call(this);k={};j=c('P2PDispatcher').register(this.onEventDispatched.bind(this));}l.prototype.onEventDispatched=function(m){var n=m.data,o=m.type;switch(o){case c('P2PActionConstants').USER_ELIGIBILITY_UDPATED:this.handleEligibilityUpdated(n);this.emit('change');break;}};l.prototype.getEligibilityByUserIDs=function(m){var n=[],o,p,q={};for(var r=0;r<m.length;r++){p=m[r];o=k[p];if(o===undefined){k[p]=null;n.push(p);}q[p]=k[p];}if(c('P2PGKValues').P2PEnabled&&n.length)c('P2PAPI').getUserEligibility({userIDs:n});return q;};l.prototype.getEligibilityByUserID=function(m){var n=this.getEligibilityByUserIDs([m]);return n[m];};l.prototype.getEligibilityByThreadID=function(m){return (this.getEligibilityByUserID(c('MercuryIDs').getUserIDFromThreadID(m)));};l.prototype.handleEligibilityUpdated=function(m){for(var n=0;n<m.length;n++)k[m[n].user_id]=m[n].p2p_eligible;};f.exports=new l();},null);
__d('P2PSendMoneyButton.react',['fbt','CurrentEnvironment','immutable','P2PAbstractSendMoneyButton.react','P2PActions','P2PErrorDialog.react','P2PGroupSendDialog.react','P2PLogger','P2PPaymentLoggerEvent','P2PPaymentLoggerEventFlow','P2PPasswordProtectionNUXDialog.react','P2PPasswordProtectionStatusTypes','P2PSendMoneyDialog.react','P2PUserEligibilityContainer.react','P2PSendMoneyNUXContextualDialog.react','P2PSendMoneySuccessDialog.react','P2PTransferParam','P2PTransferStore','P2PTransferRiskResult','P2PUnderManualReviewDialog.react','P2PVerificationFlowHelper','React','ReactDOM','ReactLayeredComponentMixin','emptyFunction'],function a(b,c,d,e,f,g,h){'use strict';if(c.__markCompiled)c.__markCompiled();var i=c('React').PropTypes,j=c('React').createClass({displayName:'P2PSendMoneyButton',mixins:[c('ReactLayeredComponentMixin')],propTypes:{amount:i.string,button:i.object,groupThreadFBID:i.string,flyoutAlignment:i.string,openRequestTab:i.bool,isNewEmptyLocalThread:i.bool,isGroupChat:i.bool,onTrigger:i.func,participants:i.instanceOf(c('immutable').List),platformData:i.object,sendMoneyDialogShown:i.bool,threadID:i.string,threadFBID:i.string},getDefaultProps:function(){return {amount:'',groupThreadFBID:'',openRequestTab:false,onTrigger:c('emptyFunction'),sendMoneyDialogShown:false};},getInitialState:function(){return {sendMoneyNUXDismissed:false};},log:function(k,l){c('P2PLogger').log(k,babelHelpers['extends']({www_event_flow:c('P2PPaymentLoggerEventFlow').UI_FLOW_P2P_SEND},l));},canShowSendMoneyDialog:function(){return !!(this.props.participants&&this.props.participants.size);},originalCreateTransactionResponse:null,_onButtonClick:function(){var k=this.getRecipient();this.log(c('P2PPaymentLoggerEvent').UI_ACTN_SEND_MONEY_BUTTON_CLICKED,{object_id:k&&k.userID});this.canShowSendMoneyDialog()&&this.showSendMoneyDialog();},hideSendMoneyDialog:function(){c('P2PActions').chatSendViewClosed({threadID:this.props.threadID});},showSendMoneyDialog:function(){c('P2PActions').chatSendViewOpened({referrer:this.props.referrer,threadID:this.props.threadID});},_handleDialogToggle:function(k){if(!k){this.hideSendMoneyDialog();}else this.showSendMoneyDialog();},handlePaymentRequestSuccess:function(){this.hideSendMoneyDialog();this.props.onTrigger();},handleOnSendSuccess:function(k){this.hideSendMoneyDialog();var l=k[c('P2PTransferParam').RISK_RESULT];this.originalCreateTransactionResponse=k;if(l===c('P2PTransferRiskResult').REQUIRE_VERIFICATION){c('P2PVerificationFlowHelper').startVerificationFlow(k.id,true,this.handleVerificationCompleted);}else if(l===c('P2PTransferRiskResult').UNDER_MANUAL_REVIEW){this.showUnderManualReviewDialog();}else this.handlePostSendSteps(false);},handleVerificationCompleted:function(k){this.handlePostSendSteps(!k);},handlePostSendSteps:function(k){var l=this.originalCreateTransactionResponse,m=l.passwordProtection===c('P2PPasswordProtectionStatusTypes').NOT_SET;if(!k&&l.isFirstSend){this._passwordNUXPending=m;this.showSendSuccessDialog();}else if(m){this.showPasswordProtectionDialog();}else c('P2PActions').hideDialog();this.originalCreateTransactionResponse=null;this.props.onTrigger();},handleConfirmationOKClick:function(){if(this._passwordNUXPending){this._passwordNUXPending=false;this.showPasswordProtectionDialog();}else c('P2PActions').hideDialog();},handleSendError:function(k){this.showErrorDialog(k);},handlePaymentRequestError:function(k){this.showPaymentRequestErrorDialog(k);},getMoneyButtonNode:function(){return c('ReactDOM').findDOMNode(this.refs.p2pSendMoneyButton);},getRecipient:function(){if(!this.props.isGroupChat&&this.props.participants){return this.props.participants.first();}else return this.getGroupSendRecipient();},getGroupSendRecipient:function(){return (this.props.participants&&this.props.participants.find(function(k){return k.userID===this.props.groupSendRecipientUserID;}.bind(this)));},showErrorDialog:function(k){var l=h._("Problema al enviar el dinero");c('P2PActions').showDialog(c('P2PErrorDialog.react'),{error:k,onOKClick:c('P2PActions').hideDialog,title:l});},showPaymentRequestErrorDialog:function(k){var l=h._("Problema al solicitar dinero");c('P2PActions').showDialog(c('P2PErrorDialog.react'),{error:k,onOKClick:c('P2PActions').hideDialog,title:l});},showPasswordProtectionDialog:function(){c('P2PActions').showDialog(c('P2PPasswordProtectionNUXDialog.react'),{onComplete:c('P2PActions').hideDialog});},showSendSuccessDialog:function(){c('P2PActions').showDialog(c('P2PSendMoneySuccessDialog.react'),{onClose:this.handleConfirmationOKClick});},showUnderManualReviewDialog:function(){c('P2PActions').showDialog(c('P2PUnderManualReviewDialog.react'),{onClose:this.handlePostSendSteps.bind(this,false)});},shouldShowNUX:function(){return (!this.props.sendMoneyNUXDismissed&&!this.state.sendMoneyNUXDismissed&&c('P2PTransferStore').shouldShowSenderNUX());},renderLayers:function(){var k={},l='USD',m='$',n=this.getRecipient();if(this.canShowSendMoneyDialog()&&this.props.sendMoneyDialogShown)if(this.shouldShowNUX()){k.nux=c('React').createElement(c('P2PSendMoneyNUXContextualDialog.react'),{context:this.getMoneyButtonNode(),loggingData:{object_id:n&&n.userID},flyoutAlignment:this.props.flyoutAlignment,onNextClick:function(){return this.setState({sendMoneyNUXDismissed:true});}.bind(this),onToggle:this._handleDialogToggle});}else if(!this.props.groupSendRecipientUserID&&this.props.isGroupChat&&this.props.threadFBID){k.groupSendMoneyDialog=c('React').createElement(c('P2PUserEligibilityContainer.react'),{participants:this.props.participants},c('React').createElement(c('P2PGroupSendDialog.react'),{amount:this.props.amount,context:this.getMoneyButtonNode(),flyoutAlignment:this.props.flyoutAlignment,openRequestTab:this.props.openRequestTab,currency:l,onToggle:this._handleDialogToggle,participants:this.props.participants,referrer:this.props.referrer,threadID:this.props.threadID,threadFBID:this.props.threadFBID}));}else k.sendMoneyDialog=c('React').createElement(c('P2PUserEligibilityContainer.react'),{participants:this.props.participants,ref:'send_money_dialog'},c('React').createElement(c('P2PSendMoneyDialog.react'),{amount:this.props.amount,context:this.getMoneyButtonNode(),currency:l,currencySymbol:m,groupThreadFBID:this.props.groupThreadFBID,flyoutAlignment:this.props.flyoutAlignment,openRequestTab:this.props.openRequestTab,memoText:this.props.memoText,onToggle:this._handleDialogToggle,onError:this.handleSendError,onPaymentRequestError:this.handlePaymentRequestError,onPaymentRequestSuccess:this.handlePaymentRequestSuccess,onSendSuccess:this.handleOnSendSuccess,platformData:this.props.platformData,receiver:n,ref:'send_money_dialog',referrer:this.props.referrer,paymentRequestID:this.props.paymentRequestID,useModal:this.props.useModal}));return k;},render:function(){var k=this.getRecipient();if(this.props.isNewEmptyLocalThread&&this.props.participants.size>1)return null;return (c('React').createElement(c('P2PAbstractSendMoneyButton.react'),{button:this.props.button,className:this.props.className,isActive:this.canShowSendMoneyDialog()&&this.props.sendMoneyDialogShown,isMessenger:!!c('CurrentEnvironment').messengerdotcom,onClick:this._onButtonClick,recipientName:k&&k.short_name,ref:'p2pSendMoneyButton'}));}});f.exports=j;},null);
__d('P2PSendMoneyContainer.react',['CurrentUser','immutable','ImmutableObject','MercuryIDs','MercuryParticipants','MercuryShareAttachmentRenderLocations','MercuryThreads','P2PSendMoneyButton.react','P2PSendMoneyDialogStore','React','StoreAndPropBasedStateMixin'],function a(b,c,d,e,f,g){'use strict';if(c.__markCompiled)c.__markCompiled();var h=c('React').PropTypes,i=c('React').createClass({displayName:'P2PSendMoneyContainer',propTypes:{button:h.node,className:h.string,flyoutAlignment:h.string,participantIDs:h.array,onTrigger:h.func,threadID:h.string},getDefaultProps:function(){return {referrer:c('MercuryShareAttachmentRenderLocations').CHAT};},mixins:[c('StoreAndPropBasedStateMixin')(c('MercuryParticipants'),c('MercuryThreads'),c('P2PSendMoneyDialogStore'))],statics:{calculateState:function(j){var k=j.threadID,l=null,m=j.participantIDs,n=null,o=null,p,q,r=j.referrer;if(!k)return {};p=c('P2PSendMoneyDialogStore').getStateByThreadID(k);q=p&&p.referrer;if(r===c('MercuryShareAttachmentRenderLocations').WEB_INBOX&&q===c('MercuryShareAttachmentRenderLocations').CHAT||r===c('MercuryShareAttachmentRenderLocations').CHAT&&q===c('MercuryShareAttachmentRenderLocations').WEB_INBOX)p={};if(k){l=c('MercuryThreads').getForFBID(c('CurrentUser').getID()).getOrFetch(k);if(!m&&l&&l.participants)m=l.participants.filter(function(s){return (c('CurrentUser').getID()!==c('MercuryIDs').getUserIDFromParticipantID(s));});if(m){n=c('immutable').List();n=n.withMutations(function(s){m&&m.forEach(function(t){var u=c('MercuryParticipants').getOrFetch(t),v=c('MercuryIDs').getUserIDFromParticipantID(t);u=u&&c('ImmutableObject').set(u,{userID:v});if(u)s.push(u);});});}}o=c('MercuryThreads').get().isNewEmptyLocalThread(k);return babelHelpers['extends']({},p,{participants:n,isNewEmptyLocalThread:o,isGroupChat:c('MercuryIDs').isGroupChat(k)&&!o,threadID:k,threadFBID:l&&l.thread_fbid,referrer:q||r});}},render:function(){return (c('React').createElement(c('P2PSendMoneyButton.react'),babelHelpers['extends']({},this.state,{button:this.props.button,className:this.props.className,flyoutAlignment:this.props.flyoutAlignment,onTrigger:this.props.onTrigger})));}});f.exports=i;},null);