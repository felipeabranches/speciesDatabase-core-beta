<?php
include_once 'config.php';

/*
$id = $_GET['id'];
$order_by = $_GET['order_by'];
$filter_by = 'published';
include_once 'libraries/systematics/taxonomists.php';
$taxonomists = new Taxonomists;
$result = mysqli_query($mysqli, $taxonomists->getTaxonomists($id, $order_by,$filter_by));
*/

// Connect to DB
$db = getDbInstance();

// Query
$cols = array ('tt.id id', 'tt.name name', 'tt.description description', 'tt.note note', 'tt.image image', 'tt.published published');
$taxonomists = $db->get('systematics_taxonomists tt', null, $cols);

// Titles
$page_title = 'Taxonomists';
$title = $page_title.' - '.$site_name;
?>
<!doctype html>
<html lang="pt">
<?php include_once BASE_PATH.'/modules/header.php'; ?>

<body class="bg-light <?php echo lcfirst($page_title); ?>">
<?php include_once 'modules/menu.php'; ?>
<div class="container-fluid" role="main">
    <div class="toolbar sticky-top row my-2 p-2">
        <div class="col-12">
            <h4><?php echo $page_title; ?></h4>
        </div>
    </div>

    <div class="row">
        <?php if (!$db->count): ?>
        <p>No entries</p>
        <?php else: ?>
        <?php foreach ($taxonomists as $row): ?>
        <div class="col-12 col-md-3">
            <div class="card mt-3 mb-3">
                <?php if ($row['image'] && file_exists($row['image'])): ?>
                <img class="card-img-top" src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <?php endif; ?>
                <div class="card-header">
                    <h5 class="float-left"><?php echo $row['name']; ?></h5>
                    <a href="taxonomist.php?id=<?php echo $row['id']; ?>" role="button" class="btn btn-primary btn-sm float-right">Details</a>
                </div>
                <div class="card-body">
                    <?php //echo $row['description']; ?>
                </div>
                <div class="card-footer">
                    <span class="badge badge-secondary"><?php echo $row['note']; ?></span>
                    <span class="float-right">ID#<span class="badge badge-secondary badge-pill"><?php echo $row['id']; ?></span></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php include_once 'modules/footer.php'; ?>
</body>
</html>
