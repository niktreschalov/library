<?php

class DataBase
{

    private static $db = null; // Единственный экземпляр класса, чтобы не создавать множество подключений
    private $mysqli; // Идентификатор соединения
    /* Получение экземпляра класса. Если он уже существует, то возвращается, если его не было, то создаётся и возвращается (паттерн Singleton) */
    public static function getDB()
    {
        if (self::$db == null) self::$db = new DataBase();
        return self::$db;
    }

    public function __construct()
    {
        $host = 'localhost'; // адрес сервера
        $database = 'library'; // имя базы данных
        $user = 'root'; // имя пользователя
        $password = ''; // пароль
        $this->mysqli = new mysqli($host, $user, $password, $database);
        $this->mysqli->query("SET lc_time_names = 'ru_RU'");
        $this->mysqli->query("SET NAMES 'utf8'");
    }

    public function get_all_authors()
    {
            $res = $this->mysqli->query(
                "SELECT author.author_id, author.author_name, (select TRUNCATE(AVG(rating_book),1) AS rating FROM book, authorship WHERE (book.book_id = authorship.book_id) AND (authorship.author_id = author.author_id) GROUP BY author.author_id ) AS rating FROM author;");

        $i = 0;
        while (($row = $res->fetch_assoc()) != false) {
            $array[$i] = $row;
            $i = $i + 1;
        }
        return $array;
    }

    public function add_author($author_name)
    {
        echo "Автор добавлен! <br>";
        $res = $this->mysqli->query("INSERT INTO `author` (`author_id`, `author_name`) VALUES (NULL, '" . $author_name . "');");
        header('Location: ./index.php');
    }

    public function delete_author($author_name)
    {
        $res = $this->mysqli->query("DELETE FROM `author` WHERE `author_id` = '" . $author_name . "';");
        header('Location: ./index.php');
    }

    public function edit_author($id,$name)
    {
        $res = $this->mysqli->query("UPDATE `author` SET `author_name` = '".$name."' WHERE `author_id` = '".$id."';");
        header('Location: ./index.php');
    }

    public function get_author_name($id){
        $res = $this->mysqli->query("SELECT author_name FROM author where author_id=" . $id . ";");
        while (($row = $res->fetch_assoc()) != false) {
        $array=array_values($row);
        }
        $name=$array[0];
        return $name;
    }

    public function get_author_rating($id){
        $res = $this->mysqli->query("select TRUNCATE(AVG(rating_book),1) AS rating FROM book, authorship WHERE (book.book_id = authorship.book_id) AND (authorship.author_id = '".$id."') GROUP BY author_id;");
        $rating = $res->fetch_assoc();
        if (isset($rating["rating"])) {
            $resoult = $rating["rating"];
        } else {
            $resoult = 0;
        }
        return $resoult;
    }

    public function get_author_genre($id) {
        $res = $this->mysqli->query(
            "SELECT GROUP_CONCAT(`genre_id`) as `genre` from book_genre, authorship WHERE (authorship.author_id = '".$id."') AND (authorship.book_id =book_genre.book_id) GROUP BY book_genre.book_id;");
        $genre = $res->fetch_assoc();
        $resoult = $genre["genre"];
        return $resoult;
    }

    public function add_book($author_id,$title,$genre,$rating)
    {
        $book = new Book ("","","","");
        $book->title = $title;
        $book->rating = $rating;
        $genres = $book->sort_genre($genre);
        print_r($genres);
        $res = $this->mysqli->query(
            "INSERT INTO `book` (`book_id`,`book_title`,`rating_book`) VALUES (NULL,'".$book->title."','".$book->rating."');");
        $res = $this->mysqli->query(
            "SELECT `book_id` FROM `book` where `book_title`='".$book->title."';");
        while (($row = $res->fetch_assoc()) != false) {
            $array=array_values($row);
        }
        $book->id = $array[0];
        $res = $this->mysqli->query(
            "INSERT INTO `authorship` (`book_id`,`author_id`) VALUES ('".$book->id."','".$author_id."');");
        foreach ($genres as $value) {
            $res = $this->mysqli->query("INSERT INTO `book_genre` (`genre_id`,`book_id`)
            VALUES ('".$value."','".$book->id."');");
        }
        header('Location: ./index.php');
    }

    public function edit_book($author_id,$book_id,$title,$genre,$rating)
    {
        $book = new Book ("","","","");
        $book->id = $book_id;
        $book->title = $title;
        $book->rating = $rating;
        $genres = $book->sort_genre($genre);
        $res = $this->mysqli->query(
            "UPDATE `book` SET `book_title` = '".$book->title."' , `rating_book` = '".$book->rating."' WHERE `book_id` = '".$book->id ."';");
        $res = $this->mysqli->query("DELETE FROM `book_genre` WHERE `book_id` = '" . $book->id . "';");
        foreach ($genres as $value) {
            $res = $this->mysqli->query("INSERT INTO `book_genre` (`genre_id`,`book_id`)
            VALUES ('".$value."','".$book->id."');");
        }
        header('Location: ./index.php');
    }

    public function delete_book($book_id)
    {
        $res = $this->mysqli->query("DELETE FROM `book_genre` WHERE `book_id` = '".$book_id."';");
        $res = $this->mysqli->query("DELETE FROM `book` WHERE `book_id` = '".$book_id."';");
        $res = $this->mysqli->query("DELETE FROM `authorship` WHERE `book_id` = '".$book_id."';");
        header('Location: ./index.php');
    }

    public function get_books($id)
    {
        $res = $this->mysqli->query(
            "SELECT book.book_id, book.book_title , book.rating_book 
                    FROM book, authorship 
                    WHERE (book.book_id = authorship.book_id) AND (authorship.author_id = '".$id."')  ;");
        $i = 0;
        while (($row = $res->fetch_assoc()) != false) {
            $array[$i] = array_values($row);
            $i = $i + 1;
        }
        return $array;
    }

    public function get_book($book_id)
    {
        $res = $this->mysqli->query(
            "SELECT book.book_id, book.book_title , book.rating_book 
                    FROM book 
                    WHERE (book.book_id = '".$book_id."')  ;");
        $resoult=$res->fetch_assoc();
        return $resoult;
    }

    public function get_genre($id) {
        $res = $this->mysqli->query(
        "SELECT GROUP_CONCAT(`genre_id`) as `genre` from book_genre WHERE `book_id` = '".$id."';");
        $genre = $res->fetch_assoc();
        $resoult = $genre["genre"];
        return $resoult;
    }
}

class Author{
    public $id;
    public $name;
    public $rating;
    public function __construct($id,$name,$rating)
    {
        $this->id;
        $this->name;
        $this->rating;
    }
}

class Book{
    public $id;
    public $title;
    public $genre;
    public $rating;
    public function __construct($id,$title,$genre,$rating)
    {
        $this->id;
        $this->title;
        $this->genre;
        $this->rating;
    }

    public function sort_genre($genre){
        $genre = str_replace(' ', '',$genre);   //удаление пробелов
        $resoult = explode(",",$genre);         //разбиваем строку из жанров на массив
        return $resoult;
}
}

?>