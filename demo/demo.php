<?php

require_once __DIR__ . '/../vendor/autoload.php';

use luizbills\CSS_Generator\Generator as CSS_Generator;

$options = [];
$css = new CSS_Generator( $options );

$css->root_variable( 'red', '#f00' );

$css->add_rule(
    [
        '.red',
        '.col-red',
        '.col_red',
        '.c:red',
        '.-red',
        '.33',
        '.❤️'
    ],
    [
        'color' => 'var(--red)',
        'font-weight' => 700,
    ]
);

$css->open_block( 'media', 'screen' );

$css->add_rule(
    [
        '.red',
    ],
    [
        '--red' => 'blue'
    ]
);

// $css->close_block();
?>

<style>
    pre {
        border: 1px solid #ddd;
        background-color: #eee;
        padding: 1em;
    }
</style>

<style><?= $css->get_output( isset( $_GET['minify'] ) ); ?></style>

<pre><?= $css->get_output( true ); ?></pre>
<pre><?= $css->get_output( false ); ?></pre>

<div class="col-red">.col-red</div>
<div class="col_red">.col_red</div>
<div class="red">.red</div>
<div class="c:red">.c:red</div>
<div class="-red">.-red</div>
<div class="33">.33</div>
<div class="❤️">.❤️</div>
