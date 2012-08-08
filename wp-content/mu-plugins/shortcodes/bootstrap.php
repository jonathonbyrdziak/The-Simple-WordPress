<?php 
/**
 * @Author	Jonathon byrd
 * @link http://www.jonathonbyrd.com
 * @Package Wordpress
 * @copyright Proprietary Software, Copyright Byrd Incorporated. All Rights Reserved
 * @Since 1.0.0
 * 
 */

defined('ABSPATH') or die("Cannot access pages directly.");

if (!function_exists("sho_get_show_view")):
	//ACTION!
	add_action('wp_loaded', 'sho_show_ajax', 100);
	add_action('wp_loaded', 'sho_show_script');
	add_action('wp_loaded', 'sho_show_style');
	add_action('wp_loaded', 'sho_show_image');
	
	/**
	 * Controller.
	 * 
	 * This function will locate the associated element and display it in the
	 * place of this function call
	 * 
	 * @param string $name
	 */
	function sho_get_show_view( $name = null )
	{
		//initializing variables
		$paths = set_controller_path();
		$theme = get_theme_path();
		$html = '';
		
		if (!($view = sho_find(array($theme), "views".DS.$name.".php")))
		{
			$view = sho_find($paths, "views".DS.$name.".php");
		}
		if (!($model = sho_find(array($theme), "models".DS.$name.".php")))
		{
			$model = sho_find($paths, "models".DS.$name.".php");
		}
		
		if (is_null($name)) return false;
		if (!$view && !$model) return false;
		
		do_action( "byrd-controller", $model, $view );
		$path = $view;
		$html = false;
		
		if (file_exists($model))
		{
			ob_start();
				$args = func_get_args();
				require $model;
				unset($html);
			$html = ob_get_clean();
		}
		else
		{
			ob_start();
				$args = func_get_args();
				require $path;
				unset($html);
			$html = ob_get_clean();
		}
		
		$html = apply_filters( "byrd-controller-html", $html );
		
		return $html;
	}
endif;

if (!function_exists("sho_show_view")):
	/**
	 * Function prints out the sho_get_show_view()
	 * 
	 * @param string $name
	 * @see sho_get_show_view
	 */
	function sho_show_view( $name = null )
	{
		$args = func_get_args();
		unset($args[0]);
		
		echo sho_get_show_view($name, @$args[1]);
	}
endif;

if (!function_exists("sho_show_ajax")):
	
	//actions
	add_action('init', 'sho_show_ajax', 100);
	
	/**
	 * Show the Ajax
	 * 
	 * Function will return the view file without the template. This makes for easy access
	 * to the view files during an ajax call
	 * 
	 * 
	 */
	function sho_show_ajax() 
	{
		if(!isset($_REQUEST['sho_view']) || empty($_REQUEST['sho_view'])) return false;
		
		//making sure that we load the template file
		$functions = get_theme_root()."/".get_option('template').'/functions.php';
		if (file_exists($functions)) require_once $functions;
		
		$html = sho_get_show_view( $_REQUEST['sho_view'] );
		
		echo apply_filters( 'five-view-html', $html );
		die();
	}
endif;

if (!function_exists("sho_show_script")):
	/**
	 * Show the Ajax
	 * 
	 * Function will return the view file without the template. This makes for easy access
	 * to the view files during an ajax call
	 * 
	 * 
	 */
	function sho_show_script() 
	{
		//reasons to fail
		if (!isset($_REQUEST['sho_script'])) return false;
		$view = $_REQUEST['sho_script'];
		if (!isset($view) || empty($view)) return false;
		
		//making sure that we load the template file
		$functions = get_theme_root().DS.get_option('template').DS.'functions.php';
		if (file_exists($functions)) require_once $functions;
		
		if (!file_exists(dirname(__file__).DS."js".DS.$view.'.js')) return false;
		
		$content = file_get_contents(dirname(__file__).DS."js".DS.$view.'.js');    
		header("Content-type: text/javascript");
		header("Content-Length: ".strlen($content));
		
		die($content);
	}
endif;

if (!function_exists("sho_show_style")):
	/**
	 * Show the Ajax
	 * 
	 * Function will return the view file without the template. This makes for easy access
	 * to the view files during an ajax call
	 * 
	 * 
	 */
	function sho_show_style() 
	{
		//reasons to fail
		if (!isset($_REQUEST['sho_style'])) return false;
		$view = $_REQUEST['sho_style'];
		if (!isset($view) || empty($view)) return false;
		
		//making sure that we load the template file
		$functions = get_theme_root().DS.get_option('template').DS.'functions.php';
		if (file_exists($functions)) require_once $functions;
		
		if (!file_exists(dirname(__file__).DS."css".DS.$view.'.css')) return false;
		
		$content = file_get_contents(dirname(__file__).DS."css".DS.$view.'.css');    
		header("Content-type: text/css");
		header("Content-Length: ".strlen($content));
		
		die($content);
	}
endif;

if (!function_exists("sho_show_image")):
	/**
	 * Show the Ajax
	 * 
	 * Function will return the view file without the template. This makes for easy access
	 * to the view files during an ajax call
	 * 
	 * 
	 */
	function sho_show_image() 
	{
		//reasons to fail
		if (!isset($_REQUEST['sho_image'])) return false;
		$view = $_REQUEST['sho_image'];
		if (!isset($view) || empty($view)) return false;
		
		//making sure that we load the template file
		$functions = get_theme_root().DS.get_option('template').DS.'functions.php';
		if (file_exists($functions)) require_once $functions;
		
		if (!file_exists(dirname(__file__).DS."images".DS.$view)) return false;
		$fullpath = dirname(__file__).DS."images".DS.$view;
		$content = file_get_contents();    
		
		$parts = pathinfo($fullpath); 
		$ext = strtolower($parts["extension"]); 
		switch ($ext) { 
	      case "gif": $ctype="image/gif"; break; 
	      case "png": $ctype="image/png"; break; 
	      case "jpeg": 
	      case "jpg": $ctype="image/jpg"; break; 
	      default: $ctype="application/force-download"; 
	    } 
	    
	    header("Content-Type: $ctype"); 
		header("Content-Length: ".strlen($content));
		
		die($content);
	}
endif;

if (!function_exists("set_controller_path")):
	/**
	 * Function prints out the sho_get_show_view()
	 * 
	 * @param string $name
	 * @see sho_get_show_view
	 */
	function set_controller_path( $name = null )
	{
		static $controller_paths;
		
		if (!isset($controller_paths))
		{
			$controller_paths = array();
		}
		
		if (!is_null($name))
		{
			$controller_paths[$name] = $name;
		}
		
		return $controller_paths;
	}
endif;

if (!function_exists("get_theme_path")):
	/**
	 * Returns the name of the theme
	 * 
	 */
	function get_theme_path()
	{
		$templateurl = ABSPATH."wp-content".DS."themes".DS.get_option('template');
		
		return $templateurl;
	}
endif;

if (!function_exists("sho_find")):
	/**
	 * Searches the directory paths for a given file.
	 *
	 * @access	protected
	  * @param	array|string	$path	An path or array of path to search in
	 * @param	string	$file	The file name to look for.
	 * @return	mixed	The full path and file name for the target file, or boolean false if the file is not found in any of the paths.
	 * @since	1.5
	 */
	function sho_find($paths, $file)
	{
		settype($paths, 'array'); //force to array
		
		// start looping through the path set
		foreach ($paths as $path)
		{
			// get the path to the file
			$fullname = $path.DS.$file;

			// is the path based on a stream?
			if (strpos($path, '://') === false)
			{
				// not a stream, so do a realpath() to avoid directory
				// traversal attempts on the local file system.
				$path = realpath($path); // needed for substr() later
				$fullname = realpath($fullname);
			}

			// the substr() check added to make sure that the realpath()
			// results in a directory registered so that
			// non-registered directores are not accessible via directory
			// traversal attempts.
			
			if (file_exists($fullname) && substr($fullname, 0, strlen($path)) == $path) {
				return $fullname;
			}
		}

		// could not find the file in the set of paths
		return false;
	}
endif;