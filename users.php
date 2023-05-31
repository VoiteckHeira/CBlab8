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
        // $users = 1; // Zastąp tym konkretnym ID użytkownika
        $users = $Perm->get_users();
        foreach ($users as $user) {
            echo "<p>{$user['login']}</p>";
            echo "<a href='user_permissions.php?user_id={$user['id']}'>Permission List</a>";
            echo "</br>";
            echo "<a href='user_roles.php?user_id={$user['id']}'>Role List</a>";
        }
        ?>
        <input type="submit" value="Update Roles">
    </form>
    <P>Navigation</P>
    <?php
    Page::display_navigation();
    ?>
</body>

</html>