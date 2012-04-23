<?php
class Category extends DataObject {

	public static $db = array(
		'Title' => 'Varchar(100)',
		'Description' => 'Text'
	);

	public static $has_many = array(
		'Event' => 'Event'
	);
	
	

}
