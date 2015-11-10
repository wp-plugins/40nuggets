<?php
//see: http://php.net/manual/en/function.get-magic-quotes-gpc.php
$form = get_magic_quotes_gpc() ? my_stripslashes_deep($_REQUEST) : $_REQUEST;

switch ($form["form-action"]){
	case "login" : 
		login($form); 
		break;
	case "associate" :
		associate($form);
		break;
	case "signup" : 
		signup($form); 
		break;
	default : forms();
}

function login($form){
	$plugin = new Fortynuggets_Plugin();
	if ($plugin->login($form["email"], $form["password"])){
		$url = $plugin->getURL("dashboard");
		echo "<div class='wrap'>
				<h2>Welcome to 40Nuggets</h2>
				<div id='message' class='updated'>
					<p align='center'><strong>Your site is now connected to 40Nuggets. It's time to convert your visitors into leads.</strong></p>
					<p align='center'><a href='{$url}' target='_blank' class='button-primary'>LET'S GET STARTED</a></p>
				</div>
			</div>";
	}else{
		$options = $plugin->get_options();
		echo "<div class='wrap'>
				<h2></h2>
				<div id='message' class='error'>
					<p align='center'><strong>Login Failed</strong></p>
				</div>
			</div>";
		showSignupForm($form["email"],false);
		showLoginForm($form["email"],true);
	}
}

function associate($form){
	$plugin = new Fortynuggets_Plugin();
	$plugin->set_api_key($form['api_key']);
	echo "<div class='wrap'>
			<h2>Welcome to 40Nuggets</h2>
			<div id='message' class='updated'>
				<p align='center'><strong>40Nuggets' snippet code is now live on your site. It's time to convert your visitors into leads.</strong></p>
				<p align='center'><a href='https://40nuggets.com/dashboard/dashboard' target='_blank' class='button-primary'>LET'S GET STARTED</a></p>
			</div>
		</div>";
}

function signup($form){
	$plugin = new Fortynuggets_Plugin();

	if ($plugin->create_client($form["email"], $form["password"], $form["name"], $form["url"])){
		$url = $plugin->getURL("dashboard");
		echo "<div class='wrap'>
				<h2>Welcome to 40Nuggets</h2>
				<div id='message' class='updated'>
					<p align='center'><strong>Your site is now connected to 40Nuggets. It's time to convert your visitors into leads.</strong></p>
					<p align='center'><a href='{$url}' target='_blank' class='button-primary'>LET'S GET STARTED</a></p>
				</div>
			</div>";
	}else{
		$options = $plugin->get_options();
		echo "<div class='wrap'>
				<h2></h2>
				<div id='message' class='error'>
					<p align='center'><strong>Oops... Something went wrong.<br>Let's try to <a href='#' id='show-associate'>set you up manually</a></strong></p>
				</div>
			</div>";
		showSignupForm($form["email"],true);
		showLoginForm($form["email"],false);
	}
}

function forms(){
	$plugin = new Fortynuggets_Plugin();
	$email = getEmail();
	$shouldShowCreateAccount = $plugin->shouldCreateAccount();
	showSignupForm($email,$shouldShowCreateAccount);
	showLoginForm($email,!$shouldShowCreateAccount);
}





function showSignupForm($email,$visible){
	$hidden = !$visible ? 'style="display:none;"' : '';
?>
<div id="fn-signup" <?php echo $hidden;?> class="wrap">
	<form method="POST" action="" name="signup">
		<h2>Create your 40Nuggets account</h2>
		<p class="description">
			If you already opened an account <a href="#" id="show-login">click here to sign in</a>.
		</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="name">Name</label>
					</th>
					<td>
						<input name="name" type="text" value="<?php echo get_option('blogname');?>" class="regular-text code" required/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="email">E-mail</label>
					</th>
					<td>
						<input name="email" type="email" value="<?php echo $email;?>" class="regular-text code" required/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="url">Website URL</label>
					</th>
					<td>
						<input name="url" type="text" value="<?php echo get_option('home');?>" class="regular-text code" required/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="password">Password</label>
					</th>
					<td>
						<input name="password" type="password" value="" class="regular-text code" required onchange="validatePassword()"/>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="confirm">Confirm Password</label>
					</th>
					<td>
						<input name="confirm" type="password" value="" class="regular-text code" required onkeyup="validatePassword()"/>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="hidden" name="form-action" value="signup" />
			<input class="button-primary" type="submit" name="login" value=" <?php _e( 'Create My Account' ); ?> " />
		</p>
	</form>
	<script>
	function validatePassword(){
		var password = document.signup.elements["password"];
		var confirm = document.signup.elements["confirm"];
		if (password.value != confirm.value) {
			confirm.setCustomValidity("Passwords Don't Match");
		}else{
			confirm.setCustomValidity('');
		}
	}
	document.getElementById("show-login").onclick = function(e){
		document.getElementById("fn-signup").style.display = "none";
		document.getElementById("fn-login").style.display = "block";
	}
	</script>
</div>
<?php
}

function showLoginForm($email,$visible){
	$plugin = new Fortynuggets_Plugin();
	$options = $plugin->get_options();
	$hidden = !$visible ? 'style="display:none;"' : '';
?>
<div id="fn-login" <?php echo $hidden;?> class="wrap">
	<form method="POST" action="">
		<h2>Sign in to 40Nuggets</h2>
		<p class="description">
			If you don't have an account <a href="#" id="show-signup">click here to create one</a>.
		</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="email">E-mail</label>
					</th>
					<td>
						<input name="email" type="text" value="<?php echo $email;?>" class="regular-text code" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="password">Password</label>
					</th>
					<td>
						<input name="password" type="password" value="" class="regular-text code" />
						<br/>
						<p class="description"><a href="https://40nuggets.com/site/recovery.php" target="_blank">Forgot your password?</a></p>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="hidden" name="form-action" value="login" />
			<input class="button-primary" type="submit" name="login" value=" <?php _e( 'Sign In' ); ?> " />
		</p>
	</form>
	<script>
	document.getElementById("show-signup").onclick = function(e){
		document.getElementById("fn-login").style.display = "none";
		document.getElementById("fn-signup").style.display = "block";
	}
	</script>
</div>
<div id="fn-associate" class="wrap" style="display:none;">
	<form method="POST" action="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="api_key">Account ID</label>
					</th>
					<td>
						<input name="api_key" type="text" value="<?php echo $options->api_key;?>" class="regular-text code" />
						<p class="description">Your account ID can be found <a href="https://40nuggets.com/dashboard/account" target="_blank">here</a></p>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="hidden" name="form-action" value="associate" />
			<input class="button-primary" type="submit" name="login" value=" <?php _e( 'Connect' ); ?> " />
		</p>
	</form>
	<script>
	document.getElementById("show-associate").onclick = function(e){
		document.getElementById("fn-signup").style.display = "none";
		document.getElementById("fn-associate").style.display = "block";
	}
	</script>
</div>
<?php
}


function getEmail(){
	$email = get_option('admin_email');
	try {
		$file = plugin_dir_path(__FILE__) . 'fortynuggets.key';
		if (file_exists($file)){
			$data = json_decode(file_get_contents($file));
			$email = $data->email;
		}
	}catch(Exception $e) {}
	return $email;
}

function my_stripslashes_deep($value){
	$value = is_array($value) ?
	array_map('stripslashes_deep', $value) :   
	stripslashes($value);
	return $value;
}
?>