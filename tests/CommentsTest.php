<?php

use luizbills\CSS_Generator\Generator as CSS_Generator;

class CommentsTest extends \Codeception\Test\Unit {

    // protected function _before () {
    // }

    // protected function _after () {
    // }

    // tests
    public function test_pretty () {
        $compressed = false;
        $css = new CSS_Generator();

        $css->add_comment( 'a' );
        $css->add_comment( 'b' );

        $expected = "/* a */\n/* b */\n";
        $actual = $css->get_output( $compressed );

        $this->assertEquals( $expected, $actual );
    }

    public function test_compressed () {
        $compressed = true;
        $css = new CSS_Generator();

        $css->add_comment( 'a' );
        $css->add_comment( 'b' );

        $expected = "/* a *//* b */";
        $actual = $css->get_output( $compressed );

        $this->assertEquals( $expected, $actual );
    }
}