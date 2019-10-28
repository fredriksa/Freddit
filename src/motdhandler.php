<?php
/**
 * MOTDHandler is responsible for handling messages of the day.
 */

class MOTDHandler {  
  /**
   * generate
   * Generates a new MOTD string.
   *
   * @return string The generated MOTD string.
   */
  public function generate() {
    $count = $this->fetchCount();

    $noun = $this->fetchNoun();
    $noun[0] = strtoupper($noun[0]);

    $mid = $noun[strlen($noun)-2] == 's' ? 'are' : 'is';
    $adjective = $this->fetchAdjective();

    $this->incrementCount();
    return '#' . $count . ' ' . $noun . ' ' . $mid . ' ' . $adjective;
  }
  
  /**
   * fetchNoun
   * Fetches a random noun from the noun list.
   * 
   * @return string The fetched noun.
   */
  private function fetchNoun() {
    return $this->fetchRandomEntry('nouns.txt');
  }

  /**
   * fetchAdjective
   * Fetches a random adjective from the adjective list.
   * 
   * @return string The fetched adjective.
   */
  private function fetchAdjective() {
    return $this->fetchRandomEntry('adjectives.txt');
  }

  /**
   * fetchRandomEntry
   * Fetches a random entry from a resource file.
   *
   * @param  string $resourceFile The path to the resource file.
   *
   * @return string The random entry that was fetched from the file.
   */
  private function fetchRandomEntry($resourceFile) {
    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/resources/' . $resourceFile;
    $file = fopen($filePath, 'r');
    $lock = flock($file, LOCK_SH);

    $entries = array();
    if ($lock) {
      while ($line = fgets($file)) {
        array_push($entries, $line);
      }

      flock($file, LOCK_UN);
      fclose($file);
    }

    if (count($entries) > 0) {
      $entry = $entries[rand(0, count($entries)-1)];
      return $entry;
    }

    return null;
  }

  /**
   * fetchCount
   * Fetches the MOTD count.
   * @return void
   */
  private function fetchCount() {
    $countPath = $_SERVER['DOCUMENT_ROOT'] . '/resources/' . 'messageofthedaycounter.txt';
    $file = fopen($countPath, 'r'); 
    $lock = flock($file, LOCK_SH);

    if ($lock) {
      $count = intval(fgets($file));
      flock($file, LOCK_UN);
      fclose($file);
      return $count;
    }

    return -1;
  }

  /**
   * incrementCount
   * Increments the MOTD count by one.
   *
   * @return void Returns nothing.
   */
  private function incrementCount() {
    $currentCount = $this->fetchCount();
    
    if ($currentCount > 100000)
      $currentCount = -1;

    $countPath = $_SERVER['DOCUMENT_ROOT'] . '/resources/' . 'messageofthedaycounter.txt';
    $file = fopen($countPath, 'w');
    $lock = flock($file, LOCK_EX);

    if (!$lock) 
      return;

    fwrite($file, ++$currentCount);
    flock($file, LOCK_UN);
    fclose($file);
  }
}
?>