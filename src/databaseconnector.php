<?php
/**
* DatabaseConnector provides the functionality required to create
* prepared SQL statements and execute them against the server environment's
* MySQL database.
*/
class DatabaseConnector {
  private static $instance = null;
  private $db = null;

  /**
   * __construct 
   * The constructor of DatabaseConnector.
   * Prepares the PDO by reading the database information from the
   * server side configuration file.
   *
   * @return void Returns nothing.
   */
  private function __construct() {
    $config = include dirname(__DIR__, 1) . '/config.php';
    $link = sprintf('mysql:host=%s;port=%i;dbname=%s', $config['DB_HOST'], $config['DB_PORT'], $config['DB_NAME']);
    $this->db = new PDO($link, $config['DB_USERNAME'], $config['DB_PASSWORD']);
  }

  /**
   * getInstance
   * Fetches the DatabaseConnector's single(ton) instance.
   * If singleton does not exist, it is created at this time.
   *
   * @return The DatabaseConnector singleton instance.
   */
  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new DatabaseConnector();
    }

    return self::$instance;
  }
  
  /**
   * prepare
   * Prepares a PDOStatement for the DatabaseConnector's PDO 
   * with the provided SQL string.
   *
   * @param string $statement The statement to be prepared.
   *
   * @return PDOStatement Returns the prepared PDO statement object
   *                      if successfully prepared, False if not.
   */
  public function prepare($statement) {
    return $this->db->prepare($statement);
  }
}
?>