/**
 * Simple JavaScript Inheritance
 * By John Resig http://ejohn.org/
 * MIT Licensed.
 * 
 * http://ejohn.org/blog/simple-javascript-inheritance/
 * Inspired by base2 and Prototype
 * 
 */
(function(){var initializing=false,fnTest=/xyz/.test(function(){xyz})?/\b_super\b/ :/.*/;this.Class=function(){};Class.extend=function(prop){var _super=this.prototype;initializing=true;var prototype=new this();initializing=false;for(var name in prop){prototype[name]=typeof prop[name]=="function"&&typeof _super[name]=="function"&&fnTest.test(prop[name])?(function(name,fn){return function(){var tmp=this._super;this._super=_super[name];var ret=fn.apply(this,arguments);this._super=tmp;return ret}})(name,prop[name]):prop[name]}function Class(){if(!initializing&&this.init)this.init.apply(this,arguments)}Class.prototype=prototype;Class.constructor=Class;Class.extend=arguments.callee;return Class}})();

/**
 * Red Rokk Layout Designer
 * By Jonathon Byrd http://redrokk.com
 * MIT Licensed.
 * 
 * Inspired by hours of looking for something that didn't exist,
 * until now.
 * 
 */
var LayoutWidget;
(function(j){
	var LayoutWidgets = {};
	
	// Container
	var Container = Class.extend({
		defaults	: {
			container		: '#rokkout', //the css
			existing 		: false,
			sortable		: {
				handle		: '.rokkout-handle',
				activeclass : 'row-being-drug',
				placeholder	: {
			        element: function(currentItem) {
			            var p = j("<div/>")
			            	.css({
			            		height	: j(currentItem).height() + 4,
			            		width	: j(currentItem).parent().width() - 8,
			            		border	: '4px dashed #e1e1e1',
			            		marginBottom	: '16px'
			            	});
			            return p;
			        },
			        update: function(container, p) {
			            return;
			        }
		        },
				cursor		: 'move',
				drop		: function(){},
				create:function(){
				    j(this).height(j(this).height());
				}
			},
			droppable		: { 
				accept		: ".layouts_design_item",
				drop		: function(){}
			},
			draggable		: { 
				start		: function(){},
				revert		: "invalid",
				helper		: "clone",
				cursor		: "move",
			},
			cssRows			: {
				position	: 'relative'
			},
			cssRowDesignOn	: {
				border		: '4px solid #627DB4'
			},
			cssRowDesignOff	: {
				border		: '4px solid #fff'
			},
			cssRowHandleMouseover	: {
				visibility	: 'visible'
			},
			cssRowHandleMouseout	: {
				visibility	: 'hidden'
			},
			cssMenu		: {
				listItemStyle: 'none',
				display		: 'none',
				borderRadius: '3px',
				position	: 'relative',
				border		: '1px solid #627DB4',
				width		: '150px',
				left		: '-138px',
				top			: '17px',
				background	: '#627DB4',
				margin		: '0',
				padding		: '5px 0 5px 5px'
			},
			cssMenuItem		: {
				padding		: '2px 0',
				margin		: '0',
				textAlign	: 'left',
				fontSize	: '13px',
				fontWeight	: 'bold',
				color		: '#fff',
				cursor		: 'pointer',
				textTransform : 'capitalize'
			},
			cssHandle		: {}
		},
		
		init		: function(options) {
			this.o = jQuery.extend(true, this.defaults, options);
			this.c = j(this.o.container);
			this.rows = this.c.children();
			
			this.addListeners();
			this.setExisting();
			this.designModeOff();
		},
		
		addListeners	: function() {
			var o = this.o;

			j('.rokkin_button').bind('click', this.modeClick.bind(this));

			// managing the container
			this.o.droppable.drop = this.newRow.bind(this);
			this.c.droppable(this.o.droppable).sortable(this.o.sortable);
			
			// managing the widets api
			j.each(LayoutWidgets, this.newLayoutWidget.bind(this));
		},
		
		setExisting		: function() {
			var self = this;
			
			if (this.o.existing) {
				j.each(this.o.existing, function(k,id){
					var number = 0;
					var substr = id.split('---');
					i = substr[0];

					if (typeof substr[1] !=='undefined') {
						number = u( substr[1] );
					}
					
					var w = self.getWidget( i );
					
					var ele = (w) ?self.wrapWidget(w) :j('#'+id);
					var row = new DesignRow(ele, self.o, w, number);
					
					(!w) ?row.e.width( row.e.width() - 8 ) :null;
					(!k) ?row.prepend(self.c) :row.append(self.c);
				});
			}
			else {
				this.rows.each(function(i,e){ new DesignRow(e,self.o,false); });
			}
			this.containerHeight();
		},
		
		getWidget		: function( id ) {
			var widget = false;
			j.each(LayoutWidgets, function(i,w) {
				if (w.id != id) return true;
				widget = w;
			});
			
			return widget;
		},
		
		wrapWidget		: function(widget) {
			var wrapper = j('<div class="layouts_design_item button"/>');
			var thumbnail = widget.thumbnail( wrapper );
			var inner = j('<div class="innerWrapper"/>').append( thumbnail );
			
			return wrapper.append( inner );
		},
		
		newLayoutWidget	: function(i,widget) {
			this.o.draggable.connectToSortable = this.o.container;
			this.o.draggable.start = function(event, ui) 
			{
				j(ui.helper).data("LayoutWidget", widget);
		    }
			
			// drop it in the dom
			var wrapper = this.wrapWidget( widget )
				.draggable(this.o.draggable);
			j('#designmode').append( wrapper );
		},
		
		newRow			: function(ev, ui) {
			if (j(ui.draggable).data('DesignRow')) return;

			var row = new DesignRow(ui.draggable,this.o,ui.helper.data("LayoutWidget"));
			j(ui.draggable).data('DesignRow', row);
			
			this.containerHeight();
		},
		
		containerHeight	: function() {
			j(this.o.container).height('auto');
			j(this.o.container).height(j(this.o.container).height() + 50);
		},
		
		modeClick		: function(e) {
			e.stopPropagation();
			var el = j(e.currentTarget);
			var win = j(el.attr('data'));
			
			var on = true;
			if (el.hasClass('button-primary'))
				var on = false;
			
			// turn everything off
			j('.rokkin_button').removeClass('button-primary');
			this.designModeOff();
			this.contentModeOff();
			
			// turning on the modes
			if (on && el.attr('data') == '#designmode') {
				this.designModeOn();
			}
			else if (on) {
				this.contentModeOn();
			}
			
			// changing the menu
			if (on && this.winopen) {
				el.addClass('button-primary');
				
				j('.rokkin_scroll').css('position','absolute');
				j('.rokkin_scroll').parent('div').css('height',win.height()+29);
				j('.rokkin_button').each(function(i,ele){
					j( j(ele).attr('data') ).fadeOut();
				});
				
				win.fadeIn(function(){
					j('.rokkin_scroll').css('position','relative')
					j('.rokkin_scroll').parent('div').css('height','auto');
				});
			}
			else if (on) {
				el.addClass('button-primary');
				win.slideDown();
				this.winopen = true;
			}
			else {
				win.slideUp();
				this.winopen = false;
			}
			return false;
		},
		
		designModeOn	: function() {
			this.designmode = true;
			this.rows.css(this.o.cssRowDesignOn);
			j('.layout_widget_cell').css(this.o.cssRowDesignOn);
		},
		
		designModeOff	: function() {
			this.designmode = false;
			this.rows.css(this.o.cssRowDesignOff);
			j('.layout_widget_cell').css(this.o.cssRowDesignOff);
		},
		
		contentModeOn	: function() {
			this.contentmode = true;
		},
		
		contentModeOff	: function() {
			this.contentmode = false;
		}
	});
	
	// Design Row
	var DesignRow = Class.extend({
		number		: 0,
		init		: function(e, o, widget, number) {
			this.e = j(e);
			this.o = o;
			this.widget = widget;
			
			this.menu = j('<ul class="rokkout-handle-menu"></ul>')
				.css(this.o.cssMenu);
			
			this.handle = j('<div class="rokkout-handle"></div>')
				.css(this.o.cssHandle)
				.append(this.menu);
			 
			this.e
				.css(this.o.cssRows)
				.append(this.handle);
			
			if (this.widget) {
				if (!this.widget.number) {
					this.widget.number = u();
				}
				this.newOnDrop();
				this.number = this.widget.number
			} else {
				this.e.append('<input name="layouts_design_items[]" type="hidden" value="'+ 
						this.e.attr('id') +'" />');
			}
			
			if (!this.isDesign()) {
				this.e.css({'width' : j(o.container).width() });
			}
			
			if (!this.number) {
				this.number = u();
			}
			
			this.addListeners();
		},
		
		addListeners	: function() {
			this.e
				.bind('mouseover', this.rowMouseover.bind(this))
				.bind('mouseout', this.rowMouseout.bind(this));
			this.handle
				.bind('click', this.handleClick.bind(this));
		},
		
		newOnDrop		: function() {
			var self = this;
			
			// creating the menu
			j(this.widget.menuItems).each(function(k,item){
				var ele = j(item.html)
					.css(self.o.cssMenuItem);
				
				if (item.click) 
					ele.bind('click', function(e){
						return item.click(e, self.e, self.widget);
					});
				
				self.menu.append(ele);
			});

			this.e.css('width', this.e.parent().width() )
				.removeClass('button')
				.removeClass('layouts_design_item');
			
			this.e.find('> .innerWrapper')
				.after('<input name="layouts_design_items[]" type="hidden" value="'+ 
						this.widget.i() +'" />')
				.replaceWith( this.widget.element( this.e, this ) );
			
		},
		
		append			: function(el) {
			j(el).append( this.e );
		},
		
		prepend			: function(el) {
			j(el).prepend( this.e );
		},
		
		isDesign		: function() {
			return (j('#layouts_design_mode').hasClass('button-primary'));
		},
		
		rowMouseover	: function(e) {
			if (!this.isDesign()) return;
			if (j('.rokkout-active').length) return;
			
			if (this.menu && !this.menu.html())
				this.menu.remove();
			
			var ele = j(e.currentTarget).find('.rokkout-handle');
			if (!ele.hasClass('rokkout-active'))
				ele.css(this.o.cssRowHandleMouseover);
		},
		
		rowMouseout		: function(e) {
			if (!this.isDesign()) return;
			if (j('.rokkout-active').length) return;
			
			var ele = j(e.currentTarget).find('.rokkout-handle');
			if (!ele.hasClass('rokkout-active'))
				ele.css(this.o.cssRowHandleMouseout);
		},
		
		handleClick		: function(e) {
			if (!this.menu.html()) return;
			var ele = j(e.currentTarget);
			
			if (ele.hasClass('rokkout-active')) {
				ele.find('> .rokkout-handle-menu').hide();
				ele.removeClass('rokkout-active');
				j('.rokkout-handle').css(this.o.cssRowHandleMouseout);
			} 
			else {
				ele.find('> .rokkout-handle-menu').show();
				ele.addClass('rokkout-active');
			}
		}
	});
	
	// Widget api class
	LayoutWidget = Class.extend({
		menuItems : [
     		{
    			html	: '<li>Delete this row</li>',
    			click	: function(e, el, widget) {
     				e.stopPropagation();
     				el.remove();
     				return false;
    			}
    		}
		],
		init	: function(o) {
			var menuItems = [];
			j.each(this.menuItems, function(i,e){
				menuItems.push(e);
			});
			
			if (o.menuItems) j.each(o.menuItems, function(i,e){
				menuItems.push(e);
			});
			o.menuItems = menuItems;
			
			var self = j.extend(true, this, o);
			LayoutWidgets[self.id] = self;
		},
		
		i		: function() {
			return this.id +'---'+ this.number;
		}
	});
	
	// Activate
	j.fn.RokkOut = j.fn.rokkout = function( o ) {
		return this.each(function() {
			var no = jQuery.extend(o, {container:this});
			j(this).ro = new Container(no);
		});
	};
	
	var id = 1;
	function u(v) {
		var v = (typeof v == 'undefined') ?0 :v*1;
		
		if (v > id) {
			return id = v;
		} else if  (v) {
			return v;
		} else {
			return ++id;
		}
	}
})(jQuery);


// Default Widgets
new LayoutWidget({
	id		: 'SingleCell',
	number	: 0,
	element		: function(original) {
		var el = jQuery('<div class="layout_widget_cell layout---'+ this.number +'"/>');
		return el;
	},
	thumbnail	: function(wrapper) {
		return jQuery('<div>Single Cell</div>');
	}
});

new LayoutWidget({
	id		: 'DoubleCell',
	number	: 0,
	element		: function(original, obj) {
		var n = 2;
		var ta = jQuery(obj.o.container).width() - 16;
		var tr = ta - ((n - 1) * 20);
		var td = tr / n;
		
		var el = jQuery('<div class="layout_widget_cell layout---'+ this.number +'1" style="width:' + td + 'px;margin-right:20px;float:left;"></div>' +
				'<div class="layout_widget_cell layout---'+ this.number +'2" style="width:' + td + 'px;float:right;"></div>' +
				'<div style="clear:both;width:100%"></div>');
		return el;
	},
	thumbnail	: function(wrapper) {
		return jQuery('<div>Two Cells</div>');
	}
});


jQuery(document).ready(function(){
	jQuery('#post-body-content').RokkOut({
		existing : layouts_design_mode
	});
});	