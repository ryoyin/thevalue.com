<?php

function sayHi() {
    return 'hi';
}

function isActiveMenu($needle, $haystack) {
    if(in_array($needle, $haystack)) return 'active';
}
