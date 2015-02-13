<?php
namespace Craft;

class FormerlyPlugin extends BasePlugin
{
	public function getName()
	{
	    return 'Formerly';
	}

	public function getVersion()
	{
	    return '1.3.0';
	}

	public function getDeveloper()
	{
	    return 'XO Digital';
	}

	public function getDeveloperUrl()
	{
	    return 'http://www.xodigital.com.au';
	}

	public function hasCpSection()
	{
		return true;
	}

	public function registerCpRoutes()
	{
		return array(
			'formerly/forms'                                          => array('action' => 'formerly/forms/index'),
			'formerly/forms/new'                                      => array('action' => 'formerly/forms/editForm'),
			'formerly/forms/(?P<formId>\d+)'                          => array('action' => 'formerly/forms/editForm'),
			'formerly'                                                => array('action' => 'formerly/submissions/index'),
			'formerly/(?P<formHandle>{handle})/(?P<submissionId>\d+)' => array('action' => 'formerly/submissions/viewSubmission'),
			'formerly/export'                                         => array('action' => 'formerly/export/index'),
			'formerly/export/csv'                                     => array('action' => 'formerly/export/csv')
		);
	}

	public function init()
	{
		//A way to get around display for JSON strings
		craft()->templates->hook('jsonProcessCoded', function(&$context)
		{
			foreach($context["submission"]->getForm()->getQuestions() as $question)
			{
				if($question["type"] == 'Coded')
				{
					$context['jsonParsed'][$question["handle"]] = json_decode($context["submission"][$question["handle"]], true);
				}
			}
		    $context['json'] = 'bar';
		    //return "JSON! <pre>".print_r($context,true)."</pre>";
		});
	}
}
