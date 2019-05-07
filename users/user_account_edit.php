<?php
session_start();
require_once '../../config.php';
require_once BASE_PATH.'/admin/includes/auth_validate.php';
require_once BASE_PATH.'/libraries/HTML/Fields.php';
$field = new Fields;

$task = filter_input(INPUT_GET, 'task', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$NorE = (!$id) ? 'New' : 'Edit';

$page_title = 'User Account';
$title = $NorE.' '.$page_title.' - '.$site_name;

// If non-super user accesses this script via url. Stop the exexution
// Only super admin is allowed to access this page
if ($_SESSION['user_type'] !== 'super')
{
	// show permission denied message
	echo 'Permission Denied';
	exit();
}

//Serve POST request.
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// Sanitize input post if we want
	$data_to_db = filter_input_array(INPUT_POST);
	// Check whether the user name already exists;
	$db = getDbInstance();
	$db->where('username', $data_to_db['username']);
	$db->where('id', $id, '!=');
	//print_r($data_to_db['username']);die();
	$row = $db->getOne('users_accounts');
	//print_r($data_to_db['username']);
	//print_r($row);die();

	if (!empty($row['username']))
	{
		$_SESSION['failure'] = 'Username already exists';

		$query_string = http_build_query(array(
			'id' => $id,
			'task' => $task
		));
		header('location: user_account_edit.php?'.$query_string );
		exit();
	}

	// Encrypting the password
	$data_to_db['password'] = password_hash($data_to_db['password'], PASSWORD_DEFAULT);

	// reset db instance
	$db = getDbInstance();
	$db->where('id', $id);
	$stat = $db->update('users_accounts', $data_to_db);

	if ($stat)
	{
		$_SESSION['success'] = 'User account successfully updated';
	} else {
		$_SESSION['failure'] = 'Failed to update Admin user : ' . $db->getLastError();
	}

		header('location: users_accounts.php');
		exit();

}

//Select where clause
$db = getDbInstance();
$db->where('id', $id);

$user_account = $db->getOne('users_accounts');
?>
<!doctype html>
<html lang="pt">
<?php include BASE_PATH.'/modules/header.php'; ?>
<body class="bg-light <?php echo lcfirst($page_title); ?>">
<?php include BASE_PATH.'/admin/modules/menu.php'; ?>
<div class="container-fluid" role="main">
    <form action="" method="post" id="user_form" enctype="multipart/form-data">
        <!-- Toolbar -->
        <div class="toolbar sticky-top row my-2 p-2">
            <div class="col-12">
                <h4 class="float-left"><?php echo (!$id) ? 'New' : 'Edit'; ?> <?php echo $page_title; ?></h4>
                <div class="float-right">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check"></i>Save</button>
                    <a href="users_accounts.php?order_by=id" class="btn btn-outline-danger btn-sm" role="button"><i class="fas fa-times"></i>Cancel</a>
                </div>
            </div>
        </div>
        <?php include BASE_PATH.'/admin/includes/flash_messages.php'; ?>
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <?php $field->text ('Name', 'name', $user_account['name'], 'Enter a Name', 'required'); ?>
                    <?php $field->email ('E-mail', 'email', $user_account['email'], 'Enter an E-mail', 'required'); ?>
                    <?php $field->text ('Username', 'username', $user_account['username'], 'Enter an Username', 'required'); ?>
                    <?php $field->password ('Password', 'password', $user_account['password'], 'Enter a Password', 'required'); ?>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="my-3 p-3 bg-white rounded box-shadow">
                    <h5>State</h5>
                    <?php //if ($id) echo '<p><strong>ID:</strong> '.$id.'</p>'; ?>
                    <?php $field->select ('Usertype', 'user_type', array('super' => 'super', 'admin' => 'admin'), 'users_accounts', $user_account['id'], 0, '<option name="none" value="none">-- None --</option>', ''); ?>
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
	   $('#user_form').validate({
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
