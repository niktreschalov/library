<?php
require_once 'class.php';
$db = DataBase::getDB();

//Просмотр данных и рейтинга автора по id
$author = new Author("","","");
$author->id=$_GET["author"];
$author->name=$db->get_author_name($author->id);
$author->rating=$db->get_author_rating($author->id);
if (isset($_GET["author"])){
    echo "<h1>Карточка автора</h1>";
echo "<a href='./index.php'>Назад</a><br>";
echo "<table border=1>
<tr><td>ID</td><td>Автор</td><td>Рейтинг автора</td></tr>";
    echo "<tr><td>".$author->id."</td><td>".$author->name."</td><td>".$author->rating."</td></tr></table>";
}

//Каталог книг автора
echo "<h2>Книги:</h2>";
echo "<a href='./editor.php?new_book&author=".$author->id."'>Новая книга</a><br><br>";
echo "<table border=1>
<tr><td>ID книги</td><td>Название</td><td>Жанр</td><td>Рейтинг</td></tr>";
$array = $db->get_books($author->id);
$book = new Book("","","","");

for ($i = 0; $i < count($array); $i++)
{
    $book->id=$array[$i][0];
    $book->title=$array[$i][1];
    $book->genre=$db->get_genre($book->id);
    $book->rating=$array[$i][2];

    echo "<tr><td>".$book->id."</td>
              <td>".$book->title."</td>
              <td>".$book->genre."</td>
              <td>".$book->rating."</td>
              <td><a href='./editor.php?edit_book&author_id=".$author->id."&book_id=".$book->id."'>Изменить</a></td>
              <td><a href='./editor.php?delete_book&book_id=".$book->id."'>Удалить</a></td>
              </tr>";
}
echo "</table>";

?>