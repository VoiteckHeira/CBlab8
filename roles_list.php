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
?>
<!DOCTYPE html>
<html>

<head>
    <title>Roles List</title>
</head>

<body>
    <h1>Roles List</h1>
    <?php
    $pdo = new Pdo_();
    $Perm = new Permission();
    $roles = $Perm->get_roles();
    foreach ($roles as $role) {
        echo "<p>" . $role['name'] . "</p> <a href=\"role_permissions.php?role_id={$role['id']}\">Set permissions</a>";
    }
    ?>
    <form action="add_role.php" method="post">
        <input type="text" name="role_name" placeholder="Role name">
        <input type="submit" value="Add Role">
    </form>
    <P>Navigation</P>
    <?php
    Page::display_navigation();
    ?>
</body>

</html>