<?php

/**
 * Plugin Name:         WP-Cubi Image Webp
 * Plugin URI:          https://github.com/Greite/wp-cubi-image-webp
 * Description:         Standalone image webp converter and provider WordPress plugin
 * Author:              Gauthier Painteaux, Globalis Media Systems
 * Author URI:          https://www.globalis-ms.com/
 * License:             GPL2
 *
 * Version:             0.1.0
 * Requires at least:   5.0.0
 * Tested up to:        5.9.0
 */

namespace Globalis\WP\Cubi\ImageWebp;

if (!extension_loaded('gd')) {
    return;
}

require_once __DIR__ . '/src/WonologAdaptaterLogger.php';
require_once __DIR__ . '/src/ImageWebp.php';

add_filter('wp_handle_upload', [__NAMESPACE__ . '\\ImageWebp', 'generateWebPMainFile']);
add_filter('wp_generate_attachment_metadata', [__NAMESPACE__ . '\\ImageWebp', 'generateWebPSubFile']);
add_filter('wp_delete_file', [__NAMESPACE__ . '\\ImageWebp', 'deleteWebPSubFile']);

if (!defined('WP_CUBI_IMAGE_WEBP_REWRITE_URL')) {
    define('WP_CUBI_IMAGE_WEBP_REWRITE_URL', false);
}

if (WP_CUBI_IMAGE_WEBP_REWRITE_URL && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
    require_once __DIR__ . '/src/RewriteURLs.php';
    add_filter('wp_get_attachment_url', [__NAMESPACE__ . '\\RewriteURLs', 'rewriteURL']);
    add_filter('wp_calculate_image_srcset', [__NAMESPACE__ . '\\RewriteURLs', 'rewriteSrcset']);
}

if (!class_exists('WP_CLI')) {
    return;
}

require_once __DIR__ . '/src/WpCliWebPGenerateCommand.php';

\WP_CLI::add_command('webp generate', __NAMESPACE__ . '\\WpCliWebPGenerateCommand');
