<?php

Member::set_unique_identifier_field('Username');

Object::add_extension('Member', 'ScavengerMember');
