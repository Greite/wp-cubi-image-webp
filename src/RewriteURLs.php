<?php

namespace Globalis\WP\Cubi\ImageWebp;

class RewriteURLs
{
    /**
     * Convert an absolute URL to a webp URL.
     *
     * @param string $url
     * @return string
     */
    public static function rewriteURL($url): string
    {
        var_dump($url);die;
        $file = '';

        return $url;
    }


    /**
     * Convert multiple URL sources to webp URLs
     *
     * @internal Used by `wp_calculate_image_srcset`
     * @param string[] $sources
     * @return string[]
     */
    public static function rewriteSrcset($sources): array
    {
        if (!is_array($sources)) {
            return $sources;
        }

        return array_map(function ($source) {
            $source['url'] = self::rewriteURL($source['url']);

            return $source;
        }, $sources);
    }
}
