<?php

use luizbills\CSS_Generator\Generator as CSS_Generator;

class IndentTest extends \Codeception\Test\Unit {

    /**
     * @return void
     */
    public function test_tab_indent_unit () {
        $config = [
            'indent_style' => 'tab',
            'indent_size' => 4, // should br ignored
        ];
        $css = new CSS_Generator( $config );

        $expected = "\t";
        $actual = $css->get_indent_unit();

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     */
    public function test_space_indent_unit () {
        $config = [
            'indent_style' => 'space',
            'indent_size' => 1, // minimum is 2 spaces
        ];
        $css = new CSS_Generator( $config );

        $expected = "  ";
        $actual = $css->get_indent_unit();

        self::assertEquals( $expected, $actual );

        // test 4 spaces
        $config = [
            'indent_style' => 'space',
            'indent_size' => 4, // minimum is 2 spaces
        ];
        $css = new CSS_Generator( $config );

        $expected = "    ";
        $actual = $css->get_indent_unit();

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     */
    public function test_tab_pretty () {
        $compressed = false;
        $config = [
            'indent_style' => 'tab',
            'indent_size' => 4, // should br ignored
        ];
        $css = new CSS_Generator( $config );

        $css->add_rule( 'a', [ 'color' => 'red' ] );

        $tab = "\t";
        $expected = "a {\n{$tab}color: red;\n}\n";
        $actual = $css->get_output( $compressed );

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     */
    public function test_tab_compressed () {
        $compressed = true;
        $config = [
            'indent_style' => 'tab',
            'indent_size' => 4, // should br ignored
        ];
        $css = new CSS_Generator( $config );

        $css->add_rule( 'a', [ 'color' => 'red' ] );

        $tab = "\t";
        $expected = "a{color:red;}";
        $actual = $css->get_output( $compressed );

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     */
    public function test_space_pretty () {
        $compressed = false;
        $config = [
            'indent_style' => 'space',
            'indent_size' => 2, // should br ignored
        ];
        $css = new CSS_Generator( $config );

        $css->add_rule( 'a', [ 'color' => 'red' ] );

        $tab = "  "; // 2 spaces
        $expected = "a {\n{$tab}color: red;\n}\n";
        $actual = $css->get_output( $compressed );

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     */
    public function test_space_compressed () {
        $compressed = true;
        $config = [
            'indent_style' => 'space',
            'indent_size' => 2, // should br ignored
        ];
        $css = new CSS_Generator( $config );

        $css->add_rule( 'a', [ 'color' => 'red' ] );

        $tab = "  "; // 2 spaces
        $expected = "a{color:red;}";
        $actual = $css->get_output( $compressed );

        self::assertEquals( $expected, $actual );
    }
}