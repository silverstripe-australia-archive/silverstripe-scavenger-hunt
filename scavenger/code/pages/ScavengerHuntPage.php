<?php

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class ScavengerHuntPage extends Page {

	public static $db = array(
		'CompletedContent' => 'HTMLText'
	);

	public static $has_many = array(
		'Tasks'			=> 'ScavengerTask',
	);

	public static $defaults = array(
		'CompletedContent' => '<p>Congratulations, you have completed all tasks!</p>'
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$config = new GridFieldConfig_RecordEditor;
		$config->addComponent(new GridFieldSortableRows('Sort'));
		$gf = GridField::create('Tasks', 'Tasks', $this->Tasks(), $config);
		
		$fields->addFieldToTab('Root.Tasks', $gf);

		$fields->addFieldToTab(
			'Root.Main',
			new HtmlEditorField('CompletedContent', 'Tasks Completed Content'),
			'Metadata'
		);

		return $fields;
	}
	
	protected $currentTask;
	
	public function CurrentMemberTask() {
		$member = Member::currentUser();
		if (!$member) {
			throw new Exception('Must be logged in ');
		}
		
		if ($this->currentTask) {
			return $this->currentTask;
		}
		
		$currentResponses = $member->responsesInHunt($this);
		$responseMap = array();
		foreach ($currentResponses as $response) {
			$responseMap[$response->TaskID] = $response;
		}

		$tasks = $this->Tasks()->toArray();

		foreach ($tasks as $task) {
			if (isset($responseMap[$task->ID])) {
				// see whether we've got an un-finished task
				$response = $responseMap[$task->ID];
				if ($response->Status == 'Pending') {
					$this->currentTask = $task;
					// store the user's response against the task...
					$this->currentTask->Response = $response;
					return $task;
				}
			} else {
				$this->currentTask = $task;
				return $task;
			}
		}
	}
}

class ScavengerHuntPage_Controller extends Page_Controller {

	public function TaskForm() {
		if($task = $this->CurrentMemberTask()) {
			$fields = new FieldList();
			$task->updateTaskFields($fields);

			return new Form(
				$this,
				'TaskForm',
				$fields,
				new FieldList(new FormAction('submit', 'Submit'))
			);
		}
	}

	public function Content() {
		if(!$this->CurrentMemberTask()) {
			return $this->data()->CompletedContent;
		} else {
			return $this->data()->Content;
		}
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
