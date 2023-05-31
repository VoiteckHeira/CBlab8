<?php
include_once "classes/Permission.php";

function create_session()
{
    session_start();
    $Perm = new Permission();
    if (isset($_SESSION['session_expire'])) {
        if (time() - $_SESSION['session_expire'] > (60 * 5)) {
            session_unset();
            session_destroy();

            header("Location: index.php");
            exit();
        } else {
            $_SESSION['session_expire'] = time();
        }
    }
    if (isset($_REQUEST['logout'])) {
        unset($_SESSION['login']);
        unset($_SESSION['permissions']);
        unset($_SESSION['roles']);

        $Perm->save_sing_out();
        session_regenerate_id(); //zmiana id
    }
    if (!empty($_SESSION['login'])) {
        echo 'Zalogowany jako: </br>';
        echo $_SESSION['login'];
        echo '</br>';
        $login = $_SESSION['login'];
    } else {
        echo 'niezalogowany';
    }
}
?>