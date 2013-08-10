<fieldset >
					<legend style="color:#37b2d1">User information</legend>
					<div style="color:white">
						<img alt="user" height="12" src="img/mono-icons/contactcard32.png" width="12"> <?php printf("<b>%s %s</b>",$personal[0]['firstName'],$personal[0]['lastName']);?> 
						|<a href="profile.php">Edit profile</a>|<a href="logout.php">LogOut</a> </div>
					<div style="color:white">
						<?php 
						
						?>Role :<?php echo $personal[0]['type']?><b></b>
					</div>
					</fieldset>
