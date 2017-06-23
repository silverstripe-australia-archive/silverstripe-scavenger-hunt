<?php

/**
 * @author marcus@symbiote.com.au
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
		
		if (!$this->owner->FirstName) {
			$this->owner->FirstName = $this->owner->Username;
		}
		
		if (!$this->owner->Surname) {
			$this->owner->Surname = $this->owner->Email;
		}
	}

	public function responsesInHunt(ScavengerHuntPage $scavengerHunt) {
		// return responses that haven't been rejected
		
		return DataList::create('TaskResponse')->filter(array(
			'ResponderID' => $this->owner->ID, 
			'Status:Negation' => 'Rejected'
		));
	}
	
	public function summaryForHunt(ScavengerHuntPage $scavengerHunt) {
		$existing = DataList::create('MemberHuntSummary')->filter(array(
			'MemberID'		=> $this->owner->ID,
			'HuntID'		=> $scavengerHunt->ID,
		))->first();
		
		if (!$existing) {
			$existing = MemberHuntSummary::create();
			$existing->Title = 'Summary for ' . $this->owner->Username . ' in "' . $scavengerHunt->Title . '"';
			$existing->MemberID = $this->owner->ID;
			$existing->HuntID = $scavengerHunt->ID;
			$existing->write();
		}
		return $existing;
	}

	public function memberFolder() {
		// get the folder for this user
		$name = md5($this->owner->Username);
		$path = 'user-files/' . $name;
		$folder = Folder::find_or_make($path);
		return $folder;
	}
}
