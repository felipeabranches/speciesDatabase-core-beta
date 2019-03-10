<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="<?php echo $metaAuthor; ?>">
    <title><?php echo $title; ?></title>
    <!-- Bootstrap CSS -->
    <?php if($bootstrap_cdn): ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <?php else: ?>
    <link href="<?php echo $bootstrap_path; ?>/css/bootstrap.min.css" rel="stylesheet" />
    <?php endif; ?>
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!-- speciesDatabase CSS -->
    <link href="<?php echo $base_url; ?>/assets/css/style.min.css" rel="stylesheet" type="text/css" />
    <!-- speciesDatabase Favicon -->
    <link href="<?php echo $base_url; ?>/assets/images/favicon.ico" rel="icon" />
</head>