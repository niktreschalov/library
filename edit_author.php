<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Редактор автора</title>
</head>
<body>
<b>Редактор автора</b><br>
<a href='./index.php'>Назад</a><br>
<br>
<?php
require_once 'class.php';
$db = DataBase::getDB();
if (isset($_POST["edit_author"])) {
  echo "<form name='edit_author' action='editor.php' method='post'>

        <input type='text' name='author_name' placeholder='Имя автора' value = ''>

        <input type='submit' name='add_author' value='Изменить'>
    </form>";
}

$db->add_author($_POST["author_name"]);

?>

    <form name="edit_author" action="editor.php" method="post">

        <input type="text" name="author_name" placeholder="Имя автора">

        <input type="submit" name="add_author" value="Добавить">
    </form>
    </body>
    </html>
