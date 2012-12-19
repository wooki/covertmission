<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// display the players role in a twitter bootstrap label
if ( ! function_exists('nocache')) {
    function nocache($output) {
        $output->set_header("HTTP/1.0 200 OK");
        $output->set_header("HTTP/1.1 200 OK");
        $output->set_header('Expires: '.date('D, d M Y 05:00:00').' GMT');
        $output->set_header('Last-Modified: '.date('D, d M Y H:i:s').' GMT');
        $output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $output->set_header("Cache-Control: post-check=0, pre-check=0");
        $output->set_header("Pragma: no-cache");
    }
}