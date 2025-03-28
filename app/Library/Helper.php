<?php
// Custom helper file


use Illuminate\Support\Facades\Storage;
use App\Models\SettingsModel;

if (!function_exists('local')) {
	function local($name, $default = null)
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if (isset($_SESSION[$name])) {
			return $_SESSION[$name];
		} else {
			return $default;
		}
	};
}

if (!function_exists('vars')) {
	function vars($name, $default = null)
	{
		if (lib()->cache->__isset($name)) {
			return lib()->cache->__get($name);
		} else {
			return $default;
		}
	};
}

if (!function_exists('img_url')) {
	/**
	 * Generate a url for the images of the application.
	 *
	 * @param  string|null  $path
	 * @param  string|null  $default
	 * @param  mixed  $parameters
	 * @param  bool|null  $secure
	 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
	 */
	function img_url($path = null, $default = null, $use_cdn = true, $parameters = [], $secure = null)
	{
		if (empty($path)) {
			if (empty($default)) {
				$path = 'system/subscription/default_image.png';
			} else {
				$path = $default;
			}
		}

		// Check if the path is valid
		if (!Storage::exists($path)) {
			$path = 'system/subscription/default_image.png';
		}

		if (empty(lib()->config->cdn_base_url) || !$use_cdn) {
			return url(Storage::url(base64_encode($path)), $parameters, $secure);
		} else {
			return lib()->config->cdn_base_url . 'storage/' . base64_encode($path) . '?v=' . SettingsModel::get_arr(1)->versions_name;
		}
	}
}

if (!function_exists('img_link')) {
	/**
	 * Generate an external or internal url for the images of the application.
	 *
	 * @param  string|null  $path
	 * @param  string|null  $default
	 * @param  mixed  $parameters
	 * @param  bool|null  $secure
	 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
	 */
	function img_link($path = null, $default = null, $parameters = [], $secure = null)
	{
		if (empty($path)) {
			return null;
		} else {
			if (filter_var($path, FILTER_VALIDATE_URL) === FALSE) {
				return url(Storage::url(base64_encode($path)), $parameters, $secure);
			} else {
				return $path;
			}
		}
	}
}

if (!function_exists('enum')) {
	/**
	 * Fetch value from the configuration.
	 *
	 * @param  string  $config_key
	 * @param  mixed  $value
	 * @return string
	 */
	function enum($config_key, $value = null)
	{
		$prefix = substr($config_key, 0, 6);
		if ($prefix != 'table.') {
			$config_key = 'table.' . $config_key;
		}

		$config = config($config_key);

		// Check if configuration exists
		if (isset($config[$value])) {
			return $config[$value];
		} else {
			return null;
		}
	}
}

if (!function_exists('table')) {
	/**
	 * Get the specified configuration value.
	 *
	 * @param  string  $config_key
	 * @param  mixed  $default
	 * @return mixed|\Illuminate\Config\Repository
	 */
	function table($config_key, $search = null, $default = null)
	{
		$prefix = substr($config_key, 0, 6);
		if ($prefix != 'table.') {
			$config_key = 'table.' . $config_key;
		}

		// Get the configuration
		$config = config($config_key);

		// Check for function arguments
		if (func_num_args() == 1) {
			return $config;
		} else if (func_num_args() == 2) {
			if (is_array($config) && isset($config[$search])) {
				return $config[$search];
			}
		} else if (func_num_args() == 3) {
			if (is_array($config) && isset($config[$search])) {
				return $config[$search];
			} else {
				return $default;
			}
		}

		return null;
	}
}

if (!function_exists('asset_ver')) {
	/**
	 * Generate an asset path for the application.
	 *
	 * @param  string  $path
	 * @param  bool|null  $secure
	 * @return string
	 */
	function asset_ver($path, $secure = null)
	{
		static $app_version = null;
		if (!$app_version) {
			$app_version = SettingsModel::get_arr(1)->versions_name;
		}
		if (empty(lib()->config->cdn_base_url)) {
			return app('url')->asset($path . '?v=' . $app_version, $secure);
		} else {
			return lib()->config->cdn_base_url . $path . '?v=' . $app_version;
		}
	}
}

if (!function_exists('len')) {

	if (!isset($GLOBALS['__len'])) {
		$GLOBALS['__len'] = json_decode(json_encode(len));
	}

	/**
	 * Get length of table fields.
	 *
	 * @return object
	 */
	function len()
	{
		return $GLOBALS['__len'];
	};
}
