<!DOCTYPE html>
<html>

<head>
	<title>Chat app</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha512-3P8rXCuGJdNZOnUx/03c1jOTnMn3rP63nBip5gOP2qmUh5YAdVAvFZ1E+QLZZbC1rtMrQb+mah3AfYW11RUrWA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<style type="text/css">
		#messages {
			height: 200px;
			background: whitesmoke;
			overflow: auto;
		}

		#chat-room-frm {
			margin-top: 10px;
		}
	</style>
</head>

<body>
	<div class="container">
		<h2 class="text-center" style="margin-top: 5px; padding-top: 0;">Chat app</h2>
		<hr>
		<div class="row">
			<div class="col-md-4">
				<?php
				session_start();
				if (!isset($_SESSION['user'])) {
					header("location: index.php");
				}
				require("db/users.php");
				require("db/chatrooms.php");

				$objChatroom = new chatrooms;
				$chatrooms   = $objChatroom->getAllChatRooms();

				$objUser = new users;
				$users   = $objUser->getAllUsers();
				?>
				<table class="table table-striped">
					<thead>
						<tr>
							<td>
								<?php
								foreach ($_SESSION['user'] as $key => $user) {
									$userId = $key;
									echo '<input type="hidden" name="userId" id="userId" value="' . $key . '">';
									echo "<div>" . $user['name'] . "</div>";
									echo "<div>" . $user['email'] . "</div>";
								}
								?>
							</td>
							<td align="right" colspan="2">
								<input type="button" class="btn btn-warning" id="leave-chat" name="leave-chat" value="Leave">
							</td>
						</tr>
						<tr>
							<th colspan="3">Users</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($users as $key => $user) {
							$color = 'color: grey';
							if ($user['login_status'] == 1) {
								$color = 'color: green';
							}
							if (!isset($_SESSION['user'][$user['id']])) {
								echo "<tr class='user_list_row' style='cursor:pointer' data-receiver='" . $user['id'] . "'><td>" . $user['name'] . "</td>";
								echo "<td><i class='fa-solid fa-globe' style='" . $color . "'></i></td>";
								echo "<td>" . $user['last_login'] . "</td></tr>";
							}
						}
						?>
					</tbody>
				</table>
			</div>
			<div class="col-md-8">
				<div id="messages">
					<table id="chats" class="table table-striped">
						<thead>
							<tr>
								<th colspan="4" scope="col"><strong>Chat Room</strong></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($chatrooms as $key => $chatroom) {

								if ($userId == $chatroom['userid']) {
									$from = "Me";
								} else {
									$from = $chatroom['name'];
								}
								echo '<tr><td valign="top"><div><strong>' . $from . '</strong></div><div>' . $chatroom['msg'] . '</div><td align="right" valign="top">' . date("d/m/Y h:i:s A", strtotime($chatroom['created_on'])) . '</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
				<form id="chat-room-frm" method="post" action="">
					<div class="form-group">
						<textarea class="form-control" id="msg" name="msg" placeholder="Enter Message"></textarea>
					</div>
					<div class="form-group">
						<input type="button" value="Send" class="btn btn-success btn-block" id="send" name="send">
					</div>
				</form>
			</div>
		</div>
	</div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		var conn = new WebSocket('ws://localhost:8080?uid=' + $("#userId").val());

		conn.onopen = function(e) {
			console.log("Connection established!");
		};

		conn.onmessage = function(e) {
			let data = JSON.parse(e.data);
			let msgs = data[0];
			let type = data[1].type;
			
			if (type === 'send_msg') {
				console.log("🚀 ~ file: chatroom.php:133 ~ $ ~ msgs:", msgs)
				var row = '<tr><td valign="top"><div><strong>' + msgs.from + '</strong></div><div>' + msgs.msg + '</div><td align="right" valign="top">' + msgs.dt + '</td></tr>';
				$('#chats > tbody').append(row);

			} else if (type === 'get_chat') {
				var chatLog = '';
				msgs.forEach(msg => {
					chatLog += '<tr><td valign="top"><div><strong>' + msg.name+ '</strong></div><div>' + msg.msg + '</div><td align="right" valign="top">' + msg.created_on + '</td></tr>';
				});
				$('#chats > tbody').html(chatLog);

			}
		};

		conn.onclose = function(e) {
			console.log("Connection Closed!");
		}

		//send the message
		$("#send").click(function() {
			var receiverID = $(this).attr('data-receiver');
			var userId = $("#userId").val();
			var msg = $("#msg").val();
			var data = {
				userId: userId,
				receiverID: receiverID,
				msg: msg,
				type: 'send_msg'
			};
			conn.send(JSON.stringify(data));
			$("#msg").val("");
		});


		//change convos to another user
		$(".user_list_row").click(function() {
			var senderID = $("#userId").val();
			var receiverID = $(this).attr('data-receiver');

			$("#send").attr('data-receiver', receiverID)

			var data = {
				senderID: senderID,
				receiverID: receiverID,
				type: 'get_chat'
			};
			conn.send(JSON.stringify(data));
		});


		//logs out the user
		$("#leave-chat").click(function() {
			var userId = $("#userId").val();
			$.ajax({
				url: "action.php",
				method: "post",
				data: "userId=" + userId + "&action=leave"
			}).done(function(result) {
				var data = JSON.parse(result);
				if (data.status == 1) {
					conn.close();
					location = "index.php";
				} else {
					console.log(data.msg);
				}

			});
		});
	})
</script>

</html>