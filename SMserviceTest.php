<?php
/**
 * UnitTest webservice
 * Test each method in SMservice.
 */

ini_set('display_errors', '1');
require_once("SMservice.php");

class test_SMservice {

  /**
   * Test createTopic method
   * Expected result matches returned result
   */
  public function test_createTopic_success() {
    $test = new SMservice();
    $data = "New Topic";
    $result = $test->createTopic($data);
    $json = json_decode($result); // decode JSON

    assert($json[1] == $data); // verify result
    echo "Success: created Topic > ".$data;
  }

  /**
   * Test deleteTopic method
   * Expected result matches returned result
   */
  public function test_deleteTopic_success() {
    $test = new SMservice();
    $data = "New Topic";
    $result = $test->deleteTopic($data);
    $json = json_decode($result); // decode JSON

    assert($json[0] == $data); // verify result
    echo "Success: deleted Topic > ".$data;
  }

  /**
   * Test allTopics method
   * If valid JSON is returned consider a success.
   */
  public function test_allTopics_success() {
    $test = new SMservice();
    $result = $test->allTopics();
    $json = json_decode($result); // decode JSON

    echo "Success: list of all Topics\n";
    assert(!json_last_error()); // verify result
    echo $result;
  }

  /**
   * Test getTopic method
   * Expected result matches returned result
   */
  public function test_getTopic_success() {
    $test = new SMservice();
    $data = "Mobile";
    $result = $test->getTopic($data);
    $json = json_decode($result); // decode JSON

    echo "Success: get Topic details\n";
    assert($json->{"title"} == $data); // verify result
    echo $result;
  }

  /**
   * Test createArticle method
   * Expect failure creating Article for Topic that does not exist.
   * Assertion fails because sql exception, not valid JSON returned.
   */
  public function test_createArticle_failure() {
    $test = new SMservice();
    $title = "Test Title";
    $author = "Test Author";
    $text = "Test Text";
    $topicId = 88;
    $result = $test->createArticle($title, $author, $text, $topicId); // no TopicID 88
    $json = json_decode($result); // decode JSON

    assert(!json_last_error(), "Failure: error creating Article for Topic that does not exist."); // verify result
  }

  /**
   * Test createArticle method
   * Expected result matches returned result
   */
  public function test_createArticle_success() {
    $test = new SMservice();
    $title = "New Article";
    $author = "Test Author";
    $text = "Test Text";
    $topicId = 8;
    $result = $test->createArticle($title, $author, $text, $topicId); // valid topicID 8
    $json = json_decode($result); // decode JSON

    assert($json[1] == $title); // verify result
    echo "\n\nSuccess: creating Article > ".$title;
  }

  /**
   * Test deleteArticle method
   * Expected result matches returned result
   */
  public function test_deleteArticle_success() {
    $test = new SMservice();
    $data = "New Article";
    $result = $test->deleteArticle($data);
    $json = json_decode($result); // decode JSON

    assert($json[0] == $data); // verify result
    echo "Success: deleted Article > ".$data;
  }

  /**
   * Test allArticles method
   * If valid JSON is returned consider a success.
   */
  public function test_allArticles_success() {
    $test = new SMservice();
    $data = 8;
    $result = $test->allArticles($data);
    $json = json_decode($result); // decode JSON

    echo "Success: list of all Articles\n";
    assert(!json_last_error()); // verify result
    echo $result;
  }

  /**
   * Test getArticle method
   * Expected result matches returned result
   */
  public function test_getArticle_success() {
    $test = new SMservice();
    $data = "New Article";
    $result = $test->getArticle($data);
    $json = json_decode($result); // decode JSON

    echo "Success: get Article details\n";
    assert($json->{"title"} == $data); // verify result
    echo $result;
  }

}



/**
 * quick scripting to run unit tests
 * included a failure test
 */
$runTest = new test_SMservice();

echo "Running Unit Tests...";

echo "\n\n*** Test Create Topic\n\n";
$runTest->test_createTopic_success();

echo "\n\n*** Test List All Topics\n\n";
$runTest->test_allTopics_success();

echo "\n\n*** Test Delete Topic\n\n";
$runTest->test_deleteTopic_success();

echo "\n\n*** Test Get Topic\n\n";
$runTest->test_getTopic_success();

echo "\n\n*** Test Create Article for Topic\n\n";
$runTest->test_createArticle_failure();
$runTest->test_createArticle_success();

echo "\n\n*** Test Get Article\n\n";
$runTest->test_getArticle_success();

echo "\n\n*** Test List All Articles\n\n";
$runTest->test_allArticles_success();

echo "\n\n*** Test Delete Article\n\n";
$runTest->test_deleteArticle_success();

echo "\n\nTests Completed";
?>
