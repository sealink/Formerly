<?php
namespace Craft;

class Formerly_SubmissionElementType extends BaseElementType
{
	public function getName()
	{
		return Craft::t('Submissions');
	}

	public function hasContent()
	{
		return true;
	}

	public function hasTitles()
	{
		return false;
	}

	public function getSources($context = null)
	{
		$sources = array();

		foreach (craft()->formerly_forms->getAllForms() as $form)
		{
			$sources[$form->id] = array(
				'label'    => $form->name,
				'criteria' => array('formId' => $form->id)
			);
		}

		return $sources;
	}

	public function defineTableAttributes($source = null)
	{
		$form = craft()->formerly_forms->getFormById($source);

		$attributes = array();

		$attributes['id'] = 'ID';

		if ($form)
		{
			foreach ($form->getQuestions() as $question)
			{
				if ($question->type != Formerly_QuestionType::MultilineText)
				{
					$attributes[$question->handle] = $question->name;
				}

				if (count($attributes) >= 5)
				{
					break;
				}
			}
		}

		$attributes['dateCreated'] = Craft::t('Date Created');
		$attributes['action'] = '';

		return $attributes;
	}

	public function getTableAttributeHtml(BaseElementModel $element, $attribute)
	{
		if ($attribute == 'action')
		{
			return '<a class="delete icon" role="button" title="Delete"></a>';
		}
		else
		{
			$value = $element->$attribute;

			if($value instanceof MultiOptionsFieldData)
			{
				$options = $value->getOptions();

				$summary = array();

				for ($j = 0; $j < count($options); ++$j)
				{
					$option = $options[$j];
					if($option->selected)
						$summary[] = $option->label;
				}
				return implode($summary, ', ');
			}
			else
			{
				//Output JSON Objects in a nicer format
				$jsnObject = json_decode($value);
				if($jsnObject != null && (is_object($jsnObject) || is_array($jsnObject)) )
				{
					return Formerly_SubmissionElementType::arrayOutputFormatter($jsnObject);
				}
				else
				{
					return parent::getTableAttributeHtml($element, $attribute);
				}
			}
		}
	}

	//New formatter for JSON objects from the Programic type
	private function arrayOutputFormatter($arr)
	{
		$return = '';
		foreach ($arr as $k => $v)
		{
			if(is_array($v)){
				$v = arrayOutputFormatter($v);
			}

			if(strlen($v))
			{
				$return .= $k.': '.$v.' &nbsp; ';
			}
		}
		return $return;
	}

	public function defineCriteriaAttributes()
	{
		return array(
			'form'   => AttributeType::Mixed,
			'formId' => AttributeType::Mixed,
		);
	}

	public function modifyElementsQuery(DbCommand $query, ElementCriteriaModel $criteria)
	{
		$query
			->addSelect('submissions.formId')
			->join('formerly_submissions submissions', 'submissions.id = elements.id');

		if ($criteria->formId)
		{
			$query->andWhere(DbHelper::parseParam('submissions.formId', $criteria->formId, $query->params));
		}

		if ($criteria->form)
		{
			$query->join('formerly_forms forms', 'forms.id = submissions.formId');
			$query->andWhere(DbHelper::parseParam('formerly_forms.handle', $criteria->form, $query->params));
		}
	}

	public function populateElementModel($row)
	{
		return Formerly_SubmissionModel::populateModel($row);
	}
}
