<?php

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class ScavengerTask extends DataObject {
	
	public static $db = array(
		'Title'			=> 'Varchar(255)',
		'Description'	=> 'HTMLText',
		'Sort'			=> 'Int',
	);

	public static $has_one = array(
		'Hunt'			=> 'ScavengerHuntPage',
	);
	
	public static $has_many = array(
		'Responses'		=> 'TaskResponse'
	);
	
	public static $default_sort = 'Sort ASC';

	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$fields->removeByName('HuntID');
		$fields->removeByName('Sort');
		
		if ($this->ClassName == 'ScavengerTask') {
			// allow swapping to a subclass
			$classes = ClassInfo::getValidSubClasses('ScavengerTask', true);
			$dropdown = new DropdownField('ClassName', 'Task type', array_combine($classes, $classes));
			$fields->addFieldToTab('Root.Main', $dropdown);
		}

		return $fields;
	}
	
	protected function newResponse($type = 'TaskResponse') {
		$response = $type::create();
		$response->TaskID = $this->ID;
		$response->HuntID = $this->HuntID;
		$response->Title = 'Submitted by ' . Convert::raw2sql(Member::currentUser()->Username);
		return $response;
	}

	public function updateTaskFields(FieldList $fields) {
		
	}
	
	public function processSubmission($data) {
		
	}
}