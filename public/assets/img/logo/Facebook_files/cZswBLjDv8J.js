/*!CK:1811057348!*//*1456881045,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["YRR29"]); }

__d('ReactComposerNotesAttachmentUpsell.react',['cx','fbt','Promise','ReactComposerContextMixin','ReactComposerPropsAndStoreBasedStateMixin','ReactComposerStatusStore','AsyncRequest','FBOverlayBase.react','FBOverlayContainer.react','FBOverlayElement.react','XUIButton.react','XUICloseButton.react','XUISpinner.react','encodeBlocks','Bootloader','React'],function a(b,c,d,e,f,g,h,i){if(c.__markCompiled)c.__markCompiled();var j=c('React').PropTypes,k=undefined,l=c('React').createClass({displayName:'ReactComposerNotesAttachmentUpsell',mixins:[c('ReactComposerContextMixin'),c('ReactComposerPropsAndStoreBasedStateMixin')(c('ReactComposerStatusStore'))],propTypes:{minLength:j.number.isRequired},statics:{calculateState:function(m,n){var o=c('ReactComposerStatusStore').getTextLength(m);return {showUpsell:o>n.minLength};}},getInitialState:function(){return {dismissed:false,loading:false};},componentDidUpdate:function(){if(this.state.showUpsell&&!k)k=new (c('Promise'))(function(m){c('Bootloader').loadModules(["FunnelLogger"],function(n){n.startFunnel('WWW_CAMPFIRE_COMPOSER_UPSELL_FUNNEL');n.setFunnelTimeout('WWW_CAMPFIRE_COMPOSER_UPSELL_FUNNEL',10800);m(n);});});},openComposer:function(){this.setState({loading:true});c('Bootloader').loadModules(["XNotesComposerController","getCommonNoteDataForSave"],function(m,n){var o=c('ReactComposerStatusStore').getEditorState(this.context.composerID);if(!o)return;var p=c('encodeBlocks')(o.getCurrentContent()),q=n({encodedContent:p}),r=m.getURIBuilder().getURI();new (c('AsyncRequest'))(r).setData(babelHelpers['extends']({},q,{upsell_composer_id:this.context.composerID,via_composer_upsell:true})).setMethod('POST').setHandler(this._dismiss).send();k.then(function(s){s.appendAction('WWW_CAMPFIRE_COMPOSER_UPSELL_FUNNEL','switched_to_note');s.endFunnel('WWW_CAMPFIRE_COMPOSER_UPSELL_FUNNEL');}).done();}.bind(this));},_dismiss:function(){this.setState({dismissed:true});k.then(function(m){m.appendAction('WWW_CAMPFIRE_COMPOSER_UPSELL_FUNNEL','dismissed_upsell');m.endFunnel('WWW_CAMPFIRE_COMPOSER_UPSELL_FUNNEL');}).done();},render:function(){if(!this.state.showUpsell||this.state.dismissed)return null;return (c('React').createElement(c('FBOverlayContainer.react'),{className:"_3-8x _3utr"},c('React').createElement(c('FBOverlayBase.react'),null,c('React').createElement('div',null,c('React').createElement('div',{className:"_3uts"},i._("\u00bfEst\u00e1s escribiendo una publicaci\u00f3n larga? Cambia a nota.")),c('React').createElement('div',{className:"_3utu"},i._("Hemos creado un nuevo y atractivo formato de notas para que tus publicaciones m\u00e1s largas queden siempre genial. \u00bfQuieres mover lo que has hecho hasta ahora a una nota?")),c('React').createElement('div',null,c('React').createElement(c('XUIButton.react'),{className:"_3utv",onClick:this.openComposer,label:i._("Cambiar a nota"),suppressed:true})))),c('React').createElement(c('FBOverlayElement.react'),{horizontal:'right',vertical:'top'},c('React').createElement(c('XUICloseButton.react'),{size:'small',onClick:this._dismiss,className:"_3-8j"})),c('React').createElement(c('FBOverlayElement.react'),{horizontal:'right',vertical:'bottom'},c('React').createElement('div',{className:"_3-8j"},this.state.loading?c('React').createElement(c('XUISpinner.react'),{size:'small'}):null))));}});f.exports=l;},null);
__d("XIntlInlineActionDelegateController",["XController"],function a(b,c,d,e,f,g){c.__markCompiled&&c.__markCompiled();f.exports=c("XController").create("\/translations\/inline\/",{});},null);