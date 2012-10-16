<?php

/**
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class MemberHuntSummary extends DataObject {
	
	public static $db = array(
		'Title'				=> 'Varchar',
		'PointsTotal'		=> 'Int',
	);
	
	public static $has_one = array(
		'Member'			=> 'Member',
		'Hunt'				=> 'ScavengerHuntPage',
	);
	
	public static $summary_fields = array(
		'Member.Username', 'PointsTotal',
	);
	
	public function updateTotal() {
		$responses = $this->Member()->responsesInHunt($this->Hunt());
		$this->PointsTotal = 0;
		foreach ($responses as $response) {
			if ($response->Status == 'Accepted') {
				$this->PointsTotal += $response->Points;
			}
		}
		$this->write();
	}
}
