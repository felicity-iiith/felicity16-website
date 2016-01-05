<?php
function linkify($text) {
    return preg_replace('/(https?:\/\/[^\s,<)]*)/', '<a target="_blank" href="$1">$1</a>', $text);
}
