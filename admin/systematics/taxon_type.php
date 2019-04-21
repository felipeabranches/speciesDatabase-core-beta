<?php
session_start();
require_once '../../config.php';
require_once BASE_PATH.'/admin/includes/auth_validate.php';
require_once BASE_PATH.'/libraries/HTML/Fields.php';
$field = new Fields;

$task = filter_input(INPUT_GET, 'task', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$NorE = (!$id) ? 'New' : 'Edit';

$page_title = 'Taxon Type';
$title = $NorE.' '.$page_title.' - '.$site_name;

if ($id) $db = getDbInstance();

// Handle update request. As the form's action attribute is set to the same script, but 'POST' method, 
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if (!$id)
	{
	    // Mass Insert Data. Keep "name" attribute in html form same as column name in mysql table.
	    $data_to_db = array_filter($_POST);
	    // Insert timestamp
	    $data_to_db['created_at'] = date('Y-m-d H:i:s');

        $db = getDbInstance();
        //image Upload
       
        $image = $_FILES['file']['tmp_name'];
        $size = $_FILES['file']['size'];
        $type = $_FILES['file']['type'];
        $name = $_FILES['file']['name'];
        $fp = fopen($image, "rb");
        $content = fread($fp, $size);
        $content = addslashes($content);
        fclose($fp);
        $data_to_db['image']=$name;
        $data_to_db['image_content']=$content;

	    $last_id = $db->insert('systematics_taxa_types', $data_to_db);
	
	    if ($last_id)
	    {
	    	$_SESSION['success'] = $page_title.' successfully '.$NorE;
	        header('location: systematics_taxa_types.php');
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
	    $data_to_db['updated_at'] = date('Y-m-d H:i:s');

	    $db->where('id', $id);
        //image Upload
       
        $image = $_FILES['file']['tmp_name'];
        $size = $_FILES['file']['size'];
        $type = $_FILES['file']['type'];
        $name = $_FILES['file']['name'];
        $fp = fopen($image, "rb");
        $content = fread($fp, $size);
        $content = addslashes($content);
        fclose($fp);
        $data_to_db['image']=$name;
        $data_to_db['image_content']=$content;

	    $stat = $db->update('systematics_taxa_types', $data_to_db);

	    if($stat)
	    {
	    	$_SESSION['success'] = $page_title.' successfully '.$NorE;
	        // Redirect to the listing page,
	    	header('location: systematics_taxa_types.php');
	    	// Important! Don't execute the rest put the exit/die. 

	        exit();
		}
    }
}

//If edit variable is set, we are performing the update task.
if($id)
{
    $db->where('id', $id);
    //Get data to pre-populate the form.
    $row = $db->getOne('systematics_taxa_types');
}

$name = !$id ? '' : $row['name'];
$description = !$id ? '' : $row['description'];
$published = !$id ? '' : $row['published'];
$image = !$id ? '' : $row['image'];
$note = !$id ? '' : $row['note'];
?>
<!doctype html>
<html lang="pt">
<?php include BASE_PATH.'/modules/header.php'; ?>
<body class="bg-light <?php echo lcfirst($page_title); ?>">
<?php include BASE_PATH.'/admin/modules/menu.php'; ?>
<div class="container-fluid" role="main">
    <form action="" method="post" id="taxon_type_form" enctype="multipart/form-data">
        <!-- Toolbar -->
        <div class="toolbar sticky-top row my-2 p-2">
            <div class="col-12">
                <h4 class="float-left"><?php echo (!$id) ? 'New' : 'Edit'; ?> <?php echo $page_title; ?></h4>
                <div class="float-right">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check"></i>Save</button>
                    <a href="systematics_taxa_types.php" class="btn btn-outline-danger btn-sm" role="button"><i class="fas fa-times"></i>Cancel</a>
                </div>
            </div>
        </div>
        <?php include BASE_PATH.'/admin/includes/flash_messages.php'; ?>
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <?php $field->text ('Name', 'name', $name, 'Enter the Taxonomist name', 'required'); ?>
                </div>
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <?php $field->textarea('Description', 'description', $description, '', ''); ?>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <h5>State</h5>
                    <?php $field->radioToggle('Published', 'published', array(1 => 'yes', 0 => 'no'), 'systematics_taxa_types', $id, 1, 'yesno'); ?>
                    <?php if ($id) echo '<p><strong>ID:</strong> '.$id.'</p>'; ?>
                </div>
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <h5>Media</h5>
                    <?php $field->file('Image', 'image', $image, 'Enter the Image path', ''); ?>
                </div>
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <h5>Others</h5>
                    <?php $field->text('Note', 'note', $note, 'Enter some notes...', ''); ?>
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
	   $('#taxon_type_form').validate({
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
