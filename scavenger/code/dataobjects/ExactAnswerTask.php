<?php

/**
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class ExactAnswerTask extends ScavengerTask {
	public static $db = array(
		'ExpectedAnswer'	=> 'Varchar(255)',
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldToTab('Root.Main', TextField::create('ExpectedAnswer','Answer'), 'Description');
		return $fields;
	}
}
