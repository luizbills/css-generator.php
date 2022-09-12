<?php

use luizbills\CSS_Generator\Generator as CSS_Generator;

class VarTest extends \Codeception\Test\Unit {

    /**
     * @return void
     */
    public function test () {
        $compressed = false;
        $css = new CSS_Generator();

        $css->root_variable( 'foo', 'bar' );
        $css->add_rule( 'a', [ 'color' => 'red' ] );
        $css->root_variable( 'x', 'y' );

        $tab = $css->get_indent_unit();
        $expected = ":root {\n$tab--foo: bar;\n$tab--x: y;\n}\n\na {\n{$tab}color: red;\n}\n";
        $actual = $css->get_output( $compressed );

        self::assertEquals( $expected, $actual );
    }
}