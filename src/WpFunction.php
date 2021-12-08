<?php
namespace Vustech\Wplibs;

/**
 * 
 */
class WpFunction
{

    public static function saveMeta($post_id, $meta_key, $type = 'text')
    {
        if(!isset($_POST[$meta_key])) {
            return $post_id;
        }

        switch($type)
        {
            case 'int':
                $new_meta_value = intval($_POST[$meta_key]);
                break;
            case 'textarea':
                $new_meta_value = wp_kses_post($_POST[$meta_key]);
                break;
            case 'object':
                $new_meta_value = $_POST[$meta_key];
                break;
            default:
                $new_meta_value = sanitize_text_field($_POST[$meta_key]);
                break;
        }
        $meta_value = get_post_meta($post_id, $meta_key, true);
        if($new_meta_value && $new_meta_value != $meta_value) {
            update_post_meta($post_id, $meta_key, $new_meta_value);
        } elseif(!$new_meta_value) {
            delete_post_meta($post_id, $meta_key);
        }

        return $post_id;
    }
    
    public static function bootstrap_pagination($args = []) {
        $defaults = [
            'range'         => 4,
            'custom_query'  => FALSE,
            'before_output' => '<nav class="paginations"><ul class="pagination">',
            'after_output'  => '</ul></nav>'
        ];

        $args = wp_parse_args(
            $args, 
            apply_filters('vus_bootstrap_pagination_defaults', $defaults)
        );

        $args['range'] =(int) $args['range'] - 1;
        if(!$args['custom_query'])
            $args['custom_query'] = @$GLOBALS['wp_query'];
        $count =(int) $args['custom_query']->max_num_pages;
        $page  = intval(get_query_var('paged'));
        $ceil  = ceil($args['range'] / 2);
        
        if($count <= 1)
            return FALSE;
        
        if(!$page)
            $page = 1;
        
        if($count > $args['range']) {
            if($page <= $args['range']) {
                $min = 1;
                $max = $args['range'] + 1;
            } elseif($page >=($count - $ceil)) {
                $min = $count - $args['range'];
                $max = $count;
            } elseif($page >= $args['range'] && $page <($count - $ceil)) {
                $min = $page - $ceil;
                $max = $page + $ceil;
            }
        } else {
            $min = 1;
            $max = $count;
        }
        
        $pagination = '';
        $previous = intval($page) - 1;
        $previous = esc_attr(get_pagenum_link($previous));
        
        $firstpage = esc_attr(get_pagenum_link(1));
        if($firstpage &&(1 != $page))
            $pagination .= '<li class="previous"><a href="'.$firstpage.'">'.__('First', 'leo_restaurant').'</a></li>';
        if($previous &&(1 != $page))
            $pagination .= '<li><a href="'.$previous.'" title="'.__('&laquo; Previous', 'leo_restaurant').'">'.__('Previous', 'leo_restaurant').'</a></li>';
        
        if(!empty($min) && !empty($max)) {
            for($i = $min; $i <= $max; $i++) {
                if($page == $i) {
                    $pagination .= '<li class="active"><span class="active">'.(int)$i.'<span class="sr-only">(current)</span></span></li>';
                } else {
                    $pagination .= sprintf('<li><a href="%s">%d</a></li>', esc_attr(get_pagenum_link($i)), $i);
                }
            }
        }
        
        $next = intval($page) + 1;
        $next = esc_attr(get_pagenum_link($next));
        if($next &&($count != $page))
            $pagination .= '<li><a href="'.$next.'" title="'.__('Next', 'leo_restaurant').'">'.__('Next &raquo;', 'leo_restaurant').'</a></li>';
        
        $lastpage = esc_attr(get_pagenum_link($count));
        if($lastpage) {
            $pagination .= '<li class="next"><a href="'.$lastpage.'">'.__('Last', 'leo_restaurant').'</a></li>';
        }
        if(isset($pagination))
            echo $args['before_output'].$pagination.$args['after_output'];
    }
    function the_entry_date()
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

        if(get_the_time('U') !== get_the_modified_time('U'))
        {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated hidden" datetime="%3$s">%4$s</time>';
        }

        printf($time_string,
            esc_attr(get_the_date('c')),
            get_the_date(),
            esc_attr(get_the_modified_date('c')),
            get_the_modified_date()
        );
    }
}