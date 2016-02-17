<?php
$this->load_fragment('skeleton_template/header');
?>
<article class="page full open">
    <iframe src="https://docs.google.com/forms/d/1lYltH3CDnekEL7kssQ8uHxF8_pKR7fuAlEd-ZmRYPYE/viewform?embedded=true&entry.352625283=<?= $user['name'] ?>&entry.1229900011=<?= $user['nick'] ?>&entry.2034154260=<?= $user['mail'] ?>&entry.1164380424=<?= ucfirst($user['gender']) ?>" style="width: 70%; min-width: 600px; max-width: 650px; margin:auto; display: block; height: calc(100vh - 14em);" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>
</article>
<?php
$this->load_fragment('skeleton_template/footer');
