<?php

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class FileUploadTask extends ScavengerTask {

	
	public function updateTaskFields(FieldList $fields) {
		$fields->push(new FileField('File', 'Upload file'));
	}
	
	public function processSubmission($data) {
		print_r($data);
		exit;
	}
}
