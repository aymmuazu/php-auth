<?php 
require 'core.inc.php';

if(!loggedin())
{
	if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['password_confirm'])&&isset($_POST['firstname'])&&isset($_POST['surname']))
	{
		$username = trim($_POST['username']);
		
		$password = trim($_POST['password']);
		$password_again = trim($_POST['password_confirm']);
		
		$firstname = trim($_POST['firstname']);
		$surname = trim($_POST['surname']);
		if(!empty($username)&&!empty($password)&&!empty($password_again)&&!empty($firstname)&&!empty($surname))
		{
			if(strlen($username)>30||strlen($firstname)>30||strlen($surname)>30)
			{
				echo 'Please adhere to maxlength of fields.';
			}
			else
			{
				if($password!=$password_again)
				{
					echo 'Passwords do not match.';
				}
				else
				{
					$password_hash = md5($password);

					$query = "SELECT username FROM users WHERE username = :username";
					$statement = $con->prepare($query);
					$statement->execute(['username'=>$username]);
					$count = $statement->rowCount();
					$fetch = $statement->fetch(PDO::FETCH_OBJ);
					
					if($count > 0)
					{
						echo 'The username '.$fetch->username.' already exists.';
					}
					else
					{

						$query = "INSERT INTO users (username,`password`,firstname,surname,created_at) 
									VALUES(:username, :pass, :firstname, :surname, :created_at)";
						try {
							$time = time();
							$statement = $con->prepare($query);
							$params = [
								':username' => $username,
								':pass' => $password_hash,
								':firstname' => $firstname,
								':surname' => $surname,
								':created_at' => date('Y-m-d h:m::s')
							];

							$statement->execute($params);
							header('Location: register_success.php');

						} catch (PDOException $e) {
							//$errors[] = $e->getMessage();
							echo 'Something went wrong try again please.';
						}
					}
				}
			}
		}
		else
		{
			echo 'All fields are required.';
		}
	}
?>

<form action="register.php" method="POST">
	Username: <br/><input type="text" name="username" maxlength="30" value="<?php if(isset($username)) { echo $username; } ?>"><br/><br/>
	Password: <br/><input type="password" name="password" maxlength="30"><br/><br/>
	Confirm Password: <br/><input type="password" name="password_confirm" maxlength="30"><br/><br/>
	First Name:<br/><input type="text" name="firstname" maxlength="30" value="<?php if(isset($firstname)) { echo $firstname; } ?>"><br/><br/>
	Surname:<br/><input type="text" name="surname" maxlength="30" value="<?php if(isset($surname)) { echo $surname; } ?>"><br/><br/>
	<input type="submit" value="Register">
</form>

<?php
}
else if(loggedin())
{
	echo 'You\'re already registered and logged in.';
}
?>