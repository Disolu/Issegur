/*!CK:2589475256!*//*1457137435,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["cOHWo"]); }

__d('P2PPendingPaymentRequestJewelBanner.react',['fbt','P2PJewelBanner.react','P2PPaymentRequest','P2PPaymentRequestActionHelper','React'],function a(b,c,d,e,f,g,h){'use strict';if(c.__markCompiled)c.__markCompiled();var i=c('React').PropTypes,j=c('React').createClass({displayName:'P2PPendingPaymentRequestJewelBanner',propTypes:{paymentRequest:i.instanceOf(c('P2PPaymentRequest')).isRequired},handleDeclineClick:function(){c('P2PPaymentRequestActionHelper').initDeclinePaymentRequestFlow(this.props.paymentRequest);},handlePayClick:function(){c('P2PPaymentRequestActionHelper').initPayForPaymentRequestFlow(this.props.paymentRequest,'banner');},renderBodyText:function(){var k=this.props.paymentRequest,l=k.memoText;if(!l)return null;return h._("Para {What the user paid for}",[h.param('What the user paid for',l)]);},render:function(){var k=this.props.paymentRequest;return (c('React').createElement(c('P2PJewelBanner.react'),{bodyText:this.renderBodyText(),primaryActionConfig:{onClick:this.handlePayClick,text:h._("Pagar")},secondaryActionConfig:{onClick:this.handleDeclineClick,text:h._("Rechazar")},titleText:h._("{requester_name} ha solicitado {amount}",[h.param('requester_name',k.requester.name),h.param('amount',k.amountWithSymbol)])}));}});f.exports=j;},null);