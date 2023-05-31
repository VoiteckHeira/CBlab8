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
    <title>User Permissions</title>
</head>

<body>
    <h1>User Permissions</h1>
    <form action="update_user_permissions.php" method="post">
        <?php
        $pdo = new Pdo_();
        $user_id = $_GET['user_id']; // Zastąp tym konkretnym ID użytkownika
        
        $permissions = $Perm->get_permissions();
        $user_permissions = $Perm->get_user_permissions($user_id);

        $user_permission_id = [];
        foreach ($user_permissions as $user_permission) {
            $user_permission_id[] = $user_permission['id'];
        }

        foreach ($permissions as $permission) {
            $has_permission = in_array($permission['id'], $user_permission_id);

            echo "
                <input type='checkbox' 
                name='permissions[]' " .
                ($has_permission ? 'checked' : '')
                . "
                value='" . $permission['id'] . "'
            >" . $permission['name'] . "<br>";
        }
        ?>

        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
        <input type="submit" value="Update Permissions">
    </form>
    <P>Navigation</P>
    <?php
    Page::display_navigation();
    ?>
</body>

</html>