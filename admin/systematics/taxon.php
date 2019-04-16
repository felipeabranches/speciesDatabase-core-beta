<?php
session_start();
require_once '../../config.php';
require_once BASE_PATH.'/admin/includes/auth_validate.php';
require_once BASE_PATH.'/libraries/HTML/Fields.php';
$field = new Fields;

$task = filter_input(INPUT_GET, 'task', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$NorE = (!$id) ? 'New' : 'Edit';

// Titles
$page_title = 'Taxon';
$title = $NorE.' '.$page_title.' - '.$site_name;

//If id variable is set and diferent of '0', get data to pre-populate the form.
if($id)
{
    $db = getDbInstance();
    $db->where('id', $id);
    //Get data to pre-populate the form.
    $row = $db->getOne('systematics_taxa');
}

$name = !$id ? '' : $row['name'];
$etymology = !$id ? '' : $row['etymology'];
$id_parent = !$id ? '' : $row['id_parent'];
$id_type = !$id ? '' : $row['id_type'];
$description = !$id ? '' : $row['description'];
$note = !$id ? '' : $row['note'];
$image = !$id ? '' : $row['image'];
$published = !$id ? '' : $row['published'];

// Handle update request. As the form's action attribute is set to the same script, but 'POST' method, 
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if (!$id)
	{
	    // Mass Insert Data. Keep "name" attribute in html form same as column name in mysql table.
	    $data_to_db = array_filter($_POST);
	    // Insert timestamp
	    //$data_to_db['created_at'] = date('Y-m-d H:i:s');

        $db = getDbInstance();
	    $last_id = $db->insert('systematics_taxa', $data_to_db);
	
	    if ($last_id)
	    {
	    	$_SESSION['success'] = $page_title.' successfully '.$NorE;
	        header('location: taxa.php');
	    	exit();
	    }
	    else
	    {
	        echo 'insert failed: ' . $db->getLastError();
	        exit();
	    }
	}
	else
	{
	    // Get input data
	    $data_to_db = filter_input_array(INPUT_POST);
	    // Insert timestamp
	    //$data_to_db['updated_at'] = date('Y-m-d H:i:s');
	    // Performing the update task.
        $db->where('id', $id);
	    $stat = $db->update('systematics_taxa', $data_to_db);

	    if($stat)
	    {
	    	$_SESSION['success'] = $page_title.' successfully '.$NorE;
	        // Redirect to the listing page,
	    	header('location: taxa.php');
	    	// Important! Don't execute the rest put the exit/die. 

	        exit();
		}
    }
}
?>
<!doctype html>
<html lang="pt">
<?php include BASE_PATH.'/modules/header.php'; ?>
<body class="bg-light <?php echo lcfirst($page_title); ?>">
<?php include BASE_PATH.'/admin/modules/menu.php'; ?>
<div class="container-fluid" role="main">
    <form action="" method="post" id="taxon_form" enctype="multipart/form-data">
        <!-- Toolbar -->
        <div class="toolbar sticky-top row my-2 p-2">
            <div class="col-12">
                <h4 class="float-left"><?php echo (!$id) ? 'New' : 'Edit'; ?> <?php echo $page_title; ?></h4>
                <div class="float-right">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check"></i>Save</button>
                    <a href="taxa.php" class="btn btn-outline-danger btn-sm" role="button"><i class="fas fa-times"></i>Cancel</a>
                </div>
            </div>
        </div>
        <?php include BASE_PATH.'/admin/includes/flash_messages.php'; ?>
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <?php $field->text('Name', 'name', $name, 'Enter the Taxonomist name', 'required'); ?>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <?php $field->selectDB('Type', 'id_type', $id_type, 'name', 'systematics_taxa_types', 'systematics_taxa', 'name', '<option name="none" value="0">-- Choose --</option>'); ?>
                        </div>
                        <div class="col-12 col-md-6">
                            <?php $field->selectDB('Parent', 'id_parent', $id_parent, 'name', 'systematics_taxa', 'systematics_taxa', 'name', '<option name="none" value="0">None</option>'); ?>
                        </div>
                    </div>
                </div>
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <?php $field->textarea('Description', 'description', $description, '', ''); ?>
                </div>
            </div>
            <!-- Aside -->
            <div class="col-12 col-md-4">
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <h5>State</h5>
                    <?php $field->radioToggle('Published', 'published', array(1 => 'yes', 0 => 'no'), 'systematics_taxa', $id, 1, 'yesno'); ?>
                    <?php if ($id) echo '<p><strong>ID:</strong> '.$id.'</p>'; ?>
                </div>
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <h5>Media</h5>
                    <?php $field->text('Image', 'image', $image, 'Enter the Image path', ''); ?>
                </div>
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <h5>Others</h5>
                    <?php $field->textarea('Etymology', 'etymology', $etymology, '', ''); ?>
                    <?php $field->text('Note', 'note', $note, 'Enter some Notes', ''); ?>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- TinyMCE -->
<script src="<?php echo $tinymce_path; ?>/tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector:'#description',
        height: 300
    });

    $(document).ready(function(){
        $('#taxon_form').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                }
            }
        });
    });
</script>
<?php include BASE_PATH.'/modules/footer.php'; ?>
</body>
</html>
