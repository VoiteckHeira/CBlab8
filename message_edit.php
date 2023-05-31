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




?><h5><?php
    if (!empty($_SESSION['login'])) {
        echo $_SESSION['login'];
    } else {
        echo 'niezalogowany';
    }
?></h5><?php
include_once "classes/Page.php";
include_once "classes/Db.php";
include_once "classes/Filter.php";
include_once "classes/Permission.php";
//require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
Page::display_header("Edit message");



if (isset($_POST['id'])) {
    $message_id = Filter::sanitizeData($_POST['id'], 'num');
    $db = new Db("localhost", "news", "root", "");
    $res = $db->getSingleMessage($message_id);
    foreach ($res as $msg) :
        $message_title = $msg['name'];
        $message_content = $msg['message'];
        $message_type = $msg['type'];
        $message_author = $msg['login'];
    endforeach;
}



if (empty($_SESSION['permissions'][9]) && $message_author != $_SESSION['login']) {
    die;
}

if (isset($_POST['update_message'])) {
    $message_id = Filter::sanitizeData($_POST['id'], 'num');
    $message_title = Filter::sanitizeData($_POST['name'], 'str');
    $message_type = Filter::sanitizeData($_POST['type'], 'str');
    $message_content = Filter::sanitizeData($_POST['content'], 'str');

    $db->updateMessage($message_id, $message_title, $message_type, $message_content);
    header("Location: messages.php");
    exit();
}

?>
<hr>
<P> Edit message</P>
<form method="post" action="">
    <table>
        <tr>
            <td></td>
            <td>
                <label for="id"></label>
                <input disabled type="number" name="id" value="<?php echo $message_id ?>">
                <input type="hidden" name="id" value="<?php echo $message_id ?>">
            </td>
        </tr>
        <tr>
            <td>Name</td>
            <td>
                <label for="name"></label>
                <input required type="text" name="name" id="name" size="56"
                value="<?php echo $message_title ?>"/>
            </td>
        </tr>
        <tr>
            <td>Type</td>
            <td>
                <label for="type"></label>
                <select name="type" id="type">
                    <option value="public"
                    <?php if($message_type == 'public'): ?>
                        selected="selected"
                    <?php endif; ?>>Public</option>
                    <option value="private"
                    <?php if($message_type == 'private'): ?>
                        selected="selected"
                    <?php endif; ?>>Private</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Message content</td>
            <td>
                <label for="content"></label>
                <textarea required type="text" name="content"
                id="content" rows="10" cols="40"><?php echo $message_content ?></textarea>
            </td>
        </tr>
    </table>
    <input type="submit" id="update_message" value="Update message" name="update_message">
</form>
<hr>
<P>Navigation</P>
<?php Page::display_navigation(); ?>
</body>

</html>
