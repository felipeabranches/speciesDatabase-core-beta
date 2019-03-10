<?php $synonyms = $species->getSynonyms($species->id); ?>
<?php if($synonyms): ?>
<!-- Synonyms -->
<div class="col-12 col-md-4">
    <div class="my-3 p-3 bg-white rounded box-shadow">
        <h5><?php echo $sp_detail['name']; ?></h5>
        <ul>
        <?php foreach ($synonyms as $synonym): ?>
        <li><?php echo $species->getNomenclature($synonym['id']); ?> <?php echo $species->getAuthoring($synonym['id']); ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>
