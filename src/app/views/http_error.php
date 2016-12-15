<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $error_code ?><?php if (isset($message)) echo " - ", $message; ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/error.css">
</head>
<body>
    <div id="container">
    <h2><?= $error_code ?></h2>
    <div class="messages">
    <?php
    switch ($error_code) {
        case '400':
            $message = __('Invalid request!');
            $message_details = __("Please try again.</p><p>If this issue persists, then you're probably trying to repeat an action that can only be performed once.");
            break;
        case '403':
            $message = __('Forbidden!');
            $message_details = __('Sorry, you do not have permission to view this page.');
            break;
        case '404':
            $message = __('Page not found!');
            $message_details = __('Sorry, the page you were looking for could not be found.');
            break;
        default:
            $message = __('Something went wrong.');
            $message_details = sprintf(__('If this issue persists, please mail %s'),
                '<a href="mailto:help@felicity.iiit.ac.in">help@felicity.iiit.ac.in</a>.');
    }
    ?>
        <?php if (isset($message)): ?>
            <h1><?= $message ?></h1>
        <?php endif; if (isset($message_details)): ?>
            <p><?= $message_details ?></p>
        <?php endif; ?>
    </div>
    <p><a href="<?= locale_base_url() ?>">&lt;&lt; <?= __('Felicity ʼ17 Home') ?></a></p>
    <p><a href="javascript:history.back()">&lt;&lt; <?= __('Back') ?></a></p>
    </div>
</body>
</html>
