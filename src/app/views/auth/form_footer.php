        </div>
    </div>
<?php if (empty($is_ajax)): ?>
</div>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
<script>
    (function() {
        $('#toggle').removeClass('i');
        $('.toggle-contact').css('display', 'none');
    })();
</script>
