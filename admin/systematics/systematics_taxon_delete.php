<?php
session_start();
require_once '../../config.php';
require_once BASE_PATH.'/admin/includes/auth_validate.php';
$del_id = filter_input(INPUT_POST, 'del_id');

if ($del_id && $_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if ($_SESSION['user_type'] != 'super')
    {
		$_SESSION['failure'] = 'You don\'t have permission to perform this action';
    	header('location: systematics_taxa.php');
        exit;
	}

    $db = getDbInstance();
    $db->where('id', $del_id);
    $status = $db->delete('systematics_taxa');

    if ($status) 
    {
        $_SESSION['info'] = 'Taxon deleted successfully!';
        header('location: systematics_taxa.php');
        exit;
    }
    else
    {
    	$_SESSION['failure'] = 'Unable to delete Taxon';
    	header('location: systematics_taxa.php');
        exit;
    }
}
