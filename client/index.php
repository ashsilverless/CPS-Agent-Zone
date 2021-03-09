<?PHP

?>
<?php $templateName = 'login';?>
<?php require_once('_header.php'); ?>
<main>
	<div class="fullscreen-wrapper" style="background:url(../images/cp-login-bg.jpg);">
		<div class="header">
			<img src="../images/cp-logo-temp.png" />
		</div>
		<div class="login-panel">
			<div class="login-panel__upper">
				<h2 class="heading heading__3">Sign In To Account</h2>
				<form action="authenticate.php" method="post" name="login" id="login">
					<label for="username"id="user" >Username</label>
					<input type="text" name="username" placeholder="Username" id="username" required>
					<label for="password" id="pass">Password</label>
					<input type="password" name="password" placeholder="Password" id="password" required>
					<p class="text-right"><a href="">Forgot Password?</a></p>
					<button id="go" type="submit" value="Login" class="button button__block"><i class="fas fa-sign-in-alt"></i> Sign In</button>
				</form>
			</div>
			<div class="login-panel__lower">
				<h2 class="heading heading__4">Don't Have An Account?</h2>
				<a href="" class="button button__highlight button__block">
					<i class="fas fa-user-circle"></i>
					Sign Up
				</a>
			</div>
		</div>
		<div class="footer">Privacy | Terms & Conditions © Cheli & Peacock 2020.  All Rights Reserved</div>
	</div>
</main>


<!-- Footer -->
<?php require_once('_footer.php'); ?>
<!-- End of Footer -->

<?php require_once('modals/logout.php'); ?>
<?php require_once('_global-scripts.php'); ?>

</body>

</html>
