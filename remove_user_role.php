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
}
?>
<?php
//require 'pdo.php';
include_once "classes/Pdo.php";
include_once "classes/Permission.php";
$pdo = new Pdo_();
$Perm = new Permission();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $role_id = $_POST['role_id'];
    $Perm->remove_user_role($user_id, $role_id);
    header("Location: user_roles.php?user_id=" . $user_id);
    exit();
}

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$users = $Perm->get_users();
if (!$user_id) {
    $user_id = $users[0]['id']; // Domyślnie wybieramy pierwszego użytkownika
}
$roles = $Perm->get_user_roles($user_id);
?>

<!DOCTYPE html>
<html>

<body>
    <form method="post">
        <label for="user_id">Wybierz użytkownika:</label>
        <select id="user_id" name="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['id']; ?>" <?php if ($user['id'] == ($_GET['user_id'] ?? 0))
                       echo 'selected'; ?>><?php echo $user['login']; ?></option>
            <?php endforeach; ?>
        </select>

        <?php if (!empty($_GET['user_id'])): ?>
            <label for="role_id">Wybierz rolę do usunięcia:</label>
            <select id="role_id" name="role_id">
                <?php
                $roles = $Perm->get_user_roles($_GET['user_id']);
                foreach ($roles as $role): ?>
                    <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>

                <?php endforeach; ?>
            </select>
            <input type="submit" value="Usuń rolę użytkownikowi">
        <?php endif; ?>
    </form>

    <script>
        document.getElementById('user_id').addEventListener('change', function () {
            window.location.href = 'remove_user_role.php?user_id=' + this.value;
        });
    </script>
</body>

</html>