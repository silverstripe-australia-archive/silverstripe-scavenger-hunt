<?php

/**
 * @author marcus@symbiote.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class ScavengerHuntPage extends Page {

	public static $db = array(
		'CompletedContent' => 'HTMLText'
	);

	public static $has_many = array(
		'Tasks'					=> 'ScavengerTask',
		'Summaries'				=> 'MemberHuntSummary',
	);

	public static $defaults = array(
		'CompletedContent' => '<p>Congratulations, you have completed all tasks!</p>'
	);
	

	protected $currentTask;
	
	protected $isComplete = false;

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
		
		$config = new GridFieldConfig_RecordViewer;
		$summary = GridField::create('Summaries', 'Member summary', $this->Summaries()->sort('PointsTotal DESC'), $config);
		
		$fields->addFieldToTab('Root.Summary', $summary);

		return $fields;
	}
	
	
	public function CurrentMemberTask() {
		$member = Member::currentUser();
		if (!$member) {
			return null;
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

		$total = 0;
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
			$total++;
		}
		
		if ($total == count($responseMap)) {
			$this->isComplete = true;
		}
	}
	
	/**
	 * Has the person completed all tasks yet?
	 * @return boolean 
	 */
	public function IsComplete() {
		$member = Member::currentUser();
		if (!$member) {
			return false;
		}
		
		// check is complete
		$this->CurrentMemberTask();
		
		return $this->isComplete;
	}
}

class ScavengerHuntPage_Controller extends Page_Controller {

	public function init(){
		parent::init();

		if(!Member::currentUser()){
			Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
			Requirements::javascript('themes/wd/javascript/registration.js');
		}
	}

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
		if($this->data()->IsComplete()) {
			return $this->data()->CompletedContent;
		} else {
			return $this->data()->Content;
		}
	}

	public function submit($data, Form $form) {
		$task = $this->data()->CurrentMemberTask();
		
		if ($task) {
			if (!$task->Answerable()) {
				$this->redirect('http://lmgtfy.com/?q=jeffk&l=1');
				return;
			}
			if ($task->getUserSubmission(Member::currentUser())) {
				$form->sessionMessage("You've already submitted for this task, duplicate submissions are not allowed!", 'warning');
			} else {
				$response = $task->processSubmission($data, $form);
				if (is_string($response)) {
					$form->sessionMessage($response, 'warning');
				} else if ($response instanceof TaskResponse) {
					$this->redirect($this->Link('submitted/' . $response->ID));
					return;
				} else {
					$form->sessionMessage('Submission could not be processed, please try again', 'warning');
				}
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
