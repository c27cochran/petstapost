<?php 

require __DIR__ .'/../core/init.php';

session_destroy();

if(isset($_COOKIE['Petsta'])) {
	unset($_COOKIE['Petsta']);
	if ($domain == "localhost") {
        setrawcookie('Petsta', '', time() - 3600, '/petstapost', '', false);
    } elseif ($domain == "petstapost.com") {
        setrawcookie('Petsta', '', time() - 3600, '/', 'petstapost.com', false);
    }
}

if ($domain == "localhost") {
    header('Location: /petstapost');
    exit();
} else if ($domain == "petstapost.com") {
    header('Location: /');
    exit();
}
?>