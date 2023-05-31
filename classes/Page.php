<?php
class Page
{
    static function display_header($title)
    { ?>
        <html lang="en-GB">

        <head>
            <title>
                <?php echo $title ?>
            </title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- <link rel="stylesheet" href="style.css" type="text/css" /> -->
        </head>

        <body>
            <?php
    }
    static function display_navigation()
    { ?>
            <a href="index.php">index</a><br>
            <a href="messages.php">messages</a><br>
            <a href="message_add.php">add new message</a><br>
            <a href="my_messages.php">my messages</a><br>

            <?php if (!empty($_SESSION['permissions'][1])): ?>
                <a href="permissions_list.php">permissions list</a><br>
                <a href="roles_list.php">roles list</a><br>
                <a href="add_permission.php">add permissions</a><br>
                <a href="delete_permission.php">delete permissions</a><br>
                <a href="delete_role.php">delete role</a><br>
                <a href="remove_user_role.php">remove user role</a><br>
                <a href="users.php">users list</a><br>

            <?php endif; ?>

            <?php
    }
}