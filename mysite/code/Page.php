<?php
class Page extends SiteTree {

	public static $db = array(
	);

	public static $has_one = array(
	);

}
class Page_Controller extends ContentController {

	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 * <code>
	 * array (
	 *     'action', // anyone can access this action
	 *     'action' => true, // same as above
	 *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */
	public static $allowed_actions = array (
	);

	public function init() {
		parent::init();

		// Note: you should use SS template require tags inside your templates 
		// instead of putting Requirements calls here.  However these are 
		// included so that our older themes still work
		Requirements::themedCSS('reset');
	}
	
	public function index(){
		if($member = Member::currentUser()){
			return $this->renderWith('HomePage');
		}else{
			return $this->renderWith('HomePage');
		}
	}
	
	public function connect($arguments){
	//Session::clear_all();
		//Check Request Variable
		$vars = $arguments->requestVars();
			//Code value sent. Get auth token, and create new user.
		
		$app_id = "284986931522062";
		$app_secret = "0d31f18ce8f8e68e2e9ec658750be9b0";
		$my_url = "http://a1-ubridge.rhcloud.com/home/connect";

		//session_start();
		$code = @$_REQUEST["code"];

		if(empty($code)) {
		 
		 $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
		   . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
		   . $_SESSION['state'] . '&scope=publish_stream,publish_actions,friends_actions:snaxmag';

		 echo("<script> top.location.href='" . $dialog_url . "'</script>");
		}

		
		 $token_url = "https://graph.facebook.com/oauth/access_token?"
		   . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
		   . "&client_secret=" . $app_secret . "&code=" . $code;

		 $response = file_get_contents($token_url);
		 $params = null;
		 parse_str($response, $params);

		 $graph_url = "https://graph.facebook.com/me?access_token=" 
		   . $params['access_token'];
		$_SESSION['access_token'] = $params['access_token'];
		 $user = json_decode(file_get_contents($graph_url));
		 //var_dump($user);
		 //Check to see if user is in database
		 $d = DataObject::get_one('Member', "Email='".$user->id."'");
			 if(!$d){
			 	$b = new Member();
			 	$b->Email = $user->id;
			 	$b->FirstName = $user->first_name;
			 	$b->Surname = $user->last_name;
			 	$b->write();
			 	$b->addToGroupByCode('event-creator','Event Creator');
			 	$b->logIn();
			 }else{
			 	$d->logIn();
			 }
		 	
		 	//Spread Love, thats the brooklyn way
		 	/*$a = new RestfulService('https://graph.facebook.com/me/snaxmag:love?art=http://www.snaxmagazine.com&access_token='. $params['access_token']);
		 	$c = $a->request('','POST');
		 	$e = new RestfulService('https://graph.facebook.com/me/feed?link=http://www.snaxmagazine.com/app&name=SNAX Magazine&picture=http://www.snaxmagazine.com/themes/gallery/images/logoface.jpg&message=Pre-Order your copy of SNAX Magazine today!&description=Snax magazine brings artists together to tell a story through their art.  Each issue centers around a theme that can be represented and cultivated in limitless ways.&access_token='. $params['access_token'],20);
		 	$f = $e->request('','POST'); */
		 	//var_dump($c->getBody());
		 	$data = array('UserInfo'=>$user);
			Director::redirectBack();
		
		//$a = new RestfulService('https://graph.facebook.com/oauth/access_token?client_id=YOUR_APP_ID&redirect_uri=YOUR_REDIRECT_URI&client_secret=YOUR_APP_SECRET&code=CODE_GENERATED_BY_FACEBOOK')
	}
	

}
