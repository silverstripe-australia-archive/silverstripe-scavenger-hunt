<?php

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class FileUploadTask extends ScavengerTask {

	public function updateTaskFields(FieldList $fields) {
		$fields->push($ff = new FileField('File', 'Select a file'));
		$folder = Member::currentUser()->memberFolder();
		$ff->setFolderName($folder->Filename);
	}
	
	public function processSubmission($data) {
		if(!isset($data['File']['tmp_name'])) {
			return 'Please select a file to upload';
		}
		
		$folder = Member::currentUser()->memberFolder();

		$upload = new Upload();
		$upload->load($data['File'], $folder->Filename);

		if($upload->isError()) {
			return sprintf(
				'Upload could not be saved: %s.',
				implode(', ', $upload->getErrors())
			);
		}

		if(!$file = $upload->getFile()) {
			return 'Upload could not be saved, please try again.';
		}

		$response = $this->newResponse('TaskResponseFileUpload');
		$response->Response = $file->getAbsoluteURL();
		$response->UploadedFileID = $file->ID;
		$response->Status = 'Pending';
		$response->write();

		return $response;
	}
}
