<?php 
session_start();
$accessToken=$_SESSION['facebook_access_token'];
$id = $_GET['id'];
$name = $_GET['name'];
    
require_once __DIR__ . '/sdks/src/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '660239077464339',
  'app_secret' => 'b993bb1cba486655bfeff8c498f5e730',
  'default_graph_version' => 'v2.7',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email','publish_actions','user_photos']; // optional
  
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

 $photos_original = $fb->get("/$id/photos?fields=images", $accessToken)->getGraphEdge()->asArray();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
  .carousel-inner > .item > img,
  .carousel-inner > .item > a > img {
      width: 70%;
      margin: auto;
      background-color:white;
      background:no-repeat center fixed; 
    background-size: cover; 
  }

  </style>
</head>
<body>

<div class="container">
  <br>
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <?php
      $len = count($photos_original);
      for($i=1;$i<$len-1;$i++)
      {
        echo "<li data-target='#myCarousel' data-slide-to=".$i."> </li> ";
      }
      ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      
<?php
for($i=0;$i<$len;$i++)
{
    echo "<div class='item' style='background-repeat: no-repeat; background-position:center; '>";
    echo "<img src='".$photos_original[$i]['images'][0]['source']."' alt='temp' width='460' height='345'/> ";
    echo "</div>";

}

?>

       
   </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>
<script type="text/javascript">
  

       $('#myCarousel').carousel({
      interval: 2000,
      cycle: true
      });

      var $item = $('.carousel .item'); 
      var $wHeight = $(window).height();
      $item.eq(0).addClass('active');
      $item.height($wHeight); 
      $item.addClass('full-screen');

      $('.carousel img').each(function() {
        var $src = $(this).attr('src');
        var $color = $(this).attr('data-color');
        $(this).parent().css({
          'background-image' : 'url(' + $src + ')',
          'background-color' : $color
        });
        $(this).remove();
      });

    $(window).on('resize', function (){
      $wHeight = $(window).height();
      $item.height($wHeight);
    });

</script>
</body>
</html>

