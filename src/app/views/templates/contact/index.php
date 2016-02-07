<?php $this->load_fragment('skeleton_template/header', ['title' => __('Contact Us')]); ?>
<?php if (!$is_ajax): ?>
<article class="page open contact">
<?php endif; ?>
    <div class="container">
        <p>
            <?= __('There are many ways of contacting us.') ?>
        </p>
        <p>
            <?= sprintf(__('You may email us at %s'), '<a target="_blank" class="underlined" href="mailto:contact@felicity.iiit.ac.in">contact@felicity.iiit.ac.in</a>') ?>
        </p>
        <p>
            <?= sprintf(
                __('Or find us on %s or %s.'),
                '<a target="_blank" href="https://www.facebook.com/felicity.iiith"><img class="social-icon" src="' . base_url() . 'static/images/fb-icon.png"> facebook</a>',
                '<a target="_blank" href="https://twitter.com/felicity_iiith"><img class="social-icon" src="' . base_url() . 'static/images/twitter-icon.png"> twitter</a>'
            ) ?>
        </p>
        <p>
            <?= __('Or bug our coordinators') ?>
        </p>
        <div class="row text-center">
            <div class="col4">
                <p>
                    <?= __('Jeevan Chowdary') ?>
                    <a target="_blank" href="https://www.facebook.com/g1.8.jeevan">
                        <img class="social-icon" src="<?= base_url() ?>static/images/fb-icon.png">
                    </a>
                </p>
                <p>
                    <a target="_blank" class="underlined" href="mailto:jeevan@felicity.iiit.ac.in">jeevan@felicity.iiit.ac.in</a>
                </p>
                <p>(+91) 9849255966</p>
            </div>
            <div class="col4">
                <p>
                    <?= __('Sanatan Mishra') ?>
                    <a target="_blank" href="https://www.facebook.com/sanatan.mishra.7">
                        <img class="social-icon" src="<?= base_url() ?>static/images/fb-icon.png">
                    </a>
                </p>
                <p>
                    <a target="_blank" class="underlined" href="mailto:sanatan@felicity.iiit.ac.in">sanatan@felicity.iiit.ac.in</a>
                </p>
                <p>(+91) 8712876675</p>
            </div>
            <div class="col4">
                <p>
                    <?= __('Vivek Ghaisas') ?>
                    <a target="_blank" href="https://www.facebook.com/vghaisas">
                        <img class="social-icon" src="<?= base_url() ?>static/images/fb-icon.png">
                    </a>
                </p>
                <p>
                    <a target="_blank" class="underlined" href="mailto:vivek@felicity.iiit.ac.in">vivek@felicity.iiit.ac.in</a>
                </p>
                <p>(+91) 9581248425</p>
            </div>
        </div>
    </div>
<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
