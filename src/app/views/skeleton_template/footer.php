<?php
if (empty($is_ajax)):
?>
            </div>
        </div>
    </div>
</section>
<button id="toggle" class="toggle i">
    <div class="cross">
        <div class="x"></div>
        <div class="y"></div>
    </div>
</button>
<button class="toggle-contact fb-btn" onclick="window.open('https://www.facebook.com/felicity.iiith/')">
  <i class="icon-facebook"></i>
</button>
<button class="toggle-contact youtube-btn" onclick="window.open('https://www.youtube.com/channel/UC_1vMv4Al_96QgYzkFjh99w/')">
  <i class="icon-youtube"></i>
</button>
<button class="toggle-contact instagram-btn" onclick="window.open('https://www.instagram.com/felicity.iiith/')">
  <i class="icon-instagram"></i>
</button>
<!-- Scripts -->
<script src="<?= base_url() ?>static/scripts/common.js" charset="utf-8"></script>
<script src="<?= base_url() ?>static/scripts/ajaxify.js" charset="utf-8"></script>
<script src="<?= base_url() ?>static/scripts/navigation.js" charset="utf-8"></script>
<?php $this->load_fragment('google_analytics'); ?>
    </body>
</html>
<?php endif;
