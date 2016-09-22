
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('html_errors', 'On');
require_once __DIR__.'/sdks/src/Facebook/autoload.php';
?>

<html>
	<head> 
		<title>
			Facebook Album
		</title>
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.1.1/css/mdb.min.css">
		<!-- Latest compiled and minified CSS -->
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">

		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>
        <style>
        #cover{
        	width:90%;
        	margin-top: 10px;
        }
        .btn-primary{
        	background-color: grey;
        }

       
		</style>
	</head>



<?php 

$fb = new Facebook\Facebook([
'app_id' => '660239077464339',
'app_secret' => 'b993bb1cba486655bfeff8c498f5e730',
'default_graph_version' => 'v2.7',
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email','publish_actions','user_photos']; 
	

try {
	if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
	} else {
		session_destroy();
		header('Location:index.php');
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


		//validating token
	try{
		$name_request = $fb->get('/me?fields=first_name');
		$name_response = $name_request->getGraphNode()->asArray();		
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


    $name = $name_response['first_name'];

					
	try{
		$albums = $fb->get('/me/albums', $accessToken);
		$albums_array = $albums->getGraphEdge()->asArray();
	}catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		session_destroy();
		// redirecting user back to app login page
		header("Location:index.php");
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	
	//print_r($albums_array);

	$all_download = array();
	
?>
	<body>
	
		<form method="post" action="create_zip.php">
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    
		    	<div class="navbar-brand">
		    	<h4>Facebook Album Challenge<h4>
		    	</div>
		    	
		    	<div class="pull-xs-right">
		    		 Hello <?php echo $name; ?> 
		    		  <Button class="btn btn-primary" type="submit" name="selected">Download Selected</Button>
                      <Button class="btn btn-primary" type="submit" name="all" >Download All</Button>
		    		 <a href="logout.php"><button type="button" class="btn btn-primary navbar-btn">Logout</button></a>
		    	</div>
		    </div>
		</nav>

		<div class="container" id="cover">
<?php	
	
	$total_album = count($albums_array);
	
    
	
	for($i=0; $i<$total_album; $i++)
		{

		$id = $albums_array[$i]['id'];
		$album_name = $albums_array[$i]['name'];

		array_push($all_download, $id);
		$photos_small = $fb->get("/$id/photos?fields=picture", $accessToken)->getGraphEdge()->asArray();
		$no_photos = count($photos_small);


			if(($i%3) == 0)
			{
				echo '<div class="card-deck-wrapper">';
				echo '<div class="card-deck">';
			}
			
			if($no_photos != 0)
			{			
			?>


			<div class="card">
				    <img class="card-img-top" src="<?php echo $photos_small[0]['picture']; ?>" alt="<?php echo 'Album'.($i+1);?>" />
				    <div class="card-block" >
				      <h4 class="card-title"> <?php echo $album_name;?> </h4>
				      <small><p class="card-text"><?php echo ''.$no_photos.' Photos'; ?></p></small>
				    
				      <input  type="checkbox" name="files_s[]" value="<?php echo $id; ?>">
				      
				      <button type="button" class='btn btn-primary' onClick="window.open('slideshow.php?id=<?php echo $id."&name=".$album_name; ?>')">Show Pictures </button>
				       <button class='btn btn-primary' type="submit" name="down" value="<?php echo $id; ?>">Download </button>
				    
				    </div>
		   </div>


	<?php
	      }
	      else
	      {
	      	?>
	      	<div class="card">
				    <img class="card-img-top"  src="img/no_image.jpg" alt="<?php echo 'Album'.($i+1);?>" />
				    <div class="card-block">
				      <h4 class="card-title"> <?php echo $album_name;?> </h4>
				      <small><p class="card-text"><?php echo ''.$no_photos.' Photos'; ?></p></small>
				    </div>
		   </div>
		   <?php

	      }

			if($i%3 == 2)
			{
				echo '</div>';
				echo '</div>';
			}
	    
		  }
		
        
		if(($i-1)%3 != 2)
		{
			$reap = ($i-1)%3;
			for($j=1; $j<=(2-$reap);$j++)
			{
				echo '<div class="card" style="visibility:hidden">';
				    
		   		echo '</div>';
			}

			echo '</div>';
			echo '</div>';
		}
		

		echo '</div>';
		$_SESSION['all_download'] = $all_download;
		$var = $_SESSION['all_download'];



	
	echo '</form>';

?>

  <?php
    echo '</body> ';
  	// Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token'].	
} else {
	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
	session_destroy();
	header('Location:index.php');
	/*
	$loginUrl = $helper->getLoginUrl('http://vrajesh.xyz/index.php', $permissions);
	echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
	*/
}

?>

</html>