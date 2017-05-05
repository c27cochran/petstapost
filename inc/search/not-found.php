    <!-- Intro Header if user is not found -->
    <header class="intro-not-found">
        <div class="intro-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 title-div">
                        <h1 class="brand-heading">Oops..</h1>
                        <p class="intro-text">Sorry, that user doesn't exist.</p>
                        <p class="intro-text">
                            <button class="btn btn-default back-btn" onclick="goBack()"><i class="fa fa-arrow-circle-o-left"></i>&nbsp;Go back</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>