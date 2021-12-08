<?php
namespace Vustech\Wplibs;
/*
 $sidebar = new Sidebar([
    // id => name,
    'left_sidebar' => __('Left Sidebar'),
 ], true);
 // $sidebar->action();
 */

class Sidebar
{
    private $sidebar = [];

    function __construct($sidebar = [], $action = false) {
        $this->add($sidebar);
        if($action) {
            $this->action();
        }
    }

    public function add($id, $name = '') {
        if(empty($id)) return;
        $sidebar = [];
        if(is_string($id)) {
            $sidebar[$id] = $name;
        }
        foreach($sidebar as $k => $name) {
            /* if the sidebar exists then it will be overwride */
            $this->sidebar[$k] = $name;
        }
    }

    private function registerSidebar() {
        if(!$this->sidebar) return;
        foreach ($this->sidebar as $id => $name) {
            register_sidebar([
                'name'          => $name,
                'id'            => $id,
                'before_widget' => '<div id="%1$s" class="'.$id.'-widget widget %2$s clearfix">',
                'after_widget'  => "</div><!-- content-widget -->\n</div><!-- $id-widget -->",
                'before_title'  => '<h3 class="heading heading-widget"><span>',
                'after_title'   => "</span></h3>\n<div class=\"content-widget\">",
            ]);
        }
    }

    public function action() {
        return add_action('widgets_init', [$this, 'registerSidebar']);
    }
}