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
		$responseMap = array();
		foreach ($currentResponses as $response) {
			$responseMap[$response->ID] = $response;
		}
		
		$tasks = $this->Tasks();
		
		foreach ($tasks as $task) {
			if (isset($responseMap[$task->ID])) {
				// see whether we've got an un-finished task
				$response = $responseMap[$task->ID];
				if ($response->Status == 'Pending') {
					return $task;
				}
			} else {
				return $task;
			}
		}
	}
}

class ScavengerHuntPage_Controller extends Page_Controller {
	public function TaskForm() {
		$task = $this->data()->CurrentMemberTask();
		
		$fields = FieldList::create();
		
		$task->updateTaskFields($fields);
		
		$actions = FieldList::create(new FormAction('submit', 'Submit'));
		
		$form = Form::create($this, 'TaskForm', $fields, $actions);
		
		return $form;
	}
	
	
	public function submit($data, Form $form) {
		$task = $this->data()->CurrentMemberTask();
		
		if ($task) {
			$response = $task->processSubmission($data);
			if (is_string($response)) {
				$form->sessionMessage($response, 'warning');
			} else if ($response instanceof TaskResponse) {
				$this->redirect($this->Link('submitted/' . $response->ID));
				return;
			} else {
				$form->sessionMessage('Submission could not be processed, please try again', 'warning');
			}
		}
		$this->redirectBack();
	}
	
	
	public function submitted() {
		$responseId = (int) $this->getRequest()->param('ID');
		if ($responseId) {
			$response = DataList::create('TaskResponse')->byId($responseId);
			if ($response && $response->ID) {
				if ($response->Status == 'Accepted') {
					$this->redirect($this->Link());
					return;
				}
				
				// TODO show 'pending acceptance'
				
			}
		}
		$this->redirect($this->Link());
	}
}
