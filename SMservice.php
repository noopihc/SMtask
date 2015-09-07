<?php
/**
 * SM webservice.
 */

require_once("config.php"); // Include database configuration file

class SMservice {

  private $db;

  public function __construct() {
    $this->dbConnect();
  }

  // Database connection
  private function dbConnect() {
    $host = MYSQL_HOST;
    $port = MYSQL_PORT;
    $name = MYSQL_NAME;

    $this->db = new PDO("mysql:host=$host;port=$port;dbname=$name;charset=utf8",MYSQL_USER,MYSQL_PASS);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  }

  /**
   * Create Topic
   * @param string $title
   */
  public function createTopic($title) {
    $sql = $this->db->prepare("INSERT INTO topic (title) VALUES (?)");
    $sql->bindParam(1, $title);

    try {
      $sql->execute();
      $lastId = $this->db->lastInsertId();  // Get autoincremented Id of new Topic
      return $this->jsonResponse(array($lastId, $title)); // Return json of inserted Topic details
    } catch (Exception $e) { // Catch sql errors
      return $e->getMessage();
    }
  }

  /**
   * Delete Topic
   * @param string $title
   */
  public function deleteTopic($title) {
    $sql = $this->db->prepare("DELETE FROM topic WHERE title = ?");
    $sql->bindParam(1, $title);

    try {
      $sql->execute();
      return $this->jsonResponse(array($title));
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  /**
   * List all Topics
   */
  public function allTopics() {
    $sql = $this->db->prepare("SELECT id, title FROM topic");

    try {
      $sql->execute();
      $result = $sql->fetchAll(); // Get array of all Topics
      return $this->jsonResponse($result);
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  /**
   * Show Topic details
   * @param string $title
   */
  public function getTopic($title) {
    $sql = $this->db->prepare("SELECT id, title FROM topic WHERE title = ?");
    $sql->bindParam(1, $title);

    try {
      $sql->execute();
      $result = $sql->fetch(); // fetch Topic details result
      return $this->jsonResponse($result);
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  /**
   * Create Article for Topic
   * @param string $title
   * @param string $author
   * @param string $text
   * @param int $topicId
   */
  public function createArticle($title, $author, $text, $topicId) {
    $sql = $this->db->prepare("INSERT INTO article (title, author, text, topicID) VALUES (?, ?, ?, ?)");
    $sql->bindParam(1, $title);
    $sql->bindParam(2, $author);
    $sql->bindParam(3, $text);
    $sql->bindParam(4, $topicId);

    try {
      $sql->execute();
      $lastId = $this->db->lastInsertId();  // Get autoincremented Id of new Article
      return $this->jsonResponse(array($lastId, $title, $author, $text, $topicId)); // Return json of inserted Article details
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  /**
   * Delete Article
   * @param string $title
   */
  public function deleteArticle($title) {
    $sql = $this->db->prepare("DELETE FROM article WHERE title = ?");
    $sql->bindParam(1, $title);

    try {
      $sql->execute();
      return $this->jsonResponse(array($title));
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  /**
   * List all Articles of Topic
   * @param int $topicId
   */
  public function allArticles($topicId) {
    $sql = $this->db->prepare("SELECT id, title, author, text, topicID FROM article WHERE topicID = ?");
    $sql->bindParam(1, $topicId);

    try {
      $sql->execute();
      $result = $sql->fetchAll(); // Get array of all Articles
      return $this->jsonResponse($result);
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  /**
   * Show Article details
   * @param string $title
   */
  public function getArticle($title) {
    $sql = $this->db->prepare("SELECT id, title, author, text, topicID FROM article WHERE title = ?");
    $sql->bindParam(1, $title);

    try {
      $sql->execute();
      $result = $sql->fetch(); // fetch Article details result
      return $this->jsonResponse($result);
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  /**
   * Return JSON
   * @param array $data
   */
  private function jsonResponse($data) {
    header("HTTP/1.1 200 OK"); // send http response code '200 OK' as default for simplicity
    header("Content-Type: application/json"); // send http content type as JSON
    return json_encode($data);
  }

  /**
   * Process Request
   * Only handling simple GET requests for simplicity.
   * @param string $method
   * @param string $title
   * @param string $author
   * @param string $text
   * @param int $topicId
   */
   public function action($method, $title = "", $author = "", $text = "", $topicId = "") {
     switch($method) {
       case 'createtopic':
         echo $this->createTopic($title);
         break;
       case 'deletetopic':
         echo $this->deleteTopic($title);
         break;
       case 'alltopics':
         echo $this->allTopics();
         break;
       case 'gettopic':
         echo $this->getTopic($title);
         break;
       case 'createarticle':
         echo $this->createArticle($title, $author, $text, $topicId);
         break;
       case 'deletearticle':
         echo $this->deleteArticle($title);
         break;
       case 'allarticles':
         echo $this->allArticles($topicId);
         break;
       case 'getarticle':
         echo $this->getArticle($title);
         break;
     }
   }

}

?>
