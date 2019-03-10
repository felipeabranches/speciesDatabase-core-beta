<?php
include_once 'config.php';

// URL parameters
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Taxonomists class
require_once BASE_PATH.'/libraries/systematics/Taxonomists.php';
$taxonomist = new Taxonomists;
$tt = $taxonomist->getTaxonomist($id);

// Titles
$page_title = (!$tt) ? 'No Taxonomist' : $tt['name'];
$title = $page_title.' - '.$site_name;
?>
<!doctype html>
<html lang="pt">
<?php include BASE_PATH.'/modules/header.php'; ?>

<body class="bg-light">
<?php include BASE_PATH.'/modules/menu.php'; ?>
<div class="container-fluid" role="main">
    <div class="toolbar sticky-top row my-2 p-2">
        <div class="col-12">
            <h4 class="float-left"><?php echo $page_title; ?></h4>
            <?php if ($tt): ?>
            <span class="float-right">ID#<span class="badge badge-secondary badge-pill"><?php echo $tt['id']; ?></span></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <?php if (!$tt): ?>
        <div class="col-12">
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <p>No entries</p>
            </div>
        </div>
        <?php else: ?>
        <div class="col-12 col-md-4">
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <?php if ($tt['image'] && file_exists($tt['image'])): ?>
                <figure class="figure">
                    <img src="<?php echo $tt['image']; ?>" alt="<?php echo $tt['name']; ?>" class="figure-img img-fluid rounded">
                    <figcaption class="figure-caption">Foto: Nome, Ano (arquivo.JPG)</figcaption>
                </figure>
                <?php endif; ?>
                <p class="badge badge-secondary"><?php echo $tt['note']; ?></p>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <?php echo $tt['description']; ?>
            </div>
        </div>

        <?php
        $sp = $taxonomist->getSpecies($id);
        if ($sp):
            $spTotal = 0;
            // Species class
            require_once BASE_PATH.'/libraries/systematics/Species.php';
            $species = new Species();
        ?>
        <div class="col-12 col-md-4">
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <h5>Species</h5>
                <table class="table table-striped table-hover table-sm small">
                    <caption>Species</caption>
                    <thead>
                        <tr>
                            <th scope="col">Species</th>
                            <th scope="col">Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sp as $row): ?>
                        <tr scope="row">
                            <td><a href="species_details.php?id=<?php echo $row['spID']; ?>" target="_blank"><?php echo $species->getNomenclature($row['spID']); ?></a></td>
                            <td><?php echo $row['year']; ?></td>
                        </tr>
                        <?php $spTotal++; ?>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr scope="row">
                            <td colspan="2">Total: <?php echo $spTotal; ?> Species</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php include BASE_PATH.'/modules/footer.php'; ?>
</body>
</html>