<?php
	$plugin = new Fortynuggets_Plugin ();	
	$options = $plugin->get_options();

	$page = empty($_GET["page"]) ? "home" : str_replace("40Nuggets-","",$_GET["page"]);
	if ($page == "40Nuggets") $page = "home";
	$url = "http://localhost/dashboard/{$page}.php?alk={$options->akl}";
?>

<iframe width="100%" height="1000px" src="<?php echo $url;?>"></iframe>