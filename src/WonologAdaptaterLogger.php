<?php

namespace Globalis\WP\Cubi\ImageWebp;

class WonologAdaptaterLogger extends \Psr\Log\AbstractLogger
{
    public function log($level, $message, array $context = [])
    {
        if (!defined('Inpsyde\Wonolog\LOG')) {
            return;
        }

        $channel = apply_filters('wp-cubi-image-webp\wonolog_channel', \Inpsyde\Wonolog\Channels::DEBUG);

        do_action('wonolog.log', $message, $level, $channel, $context);
    }
}
