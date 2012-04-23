<?php
class uMessage extends DataObject {

	public static $db = array(
		'Sid' => 'Varchar(100)',
		'SmsStatus' => 'Varchar',
		'Debug' => 'Text',
		'Body' => 'Text',
		'LastResponse' => 'Int',
		'releaseDate' => 'Datetime'//Datetime 
	);

	public static $has_one = array(
		'Event' => 'Event'
	);
	
	static $summary_fields = array(
      'ID',
      'Body',
      'releaseDate'
   );
	
	public function getCMSFields()
	{
		$f = parent::getCMSFields();
	    $f->removeByName('Sid');
		$f->removeByName('SmsStatus');
		$f->removeByName('Debug');
		$f->removeByName('LastResponse');
		$f->renameField('Body','Message');
		
		$field = new DatetimeField('releaseDate', 'Date and Time to be sent to Subscribers');
 		$field->setConfig('datavalueformat', 'YYYY-MM-dd HH:mm'); // global setting
 		$field->getDateField()->setConfig('showcalendar', 1); // field-specific setting
 		$f->addFieldToTab('Root.Main', $field);
 		return $f;
	}

}
