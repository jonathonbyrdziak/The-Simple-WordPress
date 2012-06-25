<?php header('Content-Type: text/css;');?>
<?php if($_GET['t']=='a'){ ?>

	/* Common Things */
	html, body, div, span, applet, object, iframe,  p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike,  sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video{font-size: 11px;font-family: "lucida grande",tahoma,verdana,arial,sans-serif !important;color: #333;line-height: 1.28;direction: ltr;unicode-bidi: embed;}
	div.sidebar-name h3, #menu-management .nav-tab, #dashboard_plugins h5, a.rsswidget, #dashboard_right_now td.b, #dashboard-widgets h4, .tool-box .title, #poststuff h3, .metabox-holder h3, .pressthis a, #your-profile legend, .inline-edit-row fieldset span.title, .inline-edit-row fieldset span.checkbox-title, .tablenav .displaying-num, .widefat th, .quicktags, .search,h2 .nav-tab, .wrap h2, .subtitle, .login form .input {font-family: "lucida grande",tahoma,verdana,arial,sans-serif !important;}

	a, #adminmenu a, #the-comment-list p.comment-author strong a, #media-upload a.del-link, #media-items a.delete, .plugins a.delete, .ui-tabs-nav a{color: #3B5998;}
	a:hover,#the-comment-list p.comment-author strong a:hover, #media-upload a:hover.del-link, #media-items a:hover.delete, .plugins a:hover.delete, .ui-tabs-nav a:hover{text-decoration:underline; color:#3B5998;}

	/* Inputs */
	textarea,input[type="text"], input[type="password"], input[type="file"], input[type="button"], input[type="submit"], input[type="reset"], input[type="email"], input[type="number"], input[type="search"], input[type="tel"], input[type="url"]{border: 1px solid #BDC7D8;font-family: "lucida grande",tahoma,verdana,arial,sans-serif;font-size: 11px;margin: 0;padding: 3px;-webkit-appearance: none;-webkit-border-radius: 0;-moz-border-radius:0!important;border-radius:0!important;}
	 select{border: 1px solid #BDC7D8;font-family: "lucida grande",tahoma,verdana,arial,sans-serif;font-size: 11px;margin: 0;padding: 3px;-webkit-border-radius: 0;-moz-border-radius:0!important;border-radius:0!important;}
	#titlediv #title-prompt-text, #wp-fullscreen-title-prompt-text {padding: 3px 10px;}
	
	
	/* Buttons */
	input.button-primary, button.button-primary, a.button-primary,input[type="button"].button-primary{padding: 3px 16px!important; line-height:15px; font-size:11px!important;background: #617AAC url('./images/button.png') repeat-x 100% -47px!important;border: 1px solid #29447E!important;border: 1px solid #999;-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, .1); -webkit-border-radius:0;-moz-border-radius:0!important;border-radius:0!important; color:#FFFFFF}
	a.button-primary:hover,.button:hover, .button-secondary:hover{text-decoration:none!important;}
	input.button-primary:active, button.button-primary:active, a.button-primary:active{background: #617AAC!important;}
	.button, .button-secondary,.actions .button-secondary,input[type="button"] {padding: 3px 13px!important; line-height:15px!important; font-size:11px!important;background: #EEE url('./images/button.png') left top repeat-x!important;-webkit-box-shadow: rgba(0, 0, 0, 0.0976563) 0px 1px 0px;box-shadow: rgba(0, 0, 0, 0.0976563) 0px 1px 0px;border: 1px solid #999!important; color: #333; font-weight:bold;-webkit-border-radius:0!important;-moz-border-radius:0!important;border-radius:0!important;}
	.button:active, .button-secondary:active,.actions .button-secondary:active,input[type="button"]:active{background:#eee!important; color:#000000!important}
	a.button:hover,a.button:active{color:#000000!important}
	#ed_toolbar .ed_button{padding: 3px 10px!important;}
	
	#wpwrap {width: 100%;margin: 0 auto;}
	
	/* Message box */
	#update-nag, .update-nag { text-align:left; padding: 15px 0 20px 80px;margin: 30px 20px 20px 0;border: 1px dashed #CED5E5;border-top: 1px dashed #CED5E5;-webkit-border: 03px;border-radius: 0px; color:#000000; font-size:11px; background-image: url('./images/wjbfeoJSGHZ.png'); background-repeat: no-repeat; background-position: 10px center;background-color:#F5F7FA; }

	/* Admin Menu */
	#adminmenushadow, #adminmenuback {}
	#adminmenuback, #adminmenuwrap {background-color: #FFF; padding-top:20px;}
	#adminmenu li.wp-has-current-submenu .wp-menu-arrow, #adminmenu li.menu-top:hover .wp-menu-arrow, #adminmenu li.current .wp-menu-arrow, #adminmenu li.focused .wp-menu-arrow, #adminmenu li.menu-top.wp-has-submenu:hover .wp-menu-arrow div {display: none;}
	#adminmenu .wp-menu-arrow div, #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head,
	#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, #adminmenu li.current a.menu-top, .folded #adminmenu li.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, #adminmenu .wp-menu-arrow, #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head,#adminmenu li.menu-top:hover > a, #adminmenu li.menu-top.focused > a, #adminmenu li.menu-top > a:focus  {background: #627DB4!important;}
	#adminmenu li.menu-top:hover > a, #adminmenu li.menu-top.focused > a, #adminmenu li.menu-top > a:focus{color:#FFFFFF;text-shadow: 1px 0px 0 #333;}
	#adminmenu div.wp-menu-image {margin-left: 10px;}
	#adminmenu .wp-menu-image img{opacity:1!important;}
	#adminmenu .menu-icon-comments div.wp-menu-image, #adminmenu .menu-icon-dashboard div.wp-menu-image, #adminmenu .menu-icon-post div.wp-menu-image, 
	#adminmenu .menu-icon-media div.wp-menu-image, #adminmenu .menu-icon-links div.wp-menu-image, #adminmenu .menu-icon-page div.wp-menu-image, 
	#adminmenu .menu-icon-comments div.wp-menu-image, #adminmenu .menu-icon-appearance div.wp-menu-image, #adminmenu .menu-icon-plugins div.wp-menu-image, 
	#adminmenu .menu-icon-users div.wp-menu-image, #adminmenu .menu-icon-tools div.wp-menu-image,#adminmenu .menu-icon-settings div.wp-menu-image{background-image:url('images/menu.png') !important;}
	.icon16.icon-dashboard, #adminmenu .menu-icon-dashboard div.wp-menu-image {background-position:-60px -34px}
	#adminmenu li {margin-bottom:1px; }
	#adminmenu a.menu-top {padding: 6px 5px;border:none; font-size:11px}
	#adminmenu .wp-submenu ul {padding: 0;}
	#adminmenu .wp-submenu a, #adminmenu li li a, .folded #adminmenu .wp-not-current-submenu li a {padding-left: 25px; font-size:11px}
	#adminmenu li.wp-menu-separator{border:none;height:0px; }
	.folded #adminmenu div.wp-menu-image {margin-left:0;}
	.folded #adminmenu div.wp-submenu-head {font-weight:bold}
	#adminmenuback, #adminmenuwrap, #adminmenu, #adminmenu .wp-submenu, #adminmenu .wp-submenu-wrap, .folded #adminmenu .wp-has-current-submenu .wp-submenu {width: 150px;}
	.folded #adminmenu .wp-submenu .wp-submenu-wrap {margin: 4px 0 0 5px; padding:0;}
	#adminmenu li .wp-submenu-wrap {border-width: 1px;border-style: solid;margin-left:5px;padding: 5px 0;background: white; }
	#adminmenu .wp-submenu-wrap, #adminmenu .wp-submenu ul {border-color: #E0E0E0;}
	#adminmenu li.wp-menu-open {border:none;}
	#adminmenu .awaiting-mod, #adminmenu span.update-plugins, #sidemenu li a span.update-plugins {-moz-border-radius: 2px;-webkit-border-radius: 2px;border-radius: 2px;background-color: #D8DFEA;color: #3B5998; }
	#adminmenu li .awaiting-mod span, #adminmenu li span.update-plugins span, #sidemenu li a span.update-plugins span{font-weight:bold!important; font-size: 11px;padding: 1px 10px;}
	#adminmenu li.current a .awaiting-mod, #adminmenu li a.wp-has-current-submenu .update-plugins {background-color: #FFF;color: #000;}
	
	
	/* Contents */
	.icon32.icon-dashboard, #icon-index ,.icon32.icon-post, #icon-edit, #icon-post,	.icon32.icon-media, #icon-upload,	.icon32.icon-links, #icon-link-manager, #icon-link, 	#icon-link-category,.icon32.icon-page, #icon-edit-pages, #icon-page,.icon32.icon-comments, #icon-edit-comments,	.icon32.icon-appearance, #icon-themes,
	.icon32.icon-plugins, #icon-plugins,	.icon32.icon-users, #icon-users, #icon-profile, #icon-user-edit, .icon32.icon-tools, #icon-tools, #icon-admin, .icon32.icon-settings, #icon-options-general, .icon32.icon-site, #icon-ms-admin {background-image: url('./images/icons32.png')!important;}	
	.show-settings{font-size:11px!important;}
	.table_discussion .total-count,.table_discussion .approved-count,.table_discussion .pending-count,.table_discussion .spam-count{font-size: 18px;}
	h3 span {font-size: 12px;font-weight: bold;}
	.wp-editor-area{font-size:14px;}
	.inner-sidebar #side-sortables, .columns-2 .inner-sidebar #side-sortables{width:290px!important}
	.columns-2 .inner-sidebar{width:auto!important}
	
	
	#TB_overlay,.ui-widget-overlay{background:none!important;}
	#TB_window,.wp-dialog {-moz-box-shadow:none!important;-webkit-box-shadow:none!important;box-shadow:none!important; border: 10px solid rgba(82, 82, 82, .7)!important; -webkit-background-clip: padding-box!important;background-clip: padding-box!important; -moz-background-clip: padding!important;-webkit-border-radius: 3px!important;
border-radius: 3px!important;-moz-border-radius: 3px!important;}
	#TB_window #TB_title,.ui-dialog-titlebar{background-color: #6D84B4!important;border: 1px solid #3B5998!important;-webkit-border-radius: 0px!important;border-radius: 0px!important;-moz-border-radius: 0px!important;}
	#TB_ajaxWindowTitle, #TB_window #TB_title a.tb-theme-preview-link, #TB_window #TB_title a.tb-theme-preview-link:visited, #TB_window #TB_title .tb-theme-preview-link,.ui-dialog-title {color:#fff!important; font-size:12px!important; }
	
	.media-item .bar{height:30px;}
	.media-item .progress .percent{padding-top:5px; text-align:center}
	
	.wp-list-table th{font-size:12px!important; font-weight:bold!important;}
	.wp-list-table td{font-size:11px;}
	.widefat td {padding: 6px 7px; border-bottom: solid 1px #E9E9E9;}
	.widefat td {border-bottom: solid 1px #E9E9E9;}
	.widefat tbody tr:hover,.plugins .inactive, .plugins .inactive th, .plugins .inactive td, tr.inactive + tr.plugin-update-tr .plugin-update,.widefat tbody .active:hover  .active{background:#F4F6F9!important;}
	.widefat tbody tr:hover td,.widefat tbody tr:hover th,.plugins .inactive, .plugins .inactive th, .plugins .inactive td, tr.inactive + tr.plugin-update-tr .plugin-update{border-bottom: solid 1px #E3E8F0}
	.widefat,.alternate, .alt,.active {background-color: #FFF!important;}
	.column-author img, .column-username img {border: 1px solid #CCC;padding: 2px;}

	/* widgets */
	.widget, #widget-list .widget-top, .postbox, .menu-item-settings {background:none;} 
	 #widget-list .widget-top, .postbox,  .stuffbox{border: 1px solid #C5C5C5!important;}
	.widget .widget-top, .postbox h3, .stuffbox h3, .widefat thead tr th, .widefat tfoot tr th, h3.dashboard-widget-title, h3.dashboard-widget-title span, h3.dashboard-widget-title small, .find-box-head, .sidebar-name, #nav-menu-header, #nav-menu-footer, .menu-item-handle, #fullscreen-topbar{background: #F7F7F7!important;}
	#available-widgets .widget-holder ,div.widgets-sortables, #widgets-left .inactive {background:#fff;}
	.widget-title h4{margin-top:-2px}
	
	.menu-item .item-type{font-size:11px; margin-top:10px;display: block;}
	
	/* Themes */
	.available-theme {display: block;margin-right: 0px;overflow: hidden;padding: 10px;width: 100%; border-bottom:1px solid #eee}
	a.screenshot {width: 150px!important; height: 120px!important;border-width: 1px;margin-bottom: 10px;overflow: hidden; padding:3px; float:left; margin-right:10px}
	.available-theme img {width: 150px;height: 120px}
	.available-theme h3 {margin: 0px 0 5px!important;}
	.available-theme p{margin-left:170px;}
	.available-theme .action-links{}

	#wpcontent, #footer {background:#fff;}
	
	/* Login Form */
	.login{font-size:11px!important; font-family: "lucida grande",tahoma,verdana,arial,sans-serif !important;color: #333;line-height: 1.28;direction: ltr;unicode-bidi: embed; margin:0!important; padding:0 !important; background:#FFF!important; }
	.login #nav a, .login #backtoblog a,.login #nav a:hover, .login #backtoblog a:hover 
	{color: #3B5998!important;font-size:11px!important; }
	.login #login{padding: 10% 0 0; margin:auto!important; height:auto; width:350px}	
	.login h1 a {background-position: center center;width: 350px;font-size:22px;text-decoration:none;}		
	.login form	 {background:#FBFBFB; text-align: left;position: relative;border: 0px solid #AAA;	box-shadow: 0px 0px 00px #fff;border-radius: 0px!important;padding: 26px 24px 36px;}
	.login form .input{border: 1px solid #BDC7D8;font-family: "lucida grande",tahoma,verdana,arial,sans-serif;font-size: 11px;margin: 0 0 15px;padding: 3px;-webkit-appearance: none;-webkit-border-radius: 0;-moz-border-radius:0!important;border-radius:0!important;}
	.login form .button-primary{padding: 3px 16px!important; line-height:15px!important; font-size:11px!important;background: #617AAC url(\''.WP_PLUGIN_URL . '/blueadmin/images/button.png\') repeat-x 100% -47px !important;border: 1px solid #29447E!important;border: 1px solid #999;-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, .1); -webkit-border-radius:0;-moz-border-radius:0!important;border-radius:0!important; color:#EAF2FA!important;}
	.login form .button-primary:active{background:#617AAC!important}
	.login form .button-primary:hover{color:#fff!important;}
	.login form label, .login form .forgetmenot label{font-size:11px;}
	#nav{float:right;}
	#nav,#backtoblog {padding: 0!important;margin:5px!important;}
	
<?php } ?>	
	
	/* Admin Bar */
	#wpadminbar .quicklinks {width: 100%;margin: 0 auto;}
	#wpadminbar{background:#3B5998!important;color: #C3CFE5!important;border-bottom: 1px solid #133783;}
	#wpadminbar .quicklinks > ul > li,#wpadminbar .quicklinks > ul > li > a, #wpadminbar .quicklinks > ul > li > .ab-empty-item,#wpadminbar .quicklinks .ab-top-secondary > li  {border:none;font-family: "lucida grande",tahoma,verdana,arial,sans-serif !important;}
	#wpadminbar .quicklinks a, #wpadminbar .quicklinks .ab-empty-item, #wpadminbar .shortlink-input {height: 29px; font-family: "lucida grande",tahoma,verdana,arial,sans-serif !important;}
	#wpadminbar .ab-top-secondary{background:url('./images/trans.png') left top repeat scroll !important;}
	#wpadminbar .quicklinks .ab-top-secondary > li > a, #wpadminbar .quicklinks .ab-top-secondary > li > .ab-empty-item {border: none;}
	#wpadminbar a,#wpadminbar * {color:#D8DFEA;font-weight:bold; font-size:11px; background:none;font-family: "lucida grande",tahoma,verdana,arial,sans-serif !important;}
	#wpadminbar a:hover,#wpadminbar .ab-top-menu>li:hover>.ab-item,#wpadminbar .ab-top-menu>li.hover>.ab-item,#wpadminbar .ab-top-menu>li>.ab-item:focus,#wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus{color:#fafafa; background:url('./images/trans.png') left top repeat scroll ;}
	#wpadminbar .quicklinks .menupop ul li a{color:#3B5998!important;}
	#wpadminbar .quicklinks .menupop ul li a:hover{background:#F4F6F9!important;}
	#wpadminbar #wp-admin-bar-user-info .display-name {padding-bottom: 8px;}
	#wpadminbar #wp-admin-bar-my-account.with-avatar #wp-admin-bar-user-actions > li {margin-left: 0;}
	#wpadminbar #wp-admin-bar-updates, #wpadminbar #wp-admin-bar-user-info, #wpadminbar #wp-admin-bar-new-link 
	{display:none}
	#wpadminbar #wp-admin-bar-user-actions > li {margin: 0;}
	