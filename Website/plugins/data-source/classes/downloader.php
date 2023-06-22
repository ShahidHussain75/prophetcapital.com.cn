<?php

namespace DataSource;

/**
 * Class Downloader
 * @package DataSource
 */
class Downloader {

    /**
     * Returns the singleton instance
     * @return static
     */
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Protected constructor to prevent creating a new instance
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    /**
     * Download URL (or check if it is an attachment - read file)
     * @param string $var
     * @return mixed
     */
    public function download($url)
    {
        global $wpdb;

        WP_Filesystem();
        global $wp_filesystem;

        $attachmentId = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s", $url ) );

        if ($attachmentId) {
            return $wp_filesystem->get_contents(get_attached_file($attachmentId));
        } else {
            $file = download_url($url);

            $content = $wp_filesystem->get_contents($file);
            @unlink($file);

            return $content;
        }
    }
}