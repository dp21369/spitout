<!DOCTYPE html>
<html>

<head>
	<title>Chat app</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha512-3P8rXCuGJdNZOnUx/03c1jOTnMn3rP63nBip5gOP2qmUh5YAdVAvFZ1E+QLZZbC1rtMrQb+mah3AfYW11RUrWA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
	<div class="container">
		<h2 class="text-center" style="margin-top: 5px; padding-top: 0;">Chat app</h2>
		<hr>
		<?php
		if (isset($_POST['join'])) {
			session_start();
			require("db/users.php");
			$objUser = new users;
			$objUser->setEmail($_POST['email']);
			$objUser->setName($_POST['uname']);
			$objUser->setLoginStatus(1);
			$objUser->setLastLogin(date('Y-m-d h:i:s'));
			$userData = $objUser->getUserByEmail();
			if (is_array($userData) && count($userData) > 0) {
				$objUser->setId($userData['id']);
				if ($objUser->updateLoginStatus()) {
					echo "User login..";
					$_SESSION['user'][$userData['id']] = $userData;
					header("location: chatroom.php");
				} else {
					echo "Failed to login.";
				}
			} else {
				if ($objUser->save()) {
					$lastId = $objUser->dbConn->lastInsertId();
					$objUser->setId($lastId);
					$_SESSION['user'][$lastId] = [
						'id' => $objUser->getId(),
						'name' => $objUser->getName(),
						'email' => $objUser->getEmail(),
						'login_status' => $objUser->getLoginStatus(),
						'last_login' => $objUser->getLastLogin()
					];

					echo "User Registred..";
					header("location: chatroom.php");
				} else {
					echo "Failed..";
				}
			}
		}
		?>
		<div class="row join-room">
			<div class="col-md-6 col-md-offset-3">
				<form id="join-room-frm" role="form" method="post" action="" class="form-horizontal">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon addon-diff-color">
								<span class="glyphicon glyphicon-user"></span>
							</div>
							<input type="text" class="form-control" id="uname" name="uname" placeholder="Enter Name" required>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon addon-diff-color">
								<span class="glyphicon glyphicon-envelope"></span>
							</div>
							<input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address" value="" required>
						</div>
					</div>
					<div class="form-group">
						<input type="submit" value="JOIN CHATROOM" class="btn btn-success btn-block" id="join" name="join">
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>

</html>