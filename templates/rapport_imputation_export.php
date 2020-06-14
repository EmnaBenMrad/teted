<?php
session_start();// On d�marre la session
$_SESSION['liste_a_exporter'] = $_POST['rapport_imputation'];
