<?php
    if ($general->logged_in() === true)  { 
?>
	<!-- Navigation -->
    <nav id="verified-navbar" class="navbar navbar-custom navbar-yappy navbar-fixed-top" role="navigation">
        <div class="container">
			<div class="navbar-header">
			    <button type="button" class="navbar-toggle navbar-mobile-tog" data-toggle="collapse" data-target=".navbar-mobile">
			        <span class="mobile-notifications"></span><i class="fa fa-bars"></i>
			    </button>
			    <?php 
			    	if ($username === $_SESSION['username']) {
	    		?>
	    			<a class="navbar-brand kibble-header" href="kibble/<?php echo $_SESSION['username'];?>">
				    	<span class="light logo" style="margin-left: 20px;">Kibble</span>
					</a>
	    		<?php
			    	} else {
	    		?>
	    			<a class="navbar-brand" href="<?php echo $_SESSION['username'];?>">
				    	<i class="fa fa-user"></i>&nbsp;<span class="light logo">My Profile</span>
					</a>
	    		<?php
			    	}
		    	?>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse navbar-right navbar-main-collapse">
			    <ul class="nav navbar-nav">
			        <li class="hidden">
			            <a href="#page-top"></a>
			        </li>
			        <li>
			            <a class="hidden" href="#page-top"></a>
			        </li>
			        <li class="dropdown">
		                <a href="#" class="dropdown-toggle navbar-web-tog" data-toggle="dropdown" role="button" aria-expanded="false">
		                	<span class="drop-notifications"></span><i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
	                	</a>
		                <ul class="dropdown-menu" role="menu">
		                	<li>
					            <a class="show-notifications" href="util/show-notifications" ><i class="fa fa-bullhorn"></i>&nbsp;Notifications&nbsp;
					            <span class="notifications"></span></a>
					        </li>
		                	<li>
		                		<a href="/">
									<i class="fa fa-home"></i>&nbsp;Home
                                </a>
		                	</li>
		                	<li>
			            		<a href="#" onclick="alert('Petstapost app is not available yet');">
			            			<i class="fa fa-mobile"></i>&nbsp;Download App
		            			</a>
			        		</li>
							<li>
								<a href="search.php">
									<i class="fa fa-users"></i>&nbsp;Search for friends
								</a>
							</li>
							<li>
								<a id="change-modal-launcher" href="#" data-toggle="modal" data-target="#account-modal">
									<i class="fa fa-unlock-alt"></i>&nbsp;Change password
								</a>
							</li>
							<li class="divider"></li>
							<li>
								<a href="account/logout">
								    <i class="fa fa-sign-out"></i>&nbsp;<span class="light">Sign Out</span>
								</a>
							</li>
		                </ul>
	              </li>
			    </ul>
			</div>
			<div class="collapse navbar-collapse navbar-right navbar-mobile" style="float:left;">
			    <ul class="nav navbar-nav navbar-toggle navbar-mobile">
			    	<li>
			            <a class="show-notifications" href="util/show-notifications" ><i class="fa fa-bullhorn"></i>&nbsp;Notifications&nbsp;
			            <span class="notifications"></span></a>
			        </li>
			    	<li>
                		<a href="/">
							<i class="fa fa-home"></i>&nbsp;Home
                        </a>
                	</li>
                	<li>
	            		<a href="#" onclick="alert('Petstapost app is not available yet');">
	            			<i class="fa fa-mobile"></i>&nbsp;Download App
            			</a>
	        		</li>
					<li>
						<a href="search.php">
							<i class="fa fa-users"></i>&nbsp;Search for friends
						</a>
					</li>
					<li>
						<a id="change-modal-launcher" href="#" data-toggle="modal" data-target="#account-modal">
							<i class="fa fa-unlock-alt"></i>&nbsp;Change password
						</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="account/logout">
						    <i class="fa fa-sign-out"></i>&nbsp;<span class="light">Sign Out</span>
						</a>
					</li>
			    </ul>
		    </div>
		    <div class="col-md-4">
		    	<form action="search.php" method="get">
			        <div class="input-group search-box">
			        <input type="text" id="user-search" class="form-control" placeholder="Search For Friends" name="query" autocomplete="off">
			        <ul class="dropdown-menu" id="user-results"></ul>
			        <div class="input-group-btn">
			          <button class="btn btn-default btn-search" type="submit"><i class="fa fa-search"></i></button>
			        </div>
			      </div>
		    	</form>
		    </div>
			<!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

<?php
    } else {
?>     
<!-- Navigation -->
    <nav class="navbar navbar-custom navbar-yappy navbar-fixed-top" role="navigation">
        <div class="container">
			<div class="navbar-header">
			    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
			        <i class="fa fa-bars"></i>
			    </button>
				<a id="login-modal-launcher" class="navbar-brand page-scroll" href="#page-top" data-toggle="modal" data-target="#account-modal">
				    <i class="fa fa-sign-in"></i>  <span class="light logo">Log in</span>
				</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse navbar-right navbar-main-collapse">
			    <ul class="nav navbar-nav">
			        <li class="hidden active">
			            <a href="#page-top"></a>
			        </li>
			        <li>
			            <a class="page-scroll" href="index.php#about">About</a>
			        </li>
			        <li>
			            <a class="page-scroll" href="index.php#download">Download App</a>
			        </li>
			        <li>
			            <a class="page-scroll" href="index.php#contact">Contact</a>
			        </li>
			        <li>
			            <a class="page-scroll register-modal-launcher" href="#page-top" data-toggle="modal" data-target="#account-modal">Create Account</a>
			        </li>
			    </ul>
			</div>
		<!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

<?php
    }
?>