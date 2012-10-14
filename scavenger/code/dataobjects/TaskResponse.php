<?php

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class TaskResponse extends DataObject {
	
	public static $db = array(
		'Title'			=> 'Varchar',
		'Response'		=> 'Text',
		'Status'		=> "Enum('Accepted,Pending,Rejected','Pending')"
	);

	public static $has_one = array(
		'Responder'		=> 'Member',
		'Task'			=> 'ScavengerTask',
		'Hunt'			=> 'ScavengerHuntPage',
	);
	
	public static $defaults = array(
		'Status'		=> 'Pending',
	);
	
	public static $summary_fields = array(
		'Title', 'Responder.Title', 'Status'
	);
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();

		if(!$this->ResponderID) {
			$this->ResponderID = Member::currentUserID();
		}

		$this->Title = 'Submitted by ' . $this->Responder()->Username;
	}
}

