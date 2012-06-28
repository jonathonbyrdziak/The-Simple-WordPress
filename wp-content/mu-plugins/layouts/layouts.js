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
	var LayoutWidgets = [];
	
	// Container
	var Container = Class.extend({
		defaults	: {
			container		: '#rokkout', //the css
			sortable		: {
				handle		: '.rokkout-handle',
				activeclass : 'row-being-drug',
				placeholder	: {
			        element: function(currentItem) {
			            var p = j("<div/>")
			            	.css({
			            		height	: j(currentItem).height()+4,
			            		width	: j(currentItem).parent().width()-8,
			            		border	: '4px dashed #627DB4',
			            		opacity	: 0.3
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
				background	: '#fff',
				position	: 'relative',
				border		: '4px solid #fff',
//				overflowX	: 'hidden',
			},
			cssRowMouseover	: {
				border		: '4px solid #e1e1e1'
			},
			cssRowMouseout	: {
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
				border		: '1px solid #e1e1e1',
				width		: '150px',
				left		: '-138px',
				top			: '17px',
				background	: '#E1E1E1',
				margin		: '0',
				padding		: '5px 0 5px 5px'
			},
			cssMenuItem		: {
				padding		: '2px 0',
				margin		: '0',
				textAlign	: 'left',
				fontSize	: '13px',
				color		: '#627DB4',
				cursor		: 'pointer',
				textTransform : 'capitalize'
			},
			cssHandle		: {
				borderBottomLeftRadius: '3px',
				backgroundImage : 'url("' + 'data:image/png;base64,' +
				'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAALGPC/xhBQAAAAZiS0dEAP8A' +
				'/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9wGGxcBHcIEu6cAAANiSURBVDjLXZNd' +
				'bFN1GMafc05b6TkdtCVW26002ZbVzCF6BxEZCI5kxpuJI95gVD5GVOZiROetidlVIzcQRRlZr5YlRHAz' +
				'mRCYxnajumya6D7sh7O2ZaEfp1tPe9qe///1wg0Hv6v3TZ73zfs8yStgg5aWFsRiMQSDQfh8vm3ZbFYO' +
				'h8OFjo4Owe12OxOJRLGvr68OAF1Hj+L7yUk8YHFxEVtJpVJnl5eXfw8Gg0+HQqFXotHo8sTE+MtbNR8P' +
				'Dv5XeDweAMDc3FxbOp0eCgQCB1ZXVy8SESWTyXAun0+UNY3Gx8c/mZ2dPf5nLPYpABseJZVKDTHGapqm' +
				'5arVatEwDF6r1Vi1ViO9qrO1tfV8qVRaV1VVm5y8+faDwZGREQBAIBA4oGladmOQ12o1put6vaLr9XJZ' +
				'Z5VKhZcrFZ5Op/8C4AaA4ZGrMHm93m3JZPJNk8m0W5QkM+dcMAyDZzKZecMwfiTAbFNsLzkc9jYQSBTF' +
				'x/5OJvsL+UIkEolcF7PZrFwqld51uVxnJVFs4JxTOpOZHx0dPeP3+wee8vvPLSz80V8qafcNzgSrrDyp' +
				'yMpHhULh9UQibpE8jY0653zK4XA8pyhKIxFRPpcb6+npGd5wScFgMNrV1aW5HncdhABTLBb75sKFzwdk' +
				'Wc6Je57ZLTQ3tzTLsuLmRAJnnIhgBiBu5vTOe+esTqfTTyATZxyyLLsPHzmys1LWBOn0qTM7m7xNYw6H' +
				'oxkQQEQi48ze3d29ZLdvv3/ixBvKa8dePeZ2ewZFUbRyzgVJMnktFssTqlr81hRPxIt1Zry/tLS0Z//+' +
				'Fz4wm81O+44dbe3t7Ze9TU2/SJJktTVs3wtRaCACFQqFf2amZz4TRGGxoKq6sHnm3UjkeGtr61eSJNlA' +
				'AGMMnDgngkCcA4BARChppczpk6cO375ze+H5vfv+96nYlA6jbgiqqq4U14r3CABnnBgziBNHLp9Prq2v' +
				'p4kg9w8M7AOA0Mw00NnZubnDNv7dxEkA7mg0NpTO3KNQOHxt/tffZuLxBH195UrfoRcPtV+/ceOtrQED' +
				'AD48f/6h/m7k556bt26N9fb2ei9euvTs1NQP14aHrx7cqvHt2gUAEB79iS++vAzG6tLKyoqlqusVTSsL' +
				'Pp/PqqpFPRye5uHwTw/p/wXQV8s8KO6lLgAAAABJRU5ErkJggg==' + '")',
				backgroundRepeat : 'no-repeat',
				backgroundPosition : 'center center',
				backgroundColor	: '#e1e1e1',
				position 	: 'absolute',
				width		: '18px',
				height		: '18px',
				border		: '1px solid #e1e1e1',
				top			: '0',
				right		: '0',
				zIndex		: '1000',
				visibility 	: 'hidden'
			}
		},
		
		init		: function(options) {
			this.o = jQuery.extend(true, this.defaults, options);
			this.c = j(this.o.container);
			this.rows = this.c.children();
			
			this.lastdrop = '';
			
			this.addListeners();
		},
		
		addListeners	: function() {
			var o = this.o;

			j('.rokkin_button').bind('click', this.modeClick.bind(this));

			// managing the container
			this.o.droppable.drop = this.newRow.bind(this);
			this.c.droppable(this.o.droppable).sortable(this.o.sortable);
			this.rows.each(function(i,e){ new DesignRow(e,o); });
			
			// managing the widets api
			j(LayoutWidgets).each(this.newLayoutWidget.bind(this));
		},
		
		newLayoutWidget	: function(i,widget) {
			this.o.draggable.connectToSortable = this.o.container;
			this.o.draggable.start = function(event, ui) 
			{
				j(ui.helper).data("LayoutWidget", widget);
		    }
			
			// playing the shell game
			var wrapper = j('<div class="layouts_design_item button"/>');
			var thumbnail = widget.thumbnail( wrapper );
			var inner = j('<div class="innerWrapper"/>').append( thumbnail );
			
			// drop it in the dom
			wrapper.append( inner ).draggable(this.o.draggable);
			j('#designmode').append( wrapper );
		},
		
		newRow			: function(ev, ui) {
			if (j(ui.draggable).data('DesignRow')) return;
			
			var row = new DesignRow(ui.draggable,this.o);
			j(ui.draggable).data('DesignRow', row);
			
			row.newOnDrop(ev, ui, ui.helper.data("LayoutWidget"));
		},
		
		modeClick		: function(e) {
			e.stopPropagation();
			var el = j(e.currentTarget);
			var win = j(el.attr('data'));
			
			var on = true;
			if (el.hasClass('button-primary'))
				var on = false;
			
			j('.rokkin_button').removeClass('button-primary');
			
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
		}
	});
	
	// Design Row
	var DesignRow = Class.extend({
		init		: function(e, o) {
			this.e = j(e);
			this.o = o;
			
			this.menu = j('<ul class="rokkout-handle-menu"></ul>')
				.css(this.o.cssMenu);
			
			this.handle = j('<div class="rokkout-handle"></div>')
				.css(this.o.cssHandle)
				.append(this.menu);
			 
			this.e
				.css(this.o.cssRows)
				.append(this.handle);
			
			this.addListeners();
		},
		
		addListeners	: function() {
			this.e
				.bind('mouseover', this.rowMouseover.bind(this))
				.bind('mouseout', this.rowMouseout.bind(this));
			this.handle
				.bind('click', this.handleClick.bind(this));
		},
		
		newOnDrop		: function(ev, ui, widget) {
			var self = this;
			this.e.css('width', (ui.draggable.parent().width()-8) );
			
			// creating the menu
			j(widget.menuItems).each(function(k,item){
				var ele = j(item.html)
					.css(self.o.cssMenuItem);
				
				if (item.click) 
					ele.bind('click', function(e){
						if (item.click) return item.click(e, self.e, widget);
					});
				
				self.menu.append(ele);
			});
			
			this.e.removeClass('button').removeClass('layouts_design_item');
			this.e.find('> .innerWrapper').replaceWith( widget.element( this.e, this ) );
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
			
			j(e.currentTarget).css(this.o.cssRowMouseover);
			if (!ele.hasClass('rokkout-active'))
				ele.css(this.o.cssRowHandleMouseover);
		},
		
		rowMouseout		: function(e) {
			if (!this.isDesign()) return;
			if (j('.rokkout-active').length) return;
			
			var ele = j(e.currentTarget).find('.rokkout-handle');

			j(e.currentTarget).css(this.o.cssRowMouseout);
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
    			html	: '<li>Delete this item</li>',
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
			j.each(o.menuItems, function(i,e){
				menuItems.push(e);
			});
			o.menuItems = menuItems;
			
			var self = j.extend(true, this, o);
			LayoutWidgets.push(self);
		}
	});
	
	// Activate
	j.fn.RokkOut = j.fn.rokkout = function( o ) {
		return this.each(function() {
			var no = jQuery.extend(o, {container:this});
			j(this).ro = new Container(no);
		});
	};
})(jQuery);

// One row
new LayoutWidget({
	element		: function(original) {
		var el = jQuery('<div>Cell</div>')
			.css({
				border		: '4px solid #555',
				background	: '#999',
				height		: '35px',
				lineHeight	: '35px',
				textAlign	: 'center'
			});
		return el;
	},
	thumbnail	: function(wrapper) {
		return jQuery('<div>Single Cell</div>');
	},
	menuItems	: [
		{
			html	: '<li>first menu item</li>',
			click	: function(e) {
				//e.stopPropagation();
				
				//return false;
			}
		},
		{
			html	: '<li>second menu item</li>',
			click	: function(e) {
				console.log( 'second click!' );
			}
		}
	]
});

jQuery(document).ready(function(){
	jQuery('#post-body-content').RokkOut();
});	