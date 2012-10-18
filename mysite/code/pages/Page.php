<?php

class Page extends SiteTree {
	
	public static $db = array(
	);
	
	public static $has_one = array(
	);
	
	public function requireDefaultRecords() {
		if (Director::isDev()) {
			$loader = new FixtureLoader();
			$loader->loadFixtures();
		}
	}
}

class Page_Controller extends ContentController {
	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 * <code>
	 * array (
	 *     'action', // anyone can access this action
	 *     'action' => true, // same as above
	 *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */
	public static $allowed_actions = array (
	);
	
	public function init() {
		parent::init();

	}
	
	public function RegisterForm() {
		$fields = FieldList::create();
		$actions = FieldList::create();
		
		$fields->push(TextField::create('Username', 'Username'));
		$fields->push(EmailField::create('Email', 'Email'));
		$fields->push(PasswordField::create('Password', 'Password'));
		
		if($terms = $this->SiteConfig()->TermsPage()){
			$fields->push(LiteralField::create('TermsLink', "<p id='terms'>By registering you agree to the <a href='{$terms->Link()}' target='_blank'>terms and conditions</a></p>"));	
		}

		$actions->push(FormAction::create('register', 'Register'));
		
		$form = Form::create($this, 'RegisterForm', $fields, $actions);
		
		return $form;
	}
	
	public function register($data, Form $form) {
		
		$member = Member::create();
	
		$form->saveInto($member);
		
		try {
			$member->write();
			$member->addToGroupByCode('Members');
			$member->login();
		} catch (ValidationException $ve) {
			$form->sessionMessage("Registration failed: that username or email address may already be taken. Usernames must only contain letters and numbers", 'bad');
		}

		$this->redirect($this->Link());
	}
	
	public function Link($action = '') {
		if ($action == 'login') {
			$action = '';
		}
		return parent::Link($action);
	}
	
}
