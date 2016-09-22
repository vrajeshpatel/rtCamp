
<?php  
session_start();
require_once __DIR__ . '/sdks/src/Facebook/autoload.php';
?>
<!DOCTYPE html>
<html>
    <head>
    <style>
            #loading {
           width: 100%;
           height: 100%;
           top: 0;
           left: 0;
           position: fixed;
           display: block;
           opacity: 0.7;
           background-color: #fff;
           z-index: 99;
           text-align: center;
        }

        #loading-image {
          position: absolute;
          top: 100px;
          left: 240px;
          z-index: 100;
        }
    </style>
    </head>
    <body>
        <div id="loading">
          <img id="loading-image" src="images/ajax-loader.gif" alt="Loading..." />
        </div>

        <script language="javascript" type="text/javascript">
             $(window).load(function() 
             {
                $('#loading').hide();
              });
        </script>
    </body>
  </html>
<?php

$fb = new Facebook\Facebook([
  'app_id' => '660239077464339',
  'app_secret' => 'b993bb1cba486655bfeff8c498f5e730',
  'default_graph_version' => 'v2.7',
  ]);

$helper = $fb->getRedirectLoginHelper();
$accessToken = $_SESSION['facebook_access_token'];
$permissions = ['email','publish_actions','user_photos'];
 
$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);


if(isset($_POST['down']))
{   
    $id = $_POST['down'];
    $files = array();
    $photos = $fb->get("/$id/photos?fields=picture", $accessToken)->getGraphEdge()->asArray();
    
     foreach ($photos as $key) 
     {
        $photo_request = $fb->get('/'.$key['id'].'?fields=images');
        $photo = $photo_request->getGraphNode()->asArray();
        $image_url = $photo['images'][2]['source'];
        array_push($files, $image_url);
      }

       $i = 1;
       $zip = new ZipArchive();
       $tmp_file = tempnam('.','');
       $zip->open($tmp_file, ZipArchive::CREATE);

        
      foreach($files as $file)
      {
            $download_file = file_get_contents($file);
            $zip->addFromString(''.$i.'.jpg',$download_file);
            $i++;
      }
      $zip->close();
      header('Content-disposition: attachment; filename='.$id.'.zip');
      header('Content-type: application/zip');
      readfile($tmp_file);
      unlink($tmp_file);
  }


 if(isset($_POST['selected']))
 {
        
        $files2 = array();
        $files2 = $_POST['files_s'];
        $files2_length = count($files2);
        $zip = new ZipArchive();
        $j = 1;
        $tmp_file = tempnam('.','');
        $zip->open($tmp_file, ZipArchive::CREATE);
        for($i=0; $i<$files2_length; $i++)
        {
            $files_temp = array();
            $id= $files2[$i];
            $photos = $fb->get("/$id/photos?fields=picture", $accessToken)->getGraphEdge()->asArray();

            foreach ($photos as $key) 
            {
              $photo_request = $fb->get('/'.$key['id'].'?fields=images');
              $photo = $photo_request->getGraphNode()->asArray();
              $image_url = $photo['images'][2]['source'];
              array_push($files_temp, $image_url);
            }
            
            foreach($files_temp as $file)
            {
                $download_file = file_get_contents($file);
                $zip->addFromString(''.$j.'.jpg',$download_file);
                $j++;
            }
            
              $files_temp = NULL;
              $file = NULL;
        }
              $zip->close();

              header('Content-disposition: attachment; filename=selected_albums.zip');
              header('Content-type: application/zip');
              readfile($tmp_file);
              unlink($tmp_file);
   }


   if(isset($_POST['all']))
   {
      $albums_id = $_SESSION['all_download'];
      $no_album = count($albums_id);
      $zip = new ZipArchive();
      $j = 1;
      $files_temp1 = array();
      $tmp_file = tempnam('.','');
      $zip->open($tmp_file, ZipArchive::CREATE);
      
      for($i=0; $i<$no_album; $i++)
      {
           
            $id= $albums_id[$i];
            $photos = $fb->get("/$id/photos?fields=picture", $accessToken)->getGraphEdge()->asArray();

            foreach ($photos as $key) 
            {
              $photo_request = $fb->get('/'.$key['id'].'?fields=images');
              $photo = $photo_request->getGraphNode()->asArray();
              $image_url = $photo['images'][2]['source'];
              array_push($files_temp1, $image_url);
             }
      }

            foreach($files_temp1 as $file)
            {
            
                  $download_file = file_get_contents($file);
                  $zip->addFromString(''.$j.'.jpg',$download_file);
                  $j++;
            }
              
                 
             
            $files_temp1 = NULL;
            $file = NULL;
     
      $zip->close();
      header('Content-disposition: attachment; filename=albums.zip');
      header('Content-type: application/zip');
      readfile($tmp_file);
      unlink($tmp_file);
   }     


 ?>