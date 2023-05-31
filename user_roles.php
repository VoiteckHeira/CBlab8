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
    <title>User Roles</title>
</head>

<body>
    <h1>User Roles</h1>
    <form action="update_user_roles.php" method="post">
        <?php
        $pdo = new Pdo_();
        $Perm = new Permission();

        $user_id = $_GET['user_id']; // Zastąp tym konkretnym ID użytkownika
        
        $roles = $Perm->get_roles();
        $user_roles = $Perm->get_user_roles($user_id);

        $user_role_id = [];
        foreach ($user_roles as $user_role) {
            $user_role_id[] = $user_role['id'];
        }

        foreach ($roles as $role) {
            $has_role = in_array($role['id'], $user_role_id);

            echo "<input type='checkbox' name='roles[]' " . ($has_role ? 'checked' : '') . " value='" . $role['id'] . "'>" . $role['name'] . "<br>";
        }
        ?>
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="submit" value="Update Roles">
    </form>
    <P>Navigation</P>
    <?php
    Page::display_navigation();
    ?>
</body>

</html>