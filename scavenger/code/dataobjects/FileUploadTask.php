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
		if (isset($data['File']) && isset($data['File']['tmp_name'])) {
			$upload = new Upload();
			if ($upload->load($data['File'])) {
				$file = $upload->getFile();
				if ($file->ID) {
					$response = $this->newResponse('TaskResponseFileUpload');
					$response->Response = $file->getAbsoluteURL();
					$response->UploadedFileID = $file->ID;
					$response->Status = 'Pending';
					$response->write();

					return $response;
				}
				
			}
		}
		return 'Upload could not be saved, please try again';
	}
}
