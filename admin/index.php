<?php
session_start();
require_once '../config.php';
require_once BASE_PATH.'/admin/includes/auth_validate.php';

$page_title = 'Admin Panel';
$title = $page_title.' - '.$site_name;
?>
<!doctype html>
<html lang="pt">
<?php include_once BASE_PATH.'/modules/header.php'; ?>
    
<body class="bg-light">
<?php include_once BASE_PATH.'/admin/modules/menu.php'; ?>
<div class="container-fluid">
    <!-- Toolbar -->
    <div class="toolbar sticky-top row my-2 p-2">
        <div class="col-12">
            <h4 class="float-left"><?php echo $page_title; ?></h4>
        </div>
    </div>

    <?php include_once BASE_PATH.'/admin/modules/latest_panel.php'; ?>
    <div class="row" role="main">
        <div class="col-12 col-md-4">
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <?php admin_panel_table ('Species', 'systematics_species', 'CONCAT(genus, " ", species)', 'systematics', 'systematics_specie', 'id', 5); ?>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <?php admin_panel_table ('Taxa', 'systematics_taxa', 'name', 'systematics', 'systematics_taxon', 'id', 5); ?>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <?php admin_panel_table ('Taxonomists', 'systematics_taxonomists', 'name', 'systematics', 'systematics_taxonomist', 'id', 5); ?>
            </div>
        </div>
    </div>
</div>
<?php include_once BASE_PATH.'/modules/footer.php'; ?>
</body>
</html>
