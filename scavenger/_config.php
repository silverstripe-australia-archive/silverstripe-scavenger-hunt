<?php

Member::set_unique_identifier_field('Username');

Object::add_extension('Member', 'ScavengerMember');
Object::add_extension('SiteConfig', 'ScavengerSiteConfigExtension');
