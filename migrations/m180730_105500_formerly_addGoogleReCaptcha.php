<?php
namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName.
 */
class m180730_105500_formerly_addGoogleReCaptcha extends BaseMigration
{
  public function safeUp()
  {
    $table = new Formerly_FormRecord;
    $this->addColumnAfter($table->getTableName(), 'reCaptchaSecretKey', 'Varchar(255)', 'uid');
    $this->addColumnAfter($table->getTableName(), 'reCaptchaSiteKey', 'Varchar(255)', 'uid');
    $this->addColumnAfter($table->getTableName(), 'reCaptcha', ColumnType::Bool, 'uid');
    return true;
  }
}
