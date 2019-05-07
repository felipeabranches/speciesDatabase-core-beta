<?php
session_start();
require_once '../../config.php';
require_once '../includes/auth_validate.php';

$page_count = 10;
$order_by = $_GET['order_by'];
?>
<!doctype html>
<html lang="pt">
<?php include_once BASE_PATH.'/modules/header.php'; ?>

<body class="bg-light <?php echo lcfirst($page_title); ?>">
<?php include_once BASE_PATH.'/admin/modules/menu.php'; ?>

    <div class="row">
        <div class="col-12">
            <div class=" my-3 p-3 bg-white rounded box-shadow">
                <table class="table table-striped table-hover table-sm">
                    <caption><?php echo $page_title; ?></caption>
                    <tr width="100%">
                        <th width="5%"><a href="users_users.php?order_by=id">ID</a></th>
                        <th width="40%"><a href="users_users.php?order_by=name">Name</a></th>
                        <th width="25%"><a href="users_users.php?order_by=username">Username</a></th>
                        <th width="25%"><a href="users_users.php?order_by=email">Email</a></th>
                        <th width="5%"><a href="users_users.php?order_by=published">State</a></th>
                    </tr>
                    <?php
                    $sql = 'SELECT uu.id AS id, uu.name AS name, uu.username AS username, uu.email AS email 
                            FROM users_users AS uu
                            ORDER BY uu.'.$order_by.'
                            ;';

                    if($result=mysqli_query($mysqli,$sql))
                    {
                        if($result->num_rows)
                        {
                            // Fetch one and one row
                            while($row = mysqli_fetch_assoc($result))
                            {
                                echo '<tr>';
                                echo '<td>'.$row['id'].'</td>';
                                echo '<td><a href="users_user.php?id='.$row['id'].'">'.$row['name'].'</a></td>';
                                echo '<td>'.$row['username'].'</td>';
                                echo '<td>'.$row['email'].'</td>';
                                echo '<td><a data-toggle="modal" data-target="#modal-'.$row['id'].'"><i class="fas fa-trash-alt"></i></a></td>';
                                echo '</tr>'."\n";
                                echo '<!-- Modal -->
                                    <div class="modal" id="modal-'.$row['id'].'" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete <strong>'.$row['name'].'</strong> (ID: '.$row['id'].')?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="modules/users_users_delete.php?id='.$row['id'].'" class="btn btn-danger">Delete</a>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                            }
                            // Free result set
                            mysqli_free_result($result);
                        }
                    }
                    else
                    {
                        echo '<tr><td colspan="4">No entries</td></tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once BASE_PATH.'/modules/footer.php'; ?>
</body>
</html>
