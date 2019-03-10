<?php
session_start();
require_once '../../config.php';
require_once BASE_PATH.'/admin/includes/auth_validate.php';

require_once $base_dir.'/libraries/HTML/Fields.php';
$field = new Fields;

$page_title = 'User Account';
$title = $page_title.' - '.$site_name;

$id = $_GET['id'];
?>
<!doctype html>
<html lang="pt">
<?php include_once BASE_PATH.'/modules/header.php'; ?>

<body class="bg-light <?php echo lcfirst($page_title); ?>">
<?php include_once BASE_PATH.'/admin/modules/navbar.php'; ?>
<div class="container-fluid" role="main">

<?php
// check if the form has been submitted. If it has, process the form and save it to the database
if (isset($_POST['save']))
{
    // get form data, making sure it is valid
    $name = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['name']));
    $username = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['username']));
    $email = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['email']));
    $password = MD5( mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['password'])));
    //$mobile = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['mobile']));
    //$residencial = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['residencial']));
    //$comercial = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['comercial']));
    //$id_type = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['id_type']));
    //$description = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['description']));
    //$note = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['note']));
    //$image = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['image']));
    //$published = mysqli_real_escape_string($mysqli, htmlspecialchars($_POST['published']));

    // check to make sure fields are entered
    if ($name == '')
    {
        // generate error message
        $error = 'ERROR: Please fill in all required fields!';

        // if either field is blank, display the form again
        renderForm ($id, $name, $username, $email, $password, $error, $page_title);
    }
    else
    {
        if ($id == 0)
        {
            $sql = "INSERT INTO users_accounts (name, username, email, password) 
                    VALUES ('".$name."', '".$username."', '".$email."', '".$password."')
                    "."\n";

            // save the data to the database
            if (!mysqli_query($mysqli, $sql))
            {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h6>Falha</h6>
                        <p>'.$sql.'</p>
                        <p>'.mysqli_error($mysqli).'</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>'."\n";
            }
            else
            {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <h6>Sucesso</h6>
                        <p>'.$sql.'</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>'."\n";
            }

            // once saved, redirect back to the view page
            header ("Location: users_accounts.php"); //header("refresh:3;url=users_accounts.php");
        }
        else
        {
            // confirm that the 'id' value is a valid integer before getting the form data
            if (is_numeric($_POST['id']))
            {
                // get form data, making sure it is valid
                $id = $_POST['id'];

                $sql = "UPDATE users_accounts
                        SET name='".$name."', username='".$username."', email='".$email."', password='".$password."'
                        WHERE id = ".$id
                        ."\n";

                // save the data to the database
                if(!mysqli_query($mysqli, $sql))
                {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6>Falha</h6>
                            <p>'.$sql.'</p>
                            <p>'.mysqli_error($mysqli).'</p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>'."\n";
                }
                else
                {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <h6>Sucesso</h6>
                            <p>'.$sql.'</p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>'."\n";
                }

                // once saved, redirect back to the view page
                header ("Location: users_accounts.php"); //header("refresh:3;url=users_accounts.php");
            }
            else
            {
                // if the 'id' isn't valid, display an error
                echo 'Error!';
            }
        }
    }
}
else
{
    if ($id == 0)
    {
        // if the form hasn't been submitted, display the form
        renderForm ('', '', '', '', '', '', $page_title);
    }
    else
    {
        // if the form hasn't been submitted, get the data from the db and display the form
        // get the 'id' value from the URL (if it exists), making sure that it is valid (checing that it is numeric/larger than 0)
        if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0)
        {
            // query db
            $result = mysqli_query($mysqli, 'SELECT * FROM users_accounts WHERE id = '.$id)
                or die($sql_err);
            $row = mysqli_fetch_array($result);

            // check that the 'id' matches up with a row in the databse
            if($row)
            {
                // get data from db
                $name = $row['name'];
                $username = $row['username'];
                $email = $row['email'];
                $password = $row['password'];
              

                // show form
                renderForm ($id, $name, $username, $email, $password,'', $page_title);
            }
            else
            // if no match, display result
            {
                echo '<h4>Entry not found</h4>
                      <div class="alert alert-warning alert-dismissible fade show my-3" role="alert">
                        <h5>No result!</h5>
                        <p>The selected ID doesn\'t match with any row in Database</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>'."\n";
            }
        }
        else
        // if the 'id' in the URL isn't valid, or if there is no 'id' value, display an error
        {
            echo 'Error!';
        }
    }
}

/*
 *  Creates the record form (new or edit)
 */
function renderForm ($id, $name, $username, $email, $password, $error, $page_title)
{
    if ($error != '')
    {
        echo '<div style="border:1px solid red; color:red; padding:4px;">'.$error.'</div>';
    }
    ?>
	<form action="" method="post" id="user_form" enctype="multipart/form-data">
        <?php //if ($id) echo '<input type="hidden" name="id" value="'.$id.'" />'; ?>
        <div class="toolbar sticky-top row my-2 p-2">
            <div class="col-12">
                <h4 class="float-left"><?php //echo (!$id) ? 'New' : 'Edit'; ?>New <?php echo $page_title; ?></h4>
                <div class="float-right">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check"></i>Save</button>
                    <a href="users_accounts.php?order_by=id" class="btn btn-outline-danger btn-sm" role="button"><i class="fas fa-times"></i>Cancel</a>
                </div>
            </div>
        </div>
    </form>
<?php
}
?>
</div>
<?php include_once BASE_PATH.'/modules/footer.php'; ?>
<!-- TinyMCE -->
<script src="<?php echo $tinymce_path; ?>/tinymce/js/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector:'#description',
        height: 300
    });
</script>
<!-- Fontawesome -->
<script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
</body>
</html>
