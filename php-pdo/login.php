<?php 

if(isset($_POST['username']) && isset($_POST['password']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if(!empty($username) && !empty($password))
	{
		$password_hash = md5($password);
		
		$query = "SELECT id FROM users WHERE username = :username AND password = :password";
		$statement = $con->prepare($query);
		$statement->execute([
			'username'=>$username,
			'password'=>$password_hash,
		]);

		$count = $statement->rowCount();
		$fetch = $statement->fetch(PDO::FETCH_OBJ);

		if ($count > 0) {
			$user_id = $fetch->id;
			$_SESSION['user_id'] = $user_id;
			header('Location: index.php');
		}
		else{
			echo 'Invalid Username or Password';
		}
	}
	else
	{
		echo 'You must enter a username and password.';
	}
}
?>

<form action="<?php echo $current_file; ?>" method="POST">
	Username: <input type="text" name="username" maxlength="20"><br/>
	Password: <input type="password" name="password" maxlength="20"><br/>
	<input type="submit" value="Log In">
</form>