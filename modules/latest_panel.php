<?php
function admin_panel_table($title, $table, $field, $folder, $edit, $order_by, $limit)
{
    // Connect to DB
    $db = getDbInstance();
    
    // Get table query
    $cols = array ('id', $field.' AS name', 'published');
    $db->orderBy ($order_by);
    $rows = $db->get ($table, $limit, $cols);
    
    // Get link query
    $total = $db->getValue ($table, 'count(*)');
    ?>
    <h5>Latest <?php echo $limit.' '.$title; ?></h5>
    <?php if (!$db->count): ?>
    <span>No entries</span>
    <?php else: ?>
    <table class="table table-striped table-hover table-sm">
        <thead>
            <tr width="100%">
                <th width="15%">ID</th>
                <th width="70%">Name</th>
                <th width="15%" colspan="2">State</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><a href="<?php echo $folder; ?>/<?php echo $edit; ?>.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
                <td><?php echo (!$row['published']) ? '<i class="fas fa-toggle-off"></i>' : '<i class="fas fa-toggle-on"></i>'; ?></td>
                <td><a href="#" data-toggle="modal" data-target="#delete-<?php echo $row['id']; ?>"><i class="fas fa-trash"></i></a></td>
            </tr>
            <!-- Modal -->
            <div class="modal fade" id="delete-<?php echo $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="<?php echo $folder; ?>/<?php echo $edit; ?>.php" method="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="del_id" id = "del_id" value="<?php echo $row['id']; ?>">
                                <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($row['name']); ?></strong> id#<span class="badge badge-secondary"><?php echo $row['id']; ?></span>?</p>
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
    <a href="<?php echo $folder; ?>/<?php echo $table; ?>.php">View all <?php echo $total; ?> <?php echo $title; ?> <i class="fa fa-arrow-circle-right"></i></a>
    <?php endif; ?>
<?php
}
?>
