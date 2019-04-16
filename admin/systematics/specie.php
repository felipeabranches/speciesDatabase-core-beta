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
$page_title = 'Species';
$title = $NorE.' '.$page_title.' - '.$site_name;

//If id variable is set and diferent of '0', get data to pre-populate the form.
if($id)
{
    $db = getDbInstance();
    $db->where('id', $id);
    //Get data to pre-populate the form.
    $row = $db->getOne('systematics_species');
}

$genus = !$id ? '' : $row['genus'];
$species = !$id ? '' : $row['species'];
$id_taxon = !$id ? '' : $row['id_taxon'];
$incertae_sedis = !$id ? '' : $row['incertae_sedis'];
$year = !$id ? '' : $row['year'];
$common_name = !$id ? '' : $row['common_name'];
$etymology = !$id ? '' : $row['etymology'];
$habitat = !$id ? '' : $row['habitat'];
$distribution = !$id ? '' : $row['distribution'];
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
	    $last_id = $db->insert('systematics_species', $data_to_db);
	
	    if ($last_id)
	    {
	    	$_SESSION['success'] = $page_title.' successfully '.$NorE;
	        header('location: species.php');
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
	    // Performing the update task.
        $db->where('id', $id);
	    $stat = $db->update('systematics_species', $data_to_db);

	    if($stat)
	    {
	    	$_SESSION['success'] = $page_title.' successfully '.$NorE;
	        // Redirect to the listing page,
	    	header('location: species.php');
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
    <form action="" method="post" id="species_form" enctype="multipart/form-data">
        <!-- Toolbar -->
        <div class="toolbar sticky-top row my-2 p-2">
            <div class="col-12">
                <h4 class="float-left"><?php echo (!$id) ? 'New' : 'Edit'; ?> <?php echo $page_title; ?></h4>
                <div class="float-right">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check"></i>Save</button>
                    <a href="species.php" class="btn btn-outline-danger btn-sm" role="button"><i class="fas fa-times"></i>Cancel</a>
                </div>
            </div>
        </div>
        <?php include BASE_PATH.'/admin/includes/flash_messages.php'; ?>
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <h5>Classification</h5>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <?php $field->text('Genus', 'genus', $genus, 'Enter the Genus', 'required'); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <?php $field->text('Species', 'species', $species, 'Enter the Species', ''); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <?php $field->select('Dubious species', 'dubious', array(1 => 'aff.', 2 => 'cf.', 3 => 'sp.'), 'systematics_species', $id, 0, '<option name="no" value="0">-- No --</option>', 0); ?>
                        </div>
                        <div class="col-12 col-md-8">
                            <?php $field->selectDB('Taxon', 'id_taxon', $id_taxon, 'name', 'systematics_taxa', 'systematics_species', 'name', '<option>-- Choose --</option>', 1); ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <?php $field->radioToggle('<em>Incertae Sedis</em>', 'incertae_sedis', array(1 => 'yes', 0 => 'no'), 'systematics_species', $id, 0, 'yesno'); ?>
                        </div>
                        <!--div class="col-12 col-md-8">
                            <?php $field->selectDB('Taxonomists', 'id_taxonomist', $id, 'name', 'systematics_taxonomists', 'systematics_taxonomists_map', 'name', '<option>-- None --</option>', 0, 1); ?>
                        </div-->
                        <div class="col-12 col-md-4">
                            <?php $field->radioToggle('Revised', 'revised', array(1 => 'yes', 0 => 'no'), 'systematics_species', $id, 0, 'yesno'); ?>
                            <?php $field->text('Year', 'year', $year, 'Enter the identification Year in YYYY format', ''); ?>
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
                    <?php $field->radioToggle('Published', 'published', array(1 => 'yes', 0 => 'no'), 'systematics_species', $id, 1, 'yesno'); ?>
                    <?php if ($id) echo '<p><strong>ID:</strong> '.$id.'</p>'; ?>
                </div>
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <h5>Media</h5>
                    <?php $field->text('Image', 'image', $image, 'Enter the Image path', ''); ?>
                </div>
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <h5>Others</h5>
                    <?php $field->text('Common Name', 'common_name', $common_name, 'Enter the Common Name', ''); ?>
                    <?php $field->textarea('Etymology', 'etymology', $etymology, '', ''); ?>
                    <?php $field->textarea('Habitat', 'habitat', $habitat, ''); ?>
                    <?php $field->textarea('Distribution', 'distribution', $distribution, ''); ?>
                    <?php $field->radioToggle('Validate', 'validate', array(1 => 'accepted', 0 => 'synonym'), 'systematics_species', $id, 1, ''); ?>
                    <?php $field->selectDB('Redirect', 'redirect', $redirect, 'CONCAT(genus, " ", species)', 'systematics_species', 'systematics_species', 'genus, species', '<option value="0">-- Choose --</option>'); ?>
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
        $('#species_form').validate({
            rules: {
                genus: {
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
