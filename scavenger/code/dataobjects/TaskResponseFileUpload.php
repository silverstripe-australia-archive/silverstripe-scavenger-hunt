<?php

/**
 * Stores the response to a file upload task
 *
 * @author marcus@symbiote.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class TaskResponseFileUpload extends TaskResponse {
	public static $has_one = array(
		'UploadedFile'		=> 'File',
	);
}
