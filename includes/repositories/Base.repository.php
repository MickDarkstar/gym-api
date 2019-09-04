<?php
require_once('././DbPDO.php');
/**
 * Base class for repositories
 *
 * @author Mikael Fehrm <micke@tempory.org>
 * Version 2.0
 */
class BaseRepository
{
  protected static $dbHandle;

  public function __construct(PDO $pdo = null)
  {
    if ($pdo == null)
      $this::$dbHandle = dbPDO::getInstance();
    else
      $this::$dbHandle = $pdo;
  }
}
