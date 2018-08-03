<?php
namespace Craft;

class Formerly_FormRecord extends BaseRecord
{
  public function getTableName()
  {
    return 'formerly_forms';
  }

  protected function defineAttributes()
  {
    return array(
      'name'          => array(AttributeType::Name, 'required' => true),
      'handle'        => array(AttributeType::Handle, 'required' => true),
      'emails'        => AttributeType::Mixed,
      'reCaptcha'          => array(AttributeType::Bool, 'label' => 'Use Google reCaptcha', 'default' => false),
      'reCaptchaSiteKey'   => array(AttributeType::String, 'label' => 'Google reCaptcha API Site Key'),
      'reCaptchaSecretKey' => array(AttributeType::String, 'label' => 'Google reCaptcha API Secret Key'),
    );
  }

  public function defineRelations()
  {
    return array(
      'fieldGroup'  => array(static::BELONGS_TO, 'FieldGroupRecord', 'onDelete' => static::SET_NULL),
      'submissions' => array(static::HAS_MANY, 'Formerly_SubmissionRecord', 'formId'),
    );
  }

  public function defineIndexes()
  {
    return array(
      array('columns' => array('name'), 'unique' => true),
      array('columns' => array('handle'), 'unique' => true),
    );
  }

  public function scopes()
  {
    return array(
      'ordered' => array('order' => 'name'),
    );
  }
}
