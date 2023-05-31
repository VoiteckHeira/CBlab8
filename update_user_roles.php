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
if (empty($_SESSION['permissions'][1])) {
    die;
} ?>
<?php
include_once "classes/Pdo.php";
include_once "classes/Permission.php";
$pdo = new Pdo_();
$Perm = new Permission();

$user_id = $_POST['user_id']; // Zastąp tym konkretnym ID użytkownika
$roles = $_POST['roles'];

print_r($_POST);

// Usuń wszystkie role użytkownika
$Perm->remove_all_user_roles($user_id);

// Dodaj zaznaczone role
foreach ($roles as $role_id) {
    $Perm->add_user_role($user_id, $role_id);
}

header("Location: user_roles.php?user_id={$user_id}");
exit;
?>