# rtCamp Facebook Album Challenge
----
It is a web application where user can log in via facebook account and see their facebook ablum in organized way. This application also provide functionality to download the album in different way. User can download single album, multple album or all the album in '.zip' format. 

### Live Url : www.vrajesh.xyz
note: currently only user with tester or devloper permission can use the application.



## Working
It uses Facebook PHP sdk.

this generates an object with application detail. All the function will be called by this object reference.

```
$fb = new Facebook\Facebook([
'app_id' => 'Your app-id', 
'app_secret' => 'Your app-secret',
'default_graph_version' => 'v2.7'

]);

```
When first time user logs in, It will ask for one permission.
only one permission is required for this.


```
$permissions = ['user_photos'];
```
This will directly give an array wich contains all the details of the album.

```
$albums = $fb->get('/me/albums', $accessToken)->getGraphEdge()->asArray();
```
to create a zip file
```
 $zip = new ZipArchive();
 $tmp_file = tempnam('.',''); 
 $zip->open($tmp_file, ZipArchive::CREATE)
 
 ```
 ### Libraries
 1. Facebook PHP SDK : https://developers.facebook.com/docs/php
 2. Bootstrap 4 : http://v4-alpha.getbootstrap.com/
 3. JQuery : https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js
 
### Prerequisites

1. PHP 5.6 or greater 
2. The mbstring should be enabled
3. allow_url_include = On
4. allow_url_fopen = On



##Author
Vrajesh Patel
