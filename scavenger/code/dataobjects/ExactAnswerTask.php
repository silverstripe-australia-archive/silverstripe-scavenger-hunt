<?php

/**
 *
 * @author marcus@symbiote.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class ExactAnswerTask extends ScavengerTask {
	public static $db = array(
		'ExpectedAnswer'	=> 'Varchar(255)',
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab('Root.Main', TextField::create('ExpectedAnswer','Answer'), 'Description');
		return $fields;
	}
	
	public function updateTaskFields(FieldList $fields) {
		$fields->push(new TextField('Answer', 'Answer'));
	}
	
	public function processSubmission($data) {
		if (isset($data['Answer']) && strtolower($data['Answer']) == strtolower($this->ExpectedAnswer)) {
			$response = $this->newResponse();
			$response->Response = $data['Answer'];
			$response->Status = 'Accepted';
			$response->write();
			return $response;
		}
		return 'Incorrect answer';
	}
}

