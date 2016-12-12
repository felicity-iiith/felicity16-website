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
<!-- Scripts -->
<script src="<?= base_url() ?>static/scripts/common.js" charset="utf-8"></script>
<script src="<?= base_url() ?>static/scripts/ajaxify.js" charset="utf-8"></script>
<script src="<?= base_url() ?>static/scripts/navigation.js" charset="utf-8"></script>
<?php $this->load_fragment('google_analytics'); ?>
    </body>
</html>
<?php endif;
