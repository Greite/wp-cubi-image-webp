<?php

namespace Globalis\WP\Cubi\ImageWebp;

class WpCliWebPGenerateCommand extends \WP_CLI_Command
{
    private static $imageExtensions = [
        'jpg',
        'jpeg',
        'png',
    ];

    /**
     * Generate WebP images in one or more directories.
     *
     * ## OPTIONS
     *
     * <directories>...
     * : One or more media directories to generate webp images.
     *
     * [--force=<true|false>]
     * : Force webp generation
     */
    public function __invoke($args, $params = [])
    {
        $params = wp_parse_args($params, ['force' => false]);
        $force = $params['force'] === 'true' ? true : false;
        $files = [];

        foreach ($args as $directory) {
            if (!is_dir($directory)) {
                \WP_CLI::warning(sprintf('"%s" is not a valid directory, it will be skipped.', $directory));
            } else {
                $files = array_merge($files, self::listImagesRecursively($directory));
            }
        }

        $files = array_unique($files);
        $count = count($files);

        if ($count < 1) {
            \WP_CLI::warning('No image found. Nothing was done.');
            return;
        }

        \WP_CLI::log(sprintf('Found %1$d %2$s to generate.', $count, _n('image', 'images', $count)));
        \WP_CLI::confirm('Do you want to run ?');

        $skipped       = 0;

        foreach ($files as $index => $path) {
            $gdImage = ImageWebp::generateGdImage($path);
            $newPath = $path . '.webp';

            if (ImageWebp::writeWebpImage($gdImage, $newPath, $force)) {
                \WP_CLI::log(sprintf("%s Generated image: %s", sprintf("[%s/%s]", self::formatProgress($index + 1, $count), self::formatProgress($count, $count)), $newPath));
            } else {
                $skipped++;
                \WP_CLI::log(sprintf("%s Skipped image: %s", sprintf("[%s/%s]", self::formatProgress($index + 1, $count), self::formatProgress($count, $count)), $newPath));
            }
        }

        if ($skipped > 0) {
            \WP_CLI::warning(sprintf('Skipped %s %s', $skipped, _n('image', 'images', $skipped)));
        }

        $count -= $skipped;

        if ($count > 0) {
            \WP_CLI::success(sprintf('Generated %s %s', $count, _n('image', 'images', $count)));
        } else {
            \WP_CLI::success('Done, but we could not generate webp images more');
        }
    }

    protected static function listImagesRecursively($rootPath)
    {
        if (is_dir($rootPath)) {
            $files = [];
            foreach (scandir($rootPath) as $path) {
                if (!in_array($path, ['.', '..'])) {
                    $path  = untrailingslashit($rootPath) . DIRECTORY_SEPARATOR . $path;
                    $files = array_merge($files, self::listImagesRecursively($path));
                }
            }
            return $files;
        } elseif (is_file($rootPath) && self::isImagePath($rootPath)) {
            return [$rootPath];
        } else {
            return [];
        }
    }

    public static function isImagePath($path)
    {
        $pathinfo = pathinfo($path);
        return in_array(strtolower($pathinfo['extension']), self::$imageExtensions);
    }

    protected static function formatProgress($index, $total)
    {
        static $digits;
        if (!isset($digits)) {
            $digits = strlen((string) $total);
        }
        return str_pad($index, $digits, '0', STR_PAD_LEFT);
    }
}
