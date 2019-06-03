<?php
session_start();
require_once '../../config.php';
require_once BASE_PATH.'/admin/includes/auth_validate.php';

// Species class
require_once BASE_PATH.'/libraries/Systematics/Species.php';
$species = new Species();

//Pagination class
require_once BASE_PATH.'/libraries/HTML/pagination.php';
$paginationClass = new Pagination();
// Titles
$page_title = 'Species';
$title = $page_title.' - '.$site_name;

// Get Input data from query string
$search_string = filter_input(INPUT_GET, 'search_string');
$order_by = filter_input(INPUT_GET, 'order_by');
$order_dir = filter_input(INPUT_GET, 'order_dir');

// Get current page.
$page = filter_input(INPUT_GET, 'page');

// Per page limit for pagination.
$pagelimit = 20;
if (!$page)
{
    $page = 1;
}

// If filter types are not selected we show latest created data first
if (!$order_by)
{
    $order_by = 'id';
}
if (!$order_dir)
{
    $order_dir = 'desc';
}

// Get DB instance. i.e instance of MYSQLiDB Library
$db = getDbInstance();
$cols = array('sp.id id', 'sp.incertae_sedis incertae_sedis', 'sp.validate validate', 'sp.redirect redirect', 'sp.published published', 'tx.name taxon');
$db->join('systematics_taxa tx', 'tx.id = sp.id_taxon', 'LEFT');
$db->orderBy($order_by, $order_dir);

// Start building query according to input parameters.
// If search string
if ($search_string)
{
    $db->where('name', '%'.$search_string.'%', 'like');
}

// If order by option selected
if ($order_dir)
{
    $db->orderBy($order_by, $order_dir);
}

// Set pagination limit
$db->pageLimit = $pagelimit;

// Get result of the query.
$path_file='systematics_species';
$result = $db->arraybuilder()->paginate($path_file.' sp', $page, $cols);
$pagination = $db->totalPages;

// Get columns for order filter
foreach ($result as $value) {
    foreach ($value as $col_name => $col_value) {
        $filter_options[$col_name] = $col_name;
    }
    // Execute only once
    break;
}
?>
<!doctype html>
<html lang="pt">
<?php include_once BASE_PATH.'/modules/header.php'; ?>

<body class="bg-light <?php echo lcfirst($page_title); ?>">
<?php include_once BASE_PATH.'/admin/modules/menu.php'; ?>
<div class="container-fluid" role="main">
    <!-- Toolbar -->
    <div class="toolbar sticky-top row my-2 p-2">
        <div class="col-12">
            <h4 class="float-left"><?php echo $page_title; ?></h4>
            <div class="float-right">
                <a href="systematics_specie.php?task=new&id=0" class="btn btn-primary btn-sm" role="button"><i class="fas fa-plus"></i>New</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="my-3 p-3 bg-white rounded box-shadow">
                <?php include '../includes/flash_messages.php'; ?>
                <?php if (!$db->count): ?>
                <span>No entries</span>
                <?php else: ?>
                <!-- Filters -->
                <form class="form-inline mb-3" action="">
                    <label class="sr-only">Search</label>
                    <input type="text" class="form-control mr-2" name="search_string" value="<?php echo $search_string; ?>" placeholder="Search">
                    <label for="input_order" class="mr-2">Order By</label>
                    <select name="order_by" class="form-control mr-2">
                        <?php
                        foreach ($filter_options as $option):
                            ($order_by === $option) ? $selected = 'selected' : $selected = '';
                            echo ' <option value="'.$option.'" '.$selected.'>'.$option.'</option>';
                        endforeach;
                        ?>
                    </select>
                    <select name="order_dir" class="form-control mr-2" id="input_order">
                        <option value="Asc" <?php
                        if ($order_dir == 'Asc') {
                            echo 'selected';
                        }
                        ?>>Asc</option>
                        <option value="Desc" <?php
                        if ($order_dir == 'Desc') {
                            echo 'selected';
                        }
                        ?>>Desc</option>
                    </select>
                    <input type="submit" value="Go" class="btn btn-primary">
                </form>

                <!-- Table -->
                <table class="table table-striped table-hover table-sm">
                    <caption><?php echo $page_title; ?></caption>
                    <thead>
                        <tr width="100%">
                            <th width="5%"><a href="systematics_species.php?order_by=id">ID</a></th>
                            <th width="45%"><a href="systematics_species.php?order_by=genus,specie">Species</a></th>
                            <th width="22.5%"><a href="systematics_species.php?order_by=taxon,genus,specie">Taxon</a></th>
                            <th width="22.5%"><a href="systematics_species.php?order_by=validate">Validate</a></th>
                            <th width="5%" colspan="2"><a href="systematics_species.php?order_by=published">State</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row): ?>
                        <?php
                        // Incertae sedis
                        $incertae_sedis = (!$row['incertae_sedis']) ? '' : ' >> <em>Incertae serdis</em>';
                        // Validade
                        if (!$row['validate']):
                            $redirect = (!$row['redirect']) ? '' : ' (ID: '.$row['redirect'].')';
                            $validate='<span class="badge badge-warning">Synonym'.$redirect.'</span>';
                        else:
                            $validate='<span class="badge badge-info">Accepted</span>';
                        endif;
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <a href="systematics_specie.php?task=edit&id=<?php echo $row['id']; ?>"><?php echo $species->getNomenclature($row['id']); ?></a>
                                <?php if ($species->getAuthoring($row['id'])): ?>
                                <span class="badge badge-dark"><?php echo $species->getAuthoring($row['id']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['taxon'].$incertae_sedis; ?></td>
                            <td><?php echo $validate; ?></td>
                            <td><?php echo (!$row['published']) ? '<i class="fas fa-toggle-off"></i>' : '<i class="fas fa-toggle-on"></i>'; ?></td>
                            <td><a href="#" data-toggle="modal" data-target="#delete-<?php echo $row['id']; ?>"><i class="fas fa-trash"></i></a></td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="delete-<?php echo $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="systematics_specie_delete.php" method="POST">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="del_id" id = "del_id" value="<?php echo $row['id']; ?>">
                                            <p>Are you sure you want to delete <strong><?php echo $species->getNomenclature($row['id']); ?></strong> id#<span class="badge badge-secondary"><?php echo $row['id']; ?></span>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-sm">Delete</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
                $paginationClass->simplePagination($pagination,$path_file,$page_title,$page);
                ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php include_once BASE_PATH.'/modules/footer.php'; ?>
</body>
</html>
