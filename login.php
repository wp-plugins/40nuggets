<?php
//see: http://php.net/manual/en/function.get-magic-quotes-gpc.php
$form = get_magic_quotes_gpc() ? my_stripslashes_deep($_REQUEST) : $_REQUEST;

if (isset($form['redeem-account'])){
	//login
	$plugin = new Fortynuggets_Plugin();
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
		showLoginForm($form["email"]);
	}
}else{
	$email = get_option('admin_email');
	try {
		$file = plugin_dir_path(__FILE__) . 'fortynuggets.key';
		if (file_exists($file)){
			$data = json_decode(file_get_contents($file));
			$email = $data->email;
		}
	}catch(Exception $e) {}
	showLoginForm($email);
}

function showLoginForm($email){
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
<?php
}


function my_stripslashes_deep($value){
	$value = is_array($value) ?
	array_map(array($this, 'stripslashes_deep'), $value) :   
	stripslashes($value);
	return $value;
}
?>