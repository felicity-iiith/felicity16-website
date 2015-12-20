<div class="navbar" style="position:static;">
    <a class="navbar-title" href="<?= base_url() ?>sap/">Felicity ʼ16 SAP</a>
    <ul class="pull-right">
        <li><a href="<?= base_url() ?>">Felicity Home</a></li>
        <?php if (isset($logged_in) && $logged_in): ?>
            <li><a href="<?= base_url() ?>sap/portal/">Dashboard</a></li>
            <li><a href="<?= base_url() ?>sap/logout/">Logout</a></li>
        <?php else: ?>
            <li><a href="<?= base_url() ?>sap/login/">Login</a></li>
        <?php endif; ?>
    </ul>
    <a href="javascript:void(0)" class="menu-toggle-button">&equiv;</a>
    </a>
</div>
<script src="<?= base_url() ?>static/scripts/vendor/thoda.min.js"></script>
