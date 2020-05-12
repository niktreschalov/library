<?php
require_once 'class.php';

echo "<h1>Каталог</h1>";
echo "<a href='./editor.php?new_author'>Новый автор</a><br><br>";

echo "Поиск по жанру
<form name='search_genre' action='./index.php?search' method='post'>
            <input type='text' name='genre' placeholder='Введите жанр'>
            <input type='submit' name='search' value='Поиск'>
        </form><br>";
echo "<table border=1>

<tr><td>ID автора</td><td>Имя автора</td><td>Рейтинг автора
<a href='./index.php'>ꓦ</a>
<a href='./index.php?desc'>ꓥ</a>
</td><td>Жанры</td></tr>";

$db = DataBase::getDB();

    $array = $db->get_all_authors();

if (isset($_GET["desc"])) {                     /////////////////////////////   ДЛЯ СОРТИРОВКИ

    function sort_rating($a,$b)
    {
        return $a["rating"] <=> $b["rating"];
    }

    usort ($array, 'sort_rating');
}

for ($i = 0; $i < count($array); $i++)
  {
      $author = new Author("","","");
      $author->id=$array[$i]["author_id"];
      $author->name=$array[$i]["author_name"];
      $author->rating=$array[$i]["rating"];
      $genre = $db->get_author_genre($author->id);

      if (isset($_POST["genre"])) {                     /////////////////////////////   ДЛЯ ПОИСКА
          $search_flag = 1;
      } else {
          $search_flag = 0;
      }
      if ($search_flag == 1) {
          $pos = strripos($genre, $_POST["genre"]);
          if ($pos === false) {
          } else {
          echo "<tr><td>" . $author->id . "</td>
              <td><a href='./viewer.php?author=" . $author->id . "'>" . $author->name . "</a></td>
              <td>" . $author->rating . "</td>
              <td>" . $genre . "</td>
              <td><a href='./editor.php?edit_author&author_id=" . $author->id . "'>Изменить</a></td>
              <td><a href='./editor.php?delete_author&author_id=" . $author->id . "'>Удалить</a></td></td></tr>";
      }
      }
      if ($search_flag == 0) {
          echo "<tr><td>" . $author->id . "</td>
              <td><a href='./viewer.php?author=" . $author->id . "'>" . $author->name . "</a></td>
              <td>" . $author->rating . "</td>
              <td>" . $genre . "</td>
              <td><a href='./editor.php?edit_author&author_id=" . $author->id . "'>Изменить</a></td>
              <td><a href='./editor.php?delete_author&author_id=" . $author->id . "'>Удалить</a></td></td></tr>";
      }
}
echo "</table>";
?>