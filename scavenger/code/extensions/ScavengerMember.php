<?php

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class ScavengerMember extends DataExtension {
	public static $db = array(
		'Username'		=> 'Varchar',
	);
	
	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldToTab('Root.Main', TextField::create('Username', 'Username'), 'Email');
	}
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		
		if (preg_match('/\W/', $this->owner->Username)) {
			throw new ValidationException("Invalid username: a-z 0-9 or _ allowed");
		}
	}

	public function responsesInHunt(ScavengerHuntPage $scavengerHunt) {
		// return responses that haven't been rejected
		
		return DataList::create('TaskResponse')->filter(array(
			'ResponderID' => $this->owner->ID, 
			'Status:Negation' => 'Rejected'
		));
	}
}
