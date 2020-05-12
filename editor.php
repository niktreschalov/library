<?php
require_once 'class.php';
$db = DataBase::getDB();

/////////////////////////////////////////////////////////////////////// ДОБАВЛЕНИЕ АВТОРА
if (isset($_GET["new_author"])) {

    if (isset($_POST["add_author"])) {
        $db->add_author($_POST["author_name"]);
    }

    echo "<h1>Добавление автора</h1>
      <a href='./index.php'>Назад в каталог</a><br>
      <br>
      <form name='add_author' action='editor.php?new_author' method='post'>

            <input type='text' name='author_name' placeholder='Имя автора'>

            <input type='submit' name='add_author' value='Добавить'>
        </form>";
}


/////////////////////////////////////////////////////////////////////// ИЗМЕНЕНИЕ АВТОРА
if (isset($_GET["edit_author"])) {

    $author = new Author("","","");
    $author->id=$_GET["author_id"];
    $author->name=$db->get_author_name($_GET["author_id"]);

    if (isset($_POST["edit_author"])) {
        $db->edit_author($author->id,$_POST["author_name"]);
    }

    echo "<h1>Изменение автора</h1>
      <a href='./index.php'>Назад в каталог</a><br>
      <br>
      <form name='add_author' action='editor.php?edit_author&author_id=".$author->id."' method='post'>
            <input type='text' name='author_name' placeholder='Имя автора' value='".$author->name."'>
            <input type='submit' name='edit_author' value='Изменить'>
        </form>";
}



/////////////////////////////////////////////////////////////////////// УДАЛЕНИЕ АВТОРА
if (isset($_GET["delete_author"])){
    echo $_GET["author_id"];
    $db->delete_author($_GET["author_id"]);
}

/////////////////////////////////////////////////////////////////////// ДОБАВЛЕНИЕ КНИГИ
if (isset($_GET["new_book"])) {
    $author = new Author("","","");
    $author->id=$_GET["author"];
    $author->name=$db->get_author_name($_GET["author"]);
    if (isset($_POST["add_author"])) {
        $db->add_book($author->id,$_POST["book_title"],$_POST["book_genre"],$_POST["book_rating"]);
    }

    echo "<h1>Добавление книги</h1>
      <a href='./viewer.php?author=".$author->id."'>Назад в каталог</a><br>
      <br>
      <h2>Автор: ".$author->name."</h2>
      <form name='add_book' action='editor.php?new_book&author=".$author->id."' method='post'>

            <input type='text' name='book_title' placeholder='Название'>
            <input type='text' name='book_genre' placeholder='Жанры (вводите через ,)'>
            <input type='text' name='book_rating' placeholder='Рейтинг'>
            <input type='submit' name='add_author' value='Добавить'>
        </form>";
}

/////////////////////////////////////////////////////////////////////////   ИЗМЕНЕНИЕ КНИГИ
if (isset($_GET["edit_book"])) {

    $author = new Author("","","");
    $author->id=$_GET["author_id"];
    $author->name=$db->get_author_name($author->id);

    $book = new Book("","","","");
    $book->id = $_GET["book_id"];
    $array = $db->get_book($book->id);
    $book->title=$array["book_title"];
    $book->genre=$db->get_genre($book->id);
    $book->rating=$array["rating_book"];;

    if (isset($_POST["edit_book"])) {
        $db->edit_book($author->id,$_GET["book_id"],$_POST["book_title"],$_POST["book_genre"],$_POST["book_rating"]);
    }

    echo "<h1>Изменение книги</h1>
      <a href='./viewer.php?author=".$author->id."'>Назад в каталог</a><br>
      <br>
      <h2>Автор: ".$author->name."</h2>
      <form name='edit_book' action='editor.php?edit_book&author_id=".$author->id."&book_id=".$book->id."' method='post'>

            <input type='text' name='book_title' placeholder='Название' value='".$book->title."'>
            <input type='text' name='book_genre' placeholder='Жанры (вводите через ,)' value='".$book->genre."'>
            <input type='text' name='book_rating' placeholder='Рейтинг' value='".$book->rating."'>
            <input type='submit' name='edit_book' value='Изменить'>
        </form>";
}

/////////////////////////////////////////////////////////////////////// УДАЛЕНИЕ КНИГИ
if (isset($_GET["delete_book"])){
    $db->delete_book($_GET["book_id"]);
}

?>