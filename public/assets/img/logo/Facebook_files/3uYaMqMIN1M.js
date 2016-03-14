/*!CK:3193045656!*//*1457756678,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["7VE+c"]); }

__d('StickerStore.react',['cx','fbt','ix','Arbiter','BanzaiLogger','Image.react','Link.react','ReactComponentWithPureRenderMixin','React','ReactDOM','Scroll','ScrollableArea.react','StickerActions','StickerState','StickerStateStore','StickerStoreHeader.react','StickerStorePackDetail.react','StickerStorePackList.react','StoreAndPropBasedStateMixin','XUIDialogTitle.react','XUISpinner.react','XUIText.react','getElementPosition','isSocialPlugin','throttle'],function a(b,c,d,e,f,g,h,i,j){'use strict';if(c.__markCompiled)c.__markCompiled();var k=c('React').PropTypes,l=688,m=320,n=c('React').createClass({displayName:'StickerStore',mixins:[c('ReactComponentWithPureRenderMixin'),c('StoreAndPropBasedStateMixin')(c('StickerStateStore'))],statics:{calculateState:function(o){var p=c('StickerStateStore').getState();return {storePackID:p.storePackID,storePackData:null};}},propTypes:{isComposer:k.bool,shown:k.bool},getDefaultProps:function(){return {isComposer:false,shown:false};},componentDidMount:function(){this._isMounted=true;this._fetchPackData(this.state.storePackID);this.props.shown&&this.logOpen();this._scrollTop=0;},componentWillReceiveProps:function(o){if(!this.props.shown&&o.shown)this.logOpen();if(this.props.shown&&!o.shown)this.logClose();},componentWillUpdate:function(o,p){if(!this.state.storePackID&&p.storePackID)this._scrollTop=this.refs.scrollableArea.getArea().getScrollTop();this._fetchPackData(p.storePackID);},componentDidUpdate:function(){this.pokeScrollbar();},componentWillUnmount:function(){this._isMounted=false;},_fetchPackData:function(o){if(!o)return;c('StickerState').onPackDataReady(o,function(){return (this._isMounted&&this.setState({storePackData:c('StickerState').getPack(o)}));}.bind(this));},render:function(){var o=c('isSocialPlugin')()&&document.body.offsetWidth<l?m:l;if(this.state.storePackID&&!this.state.storePackData)return (c('React').createElement('div',{className:"_5r5e"},c('React').createElement(c('XUISpinner.react'),{size:'large'})));return (c('React').createElement('div',null,this.renderHeader(),c('React').createElement('div',{className:this.getContentClass()},c('React').createElement(c('ScrollableArea.react'),{fade:false,persistent:true,ref:'scrollableArea',shadow:true,onScroll:c('throttle')(this.logImpression,500,this),width:o},this.renderContent()))));},renderHeader:function(){if(!this.state.storePackID||this.state.storePackData.isGhostPack)return c('React').createElement(c('StickerStoreHeader.react'),null);return (c('React').createElement('div',{className:"_5r5i"},c('React').createElement(c('XUIDialogTitle.react'),{className:"_5r5g"},c('React').createElement(c('Link.react'),{className:"_5r5c",onClick:function(){return c('StickerActions').selectStorePack(null);}},c('React').createElement(c('Image.react'),{className:"_5r5t",src:j('/images/messaging/stickers/store/backarrow.png')}),c('React').createElement(c('XUIText.react'),{className:"_5rq_",weight:'normal'},i._("Tienda de stickers"))))));},renderContent:function(){if(!this.props.shown)return null;if(!this.state.storePackID||this.state.storePackData.isGhostPack)return (c('React').createElement(c('StickerStorePackList.react'),{isComposer:this.props.isComposer,isPermalink:true,onDataReady:this.pokeScrollbar,onPackListUpdate:this.scrollToLastViewed}));return (c('React').createElement(c('StickerStorePackDetail.react'),{onDataReady:this.pokeScrollbar,storePackData:this.state.storePackData}));},getContentClass:function(){if(!this.state.storePackID)return "_5rzk";return (this.state.storePackData.isGhostPack?"_5rzk":'')+(!this.state.storePackData.isGhostPack?' '+"_5rzl":'');},logOpen:function(){c('BanzaiLogger').log('StickersLoggerConfig',{event:'open_store'});this.logImpression();},logClose:function(){c('BanzaiLogger').log('StickersLoggerConfig',{event:'close_store'});},pokeScrollbar:function(){if(this.refs.scrollableArea)this.refs.scrollableArea.getArea().poke();},scrollToLastViewed:function(){if(!this.refs.scrollableArea)return;if(!this.state.storePackID){this.refs.scrollableArea.getArea().setScrollTop(this._scrollTop,false);this._scrollTop=0;}},logImpression:function(){if(!this.refs.scrollableArea)return;var o=c('getElementPosition')(c('ReactDOM').findDOMNode(this.refs.scrollableArea));c('Arbiter').inform('stickerStoreScrolled',{scrollTop:c('Scroll').getTop(c('ReactDOM').findDOMNode(this)),top:o.y,viewHeight:o.height});}});f.exports=n;},null);