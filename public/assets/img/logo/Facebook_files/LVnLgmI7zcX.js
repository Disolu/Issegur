/*!CK:2696661343!*//*1457733609,*/

if (self.CavalryLogger) { CavalryLogger.start_js(["YQ4Zs"]); }

__d('getMentionableRect',['Rect','UserAgent'],function a(b,c,d,e,f,g){if(c.__markCompiled)c.__markCompiled();var h=c('UserAgent').isBrowser('Mobile Safari');function i(l){var m=document.selection.createRange().duplicate();m.moveStart('character',-l);return m.getBoundingClientRect();}function j(l){var m=b.getSelection(),n=m.getRangeAt(0),o=n.cloneRange(),p=o.endContainer,q=o.endOffset,r=null;if(q>=l){o.setStart(p,q-l);r=o.getBoundingClientRect();}return r;}function k(l,m){var n=document.selection?i(l):j(l);if(!n)return null;var o=h?'document':'viewport',p=new (c('Rect'))(n.top,m?n.right:n.left,n.bottom,m?n.right:n.left,o);return p.convertTo('document');}f.exports=k;},null);