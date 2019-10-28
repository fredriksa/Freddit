<?php

include_once 'databaseconnector.php';
include_once 'datamapstore.php';

class ModelLoader {
  
  /**
   * loadAll
   * Loads all model IDs for a specific model.
   *
   * @param  string $model The model name identifier.
   * @param  mixed $filter The filter to be applied.
   * @param  string $orderby The name of the attribute to order by.
   * @param  mixed $count The maximum amount of model IDs to fetch.
   *
   * @return void
   */
  public static function loadAll($model, $filter = null, $orderby = null, $orderingorder ='DESC', $count = -1) {
    $db = DatabaseConnector::getInstance();
    $dataMap = DataMapStore::getInstance()->getMapping($model);
    $stmt = $db->prepare(
      ModelLoader::buildQuery(
        $dataMap->getCore()['primary'],
        $dataMap->getCore()['table'],
        $filter, 
        $dataMap->get($orderby), 
        $orderingorder,
        $count
      )
    );

    if ($count != -1) 
      $stmt->bindValue(':count', $count, PDO::PARAM_INT);

    $stmt->execute();
    $rows = $stmt->fetchAll();
    if (count($rows) == 0) 
      return null;

    $identifiers = array();
    foreach($rows as $row)
      array_push($identifiers, $row[$dataMap->getCore()['primary']]);

    return $identifiers;
  }

  public static function buildQuery($identifier, $table, $filter, $orderby, $orderingorder, $count) {
    $query = 'SELECT ' . $identifier . ' FROM ' . $table;

    if (isset($filter))
      $query .= ' WHERE ' . $filter; 

    if (isset($orderby)) 
      $query .= ' ORDER BY ' . $orderby . ' ' . $orderingorder;

    if ($count != -1) 
      $query .= ' LIMIT :count';

    return $query;
  }
}
?>