<?php
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

?>
<h5>
    <?php
    if (!empty($_SESSION['login'])) {
        echo $_SESSION['login'];
    } else {
        echo 'niezalogowany';
    }
    ?>
</h5>
<?php
include_once "classes/Page.php";
include_once "classes/Pdo.php";
include_once "classes/Permission.php";
$Perm = new Permission();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Permissions List</title>
</head>

<body>
    <h1>Permissions List</h1>
    <?php
    $pdo = new Pdo_();
    $permissions = $Perm->get_permissions();
    foreach ($permissions as $permission) {
        echo "<p>" . $permission['name'] . "</p>";
    }
    ?>
    <!--------------------------------------------------------------------->

    <hr>
    <P>Navigation</P>
    <?php
    Page::display_navigation();
    ?>
</body>

</html>