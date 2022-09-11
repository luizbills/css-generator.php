<?php

use luizbills\CSS_Generator\Generator as CSS_Generator;

class EscTest extends \Codeception\Test\Unit {

    /**
     * @return void
     */
    public function test () {
        $css = new CSS_Generator();

        self::assertEquals($css->esc("\0"), "\u{FFFD}" );
        self::assertEquals($css->esc("a\0"), "a\u{FFFD}" );
        self::assertEquals($css->esc("\0b"), "\u{FFFD}b" );
        self::assertEquals($css->esc("a\0b"), "a\u{FFFD}b" );

        self::assertEquals($css->esc("\u{FFFD}"), "\u{FFFD}" );
        self::assertEquals($css->esc("a\u{FFFD}"), "a\u{FFFD}" );
        self::assertEquals($css->esc("\u{FFFD}b"), "\u{FFFD}b" );
        self::assertEquals($css->esc("a\u{FFFD}b"), "a\u{FFFD}b" );

        self::assertEquals($css->esc(""), "" );

        self::assertEquals($css->esc("\x01\x02\x1E\x1F"), "\\1 \\2 \\1e \\1f " );

        self::assertEquals($css->esc("0a"), "\\30 a" );
        self::assertEquals($css->esc("1a"), "\\31 a" );
        self::assertEquals($css->esc("2a"), "\\32 a" );
        self::assertEquals($css->esc("3a"), "\\33 a" );
        self::assertEquals($css->esc("4a"), "\\34 a" );
        self::assertEquals($css->esc("5a"), "\\35 a" );
        self::assertEquals($css->esc("6a"), "\\36 a" );
        self::assertEquals($css->esc("7a"), "\\37 a" );
        self::assertEquals($css->esc("8a"), "\\38 a" );
        self::assertEquals($css->esc("9a"), "\\39 a" );

        self::assertEquals($css->esc("a0b"), "a0b" );
        self::assertEquals($css->esc("a1b"), "a1b" );
        self::assertEquals($css->esc("a2b"), "a2b" );
        self::assertEquals($css->esc("a3b"), "a3b" );
        self::assertEquals($css->esc("a4b"), "a4b" );
        self::assertEquals($css->esc("a5b"), "a5b" );
        self::assertEquals($css->esc("a6b"), "a6b" );
        self::assertEquals($css->esc("a7b"), "a7b" );
        self::assertEquals($css->esc("a8b"), "a8b" );
        self::assertEquals($css->esc("a9b"), "a9b" );

        self::assertEquals($css->esc("-0a"), "-\\30 a" );
        self::assertEquals($css->esc("-1a"), "-\\31 a" );
        self::assertEquals($css->esc("-2a"), "-\\32 a" );
        self::assertEquals($css->esc("-3a"), "-\\33 a" );
        self::assertEquals($css->esc("-4a"), "-\\34 a" );
        self::assertEquals($css->esc("-5a"), "-\\35 a" );
        self::assertEquals($css->esc("-6a"), "-\\36 a" );
        self::assertEquals($css->esc("-7a"), "-\\37 a" );
        self::assertEquals($css->esc("-8a"), "-\\38 a" );
        self::assertEquals($css->esc("-9a"), "-\\39 a" );

        self::assertEquals($css->esc("-"), "\\-" );
        self::assertEquals($css->esc("-a"), "-a" );
        self::assertEquals($css->esc("--"), "--" );
        self::assertEquals($css->esc("--a"), "--a" );

        self::assertEquals($css->esc("--a"), "--a" );
        self::assertEquals($css->esc(".class"), "\.class" );
        self::assertEquals($css->esc("#id"), "\#id" );

        self::assertEquals($css->esc("\x80\x2D\x5F\xA9"), "\x80\x2D\x5F\xA9" );
        self::assertEquals($css->esc("\x7F\x80\x81\x82\x83\x84\x85\x86\x87\x88\x89\x8A\x8B\x8C\x8D\x8E\x8F\x90\x91\x92\x93\x94\x95\x96\x97\x98\x99\x9A\x9B\x9C\x9D\x9E\x9F"), "\\7f \x80\x81\x82\x83\x84\x85\x86\x87\x88\x89\x8A\x8B\x8C\x8D\x8E\x8F\x90\x91\x92\x93\x94\x95\x96\x97\x98\x99\x9A\x9B\x9C\x9D\x9E\x9F" );
        self::assertEquals($css->esc("\xA0\xA1\xA2"), "\xA0\xA1\xA2" );
        self::assertEquals($css->esc("a0123456789b"), "a0123456789b" );
        self::assertEquals($css->esc("abcdefghijklmnopqrstuvwxyz"), "abcdefghijklmnopqrstuvwxyz" );
        self::assertEquals($css->esc("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), "ABCDEFGHIJKLMNOPQRSTUVWXYZ" );

        self::assertEquals($css->esc("\x20\x21\x78\x79"), "\\ \\!xy" );

        // astral symbol (U+1D306 TETRAGRAM FOR CENTRE)
        self::assertEquals($css->esc("\u{D834}\u{DF06}"), "\u{D834}\u{DF06}" );
        // lone surrogates
        self::assertEquals($css->esc("\u{DF06}"), "\u{DF06}" );
        self::assertEquals($css->esc("\u{D834}"), "\u{D834}" );
    }
}