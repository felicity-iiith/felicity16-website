<?php
if (!$is_ajax):
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>Felicity ʼ16</title>
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/normalize.min.css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/core.css" media="screen" title="no title" charset="utf-8">
    <link href='http://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Flamenco|Noto+Sans' rel='stylesheet' type='text/css'>
</head>
<body<?= empty($path) ? '' : ' class="page-open"' ?>>
    <article class="landing">
        <div class="landing-content">
            <p class="iiit-h">
                <img src="<?= base_url() ?>static/images/iiit-logo.png" alt="IIIT H Logo"> IIIT-H
            </p>
            <p class="presents">
                presents
            </p>
            <h1 class="title"><a href="<?= base_url() ?>">Felicity</a></h1>
            <p class="year">2016</p>
            <p class="tagline">Where magic happens</p>
            <p class="dates">February 19<sup>th</sup>, 20<sup>th</sup> and 21<sup>st</sup></p>
        </div>
    </article>
    <div class="content-holder">
<?php endif;
