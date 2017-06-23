<?php

/**
 * @author marcus@symbiote.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class MailCaptureAdmin extends ModelAdmin {
	public static $url_segment = 'mailcapture';
	public static $menu_title = 'Logs';

	public static $managed_models = array(
		'MassMailSend',
		'CapturedEmail',
	);
	
	public function init() {
		parent::init();
		$this->showImportForm = false;
	}
	
	public function getEditForm($id = null, $fields = null) {
		$form = parent::getEditForm($id, $fields);
		
		if ($this->modelClass == 'CapturedEmail') {
			$grid = $form->Fields()->dataFieldByName($this->sanitiseClassName($this->modelClass));
			if ($grid) {
				$grid->getConfig()->removeComponentsByType('GridFieldEditButton');
				$grid->getConfig()->removeComponentsByType('GridFieldDeleteAction');
				$grid->getConfig()->addComponent(new ViewEmailButton());
			}
		}
		return $form;
	}
}
