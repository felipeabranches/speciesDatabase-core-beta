<?php
include_once 'config.php';

// Species class
require_once BASE_PATH.'/libraries/Systematics/Species.php';
$species = new Species();

// Get ID from URL
$species->id = $_GET['id'];

// Titles
$page_title = $species->getNomenclature($species->id, 1);
$title = $page_title.' - '.$site_name;
?>
<!doctype html>
<html lang="pt">
<?php include_once BASE_PATH.'/modules/header.php'; ?>

<body class="bg-light species">
<?php include_once BASE_PATH.'/modules/menu.php'; ?>
<div class="container-fluid" role="main">
    <div class="toolbar sticky-top row my-2 p-2">
        <div class="col-12">
            <!-- Nomenclature -->
            <h4 class="nomenclature"><?php echo $species->getNomenclature($species->id); ?></h4>
            <!-- Authoring -->
            <span class="authoring"><?php echo $species->getAuthoring($species->id); ?></span>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-8">
            <?php if (!$species): ?>
            <!-- Not Found Alert -->
            <div class="alert alert-warning my-3" role="alert">
                <h5>No result!</h5>
                <p>You are trying to reach a species that don't have a record on our database.</p>
                <p>There's some actions you may take:</p>
                <ul>
                    <li>Check if the passed ID on browser url realy exists</li>
                    <li>Return to the <a href="index.php" class="alert-link">species list</a> and choose the desire species.</li>
                </ul>
                <hr>
                <p class="mb-0">If you think that it's not your mistake, enter in <a href="mailto:peixespnsc@gmail.com" class="alert-link">contact</a> and let us know.</p>
            </div>
            <?php elseif (!$species->validateSpecies($species->id)): ?>
            <!-- Synonym Alert -->
            <div class="alert alert-info my-3" role="alert">
                <h5>Synonym</h5>
                <p>This is synonym Species for .</p>
                <hr>
                <p class="mb-0">If you don't click in the link below, you'll be redirect in 5 seconds.<br /><a href="#" class="alert-link">Species</a></p>
            </div>
            <?php else: ?>
            <?php $details = $species->getDetails($species->id); ?>
            <!-- Details -->
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <?php if ($details['image'] && file_exists($details['image'])): ?>
                <!-- Image -->
                <figure class="figure">
                    <img src="<?php echo $details['image']; ?>" alt="<?php echo $species->getNomenclature($species->id, 1); ?>" class="figure-img img-fluid rounded" />
                    <figcaption class="figure-caption">Foto: Nome, Ano (arquivo.JPG)</figcaption>
                </figure>
                <?php endif; ?>
                
                <?php if ($details['etymology'] || $details['common_name'] || $details['distribution'] || $details['habitat']): ?>
                <!-- Others infos -->
                <dl>
                    <?php if ($details['etymology']): ?>
                    <!-- Etymology -->
                    <dt>Etimologia</dt><dd><?php echo $details['etymology']; ?></dd>
                    <?php endif; ?>
                    <?php if ($details['common_name']): ?>
                    <!-- Common Name -->
                    <dt>Nome popular</dt><dd><?php echo $details['common_name']; ?></dd>
                    <?php endif; ?>
                    <?php if ($details['distribution']): ?>
                    <!-- Distribution -->
                    <dt>Distribuição</dt><dd><?php echo $details['distribution']; ?></dd>
                    <?php endif; ?>
                    <?php if ($details['habitat']): ?>
                    <!-- Habitat -->
                    <dt>Habitat</dt><dd><?php echo $details['habitat']; ?></dd>
                    <?php endif; ?>
                </dl>
                <?php endif; ?>

                <?php if ($details['description']): ?>
                <!-- Description -->
                <?php echo $details['description']; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php
        $db = getDbInstance();
        $db->where('sd.block', 'species_details');
        $db->where('sd.client', 1);
        $db->where('sd.published', 1);
        $db->orderBy('sd.order', 'asc');
        $sp_details = $db->get('modules sd');
        ?>
        <?php foreach ($sp_details as $sp_detail): ?>
            <?php
            $params = json_decode($sp_detail['params']);
            include BASE_PATH.'/modules/'.($params->file).'.php';
            ?>
        <?php endforeach; ?>
    </div>
</div>
<?php include_once BASE_PATH.'/modules/footer.php'; ?>
<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
</script>
</body>
</html>
