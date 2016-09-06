<?php
  require('connect.php');  
  require('getThreads.php');
  require('getComments.php');
?>

<!DOCTYPE HTML5>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

		<script>
			function reply(replyID) {
				document.getElementById("commentBox").value += (">>" + replyID + "\n");
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
		<h1 id="title"><a href="index.php">/test/ - Test board.</a></h1>
		<div id='submitForm'>
		  	<form action="postComment.php" class="form-group" method="post">
		  	<?php
		  		//Change the input forms depending on wether we're in a thread or not.
				if (isset($_GET['id'])) {
			  		$get = $_GET['id'];
			  		$_SESSION['thread_id'] = $get;
			  		echo "<label for='name'>Name: </label><input type='text' class='form-control' name='name'><br>";
			  		//echo "<input type=hidden name='inThread' value='$get' />";
			  		echo "<label for='comment'>Comment: </label><textarea id='commentBox' class='form-control' type='text' name='comment' rows=40></textarea><br>";
				} else {
					$_SESSION['thread_id'] = -1;
					echo "<label for='name'>Name: </label><input type='text' class='form-control' name='name'><br>";
			  		echo "<label for='subject'>Subject: </label><input type='text' class='form-control' name='subject'><br>";
			  		echo "<label for='comment'>Comment: </label><textarea id='commentBox' type='text' class='form-control' name='comment' rows=40></textarea><br>";
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
				$temp = new CommentHandler($conn);

		  		//If we can't get any comments for this id, then we're not in a thread.
				if ($temp->getComments(intval($_GET['id'])) == false) {
				 	header('Location: 404.php');
				}
			} else {
				//We're on the front page, get the latest threads.
				$temp = new ThreadHandler($conn);
				$threads = $temp->getThreads();

				var_dump($threads);

				//getThreads();
			}
		?>
	</body>
</html>

