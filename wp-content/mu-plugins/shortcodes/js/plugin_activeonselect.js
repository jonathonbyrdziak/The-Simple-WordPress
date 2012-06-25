(function() {
	var DOM = tinymce.DOM;
	
    tinymce.create('tinymce.plugins.shoactiveonselect', {
		
        init : function(ed, url) {
			var disabled = true;
			
			ed.addCommand('DROPcap', function() {
				if ( disabled )
					return;
				
				ed.selection.setContent('<span class="dropcap">' + ed.selection.getContent() + '</span>');
				
			});
			
			ed.addCommand('QuotLeft', function() {
				if ( disabled )
					return;

				 ed.selection.setContent('<span class="quote_left">' + ed.selection.getContent() + '</span>');
				
			});
			
			ed.addCommand('QuotRight', function() {
				if ( disabled )
					return;

				 ed.selection.setContent('<span class="quote_right">' + ed.selection.getContent() + '</span>');
				
			});
			
			ed.addButton('dropcap', {
                title : 'Dropcap',
                image : url+'/../images/dropcap.png',
                cmd : 'DROPcap'
            });
			
			ed.addButton('quot_left', {
                title : 'Pullquote left',
                image : url+'/../images/bk-left.png',
                cmd: 'QuotLeft'
            });
			
			ed.addButton('quot_right', {
                title : 'Pullquote right',
                image : url+'/../images/bk-right.png',
                cmd: 'QuotRight'
            });

			ed.onNodeChange.add(function(ed, cm, n, co) {
				disabled = co;
				cm.setDisabled('dropcap', co);
				cm.setDisabled('quot_left', co);
				cm.setDisabled('quot_right', co);
			});
			
			ed.onBeforeExecCommand.add(function(ed, cmd, ui, val, o) {
				var DOM = tinymce.DOM, n, ko;
				n = ed.selection.getNode();
				if ( 'DROPcap' == cmd ){
					if ( n.nodeName == 'SPAN' ) {
						if ( ed.dom.hasClass(n, 'dropcap') )
							ed.execCommand("removeFormat");
							o.terminate = true;
					}
				}
				
				if ( 'QuotLeft' == cmd ){
					if ( n.nodeName == 'SPAN' ) {
						if ( ed.dom.hasClass(n, 'quote_left') ){
							ed.execCommand("removeFormat");
							o.terminate = true;
						} else if( ed.dom.hasClass(n, 'quote_right') ){
							ed.execCommand("removeFormat");
							ed.execCommand("mceRepaint");
						}
					}
				}
				
				if ( 'QuotRight' == cmd ){
					if ( n.nodeName == 'SPAN' ) {
						if ( ed.dom.hasClass(n, 'quote_right') ) {
							ed.execCommand("removeFormat");
							o.terminate = true;
						} else if( ed.dom.hasClass(n, 'quote_left') ){
							ed.execCommand("removeFormat");
							ed.execCommand("mceRepaint");
						}
					}
				}
			});
			
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "RedRokk theme Shortcode",
                author : 'RedRokk',
                authorurl : 'http://redrokk.com',
                infourl : 'http://redrokk.com',
                version : "1.0.0"
            };
        }
		
    });
    tinymce.PluginManager.add('shoactiveonselect', tinymce.plugins.shoactiveonselect);
	
})();