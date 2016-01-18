<!DOCTYPE html>
<html>
<head>
    <title>Scoreboard · <?= $name ?> · Threads  · Felicity</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $name ?> Threads Felicity ">
    <meta name="keywords" content="felicity, threads, <?= $name ?>, math, logic, iiit, iiit hyderabad">
    <meta property="og:title" content="Scoreboard · <?= $name ?> · Threads  · Felicity">
    <meta property="og:image" content="<?= base_url() . (isset($og_image) ? $og_image : 'files/16/logos/felicity16-logo-large.png') ?>">

    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/normalize.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pure/0.6.0/pure-min.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Flamenco|Noto+Sans">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <style>
* {
    box-sizing: border-box;
}

body {
    overflow-y: scroll;
    font-family: "Noto Sans", sans-serif;
    background-image: url('https://felicity.iiit.ac.in/static/images/bg.jpg');
    background-size: cover;
    background-position: 0 0;
    background-attachment: fixed;
    background-color: #02141E;
    transition: background-position $page-open-animation-timeout;
    color: #000;
}

.clearfix:before,
.clearfix:after {
    display: table;
    content: ' ';
}

.clearfix:after {
    clear: both;
}

a {
    outline: none;
    text-decoration: none;
    cursor: pointer;
    color: #1F8DD6;
}

a:hover {
    color: #055DA0;
    border-bottom: 1px solid;
}

a.underlined {
    border-bottom: 1px solid;
}

a.underlined:hover {
    border-bottom-style: dashed;
}

hr {
    margin: 1.2em 0;
}

.container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
    max-width: 1200px;
}

.some-top-margin {
    margin-top: 1em;
}

.text-left {
    text-align: left;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

.text-justify {
    text-align: justify;
}

.small {
    font-size: .9em;
}

.btn {
    display: inline-block;
    background-color: white;
    border: 1px solid rgb(38, 40, 61);
    color: rgb(38, 40, 61);
    font-weight: normal;
    padding: .5em 1.5em;
    transition: all .3s;
}

a.btn {
    color: rgb(38, 40, 61);
}

.btn:hover {
    color: #fff;
    background-color: rgb(38, 40, 61);
    border: 1px solid rgb(19, 20, 30);
}

.btn.large {
    font-size: 1.5em;
}

.block {
    display: block;
}

.inline-block {
    display: inline-block;
}

p {
    margin: 0 0 10px;
    line-height: 1.2;
}

.lead {
    margin-bottom: 20px;
    font-size: 1.2em;
    font-weight: 300;
    line-height: 1.4;
}

.msg {
    padding: 1em;
    border-radius: 3px;
    background-color: #ccc;
}


.msg.blue {
    background-color:#0078e7;
    color: #fff;
}

.msg.red {
    background-color: #CA3C3C;
    color: #fff;
}

.msg.orange {
    background-color: #F37B1D;
    color: #fff;
}

.msg.green {
    background-color: #159831;
    color: #fff;
}

.header {
    text-align: center;
    font-family: Flamenco, sans-serif;
    padding-top: .3em;
}

.header-content {
    display: inline-block;
}

.title,
.year {
    margin: 0;
}

.title {
    font-size: 3em;
    text-transform: lowercase;
}

.title a {
    color: inherit;
}

.title a:hover {
    border-bottom: 0;
}

.year {
    text-align: right;
    font-weight: bold;
    font-size: 1em;
}

.wrapper {
    padding-top: 1em;
    padding-bottom: 2em;
}


.title-and-nav-container {
    text-align: right;
    padding-bottom: 1em;
}

.page-title {
    display: inline-block;
    float: left;
    border-left: 2px solid;
    border-bottom: 2px solid;
    padding-left: 1em;
    color: #26283D;
}

.page-title h1 {
    margin: 0;
}

.page-title h1 a {
    color: black;
}

.page-title h1 a:hover {
    border-bottom: 0;
}

h2 {
    text-shadow: 0 0 2px white;
}

.question-table,
.score-table {
    text-align: center;
    margin: 1em auto;
    max-width: 100%;
}

td.question-title {
    min-width: 400px;
}

td.user-nick {
    min-width: 200px;
}

.question-table a {
    padding: 1px;
    border-bottom: 1px solid transparent;
}

.question-table a:hover {
    padding: 1px;
    border-bottom: 1px solid;
}

.question-table .msg {
    font-size: .9em;
    width: 100%;
    font-family: inherit;
    padding: 0.5em 1em;
    border: 0px none transparent;
    text-decoration: none;
    border-radius: 2px;
    display: inline-block;
    line-height: normal;
    white-space: nowrap;
    vertical-align: middle;
    text-align: center;
    -moz-user-select: none;
    box-sizing: border-box;
}

.dark-stripe {
    background-color: rgb(220, 235, 250);
}

.question-image {
    max-width: 100%;
    display: block;
    margin: 1em auto;
}

.comment {
    margin-top: 7px;
    margin-bottom: 7px;
    padding-left: 5px;
    border-bottom: 2px solid #035a91;
    border-left: 2px solid #035a91;
}
.comment .usernick {
    line-height: 40px;
    font-size: 1.2em;
    font-style: italic;
}
.comment .comment-text {
    padding-top: 5px;
}
.comment .time {
    text-align: right;
}

.my-bg {
    padding-bottom: 1em;
}

.my-bg,
.my-table {
    background-color: rgba(230, 230, 255, .95);
    background-color: rgba(240, 250, 255, 0.85);
}

.my-table {
    margin-left: auto;
    margin-right: auto;
    max-width: 100%;
    padding-bottom: 1em;
}

.my-table.full {
    width: 100%;
}

.my-table,
.my-table tbody,
.my-table td,
.my-table th {
    border-color: transparent;
    border: 0;
}

.my-table-header {
    background-color: rgba( 93, 188, 210, .7);
    background-color: rgba(250, 210,  50, .8);
    padding: 0.5em 1em;
    font-weight: bold;
}

.question-table {
    background-color: rgba(240, 250, 255, .85);
}

.question-table tbody {
    border-top: 1px solid;
}

@media (max-width: 44rem) {
    .header {
        margin-top: 2.2em;
    }

    .title-and-nav-container {
        text-align: center;
    }

    .page-title {
        float: none;
        margin-bottom: 1em;
    }

    nav {
        width: 100%;
        text-align: center;
    }

    td.question-title,
    td.user-nick {
        min-width: auto;
    }

    .question-table .msg {
        font-size: .8em;
    }

    .my-bg {
        padding: 1em;
    }
}
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1 class="title"><a href="<?= base_url() ?>">Felicity</a></h1>
            <p class="year">2016</p>
        </div>
    </header>
    <div class="wrapper">
        <div class="container title-and-nav-container clearfix">
            <div class="page-title text-left">
                <h1><a href="<?= base_url() . $contest_page_link ?>"><?= $name ?></a></h1>
                <p><?= $tagline ?></p>
            </div>
        </div>
        <div class="container">
            <h2 class="text-center">Final Scoreboard</h2>
            <div style="margin: 2em;" class="text-center">
                <span class="my-bg msg inline-block">
                    Congratulations to all the winners!
                    <?php if (!empty($explanations_link)): ?>
                        <br>
                        <a target="_blank" href="<?= base_url() . $explanations_link ?>">Link to explanations</a>
                    <?php endif; ?>
                </span>
            </div>
            <div>
                <table border="0" class="some-top-margin pure-table score-table my-table">
                    <thead>
                        <tr class="my-table-header">
                            <th>Rank</th>
                            <th class="text-center">Nick</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <script type="text/javascript">
        $(function(){
            var $tbody = $('tbody').empty();
            $.get('<?= base_url() . $json_link ?>', function(results) {
                $.each(results, function(i, row) {
                    var n = $('<tr>')
                    .append( $('<td>').text(  i+1 ) )
                    .append( $('<td class="user-nick">').html(row[0]) )
                    .append( $('<td>').text(row[1]) )
                    .appendTo( $tbody );
                });
            });
        });
        </script>
    </div>
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-57607777-1', 'auto');
    ga('send', 'pageview');
	</script>
</body>
</html>
