    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top <?php if ($general->logged_in() === true) {echo 'navbar-yappy';}?>" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars"></i>
                </button>
                <?php
				    if ($general->logged_in() === true)  { 
				?>
                <a class="navbar-brand kibble-header" href="kibble/<?php echo $_SESSION['username'];?>">
                    <span class="light logo" style="margin-left: 20px;">Kibble</span>
                </a>
				<?php
				    } else {
				?>        
				<a id="login-modal-launcher" class="navbar-brand page-scroll" href="#page-top" data-toggle="modal" data-target="#account-modal">
				    <i class="fa fa-sign-in"></i>  <span class="light logo">Log in</span>
				</a>
				<?php
				    }
				?>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                    <li class="hidden active">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about">About</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#download">Download App</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li>
                    <?php
					    if ($general->logged_in() === true)  { 
					?>
					<li>
                        <a href="account/logout"><i class="fa fa-sign-out"></i>&nbsp;Log Out</a>
                    </li>
					<?php
					    } else {
					?>
					<li>
                        <a class="page-scroll register-modal-launcher" href="#create-account-scroll" data-toggle="modal" data-target="#account-modal">Create Account</a>
                    </li>
					<?php
					    }
					?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>