<?php
include_once 'config.php';

// Titles
$page_title = 'Species';
$title = $page_title.' - '.$site_name;

// Connect to DB
$db = getDbInstance();
?>
<!doctype html>
<html lang="pt">
<?php include_once BASE_PATH.'/modules/header.php'; ?>

<body class="bg-light">
<?php include_once 'modules/menu.php'; ?>
<div class="container-fluid" role="main">
    <div class="row">
        <div class="col-12 col-md-8">
            <?php
            // Main modules
            include_once BASE_PATH.'/modules/taxa_tree.php';
            $db->where('mm.type', 'taxa_tree');
            $db->where('mm.block', 'main');
            $db->where('mm.client', 1);
            $db->where('mm.published', 1);
            $db->orderBy('mm.order', 'asc');
            $main_modules = $db->get('modules mm');
            ?>
            <?php foreach ($main_modules as $main): ?>
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <?php
                echo '<h5>'.$main['name'].'</h5>';
                $params = json_decode($main['params']);
                taxa_recursive_tree($params->id);
                ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="col-12 col-md-4">
            <?php
            $db->where('am.type', 'file');
            $db->where('am.block', 'aside');
            $db->where('am.client', 1);
            $db->where('am.published', 1);
            $db->orderBy('am.order', 'asc');
            $aside_modules = $db->get('modules am');
            ?>
            <?php foreach ($aside_modules as $aside): ?>
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <?php
                echo '<h5>'.$aside['name'].'</h5>';
                $params = json_decode($aside['params']);
                include BASE_PATH.'/modules/'.($params->file).'.php';
                ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include_once 'modules/footer.php'; ?>
</body>
</html>
