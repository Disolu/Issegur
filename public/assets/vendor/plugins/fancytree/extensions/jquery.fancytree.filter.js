;(function($,window,document,undefined){"use strict";function _escapeRegex(str){return(str+"").replace(/([.?*+\^\$\[\]\\(){}|-])/g,"\\$1");}
$.ui.fancytree._FancytreeClass.prototype._applyFilterImpl=function(filter,branchMode,leavesOnly){var match,re,count=0,hideMode=this.options.filter.mode==="hide";leavesOnly=!!leavesOnly&&!branchMode;if(typeof filter==="string"){match=_escapeRegex(filter);re=new RegExp(".*"+ match+".*","i");filter=function(node){return!!re.exec(node.title);};}
this.enableFilter=true;this.lastFilterArgs=arguments;this.$div.addClass("fancytree-ext-filter");if(hideMode){this.$div.addClass("fancytree-ext-filter-hide");}else{this.$div.addClass("fancytree-ext-filter-dimm");}
this.visit(function(node){delete node.match;delete node.subMatch;});this.visit(function(node){if((!leavesOnly||node.children==null)&&filter(node)){count++;node.match=true;node.visitParents(function(p){p.subMatch=true;});if(branchMode){node.visit(function(p){p.match=true;});return"skip";}}});this.render();return count;};$.ui.fancytree._FancytreeClass.prototype.filterNodes=function(filter,leavesOnly){return this._applyFilterImpl(filter,false,leavesOnly);};$.ui.fancytree._FancytreeClass.prototype.applyFilter=function(filter){this.warn("Fancytree.applyFilter() is deprecated since 2014-05-10. Use .filterNodes() instead.");return this.filterNodes.apply(this,arguments);};$.ui.fancytree._FancytreeClass.prototype.filterBranches=function(filter){return this._applyFilterImpl(filter,true,null);};$.ui.fancytree._FancytreeClass.prototype.clearFilter=function(){this.visit(function(node){delete node.match;delete node.subMatch;});this.enableFilter=false;this.lastFilterArgs=null;this.$div.removeClass("fancytree-ext-filter fancytree-ext-filter-dimm fancytree-ext-filter-hide");this.render();};$.ui.fancytree.registerExtension({name:"filter",version:"0.3.0",options:{autoApply:true,mode:"dimm"},treeInit:function(ctx){this._super(ctx);},nodeLoadChildren:function(ctx,source){return this._super(ctx,source).done(function(){if(ctx.tree.enableFilter&&ctx.tree.lastFilterArgs&&ctx.options.filter.autoApply){ctx.tree._applyFilterImpl.apply(ctx.tree,ctx.tree.lastFilterArgs);}});},nodeRenderStatus:function(ctx){var res,node=ctx.node,tree=ctx.tree,$span=$(node[tree.statusClassPropName]);res=this._super(ctx);if(!$span.length||!tree.enableFilter){return res;}
$span.toggleClass("fancytree-match",!!node.match).toggleClass("fancytree-submatch",!!node.subMatch).toggleClass("fancytree-hide",!(node.match||node.subMatch));return res;}});}(jQuery,window,document));	return this.filterNodes.apply(this, arguments);
};

/**
 * [ext-filter] Dimm or hide whole branches.
 *
 * @param {function | string} filter
 * @returns {integer} count
 * @alias Fancytree#filterBranches
 * @requires jquery.fancytree.filter.js
 */
$.ui.fancytree._FancytreeClass.prototype.filterBranches = function(filter){
	return this._applyFilterImpl(filter, true, null);
};


/**
 * [ext-filter] Reset the filter.
 *
 * @alias Fancytree#clearFilter
 * @requires jquery.fancytree.filter.js
 */
$.ui.fancytree._FancytreeClass.prototype.clearFilter = function(){
	this.visit(function(node){
		delete node.match;
		delete node.subMatch;
	});
	this.enableFilter = false;
	this.lastFilterArgs = null;
	this.$div.removeClass("fancytree-ext-filter fancytree-ext-filter-dimm fancytree-ext-filter-hide");
	this.render();
};


/*******************************************************************************
 * Extension code
 */
$.ui.fancytree.registerExtension({
	name: "filter",
	version: "0.3.0",
	// Default options for this extension.
	options: {
		autoApply: true, // re-apply last filter if lazy data is loaded
		mode: "dimm"
	},
	treeInit: function(ctx){
		this._super(ctx);
	},
	nodeLoadChildren: function(ctx, source) {
		return this._super(ctx, source).done(function() {
			if( ctx.tree.enableFilter && ctx.tree.lastFilterArgs && ctx.options.filter.autoApply ) {
				ctx.tree._applyFilterImpl.apply(ctx.tree, ctx.tree.lastFilterArgs);
			}
		});
	},
	nodeRenderStatus: function(ctx) {
		// Set classes for current status
		var res,
			node = ctx.node,
			tree = ctx.tree,
			$span = $(node[tree.statusClassPropName]);

		res = this._super(ctx);
		// nothing to do, if node was not yet rendered
		if( !$span.length || !tree.enableFilter ) {
			return res;
		}
		$span
			.toggleClass("fancytree-match", !!node.match)
			.toggleClass("fancytree-submatch", !!node.subMatch)
			.toggleClass("fancytree-hide", !(node.match || node.subMatch));

		return res;
	}
});
}(jQuery, window, document));
