<?php

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class ScavengerTask extends DataObject {
	
	public static $db = array(
		'Title'				=> 'Varchar(255)',
		'Description'		=> 'HTMLText',
		'Sort'				=> 'Int',
		'PointsToAward'		=> 'Int',
		'InternalNotes'		=> 'Text',
		'AvailableAfter'	=> 'SS_Datetime',
		'AnswerableAfter'	=> 'SS_Datetime',
	);

	public static $has_one = array(
		'Hunt'			=> 'ScavengerHuntPage',
	);
	
	public static $has_many = array(
		'Responses'		=> 'TaskResponse'
	);
	
	public static $defaults = array(
		'PointsToAward'	=> 1
	);
	
	public static $summary_fields = array(
		'Title', 'Description', 'PointsToAward'
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
		
		$fields->addFieldToTab('Root.Main', new DropdownField('PointsToAward', 'Points for correct answer', range(0, 10)), 'Description');
		
		$fields->dataFieldByName('AvailableAfter')->getDateField()->setConfig('showcalendar', true);
		$fields->dataFieldByName('AnswerableAfter')->getDateField()->setConfig('showcalendar', true);

		return $fields;
	}
	
	protected function newResponse($type = 'TaskResponse') {
		$response = $type::create();
		$response->TaskID = $this->ID;
		$response->HuntID = $this->HuntID;
		$response->Title = 'Submitted by ' . Convert::raw2sql(Member::currentUser()->Username);
		$response->Points = $this->PointsToAward;
		return $response;
	}

	public function updateTaskFields(FieldList $fields) {
		$fields->push(new TextareaField('Answer', "Enter a response below"));
	}

	public function processSubmission($data) {
		if (isset($data['Answer']) && strlen($data['Answer'])) {
			$response = $this->newResponse();
			$response->Response = $data['Answer'];
			$response->Status = 'Pending';
			$response->write();
			return $response;
		}
		return 'You must provide an answer';
	}
	
	/**
	 * Get the passed in member's accepted submission for this task
	 * 
	 * @param Member $member 
	 */
	public function getUserSubmission(Member $member) {
		return DataList::create('TaskResponse')->filter(array(
			'ResponderID'		=> $member->ID,
			'TaskID'			=> $this->ID,
			'Status:Negation'	=> 'Rejected',
		))->first();
	}

	public function Viewable() {
		if ($this->AvailableAfter) {
			return time() > strtotime($this->AvailableAfter);
		}
		return true;
	}
	
	public function Answerable() {
		if ($this->AnswerableAfter) {
			return time() > strtotime($this->AnswerableAfter);
		}
		return $this->Viewable();
	}
}