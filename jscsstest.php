<?php 

class jscssTest extends PHPUnit_Framework_TestCase {

    // test assumes TwentyFourteen Theme
    // test requires php5.3
    public function testjs() {
        $expected = <<<'EOF'
wp_enqueue_script( 'customizer', $dir.'/js/customizer.js', array() );
wp_enqueue_script( 'featured-content-admin', $dir.'/js/featured-content-admin.js', array() );
wp_enqueue_script( 'functions', $dir.'/js/functions.js', array() );
wp_enqueue_script( 'html5', $dir.'/js/html5.js', array() );
wp_enqueue_script( 'keyboard-image-navigation', $dir.'/js/keyboard-image-navigation.js', array() );
wp_enqueue_script( 'slider', $dir.'/js/slider.js', array() );

EOF;
        $obj = new psftd_get_js();
        $output = $obj->output();
        $this->assertEquals($expected, $output);
    }

}