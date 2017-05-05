<?php
    require_once 'inc/yappy/yappy-header.php';
?>

	<!-- Intro Header -->
    <header class="intro">
        <div class="intro-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 title-div">
                        <div class="col-md-10">
                            <div class="success-box activate-box">
                                <p class="alert alert-success" role="alert">
                                    <i class="fa fa-thumbs-o-up"></i>&nbsp;
                                    Post has been deleted.
                                </p>
                            </div>
                            <p class="intro-text">
                                <button class="btn btn-primary btn-back-to-profile" onclick="goToProfile()"><i class="fa fa-arrow-circle-o-left"></i>&nbsp;Back to Profile</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <script>
        function goToProfile() {
            window.location.replace('http://petstapost.com/<?php echo $_SESSION["username"];?>');
        }
    </script>

<?php
    require_once 'inc/yappy/yappy-footer.php';
?>