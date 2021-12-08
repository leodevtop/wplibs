<?php

namespace Wplibs;
/**
 * 
 */
class WpHelper
{
    function __construct($args = [])
    {
        add_filter('upload_mimes', 'add_upload_mimes', 1, 1);
        add_filter('wp_get_attachment_image_attributes', 'lazy_image_attributes', 10, 2);
        add_filter('wp_get_attachment_image_attributes', 'alt_image_attributes', 10, 2);
        add_action('wp_mail_failed', 'log_mailer_errors', 10, 1);
         // [$this, $method]
    }

    public function add_upload_mimes($mime_types) {
        $new_mime_types = [
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'psd' => 'image/vnd.adobe.photoshop',
            'cdr' => 'application/cdr',
            'eps' => 'application/postscript',
        ];
        foreach ($new_mime_types as $ext => $mime) {
            if(!isset($mime_types[$ext])) {
                $mime_types[$ext] = $mime;
            }
        }
        return $mime_types;
    }

    public function lazy_image_attributes($atts, $attachment) {
        if(isset($atts['class']) && $atts['class']) {
            $c = explode(" ", $atts['class']);
            if(in_array('lazy', $c)) {
                if(isset($atts['src'])) {
                    $atts['data-src'] = $atts['src'];
                    $atts['src'] = 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=';
                }
                if(isset($atts['srcset'])) {
                    $atts['data-srcset'] = $atts['srcset'];
                    unset($atts['srcset']);
                }
            }
        }
        return $atts;
    }

    public function alt_image_attributes($atts, $attachment) {
        if(!isset($atts['alt']) || $atts['alt'] == '') {
            $atts['alt'] = esc_attr($attachment->post_title);
            $atts['alt'] = preg_replace('%\s*[-_\s]+\s*%', ' ', $atts['alt']);
        }
        return $atts;
    }

    public function log_mailer_errors($wp_error) {
        $fn = ABSPATH . '/mail.log';
        $fp = fopen($fn, 'a');
        fputs($fp, current_time('mysql') .": ". $wp_error->get_error_message() ."\n");
        fclose($fp);
    }
}