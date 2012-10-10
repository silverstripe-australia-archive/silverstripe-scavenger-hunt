<?php

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class ScavengerHuntPage extends Page {
	public static $has_many = array(
		'Tasks'			=> 'ScavengerTask',
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$config = new GridFieldConfig_RecordEditor;
		$config->addComponent(new GridFieldSortableRows('Sort'));
		$gf = GridField::create('Tasks', 'Tasks', $this->Tasks(), $config);
		
		$fields->addFieldToTab('Root.Tasks', $gf);
		
		return $fields;
	}
	
	public function CurrentMemberTask() {
		$member = Member::currentUser();
		if (!$member) {
			throw new Exception('Must be logged in ');
		}
		
		$currentResponses = $member->responsesInHunt($this);
		if ($currentResponses) {
			$currentResponses = $currentResponses->map();
		}
		
		$tasks = $this->Tasks();
		
		foreach ($tasks as $task) {
			if (!isset($currentResponses[$task->ID])) {
				return $task;
			}
		}
	}
}

class ScavengerHuntPage_Controller extends Page_Controller {
	
}
