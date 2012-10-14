<?php
/**
 * Adds fields to the site config.
 */
class ScavengerSiteConfigExtension extends DataExtension {

	public static $has_one = array(
		'TaskApprovalGroup' => 'Group'
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldToTab('Root.Main', new TreeDropdownField(
			'TaskApprovalGroupID', 'Task Approval Group'
		));
	}

}
