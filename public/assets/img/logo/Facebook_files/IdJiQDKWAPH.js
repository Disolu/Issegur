/*!CK:529707793!*//*1457133741,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["prnHq"]); }

__d('MessagingForwardingButtonNUX.react',['AsyncRequest','ChatConfig','React','XForwardingNUXSeenController','XUIAmbientNUX.react'],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();var h=c('React').PropTypes,i=c('React').createClass({displayName:'MessagingForwardingButtonNUX',propTypes:{contextRef:h.func.isRequired},getInitialState:function(){return {showNUX:!c('ChatConfig').get('seen_forwarding_nux')};},_onClose:function(){if(!this.state.showNUX)return;c('ChatConfig').set('seen_forwarding_nux',true);new (c('AsyncRequest'))(c('XForwardingNUXSeenController').getURIBuilder().getURI()).send();this.setState({showNUX:false});},render:function(){return (c('React').createElement(c('XUIAmbientNUX.react'),{contextRef:this.props.contextRef,onCloseButtonClick:this._onClose,position:'left',width:'auto',shown:this.state.showNUX},this.props.children));},close:function(){this._onClose();}});f.exports=i;},null);