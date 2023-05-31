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
$Perm = new Permission();
$pdo = new Pdo_();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $permission_id = $_POST['permission_id'];
    $Perm->remove_permission($permission_id);
    header("Location: permissions_list.php");
    exit();
}


$permissions = $perm->get_permissions();
?>

<!DOCTYPE html>
<html>

<body>
    <form method="post">
        <label for="permission_id">Wybierz uprawnienie do usunięcia:</label>
        <select id="permission_id" name="permission_id">
            <?php foreach ($permissions as $permission): ?>
                <option value="<?php echo $permission['id']; ?>"><?php echo $permission['name']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Usuń uprawnienie">
    </form>
    <hr>
    <P>Navigation</P>
    <?php Page::display_navigation(); ?>
</body>

</html>