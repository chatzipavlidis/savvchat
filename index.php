<?php
session_start();

//Create a session of username and logging in the user to the chat room
if(isset($_POST['username'])){
	$_SESSION['username']=$_POST['username'];
}

//Unset session and logging out user from the chat room
if(isset($_GET['logout'])){
	unset($_SESSION['username']);
	header('Location:index.php');
}

?>
<html>
<head>
	<title></title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="js/jquery-1.10.2.min.js" ></script>
	<script type="text/javascript" src="js/custom.js" ></script>
</head>
<body>
<div class='header'>
	<h1>
		Chat-Room <label id="namelabel" for="">Created by Savvas</label> 
		<?php // Adding the logout link only for logged in users  ?>
		<?php if(isset($_SESSION['username'])) { ?>
			<a class='logout' href="?logout">Έξοδος</a>
		<?php } ?>
	</h1>

</div>

<div class='main'>
<?php //Check if the user is logged in or not ?>
<?php if(isset($_SESSION['username'])) { ?>

<!-- style="text-align: end;" -->
<div id='result'> </div>

<div class='chatcontrols'>
	<form method="post" onsubmit="return submitchat();">
		<input type='text' name='chat' id='chatbox' autocomplete="off" placeholder="Εισαγωγή κειμένου εδώ" />
		<input type='submit' name='send' id='send' class='btn btn-send' value='Αποστολή' />
		<input type='button' name='clear' class='btn btn-clear' id='clear' value='Clear' title="Εκαθάριση" />
	</form>
<script>
// Javascript function to submit new chat entered by user
function submitchat(){
		if($('#chat').val()=='' || $('#chatbox').val()==' ') return false;
		$.ajax({
			url:'chat.php',
			data:{chat:$('#chatbox').val(),ajaxsend:true},
			method:'post',
			success:function(data){
				$('#result').html(data); // Get the chat records and add it to result div
				$('#chatbox').val(''); //Clear chat box after successful submition
				document.getElementById('result').scrollTop=document.getElementById('result').scrollHeight; // Bring the scrollbar to bottom of the chat resultbox in case of long chatbox
			}
		})
		return false;
};

// Function to continously check the some has submitted any new chat
setInterval(function(){
	$.ajax({
			url:'chat.php',
			data:{ajaxget:true},
			method:'post',
			success:function(data){
				$('#result').html(data);
			}
	})
},1000);

// Function to chat history
$(document).ready(function(){
	$('#clear').click(function(){
		if(!confirm('Ειστε σίγουροι πως θελετε να διαγραψετε την συνομιλία?'))//Είστε σίγουροι πως θελετε να εκαθαρίσετε την συνομιλία?
			return false;
		$.ajax({
			url:'chat.php',
			data:{username:"<?php echo $_SESSION['username'] ?>",ajaxclear:true},
			method:'post',
			success:function(data){
				$('#result').html(data);
			}
		})
	})
})
</script>
<?php } else { ?>

<div class='userscreen'>
	<h1>
		<a href="" class="typewrite" data-period="2000" data-type='[ "Hi Vasoula, I m Savvas.", "I ve created a simple chat-room.", "I Like to Develop." ]'>
			<span class="wrap"></span>
		</a>
	</h1>
	<form method="post">
		<input type='text' class='input-user' placeholder="Εισαγωγή ονόματος" name='username' />
		<input type='submit' class='btn btn-user' value='Είσοδος' />
	</form>
</div>
<?php } ?>

</div>
</body>
</html>