<?php //$this->load_fragment('skeleton_template/header', ['title' => __('About Us')]); ?>
<?php if (!$is_ajax): ?>
<div class="page about-content">
  <header>
      <h1>About<span class="tabheading"> Us</span></h1>
  </header>
<?php endif; ?>
    <p>
        <?= __($about_us) ?>
    </p>
<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php //$this->load_fragment('skeleton_template/footer'); ?>
