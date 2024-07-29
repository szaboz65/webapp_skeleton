<?php

declare(strict_types = 1);

namespace App\Support;

/**
 * Url helper.
 */
final class UrlHelper
{
    /**
     * Get a base url wirh added link.
     *
     * @param string $add The added link
     * @param bool $atRoot Root switch
     * @param bool $atCore Core switch
     *
     * @return string
     */
    public static function urlBase(string $add = null, bool $atRoot = false, bool $atCore = false): string
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            $dir = str_replace('/src', '', $dir);

            $core = preg_split('@/@', str_replace(
                $_SERVER['DOCUMENT_ROOT'],
                '',
                (string)realpath(dirname(__FILE__))
            ), null, PREG_SPLIT_NO_EMPTY);
            $core = $core ? $core[0] : '';

            $tmplt = $atRoot ? ($atCore ? '%s://%s/%s/' : '%s://%s/') : ($atCore ? '%s://%s/%s/' : '%s://%s%s');
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf($tmplt, $http, $hostname, $end);
        } else {
            $base_url = 'http://localhost/';
        }

        return $base_url . $add;
    }

    /**
     * Get the given segment from an url.
     *
     * @param int $n The give segment index. If null then the current link
     *
     * @return mixed String if segment exists otherwise false
     */
    public static function urlSegment(int $n = null): mixed
    {
        $dir = (string)str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        if ($dir != '/') {
            $current_link = (string)str_replace($dir, '', $_SERVER['REQUEST_URI']);
        } else {
            $current_link = ltrim($_SERVER['REQUEST_URI'], '/');
        }
        if (strpos($current_link, '?')) {
            $get = (string)parse_url($current_link, PHP_URL_QUERY);
            $get = explode('&', $get);
            foreach ($get as $g) {
                $g = explode('=', $g);
                $_GET[$g[0]] = $g[1];
            }
            $current_link = (string)preg_replace('/\?.*/', '', $current_link);
        }
        $link_array = explode('/', $current_link);
        if ($n == null) {
            return $current_link;
        } elseif (is_numeric($n) && isset($link_array[$n])) {
            return $link_array[$n];
        } else {
            return false;
        }
    }
}
