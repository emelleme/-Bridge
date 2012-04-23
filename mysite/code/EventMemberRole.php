<?php
/**
 * ForumRole
 *
 * This decorator adds the needed fields and methods to the {@link Member}
 * object.
 *
 * @package forum
 */
class EventMemberRole extends DataExtension {

	/**
	 * Define extra database fields
	 *
	 * Return an map where the keys are db, has_one, etc, and the values are
	 * additional fields/relations to be defined
	 */
	public function extraStatics($class = null, $extension = null) {
		$fields = array(
			'db' => array(
				'PhoneNumber' => 'Varchar',
				'pin' => 'Varchar(6)'
			),
			'belongs_many_many' => array(
				'Events' => 'Event'
			)
		);
		
		return $fields;
	}
	
	public function updateCMSFields(FieldList $fields) {
		//$allForums = DataObject::get('Forum');
		$fields->addFieldToTab('Root.Main', new TextField('PhoneNumber','Phone Number'));
		//$fields->removeByName('pin');
		
	}
	
	public function updateFrontEndFields(FieldList $fields) {
		$fields->removeByName('pin');
		$fields->removeByName('Password');
		$fields->removeByName('Password');
		$fields->removeByName('Email');
		$fields->removeByName('Locale');
		$fields->removeByName('DateFormat');
		$fields->removeByName('TimeFormat');
		$fname = new TextField('FirstName','First Name');
		$fname->setAttribute('placeholder','First Name');
		$fields->push($fname);
		
		$fname = new TextField('Surname','Last Name');
		$fname->setAttribute('placeholder','Last Name');
		$fields->push($fname);
		
		$phone = new TextField('PhoneNumber','Phone Number');
		$phone->setAttribute('placeholder','Messages will be sent here.');
		$fields->push($phone);
		
	}
}
