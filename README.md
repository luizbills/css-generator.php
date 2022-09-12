# CSS Generator

Write CSS programatically using PHP.

## Install

```bash
composer require luizbills/css-generator
```

## Usage


### Create a generator

```php
require_once 'vendor/autoload.php';

use luizbills\CSS_Generator\Generator as CSS_Generator;

$options = [
    // default values
    // 'indent_style' => 'space', // you can change to 'tab'
    // 'indent_size' => 4 // 4 spaces by default
];

$css = new CSS_Generator( $options );

// define your css code (see below)

// output the generated code
echo "<style>" . $css->get_output() . "</style>";
```

### Add rules

```php
$css->add_rule( 'a', [ 'color' => 'red' ] );

$css->add_rule(
    [ 'p', 'div' ],
    [
        'margin' => '13px',
        'padding' => '9px'
    ]
);
```

Output:

```css
a {
    color: red;
}
p,
div {
    margin: 13px;
    padding: 9px;
}

```

### Add global variables

```php
$css->root_variable( 'color1', 'red' );
$css->add_rule( 'a', [ 'color' => 'var(--color3)' ] );
$css->root_variable( 'color2', 'green' );
$css->root_variable( 'color3', 'blue' );
```

Output:

```css
:root {
    --color1: red;
    --color2: green;
    --color3: blue;
}

a {
    color: var(--color3);
}

```

**Note:** all variables declared by `root_variable` will be placed at the beginning.

### Add comments

```php
$css->add_comment( 'Lorem ipsum...' )
```

Output:

```css
/* Lorem ipsum... */

```

### Open and close blocks

```php
$css->open_block( 'media', 'screen and (min-width: 30em)' );
$css->add_rule( 'a', [ 'color' => 'red' ] );
$css->close_block(); // close the last opened block
```

Output:

```css
@media screen and (min-width: 30em) {
    a {
        color: red;
    }
}

```

### Escape selectors

Sometimes you need to escape your selectors.

```html
<!-- Examples -->
<div id='@'></div>
<div class='3dots'></div>
<div class='red:hover'></div>
```

```php
$css->add_rule( '#' . $css->esc( '@' ), [
    'animation' => 'shake 1s'
] );
$css->add_rule( '.' . $css->esc( '3dots' ) . '::after', [
    'content' => '"..."'
] );
$css->add_rule( '.' . $css->esc( 'red:hover' ) . ':hover', [
    'color' => 'red'
] );
```

Output:

```css
#\@ {
    animation: shake 1s;
}
.\33 dots::after {
    content: "...";
}
.red\:hover:hover {
    color: red;
}

```

### Include anything (be careful)

```php
$css->add_raw( 'a{color:red}' );
```

Output:

```css
a{color:red}
```

### Minify your CSS

```php
echo $css->get_output( true ); // returns the compressed code
echo $css->get_output( false ); // returns the pretty code
```

## License

MIT License &copy; 2022 Luiz Bills
