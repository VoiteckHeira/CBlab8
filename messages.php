<?php
include_once "session.php";
create_session();
?>

<?php
include_once "classes/Page.php";
include_once "classes/Db.php";
include_once "classes/Filter.php";
include_once "classes/Permission.php";
//require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
Page::display_header("Messages");

// Create a new Db object
$db = new Db("localhost", "news", "root", "");
require './htmlpurifier-4.15.0/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

// Adding new message
if (isset($_REQUEST['add_message'])) {
    $name = $purifier->purify($_REQUEST['name']);
    $type = $_REQUEST['type'];
    $content = $purifier->purify($_REQUEST['content']);
    if (!$db->addMessage($name, $type, $content, $_SESSION['login']))
        echo "Adding new message failed";


    // $stmt = $db->pdo->prepare("INSERT INTO message (name, type, message, deleted) VALUES (:name, :type, :content, 0)");
    // $t = Filter::sanitizeData($name, 'str');
    // $tt = Filter::sanitizeData($type, 'str');
    // $ttt = Filter::sanitizeData($content, 'str');
    // $stmt->bindParam(':name', $t);
    // $stmt->bindParam(':type', $tt);
    // $stmt->bindParam(':content', $ttt);
    // if (!$stmt->execute())
    //     echo "Adding new message failed";
}
?>
<!---------------------------------------------------------------------->
<hr>
<P> Messages</P>
<ol>
    <?php
    $where_clause = "";
    // filtering messages
    if (isset($_REQUEST['filter_messages'])) {
        $string = $_REQUEST['string'];
        $type = $_REQUEST['type'];
        if (in_array($type, ['public', 'private'])) {
            $where_clause = " WHERE name LIKE :string AND type = :type";
        }
    }




    $sql = "SELECT * from message"; // . $where_clause; //biala_lista
    $stmt = $db->pdo->prepare($sql);
    if (isset($_REQUEST['filter_messages'])) {
        $string = "%" . $_REQUEST['string'] . "%";
        $type = $_REQUEST['type'];
        if (in_array($type, ['public', 'private'])) {
            $tttt = Filter::sanitizeData($string, 'str');
            $ttttt = Filter::sanitizeData($type, 'str');
            $stmt->bindParam(':string', $tttt);
            $stmt->bindParam(':type', $ttttt);
        }
    }




    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_OBJ);


    foreach ($messages as $msg):
        ?>
        <table>
            <tr>
                <td>
                    <?php echo $msg->id ?>
                </td>
                <td>
                    <?php echo $msg->name ?>
                </td>
                <td>
                    <?php echo $msg->message ?>
                </td>
            </tr>
        </table>

        <?php


    endforeach;
    ?>
</ol>
<!---------------------------------------------------------------------->
<hr>
<P>Messages filtering</P>
<form method="post" action="messages.php">
    <table>
        <tr>
            <td>Title contains: </td>
            <td>
                <label for="name"></label>
                <input required type="text" name="string" id="string" size="80" />
            </td>
            <td>Type: </td>
            <td>
                <select name="type" id="type">
                    <option value="public">public</option>
                    <option value="private">private</option>
                </select>
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Find messages" name="filter_messages">
</form>

<!--------------------------------------------------------------------->

<hr>
<P>Messages editing</P>
<form method="post" action="message_edit.php">
    <table>
        <tr>
            <td>Input id of message to edit: </td>
            <td>
                <label for="id"></label>
                <select name="id">
                    <?php foreach ($messages as $msg): ?>
                    <option value="<?php echo $msg->id; ?>"><?php echo $msg->id; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Edit message" name="edit_message">
</form>
<!--------------------------------------------------------------------->

<?php if (!empty($_SESSION['permissions'][10])): ?>
<hr>
<P>Messages remove</P>
<form method="post" action="message_remove.php">
    <table>
        <tr>
            <td>Input id of message to remove: </td>
            <td>
                <label for="id"></label>
                <input required type="number" name="id" id="id" size="20" />
            </td>
        </tr>
    </table>
    <input type="submit" id="submit" value="Remove message" name="remove_message">
</form>
<?php endif; ?>

<hr>
<P>Navigation</P>
<?php
Page::display_navigation();
?>

</body>

</html>