<?php
  require('connect.php');  
  require('getThreads.php');
  require('getComments.php');
?>

<!DOCTYPE HTML5>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link href='https://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
		<script>
			function reply(replyID) {
				document.getElementById("commentBox").value += (">>" + replyID);
			}
		</script>
	</head>
	<body>
	<?php
	if (isset($_SESSION['username'])) {
		if ($_SESSION['username'] == "admin") {
			echo "<form action='logout.php' method='post'>";
			echo "<input type='submit' value='logout'>";
			echo "</form>";
	  	}
	}
	?>
		<h1>
		<a href="index.php">/fuck/ - my shit up.</a></h1>
		<div id='submitForm'>
		  	<form action="postComment.php" method="post">
		  	<?php
		  		//Change the input forms depending on wether we're in a thread or not.
				if (isset($_GET['id'])) {
			  		$get = $_GET['id'];
			  		$_SESSION['thread_id'] = $get;
			  		echo "Name: <input type='text' name='name'><br>";
			  		//echo "<input type=hidden name='inThread' value='$get' />";
			  		echo "Comment: <textarea id='commentBox' type='text' name='comment' rows=40></textarea><br>";
				} else {
					$_SESSION['thread_id'] = -1;
					echo "Name: <input type='text' name='name'><br>";
			  		echo "Subject: <input type='text' name='subject'><br>";
			  		echo "Comment: <textarea id='commentBox' type='text' name='comment' rows=40></textarea><br>";
				}
		  	?>

		  	<input type="submit">
		  	</form>
		</div>
		<?php
			//If we're in a thread, add a hyperlink to return back.
			if (isset($_GET['id'])) {
				echo "<a href='index.php'>[Return]</a>";
			}
		?>
<hr>

		<?php 
			if (isset($_GET['id'])) {
		  	//If we can't get any comments for this id, then we're not in a thread.
				if (getComments(intval($_GET['id'])) == false) {

				 	header('Location: 404.php');
				}
			} else {
				//We're on the front page, get the latest threads.
				getThreads();
			}
		?>
	</body>
</html>

