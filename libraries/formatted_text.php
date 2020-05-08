<?php

// renders formatted text
/*
text styling:
#[<text>] for <H1></H1>
~[<text>] for <H3></H3>
h1-h6[] for other headlines
$[<image url>] to embed images
(<display text>)[<url>] for links
{<text>} for bold
*text* for italic
--- to separate paragraphs
§ to force a new line
*/

function render_p(string $input) {
    $output = preg_replace('/#\[(.*)\]/', '<h1>$1</h1>', $input);
    $output = preg_replace('/~\[(.*)\]/', '<h3>$1</h3>', $output);
    $output = preg_replace('/\$\[(.*)\]/', '<a href="images/$1"><img src="images/$1" style="width:30%"></a>', $output);
    $output = preg_replace('!\((.*)\)\[(.*)\]!', '<a href="$2">$1</a>', $output);
    $output = preg_replace('!\{(.*)\}!', '<b>$1</b>', $output);
    $output = preg_replace('!\*(.*)\*!', '<i>$1</i>', $output);
    $output = preg_replace('!§!', '<br>', $output);
    $output = preg_replace('/h(.)\[(.*)\]/', '<h$1>$2</h$1>', $output);
    return $output;
}

function render(string $input) {
    $paragraphs = explode('---', $input);
    $output = '';
    foreach($paragraphs as $paragraph) {
        $output .= '<p>' . render_p($paragraph) . '</p>';
    }
    return $output;
}