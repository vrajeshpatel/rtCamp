<?php
session_start();

require_once __DIR__ . '/sdks/src/Facebook/autoload.php';
?>
<html>
	<head> 
		<title>
			Facebook Album
		</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.1.1/css/mdb.min.css">
		<!-- Latest compiled and minified CSS -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">

        <style>
        #cover{
        	width:90%;
        	margin-top: 10px;
        }
		</style>
	</head>

	<body>
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    
		    	<div class="navbar-brand">
		    	<h4>Facebook Album Challenge<h4>
		    	</div>
		    	
		    	<div class="pull-xs-right">
		    		 <a href="logout.php"><button type="button" class="btn btn-primary navbar-btn">Logout</button></a>
		    	</div>
		    </div>
		</nav>

		<div class="container" id="cover">

<?php
		for($i=0; $i<13;$i++)
		{
			if($i%3 == 0)
			{
				echo '<div class="card-deck-wrapper">';
				echo '<div class="card-deck">';
			}
			?>
			<div class="card">
				    <img class="card-img-top" src="..." alt="Card image cap">
				    <div class="card-block">
				      <h4 class="card-title"><?php echo 'Card Title';?></h4>
				      <p class="card-text"><?php echo $i; ?></p>
				      <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
				    </div>
		   </div>
			<?php

			if($i%3==2)
			{
				echo '</div>';
				echo '</div>';
			}
		}
		

		if(($i-1)%3!=2)
		{
			$reap = ($i-1)%3;
			for($j=1; $j<=(2-$reap);$j++)
			{
				echo '<div class="card" style="visibility:hidden">';
				    
		   		echo '</div>';
			}

			?>
				
				<?php

			echo '</div>';
			echo '</div>';
		}



?>
        </div>
		
		

		
		
	</body>
</html>
<?php
				/*	
					for($i=0;$i<0;$i++)
					{
										echo '<div class="card">
							  <img class="card-img-top" src="..." alt="Card image cap">
							  <div class="card-block">
							    <h4 class="card-title">Card title</h4>
							    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card.</p>
							    <a href="#" class="btn btn-primary">Go somewhere</a>
							  </div>
							</div>';
					}
*/
					?>