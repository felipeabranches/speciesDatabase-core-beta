<?php
session_start();

require_once '../config.php';
$token = bin2hex(openssl_random_pseudo_bytes(16));

//If User has already logged in, redirect to dashboard page.
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === TRUE) {
	header('Location:index.php');
}

//If user has previously selected "remember me option" : 
if (isset($_COOKIE['series_id']) && isset($_COOKIE['remember_token'])) {

	//Get user credentials from cookies.
	$series_id = filter_var($_COOKIE['series_id']);
	$remember_token = filter_var($_COOKIE['remember_token']);
	$db = getDbInstance();
	//Get user By serirs ID : 
	$db->where('series_id', $series_id);
	$row = $db->get('users_accounts');


	if ($db->count >= 1) {

		//User found. verify remember token
		if (password_verify($remember_token, $row[0]['remember_token'])) {
			//Verify if expiry time is modified. 

			$expires = strtotime($row[0]['expires']);

			if(strtotime(date()) > $expires){
				
				//Remember Cookie has expired. 
				clearAuthCookie();
				header('Location:login.php');
				exit;
			}
			
			$_SESSION['user_logged_in'] = TRUE;
			$_SESSION['user_type'] = $row[0]['user_type'];
			header('Location:index.php');
			exit;
		} else {
			clearAuthCookie();
			header('Location:login.php');
			exit;
		}
	} else {
		clearAuthCookie();
		header('Location:login.php');
		exit;
	}
}

$page_title = 'Please Sign in';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="<?php echo $metaAuthor; ?>">
        <title><?php echo $page_title; ?> - <?php echo $site_name; ?></title>

        <!-- Bootstrap CSS -->
        <?php if($bootstrap_cdn): ?>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <?php else: ?>
        <link href="<?php echo $bootstrap_path; ?>/css/bootstrap.min.css" rel="stylesheet" />
        <?php endif; ?>
        <!-- Fontawesome CSS -->
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
        <!-- speciesDatabase CSS -->
        <link href="<?php echo $base_url; ?>/assets/css/login.css" rel="stylesheet" type="text/css" />
        <!-- speciesDatabase Favicon -->
        <link href="<?php echo $base_url; ?>/img/favicon.ico" rel="icon" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="text-center bg-light">
        <form class="signin bg-white rounded box-shadow" method="POST" action="authenticate.php">
            <h1 class="h3 mb-3 font-weight-normal"><?php echo $page_title; ?></h1>
            <?php if (isset($_SESSION['login_failure'])): ?>
            <!-- Alert -->
            <div class="alert alert-danger alert-dismissable fade show" role="alert">
                <?php echo $_SESSION['login_failure']; ?>
                <?php unset($_SESSION['login_failure']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>
            <label for="username" class="sr-only">Username</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-user"></i></div>
                </div>
                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required="required" autofocus="autofocus" />
            </div>
            <label for="password" class="sr-only">Password</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-lock"></i></div>
                </div>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="required" />
            </div>
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>
            <button type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
            <p class="mt-5 mb-3 text-muted">&copy; 2018 - <?php echo date('Y')?></p>
        </form>
<?php include_once BASE_PATH.'/modules/footer.php';?>
