<?php $this->load_fragment('skeleton_template/header', ['title' => __('About Us')]); ?>
    <div class="page about-content">
      <header>
          <h1>About<span class="tabheading"> Us</span></h1>
      </header>
      <p class="page about-content">
          <?= __($about_us) ?>
      </p>
    </div>
<?php $this->load_fragment('skeleton_template/footer'); ?>
