<?php

namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName.
 */
class m160223_080724_import_saveEntryIdAndVersion extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     *
     * @return bool
     */
    public function safeUp()
    {

        // Allow changing to 'Custom'
        $this->execute("ALTER TABLE `craft_formerly_questions` CHANGE `type` `type` ENUM('PlainText','MultilineText','Coded','Custom', 'Dropdown','RadioButtons','Checkboxes','Email','Tel','Url','Number','Date') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");
        //Change 'Coded' to 'Custom'
        $this->execute("UPDATE `craft_formerly_questions` SET `type`='Custom' WHERE `type`='Coded'");
        //Remove the 'Coded' type
        $this->execute("ALTER TABLE `craft_formerly_questions` CHANGE `type` `type` ENUM('PlainText','MultilineText','Custom','Dropdown','RadioButtons','Checkboxes','Email','Tel','Url','Number','Date') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULt 'PlainText'");

        return true;
    }
}
