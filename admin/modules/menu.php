<?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true): ?>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="<?php echo $base_url; ?>"><?php echo $site_name; ?></a>
    <!-- Toggle -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div id="navbar" class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <!-- Panel -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo ADMIN; ?>index.php">Panel</a>
            </li>
            <!-- Systematics -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSystematics" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Systematics</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownSystematics">
                    <a class="dropdown-item" href="<?php echo SYSCS; ?>systematics_species.php">Species</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo SYSCS; ?>systematics_taxa.php">Taxa</a>
                    <a class="dropdown-item" href="<?php echo SYSCS; ?>systematics_taxa_types.php">Taxa Types</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo SYSCS; ?>systematics_taxonomists.php">Taxonomists</a>
                </div>
            </li>
        </ul>

        <ul class="navbar-nav">
            <!-- Accounts -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAccounts" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-users"></i> Users</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownAccounts">
                    <a class="dropdown-item" href="<?php echo USERS; ?>users_accounts.php"><i class="fas fa-list"></i> List Accounts</a>
                    <a class="dropdown-item" href="<?php echo USERS; ?>user_account_add.php?id=0"><i class="fas fa-plus"></i> Add Account</a>
                </div>
            </li>
            <!-- My Account -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMyAccount" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> My Account</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMyAccount">
                    <a class="dropdown-item" href="<?php echo USERS; ?>user_account_edit.php?task=edit&id=<?php //echo $userID; ?>"><i class="fas fa-user-circle"></i> Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo ADMIN; ?>logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<?php endif;?>
