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
      $this::$dbHandle = DbPDO::getInstance();
    else
      $this::$dbHandle = $pdo;
  }

  protected static function mapHistoricalData(Base $model, $row)
  {
    $model->setCreatedByUserId($row['createdByUserId']);
    $model->created = $row['created'];
    $model->setModifiedByUserId($row['modifiedByUserId']);
    $model->modified = $row['modified'];
    return $model;
  }

  protected static function IsInvalidId($id)
  {
    return ($id === null || $id < 1);
  }
}
