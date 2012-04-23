<?php
class Event extends Page {

	public static $db = array(
		'Category' => 'Varchar',
		'AuthorName' => 'Varchar',
		'AuthorEmail' => 'Varchar',
		'Description' => 'Text',
		'startTime' => 'Datetime',
		'endTime' => 'Varchar',
		'Location' => 'Varchar(100)'
	);
	
	public static $many_many = array(
		'Subscribers' => 'Member'
	);
	
	public static $has_one = array(
		'Owner' => 'Member',
		'EventImage' => 'Image',
		'Category' => 'Category'
	);
	
	public static $has_many = array(
		'Messages' => 'uMessage'
		
	);
	
	function populateDefaults() {
		parent::populateDefaults();
		
		if(!$this->Title) $this->Title = _t('SecurityAdmin.NEWEVENT',"New Event");
	}
	
	/*function getAllChildren() {
		$doSet = new ArrayList();

		if ($children = DataObject::get('Subscribers', '"ParentID" = '.$this->ID)) {
			foreach($children as $child) {
				$doSet->push($child);
				$doSet->merge($child->getAllChildren());
			}
		}
		
		return $doSet;
	}*/
	
	public function getCMSFields() {
		//$f = parent::getCMSFields();
		$f = new FieldList(
			new TabSet("Root",
				new Tab('Details', _t('SecurityAdmin.EVENTDETAILS', 'Event Details'),
					new TextField("Title", $this->fieldLabel('Title')),
					$categoryidField = DropdownField::create(						'CategoryID', 
						$this->fieldLabel('Category'), 
						DataList::create('Category')->map('ID')
					)->setEmptyString(' ')
				),
				new Tab('Description', _t('SecurityAdmin.EVENTDESCRIPTION', 'Description'),
					$myField = new TextAreaField("Content","Description")
				),

				$messagesTab = new Tab('Messages', _t('SecurityAdmin.MESSAGES', '&micro;Messages'),
					$msgField = new HeaderField("&micro;Messages")
				),
				
				$subscribersTab = new Tab('Subscribers', _t('SecurityAdmin.SUBSCRIBERS', 'Subscribers'),
					$subheadField = new HeaderField("Subscribers")
				)
				
				
			)
		);
		
		$categoryidField->setAttribute(
			'title', 
			_t('Event.CategoryReminder', 'Categories help organize the event.')
		);
		
		if($this->ID) {
			$config = new GridFieldConfig_RelationEditor();
			$config->addComponents(new GridFieldExportButton('before'));
			$config->addComponents(new GridFieldPrintButton('before'));
			//$config->getComponentByType('GridFieldAddExistingAutocompleter')
			//	->setResultsFormat('$Title ($Email)')->setSearchFields(array('FirstName', 'Surname', 'Email'));
			//$config->getComponentByType('GridFieldDetailForm')->setValidator(new Member_Validator());
			$messageList = GridField::create('Messages',false, $this->Messages(), $config);
			// @todo Implement permission checking on GridField
			//$memberList->setPermissions(array('edit', 'delete', 'export', 'add', 'inlineadd'));
			$f->addFieldToTab('Root.Messages', $messageList);
			
			$configa = new GridFieldConfig_RelationEditor();
			$configa->addComponents(new GridFieldExportButton('before'));
			$configa->addComponents(new GridFieldPrintButton('before'));
			$configa->getComponentByType('GridFieldAddExistingAutocompleter')
				->setResultsFormat('$Title ($Email)')->setSearchFields(array('FirstName', 'Surname', 'PhoneNumber'));
			$configa->getComponentByType('GridFieldDetailForm');
			$memberList = GridField::create('Subscribers',false, $this->Subscribers(), $configa)->addExtraClass('members_grid');
			// @todo Implement permission checking on GridField
			//$memberList->setPermissions(array('edit', 'delete', 'export', 'add', 'inlineadd'));
			$f->addFieldToTab('Root.Subscribers', $memberList);
		}else{
			$f->addFieldToTab('Root.Messages',new LiteralField("SaveFirst","<p>Once you save the event, you will be able to add messages to the Event.</p>"));
			$f->addFieldToTab('Root.Subscribers',new LiteralField("SaveFirst","<p>Once you save the event, you will be able to see the subscribers of this event.</p>"));
		}
		
		$field = new DatetimeField('startTime', 'Start Time');
 		$field->setConfig('datavalueformat', 'YYYY-MM-dd HH:mm'); // global setting
 		$field->getDateField()->setConfig('showcalendar', 1); // field-specific setting
 		$f->addFieldToTab('Root.Details', $field);
 		
 		$field = new DatetimeField('endTime', 'End Time');
 		$field->setConfig('datavalueformat', 'YYYY-MM-dd HH:mm'); // global setting
 		$field->getDateField()->setConfig('showcalendar', 1); // field-specific setting
 		$f->addFieldToTab('Root.Details', $field);
 		
 		$myField = new UploadField("EventImage","Image for Event");
		$myField->setFolderName('events');
		$f->addFieldToTab('Root.Details', $myField);
		
 		return $f;
	}
	
	function fieldLabels($includerelations = false) {
		$labels = parent::fieldLabels($includerelations);
		$labels['Title'] = _t('SecurityAdmin.EVENTTITLE', 'Name of Event');
		$labels['Description'] = _t('Event.Description', 'Description');
		$labels['starTime'] = _t('Event.startTime', 'Start Time', 'Event Start Date and Time');
		$labels['endTime'] = _t('Event.endTime', 'End Time', 'Event End Date and Time');
		$labels['Location'] = _t('Event.Location', 'Location', 'Event Location (address, or venue)');
		if($includerelations){
		}
		
		return $labels;
	}

}
/* Event Controller */
class Event_Controller extends Page_Controller {
	/* add controllers for place verification, saving events, and rsvps */

	public function v($arguments){
		//View Event
    	//return $this->renderWith('EventViewPage');
    }
    
    public function index(){
		if($member = Member::currentUser()){
			return $this->renderWith('Event','Event');
		}else{
			return $this->renderWith('HomePage');
		}
	}
	
    public function FormatedStartDate(){
    	$d = date('F d, Y',strtotime($this->startTime));
    	return $d;
    }
    
    public function FormatedStartTime(){
    	$d = date('g a',strtotime($this->startTime));
    	return $d;
    }
    
    public function FormatedEndDate(){
    	$d = date('F d, Y',strtotime($this->startTime));
    	return $d;
    }
    
    public function FormatedEndTime(){
    	$d = date('g a',strtotime($this->startTime));
    	return $d;
    }
    
    public function SubscriberForm(){
    	$fields = singleton('Member')->getMemberFormFields();
    	return $fields;
    }
    
    public function saveData($arguments){
		//View Event
		$params = $this->getURLParams();
		$eventinfo = $arguments->requestVars();
		$member = Member::currentUser();
		$dmember = DataObject::get_one("Event", '"Event_Live"."ID" = '.$params["ID"].' AND "Event_Live"."OwnerID"='.$member->ID);
		$dmember->Summary = @$eventinfo["first_name"];
		$dmember->startTime = @$eventinfo["date1"]." - ".@$eventinfo["date2"];
		$dmember->endTime = @$eventinfo["date3"]." - ".@$eventinfo["date4"];
		$dmember->MemberID = $member->ID;
		$dmember->write();
    	return Director::redirect('/host');
    }
    
    public function edit($arguments){
    	//Requires Permission Check
    	$params = $this->getURLParams();
    	$member = Member::currentUser();
    	//var_dump($member);
    	$event = DataObject::get_one("Event", '"Event_Live"."ID" = '.$params["ID"].' AND "Event_Live"."OwnerID"='.$member->ID);
    	if($event){
			$data = array(
				"EventInfo" => $event
			);
			return $this->customise($data)->renderWith('EventEditPage');
    	}else{
    		return Director::redirect('/host');
    	}
    }
    
    
    public function search($arguments){
    
    	$member = Member::currentUser();
		$token = Session::get('AuthToken');
		
		if($token==''){
			Director::redirect('/login');
		}
		if($member){
			return $this->renderWith('AppPage');
		}else{
			//New Member! Get Member info and create record
			if(!$token){
				Director::redirect('/login');
			}
			$graph_url = "https://graph.facebook.com/me?access_token=" . $token;
			$userinfo = json_decode(file_get_contents($graph_url));
			$userinfo = (array) $userinfo;
				//Create Member
			$dmember = new Member();
			$dmember->FirstName = @$userinfo["first_name"];
			$dmember->LastName = @$userinfo["last_name"];
			$dmember->fbusername = @$userinfo["username"];
			$dmember->displayName = @$userinfo["name"];
			$dmember->Email = @$userinfo["email"];
			
			
			//$dmember->profilePhoto = @$userinfo["photoUrl"];
			$dmember->fbID = @$userinfo["id"];
			$dmember->locale = @$userinfo["locale"];
			$dmember->bio = @$userinfo["bio"];
			$dmember->fblocation = @$userinfo["location"]->name;
			$memberid = $dmember->write();
			
			$dmember->ID = $memberid;
			$dmember->logIn(TRUE);
			
			//Todo: direct user to setup page
			
			return $this->renderWith('AppPage');
			//var_dump((array) $user);
			//var_dump($token);
			//exit;
			//return $this->renderWith(array('SignupPage','Page'));
		}
    }
}
