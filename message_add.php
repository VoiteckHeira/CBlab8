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
include_once "classes/Db.php";
//require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
include_once "classes/Permission.php";
Page::display_header("Add message");

// Default values
$name = '';
$type = 'public';
$content = '';

// Filter input data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
}

// Validation
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($content)) {
        $errors[] = "Message content is required";
    }
}

// Save message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    $db = new Db("localhost", "news", "root", "");
    $db->addMessage($name, $type, $content, $_SESSION['login']);
    header("Location: messages.php");
    exit();
}

// Display form with errors (if any)
?>
<hr>
<P> Add message</P>
<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li>
                <?php echo $error ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<form method="post" action="messages.php">
    <table>
        <tr>
            <td>Name</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="name" id="name" size="56"
                    value="<?php echo htmlspecialchars($name) ?>" />
            </td>
        </tr>
        <tr>
            <td>Type</td>
            <td>
                <label for="type"></label>
                <select name="type" id="type">
                    <option value="public" <?php if ($type === 'public'): ?>selected<?php endif; ?>>Public</option>
                    <option value="private" <?php if ($type === 'private'): ?>selected<?php endif; ?>>Private</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Message content</td>
            <td>
                <label for="content"></label>
                <textarea required type="text" name="content" id="content" rows="10"
                    cols="40"><?php echo htmlspecialchars($content) ?></textarea>
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Add message" name="add_message">
</form>
<hr>
<P>Navigation</P>
<?php Page::display_navigation(); ?>
</body>

</html>