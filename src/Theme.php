<?php

namespace Vustech\Wplibs;

class Theme {

    function __construct($args = []) {
        $this->init($args);
    }

    function init($args = []) {
        $this->textdomain = $args['textdomain'];
        $this->support = $args['support'];
        $this->postTypeSupport = $args['postTypeSupport'];
        $this->menus = $args['menus'];
        $this->scripts = $args['scripts'];
        $this->sidebars = $args['sidebars'];
    }

    public function run() {
        add_action('after_setup_theme', [$this, 'setup']);
    }

    private function setup() {
        $this->removeGenerator();
        $this->loadLanguage($this->textdomain);
        $this->addSupport($this->support);
        $this->addPostTypeSupport($this->postTypeSupport);
        $this->registerMenu($this->menus);
        $this->enqueueScripts($this->scripts);
        $this->registerSidebar($this->sidebars);
    }

    public function removeGenerator() {
        return remove_action('wp_head', 'wp_generator');
    }

    public function loadLanguage($textdomain = '', $path = '') {
        if(!$path) $path = get_template_directory(). '/languages';
        load_theme_textdomain($textdomain, $path);
    }

    public function addSupport($features = []) {
        foreach($features as $k => $v) {
            $feature = $v;
            $args = [];
            if(is_string($k)) {
                $feature = $k;
                $args = $v;
            }
            add_theme_support($feature, $args);
        }
    }

    public function addPostTypeSupport($features = []) {
        foreach($features as $k => $v) {
            $feature = $v;
            $args = [];
            if(is_string($k)) {
                $feature = $k;
                $args = $v;
            }
            add_post_type_support($feature, $args);
        }
    }

    public registerMenu($menus = []) {
        return register_nav_menus($menus);
    }

    public function enqueueScripts($scripts = []) {
        $action = true;
        return new \vustech\Wplibs\EnqueueScripts($scripts, $action);
    }

    public static cacheHeader() {
        Header("HTTP/1.0 200 OK");
        Header("HTTP/1.1 200 OK");
        // Header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
        Header("Cache-Control: no-store, no-cache, must-revalidate");
        Header("Cache-Control: post-check=0, pre-check=0");
        Header("Pragma: no-cache");
    }

    public function registerSidebar($sidebars) {
        $action = true;
        return new \vustech\Wplibs\Sidebar($sidebars, $action);
    }

    public function addTermImageFeatured($taxes = []) {
        return new \vustech\Wplibs\TermMeta($taxes, 'featured_image');
    }
    public function addPostMetaImages($postTypes = [], $metas = []) {
        return new \vustech\Wplibs\PostMeta($postTypes, $metas);
    }
}