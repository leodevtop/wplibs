<?php

namespace Vustech\Wplibs;

class EnqueueScripts {
    public $cdn = true;
    public $locale = '';

    private $scripts = [];
    private $defaultScripts = [
        'bootstrap' => [
            'css' => [],
            'js' => [],
            'cdn' => [
                'version' => '3.4.1',
                'css' => ['https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/%1$s/css/bootstrap.min.css'],
                'js' => ['https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/%1$s/js/bootstrap.min.js'],
            ],
            'footer' => true,
        ],
        'font-awesome' => [
            'css' => [],
            'js' => [],
            'cdn' => [
                'version' => '4.7.0',
                'css' => ['https://cdnjs.cloudflare.com/ajax/libs/font-awesome/%1$s/css/font-awesome.css'],
                'js' => [],
            ],
            'footer' => true,
        ],
        'owl-carousel' => [
            'css' => [],
            'js' => [],
            'cdn' => [
                'version' => '2.2.1',
                'css' => ['https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/%1$s/assets/owl.carousel.min.css'],
                'js' => ['https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/%1$s/owl.carousel.min.js'],
            ],
            'footer' => true,
        ],
        'jquery-ui' => [
            'css' => [],
            'js' => [],
            'cdn' => [
                'version' => '1.12.1',
                'css' => ['https://cdnjs.cloudflare.com/ajax/libs/jqueryui/%1$s/themes/smoothness/jquery-ui.min.css', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/%1$s/themes/smoothness/theme.min.css'],
                'js' => ['https://cdnjs.cloudflare.com/ajax/libs/jqueryui/%1$s/jquery-ui.min.js'],
            ],
            'footer' => false,
        ],
        'animate' => [
            'css' => [],
            'js' => [],
            'cdn' => [
                'version' => '3.5.2',
                'css' => ['https://cdnjs.cloudflare.com/ajax/libs/animate.css/%1$s/animate.min.css'],
                'js' => [],
            ],
            'footer' => true,
        ],
        'jquery-validate' => [
            'css' => [],
            'js' => [],
            'cdn' => [
                'version' => '1.19.1',
                'css' => [],
                'js' => ['https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/%1$s/jquery.validate.min.js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/%1$s/additional-methods.min.js','https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/%1$s/localization/messages_%2$s.min.js'],
            ],
            'footer' => true,
        ],
        'parallax' => [
            'css' => [],
            'js' => [],
            'cdn' => [
                'version' => '1.5.0',
                'css' => [],
                'js' => ['https://cdnjs.cloudflare.com/ajax/libs/parallax.js/%1$s/parallax.min.js'],
            ],
            'footer' => true,
        ],
        'jquery-elevatezoom' => [
            'css' => [],
            'js' => [],
            'cdn' => [
                'version' => '3.0.8',
                'css' => [],
                'js' => ['https://cdnjs.cloudflare.com/ajax/libs/elevatezoom/%1$s/jquery.elevatezoom.min.js'],
            ],
            'footer' => true,
        ],
        'bootstrap-notify' => [
            'css' => [],
            'js' => [],
            'cdn' => [
                'version' => '0.2.0',
                'css' => ['https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/%1$s/css/bootstrap-notify.min.css'],
                'js' => ['https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/%1$s/js/bootstrap-notify.min.js'],
            ],
            'footer' => true,
        ],
        'readmore' => [
            'css' => [],
            'js' => [],
            'cdn' => [
                'version' => '2.2.1',
                'css' => [],
                'js' => ['https://cdnjs.cloudflare.com/ajax/libs/Readmore.js/%1$s/readmore.min.js'],
            ],
            'footer' => true,
        ],
    ];

    function __construct($scripts = [], $action = false) {
        $this->locale = get_locale();
        if(!empty($scripts)) {
            $this->scripts = array_merge($this->scripts, $scripts);
        }
        if($action) {
            $this->action();
        }
    }

    public function add($scripts) {
        if(!$scripts) return;
        if(!is_string($scripts)) {
            $scripts = [$scripts];
        }
        $this->scripts = array_merge($this->scripts, $scripts);
    }

    public function enqueue() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('jquery-ui-datepicker');
        foreach ($this->scripts as $id) {
            if(isset($this->defaultScripts[$id])) {
                $item = (object) $this->defaultScripts[$id];
                if($this->cdn && !empty($item->cdn)) {
                    $item->css = $item->cdn['css'];
                    $item->js = $item->cdn['js'];
                }
                foreach($item->css as $k=>$css) {
                    $css = sprintf($css, $item->version, $this->locale)
                    wp_enqueue_style($id.'-'.$k, $css, [], $item->version);
                }
                foreach($item->js as $k=>$js) {
                    $js = sprintf($js, $item->version, $this->locale)
                    wp_enqueue_script($id.'-'.$k, $js, null, $item->version, $item->footer);
                }
            }
            unset($this->scripts[$id]);
        }
    }

    public function action() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }
}