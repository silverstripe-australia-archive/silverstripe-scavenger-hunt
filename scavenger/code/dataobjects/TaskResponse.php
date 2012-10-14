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

	public function onAfterWrite() {
		// When a new pending notification is created, notify the approval
		// group.
		$conf = SiteConfig::current_site_config();

		if($this->isChanged('ID') && $this->Status == 'Pending' && $conf->TaskApprovalGroupID) {
			foreach($conf->TaskApprovalGroup()->Members() as $member) {
				$link = Controller::join_links(
					singleton('ScavengerAdmin')->Link(),
					'TaskResponse/EditForm/field/TaskResponse/item',
					$this->ID
				);

				$email = new Email();
				$email->setTo($member->Email);
				$email->setSubject(sprintf('New Pending Task Submitted By %s', $this->Responder()->Username));
				$email->setTemplate('PendingTaskApprovalEmail');
				$email->populateTemplate(array(
					'Member' => $member,
					'Response' => $this,
					'ReviewLink' => $link
				));
				$email->send();
			}
		}
	}

}

