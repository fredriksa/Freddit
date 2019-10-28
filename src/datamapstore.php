<?php
require_once dirname(__DIR__, 1) . '/src/datamap.php';
/**
 * DataMapStore is responsible for loading data mappings.
 */
class DataMapStore {
  private static $instance = null;
  private $mappings = array();
  /**
   * __construct 
   * The constructor of DataMapStore.
   *
   * @return void Returns nothing.
   */
  private function __construct() {
    $this->loadMappings();
  }

  /**
   * loadMappings
   * Loads the mappings from the mappings file.
   * 
   * @return void Returns nothing
   */
  private function loadMappings() {
    $contents = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/resources/datamappings.json');
    $json = json_decode($contents, true);

    foreach($json as $model => $content) {

      $core = null;
      $keys = array();
      $columns = array();

      foreach($content as $key => $value) {
        if ($key == 'core')
          $core = $value;
        else {
          array_push($keys, $key);
          $value['name'] = $key;
          array_push($columns, $value);
        }
      }

      $datamap = new DataMap($keys, $columns);
      $datamap->setCore($core);
      $this->mappings[$model] = $datamap;
    }

  }

  /**
   * getInstance
   * Fetches the DataMapStore's single(ton) instance.
   * If singleton does not exist, it is created at this time.
   *
   * @return The DataMapStore singleton instance.
   */
  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new DataMapStore();
    }

    return self::$instance;
  }

  /**
   * getMapping
   * Fetches the mapping for a specific model.
   *
   * @param  string $model The name of the model to fetch the mapping for.
   *
   * @return mixed The mapping if successful and if not null.
   */
  public function getMapping($model) {
    return isset($this->mappings[$model]) ? $this->mappings[$model] : null;
  }
}
?>