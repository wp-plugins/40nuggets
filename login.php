<?php
//see: http://php.net/manual/en/function.get-magic-quotes-gpc.php
$form = get_magic_quotes_gpc() ? my_stripslashes_deep($_REQUEST) : $_REQUEST;
$plugin = new Fortynuggets_Plugin();
$options = $plugin->get_options();

if (isset($form['redeem-account'])){
	//login
	if ($plugin->login($form["email"], $form["password"])){
		$url = $plugin->getURL("home");
		$url = $plugin->getURL("home");
		echo "<div id='message' class='updated'>
			<p align='center'><strong>Your site is now attached to 40Nuggets.</strong></p><p align='center'><a href='{$url}'>Click here to manage your account.</a></p>
			</div>";
		exit;
	}else{
		echo "<div id='message' class='error'>
			<p align='center'><strong>Login Failed</strong></p>
			</div>";
		showLoginForm($form["email"], $options->api_key);
	}
}else if (isset($form['simply-add-code'])){
	//save client ID
	$plugin->set_api_key($form['api_key']);
	$options = $plugin->get_options();
	echo "<div id='message' class='updated'>
			<p align='center'><strong>Your site is now attached to 40Nuggets.</strong></p><p align='center'><a href='https://40nuggets.com/dashboard2/home.php'>Click here to manage your account.</a></p>
		  </div>";
	exit;
}else{
	$email = get_option('admin_email');
	try {
		$file = plugin_dir_path(__FILE__) . 'fortynuggets.key';
		if (file_exists($file)){
			$data = json_decode(file_get_contents($file));
			$email = $data->email;
		}
	}catch(Exception $e) {}
	showLoginForm($email, $options->api_key);
}

function showLoginForm($email, $api_key){
?>
<div class="wrap">
	<form method="POST" action="">
		<h2>Attach your site to an existing account</h2>
		<p class="description">
			If you already have an existing account just enter your login and password below.
		</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="email">E-mail</label>
					</th>
					<td>
						<input name="email" type="text" id="email" value="<?php echo $email;?>" class="regular-text code" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="password">Password</label>
					</th>
					<td>
						<input name="password" type="password" id="password" value="" class="regular-text code" />
						<br/>
						<p class="description"><a href="https://40nuggets.com/site/recovery.php" target="_blank">Forgot your password?</a></p>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="hidden" name="redeem-account" />
			<input class="button-primary" type="submit" name="login" value=" <?php _e( 'Attach' ); ?> " />
		</p>
	</form>
</div>
<div class="wrap">
	<form method="POST" action="">
		<p class="description"><a href="#" id="add-code-button">Or manually add your account ID</a></p>
		<div id="add-code-form" style="display:none;">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="api_key">Account ID</label>
						</th>
						<td>
							<input name="api_key" type="text" id="api_key" value="<?php echo $api_key;?>" class="regular-text code" />
							<p class="description">Your account ID can be found <a href="https://40nuggets.com/dashboard2/accountSettings.php" target="_blank">here</a></p>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="hidden" name="simply-add-code" />
				<input class="button-primary" type="submit" name="login" value=" <?php _e( 'Save' ); ?> " />
			</p>
		</div>
	</form>
	<script>
	document.getElementById("add-code-button").onclick = function(e){
		var form = document.getElementById("add-code-form");
		form.style.display = (form.style.display == "none") ? "block" : "none";
	}
	</script>
</div>
<?php
}


function my_stripslashes_deep($value){
	$value = is_array($value) ?
	array_map(array($this, 'stripslashes_deep'), $value) :   
	stripslashes($value);
	return $value;
}
?>