
<html>
	<head> 
		<title>
			Facebook Album
		</title>
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.1.1/css/mdb.min.css">
		<!-- Latest compiled and minified CSS -->
		<!--
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
-->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>


		<style>
		body{
				height:100%;
		}
		#cover{
			height:100%;
			text-align: center;
			display:flex;
			align-items: center;
			background-color: rgba(242,242,242,1);
			display: flex;
			align-items: center;
		}
		#cover-caption{
			width:100%;
		}
		#con-start {
			
			
			text-align: center;
			align-items: center;
			
		}


		</style>
	</head>

<?php
session_start();
require_once __DIR__ . '/sdks/src/Facebook/autoload.php';

$app_id = '66023907746433';
$app_secret = 'b993bb1cba486655bfeff8c498f5e730';
$dfg = 'v2.7';


$fb = new Facebook\Facebook([
  'app_id' => $app_id,
  'app_secret' => $app_secret,
  'default_graph_version' => $dfg,
  ]);
$helper = $fb->getRedirectLoginHelper();

$permissions = ['email','publish_actions','user_photos']; // optional
	
try {
	if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();

  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Error' . $e->getMessage();
  	exit;
 }

if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	} else {
		// getting short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;

	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();

		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}

	// redirect the user back to the same page if it has "code" GET variable
	if (isset($_GET['code'])) {
		header('Location: ./');
	}


// validating access token
	try{
		$request = $fb->get('/me?fields=name,first_name,last_name,email');		
	
	}catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		if($e == 190){
			$helper = $fb->getRedirectLoginHelper();
			$permissions = ['email','publish_actions']; // optional
			$loginUrl = $helper->getLoginUrl('http://vrajesh.xyz/index.php', $permissions);
			echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
		}
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}


	header('location:main.php');



	// Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token'].	
} else {
	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
?>
<body>
	<section id="cover">
		<div id="cover-caption">
			<div class="container" id="con-start">
				
					<h1> rtCamp Facebook Album Challenge </h1>
					<?php
						$loginUrl = $helper->getLoginUrl('http://vrajesh.xyz/index.php', $permissions);
						echo '<a href="' . $loginUrl . '" class="btn azm-social azm-btn azm-border-bottom azm-facebook" style="background-color:blue;"><i class="fa fa-facebook"></i> Log in with Facebook</a>';
					?>
				
			</div>
			<br><br><br><br>
		<footer id="footer-main">
				<div class="container">
					<div class="row">
						<p> by <p> Vrajesh Patel </p>
					</div> 
				</div>
			</footer>
		</div>

	</section>
	
</body>
</html>
<?php
}

?>
