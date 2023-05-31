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
include_once "classes/Page.php";
//require 'pdo.php';

$pdo = new Pdo_();
$Perm = new Permission();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role_id = $_POST['role_id'];
    $Perm->remove_role($role_id);
    header("Location: roles_list.php");
    exit();
}

$roles = $Perm->get_roles();
?>

<!DOCTYPE html>
<html>

<body>
    <form method="post">
        <label for="role_id">Wybierz rolę do usunięcia:</label>
        <select id="role_id" name="role_id">
            <?php foreach ($roles as $role): ?>
                <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Usuń rolę">
    </form>
    <hr>
    <P>Navigation</P>
    <?php Page::display_navigation(); ?>
</body>

</html>