<?php
	$plugin = new Fortynuggets_Plugin ();	
	$email = $GLOBALS['MY_REQUEST']["email"];

	if( isset($GLOBALS['MY_REQUEST']['redeem-account']) ) {
		//login
		$password = $GLOBALS['MY_REQUEST']["password"];
		
		if ($plugin->login($email, $password)){
			$url = getURL("home");
			echo "
			<script type='text/javascript'>
				window.location = '$url';
			</script>";
			exit;
		}else{
			echo "<div id='message' class='error'>
				<p align='center'><strong>Login Failed</strong></p>
				</div>";
		}
	}
	
?>
<div class="wrap">

	<form method="POST" action="">
    <h2>Attach your site to an existing account</h2>
    <p class="description">
        If you already have an existing account, e.g. you are migrating your old
        site or you want to use a single 40Nuggets account on multiple websites,
        just enter your login and password below.
    </p>
	<table class="form-table">
      <tbody>
		<tr valign="top">
          <th scope="row">
            <label for="email">E-mail</label>
          </th>
          <td>
            <input name="email" type="text" id="email" value="<?php echo get_option('admin_email');?>" class="regular-text code" />
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">
            <label for="password">Password</label>
          </th>
          <td>
            <input name="password" type="password" id="password" value="" class="regular-text code" />
			<br/>
			<p class="description"><a href="https://40nuggets.com/dashboard/forgotPassword.php" target="_blank">Forgot your password?</a></p>
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
