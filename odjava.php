<?php
session_start();
session_unset(); // Uklanja sve varijable sesije
session_destroy(); // Uništava sesiju

// Preusmjeri korisnika nakon odjave
header('Location: index.html');
exit();
?>
