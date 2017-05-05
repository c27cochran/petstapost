<?php
	require __DIR__ .'/../core/init.php';
?>

    <?php
        if ($general->logged_in() === true)  { 
    ?>
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="<?php echo $_SESSION['username'];?>">
                <i class="fa fa-user"></i>  <span class="light">My Profile</span>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
            <ul class="nav navbar-nav">
                <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                <li class="hidden active">
                    <a href="#page-top"></a>
                </li>
                <li>
                    <a href="account/logout"><i class="fa fa-sign-out"></i>&nbsp;Log Out</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
    <?php
        } else {
    ?> 
    <style type="text/css">
        #ajax-navbar {
            display: none;
        }
    </style>
    <?php
        }
    ?>