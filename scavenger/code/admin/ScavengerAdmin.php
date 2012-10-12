<?php

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class ScavengerAdmin extends ModelAdmin {
	public static $menu_title = 'Scavenger Hunt';
	public static $url_segment = 'scavenger';
	public static $managed_models = array('TaskResponse');
}
