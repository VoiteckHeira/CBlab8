<?php
include_once "classes/Page.php";
session_start();

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
if (empty($_SESSION['permissions'][1])) {
    die;
}

?>
<h5>
    <?php
    if (!empty($_SESSION['login'])) {
        echo $_SESSION['login'];
    } else {
        echo 'niezalogowany';
    }
    include_once "classes/Pdo.php";
    include_once "classes/Permission.php";
    //require_once 'Pdo_.php';
    $Perm = new Permission();
    $pdo = new Pdo_();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['permission_name'])) {
            $permission_name = $_GET['permission_name'];
            $Perm->add_permission($permission_name);
            echo "\n Dodano nowe uprawnienie: " . $permission_name;
        } else {
            // echo "Nie podano nazwy uprawnienia.";
        }
    }
    ?>


    <!DOCTYPE html>
    <html>

    <head>
        <title>Dodaj uprawnienie</title>
    </head>

    <body>
        <h1>Dodaj uprawnienie</h1>
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="permission_name">Nazwa uprawnienia:</label><br>
            <input type="text" id="permission_name" name="permission_name"><br>
            <input type="submit" value="Dodaj">
        </form>
        <!--------------------------------------------------------------------->

        <hr>
        <P>Navigation</P>
        <?php
        Page::display_navigation();
        ?>
    </body>

    </html>