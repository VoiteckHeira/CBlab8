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
    <title>Role Permissions</title>
</head>

<body>
    <h1>Role Permissions</h1>
    <form action="update_role_permissions.php" method="post">
        <?php
        $pdo = new Pdo_();
        $Perm = new Permission();
        $role_id = $_GET['role_id'] ?? 0;
        // MOŻNA DODAĆ SPRAWDZANIE CZY ROLE ID > 0, JAK  NIE TO WYWALIĆ KOMUNIKAT
        $permissions = $Perm->get_permissions();

        $role_permissions = $Perm->get_role_permissions($role_id);

        $role_permissions_id = [];
        foreach ($role_permissions as $role_permission) {
            $role_permissions_id[] = $role_permission['id'];
        }




        foreach ($permissions as $permission) {
            $has_permission = in_array($permission['id'], $role_permissions_id);

            echo "
                <input 
                    type='checkbox' 
                    name='permissions[]' 
                    " . ($has_permission ? 'checked' : '') . "
                    value='" . $permission['id'] . "'
                >" . $permission['name'] . "<br>";
        }
        ?>
        <input type="hidden" name="role_id" value="<?php echo $role_id; ?>" />
        <input type="submit" value="Update Permissions">
    </form>
    <P>Navigation</P>
    <?php
    Page::display_navigation();
    ?>
</body>

</html>