<?php
session_start();
$page = 'requirements';
if (!empty($_GET['page'])) {
	$page = $_GET['page'];
	if (!in_array($page, array('requirements', 'installation', 'installing', 'update-info', 'finished'))) {
		$page = 'requirements';
	}
}
if (!empty($_POST) && !empty($_POST['remember']) && $_POST['remember'] == 'false' && !empty($_SESSION) && !empty($_SESSION['SQL_INFO'])) {
	$conn = new mysqli($_SESSION['SQL_INFO']['sql_hostname'], $_SESSION['SQL_INFO']['sql_username'], $_SESSION['SQL_INFO']['sql_password'], $_SESSION['SQL_INFO']['sql_database']);
	if (!mysqli_connect_errno()) {
		mysqli_query($conn, 'DROP DATABASE ' . $_SESSION['SQL_INFO']['sql_database']);
		mysqli_query($conn, 'CREATE DATABASE ' . $_SESSION['SQL_INFO']['sql_database']);
	}
	unset($_SESSION['SQL_INFO']);
}
$remember = false;
if (!empty($_SESSION) && !empty($_SESSION['SQL_INFO']) && (empty($_GET) && !empty($_SESSION['SQL_INFO'])) || (!empty($_GET) && !empty($_GET['action']) && $_GET['action'] != 'send')) {
	$remember = true;
	$page = 'update-info';
	$_GET['action'] = 'send';
}
$title = 'Requirements';
$subtitle = 'These are some things you need to make your platform work properly';
$site_url = str_replace('install/index.php', "", $_SERVER['SCRIPT_NAME']);

$phpversion_enabled = version_compare(PHP_VERSION, '5.5.0', '>=') ? true : false;
$curl_enabled = function_exists('curl_version') ? true : false;
$mysqli_enabled = function_exists('mysqli_connect') ? true : false;
$gdlibrary_enabled = extension_loaded('gd') && function_exists('gd_info') ? true : false;
$mbstring_enabled = extension_loaded('mbstring') ? true : false;
$mail_enabled = function_exists('mail') ? true : false;

$htaccess_enabled = file_exists('../.htaccess') ? true : false;
$tubeaksql_enabled = file_exists('../tubeak.sql') ? true : false;
$infophp_enabled = is_writable('../includes/info.php') ? true : false;
$server_nginx = preg_match('/nginx/i', $_SERVER['SERVER_SOFTWARE']) ? true : false;

$install_enabled = !in_array(false, array($phpversion_enabled, $curl_enabled, $mysqli_enabled, $gdlibrary_enabled, $mbstring_enabled, $mail_enabled, $htaccess_enabled, $tubeaksql_enabled, $infophp_enabled));
if ($install_enabled == true) {
	function TB_Url($params)
	{
		return str_replace('index.php', "?page=$params", $_SERVER['SCRIPT_NAME']);
	}
	if ($page == 'installing') {
		$title = 'Problems were detected';
		$subtitle = 'It seems that some fields have conflicts when installing the script';
		if (!empty($_POST)) {
			$ServerFails = array();
			if (!filter_var($_POST['site_url'], FILTER_VALIDATE_URL)) {
				$ServerFails['Site url'] = 'The site url provided is invalid';
			}
			if (!empty($_POST['sql_hostname']) && !empty($_POST['sql_username']) && !empty($_POST['sql_database'])) {
				$conn = new mysqli($_POST['sql_hostname'], $_POST['sql_username'], $_POST['sql_password'], $_POST['sql_database']);
				if (mysqli_connect_errno()) {
					$ServerFails['MYSQL'] = 'Failed to connect to MySQL: ' . mysqli_connect_error();
				}
			} else {
				$ServerFails['MYSQL fields'] = 'You left connection fields with MYSQL empty';
			}
			if (empty($ServerFails)) {
				$write = '<?php
$mysql_hostname = "' . $_POST['sql_hostname'] . '";
$mysql_username = "' . $_POST['sql_username'] . '";
$mysql_password = "' . $_POST['sql_password'] . '";
$mysql_database = "' . $_POST['sql_database'] . '";

$site_url = "' . $_POST['site_url'] . '"; // e.g (http://example.com)
?>';
				$info = file_put_contents('../includes/info.php', $write);
				$_SESSION['SQL_INFO'] = array('sql_hostname' => $_POST['sql_hostname'], 'sql_username' => $_POST['sql_username'], 'sql_password' => $_POST['sql_password'], 'sql_database' => $_POST['sql_database']);
				if ($info == true) {
					$query = '';
					$sqlScript = file('../tubeak.sql');
					foreach ($sqlScript as $line) {
						$startWith = substr(trim($line), 0, 2);
						$endWith = substr(trim($line), -1, 1);
						if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') continue;
						$query = $query . $line;
						if ($endWith == ';') {
							mysqli_query($conn, $query);
							$query = '';
						}
					}
					header("Location: " . TB_Url('update-info&action=send'));
					exit();
				}
			}
		} else {
			header("Location: " . TB_Url('requirements'));
			exit();
		}
	} else if ($page == 'update-info') {
		$title = 'Update the information';
		$subtitle = 'To finish fill in the following fields and make sure you enter the correct information';
		$ServerFails = array();
		if (!empty($_SESSION['SQL_INFO'])) {
			if (empty($_POST['site_title'])) {
				$ServerFails['Site title'] = 'It seems this field is empty';
			}
			if (empty($_POST['site_name'])) {
				$ServerFails['Site name'] = 'It seems this field is empty';
			}
			if (empty($_POST['site_email'])) {
				$ServerFails['Site email'] = 'It seems this field is empty';
			}
			if (empty($_POST['admin_username']) || empty($_POST['admin_password'])) {
				$ServerFails['Login'] = 'Provide a valid username and/or password';
			}
			if (empty($ServerFails)) {
				$conn = new mysqli($_SESSION['SQL_INFO']['sql_hostname'], $_SESSION['SQL_INFO']['sql_username'], $_SESSION['SQL_INFO']['sql_password'], $_SESSION['SQL_INFO']['sql_database']);
				mysqli_query($conn, "UPDATE settings SET value = '" . mysqli_real_escape_string($conn, $_POST['site_name']) . "' WHERE name = 'name'");
				mysqli_query($conn, "UPDATE settings SET value = '" . mysqli_real_escape_string($conn, $_POST['site_title']) . "' WHERE name = 'title'");
				mysqli_query($conn, "UPDATE settings SET value = '" . mysqli_real_escape_string($conn, $_POST['site_email']) . "' WHERE name = 'email'");
				mysqli_query($conn, "UPDATE users SET username = '" . mysqli_real_escape_string($conn, $_POST['admin_username']) . "', password = '" . mysqli_real_escape_string($conn, sha1($_POST['admin_password'])) . "'");
				header("Location: " . TB_Url('finished'));
				exit();
			}
		} else {
			$ServerFails['MYSQL'] = 'It seems there are no credentials to connect to the database';
		}
		if (!empty($_GET['action']) && $_GET['action'] == 'send') {
			$ServerFails = array();
		}
	} else if ($page == 'installation') {
		$title = 'Installation';
		$subtitle = 'Complete the fields and make sure you enter the correct information';
	} else if ($page == 'finished') {
		session_destroy();
	}
}

?>
<!DOCTYPE html>
<html dir="ltr">

<head>
	<meta charset="UTF-8">
	<title>Tubeak - Installation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="shortcut icon" type="image/png" href="../themes/default/images/icon-favicon.png" />
	<link rel="stylesheet" href="../themes/default/assets/css/tubeak-player.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="../themes/default/assets/css/style.min.css" rel="stylesheet" />
	<script>
		var D = document;
	</script>
	<style>
		body {
			background-image: url(background.jpg);
			background-position: center;
			background-repeat: no-repeat;
		}

		#requirements,
		#update-info,
		#installation,
		#installing {
			position: relative;
			transition: all .3s linear;
		}
	</style>
</head>

<body class="background-tertiary">
	<div class="padding-10 margin-t20 overflow-hidden">
		<?php if (!empty($ServerFails) || $page != 'finished') { ?>
			<?php if ($remember == true) { ?>
				<div class="max-w-900 margin-left-auto margin-right-auto margin-b20 margin-t20" id="remember">
					<div class="vertical-center background-green border-radius-4 padding-20">
						<form class="color-white text-center" action="?page=requirements" method="POST">
							<input type="hidden" name="remember" value="false">
							It seems that your browser has closed, you can finish your configuration. If you want to restart the installation click <button class="btn-trans color-blue">here</button>
						</form>
						<button class="btn-trans margin-left-auto" id="close-remember">
							<svg class="vh-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
								<path fill="currentColor" d="M 12.939 12.004 L 19.797 5.146 C 20.061 4.89 20.069 4.468 19.812 4.204 C 19.557 3.939 19.135 3.932 18.871 4.188 C 18.866 4.193 18.859 4.198 18.854 4.204 L 11.996 11.062 L 5.139 4.204 C 4.873 3.948 4.452 3.956 4.196 4.22 C 3.946 4.478 3.946 4.888 4.196 5.146 L 11.053 12.004 L 4.196 18.864 C 3.935 19.123 3.935 19.544 4.196 19.805 C 4.456 20.065 4.877 20.065 5.139 19.805 L 11.996 12.946 L 18.854 19.805 C 19.119 20.06 19.54 20.053 19.797 19.788 C 20.046 19.53 20.046 19.121 19.797 18.864 L 12.939 12.004 Z"></path>
							</svg>
						</button>
					</div>
				</div>
				<script>
					D.querySelector('#close-remember').addEventListener('click', function() {
						D.querySelector('#remember').style.display = 'none';
					});
				</script>
			<?php } ?>
			<div class="max-w-900 margin-left-auto margin-right-auto margin-b20 margin-t20">
				<h3 class="cont-settings-title color-white" id="title"><?php echo $title ?></h3>
				<div class="margin-t10">
					<p class="color-white" id="subtitle"><?php echo $subtitle ?></p>
				</div>
			</div>
			<div class="max-w-900 border-radius-4 margin-left-auto margin-right-auto padding-20 border-all border-tertiary background-secondary" id="requirements" style="<?php echo (in_array($page, array('installation', 'installing', 'update-info'))) ? 'display: none; left: -100%;' : 'left: 0;' ?>">
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">PHP 5.5+</label>
						</div>
						<div class="flex-grow color-secondary">
							Required PHP version 5.5 or more <?php echo ($phpversion_enabled == true) ? '<span class="font-high font-bold color-green">(OK)</span>' : '<span class="font-high font-bold color-red">(NOT OK)</span>' ?>
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">NGINX (Optional)</label>
						</div>
						<div class="flex-grow color-secondary">
							NGINX is required (Only if it provides live broadcasts) <?php echo ($server_nginx == true) ? '<span class="font-high font-bold color-green">(OK)</span>' : '<span class="font-high font-bold color-red">(NOT OK)</span>' ?>
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">cURL</label>
						</div>
						<div class="flex-grow color-secondary">
							Required cURL PHP extension <?php echo ($curl_enabled == true) ? '<span class="font-high font-bold color-green">(OK)</span>' : '<span class="font-high font-bold color-red">(NOT OK)</span>' ?>
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">MySQLi</label>
						</div>
						<div class="flex-grow color-secondary">
							Required MySQLi PHP extension <?php echo ($mysqli_enabled == true) ? '<span class="font-high font-bold color-green">(OK)</span>' : '<span class="font-high font-bold color-red">(NOT OK)</span>' ?>
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">Mail</label>
						</div>
						<div class="flex-grow color-secondary">
							Required Mail PHP extension <?php echo ($mail_enabled == true) ? '<span class="font-high font-bold color-green">(OK)</span>' : '<span class="font-high font-bold color-red">(NOT OK)</span>' ?>
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">GD Library</label>
						</div>
						<div class="flex-grow color-secondary">
							Required GD Library for image cropping <?php echo ($gdlibrary_enabled == true) ? '<span class="font-high font-bold color-green">(OK)</span>' : '<span class="font-high font-bold color-red">(NOT OK)</span>' ?>
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">Mbstring</label>
						</div>
						<div class="flex-grow color-secondary">
							Required Mbstring extension for UTF-8 Strings <?php echo ($mbstring_enabled == true) ? '<span class="font-high font-bold color-green">(OK)</span>' : '<span class="font-high font-bold color-red">(NOT OK)</span>' ?>
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">.htaccess</label>
						</div>
						<div class="flex-grow color-secondary">
							Required .htaccess file for script security (Located in ./script) <?php echo ($htaccess_enabled == true) ? '<span class="font-high font-bold color-green">(OK)</span>' : '<span class="font-high font-bold color-red">(NOT OK)</span>' ?>
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">tubeak.sql</label>
						</div>
						<div class="flex-grow color-secondary">
							Required tubeak.sql for the installation (Located in ./script) <?php echo ($tubeaksql_enabled == true) ? '<span class="font-high font-bold color-green">(OK)</span>' : '<span class="font-high font-bold color-red">(NOT OK)</span>' ?>
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">info.php</label>
						</div>
						<div class="flex-grow color-secondary">
							Required info.php to be writable for the installation <?php echo ($infophp_enabled == true) ? '<span class="font-high font-bold color-green">(OK)</span>' : '<span class="font-high font-bold color-red">(NOT OK)</span>' ?>
						</div>
					</div>
				</div>
			</div>

			<form id="update-info" action="?page=update-info" method="post" <?php echo ($page != 'update-info' || !empty($ServerFails)) ? ' style="display: none;"' : '' ?>">
				<div class="max-w-900 border-radius-4 margin-left-auto margin-right-auto padding-20 border-all border-tertiary background-secondary">
					<div class="cont-group w-100 padding-20">
						<div class="display-flex flex-wrap">
							<div class="cont-label">
								<label class="font-bold color-secondary">Site name</label>
							</div>
							<div class="flex-grow color-secondary">
								<input id="site-name" name="site_name" type="text" class="cont-control background-secondary color-secondary">
							</div>
						</div>
					</div>
					<div class="cont-group w-100 padding-20">
						<div class="display-flex flex-wrap">
							<div class="cont-label">
								<label class="font-bold color-secondary">Site title</label>
							</div>
							<div class="flex-grow color-secondary">
								<input id="site-title" name="site_title" type="text" class="cont-control background-secondary color-secondary">
							</div>
						</div>
					</div>
					<div class="cont-group w-100 padding-20">
						<div class="display-flex flex-wrap">
							<div class="cont-label">
								<label class="font-bold color-secondary">Site email</label>
							</div>
							<div class="flex-grow color-secondary">
								<input id="site-email" name="site_email" type="text" class="cont-control background-secondary color-secondary">
							</div>
						</div>
					</div>
					<div class="cont-group w-100 padding-20">
						<div class="display-flex flex-wrap">
							<div class="cont-label">
								<label class="font-bold color-secondary">Admin username</label>
							</div>
							<div class="flex-grow color-secondary">
								<input id="admin-username" name="admin_username" type="text" class="cont-control background-secondary color-secondary">
							</div>
						</div>
					</div>
					<div class="cont-group w-100 padding-20">
						<div class="display-flex flex-wrap">
							<div class="cont-label">
								<label class="font-bold color-secondary">Admin password</label>
							</div>
							<div class="flex-grow color-secondary">
								<input id="admin-password" name="admin_password" type="text" class="cont-control background-secondary color-secondary">
							</div>
						</div>
					</div>
				</div>
			</form>

			<form id="installation" action="?page=installing" method="post" style="<?php echo ($page != 'installation') ? 'display: none; left: -100%' : 'left: 0;' ?>"">
	    	<div class=" max-w-900 border-radius-4 margin-left-auto margin-right-auto padding-20 border-all border-tertiary background-secondary">
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">Site url</label>
						</div>
						<div class="flex-grow color-secondary">
							<input id="site-url" name="site_url" type="text" class="cont-control background-secondary color-secondary">
							<div class="color-tertiary margin-t10">You can use a domain, subdomain, or subfolder. With http:// or https://.</div>
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">SQL hostname</label>
						</div>
						<div class="flex-grow color-secondary">
							<input id="sql-hostname" name="sql_hostname" type="text" class="cont-control background-secondary color-secondary">
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">SQL username</label>
						</div>
						<div class="flex-grow color-secondary">
							<input id="sql-username" name="sql_username" type="text" class="cont-control background-secondary color-secondary">
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">SQL password</label>
						</div>
						<div class="flex-grow color-secondary">
							<input id="sql-password" name="sql_password" type="text" class="cont-control background-secondary color-secondary">
						</div>
					</div>
				</div>
				<div class="cont-group w-100 padding-20">
					<div class="display-flex flex-wrap">
						<div class="cont-label">
							<label class="font-bold color-secondary">SQL database name</label>
						</div>
						<div class="flex-grow color-secondary">
							<input id="sql-database" name="sql_database" type="text" class="cont-control background-secondary color-secondary">
						</div>
					</div>
				</div>
	</div>
	</form>


	<div class="max-w-900 border-radius-4 margin-left-auto margin-right-auto padding-20 border-all border-tertiary background-secondary" id="installing" style="<?php echo (empty($ServerFails)) ? 'display: none; left: -100%;' : 'left: 0;' ?>">
		<?php foreach ($ServerFails as $key => $value) { ?>
			<div class="cont-group w-100 padding-20">
				<div class="display-flex flex-wrap">
					<div class="cont-label">
						<label class="font-bold color-red"><?php echo $key ?></label>
					</div>
					<div class="flex-grow color-secondary">
						<?php echo $value; ?>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>


	<div class="max-w-900 margin-left-auto margin-right-auto border-radius-4 border-all border-tertiary padding-20 margin-t20 margin-b20 background-secondary">
		<div class="display-flex flex-end">
			<?php if ($page != 'update-info' || !empty($ServerFails)) { ?>
				<span class="color-secondary vertical-center margin-r10" id="alert-installation" <?php echo ($page != 'installation') ? ' style="display: none;' : '' ?>">
					Note: this process may take a few minutes.
				</span>
				<button id="back" class="btn-trans margin-r10 background-red global-button-blue cursor-disabled color-white hover-opacity" <?php echo ($page == 'requirements') ? ' style="display: none;"' : '' ?>>Back</button>
				<button id="next" class="btn-trans global-button-blue cursor-disabled color-white hover-opacity" <?php echo ($page != 'requirements') ? ' style="display: none;"' : '';
																													echo ($install_enabled == false) ? ' disabled' : '' ?>>Next</button>
			<?php } ?>
			<button id="install" class="btn-trans global-button-blue cursor-disabled color-white hover-opacity" form="<?php echo ($page == 'update-info') ? 'update-info' : 'installation' ?>" <?php echo (!in_array($page, array('installation', 'update-info')) || !empty($ServerFails)) ? ' style="display: none;"' : '';
																																																echo ($install_enabled == false) ? ' disabled' : '' ?>>
				<span class="color-white">Install</span>
				<div class="tb_content-spinner-circle">
					<div class="tb_spinner-circle"></div>
				</div>
			</button>
		</div>
	</div>
<?php } else { ?>
	<div class="max-w-900 display-flex h-600 border-radius-4 margin-left-auto margin-right-auto padding-20 border-all border-tertiary background-secondary">
		<div class="margin-auto text-center">
			<div class="font-high margin-b10 color-green">Installation completed</div>
			<div class="font-high margin-b10 color-red">To avoid problems in general, look for the "install" folder in the root directory of the script and delete it, also delete your browser's cache.</div>
			<a class="general-two-blue-button position-relative color-white hover-opacity" href="<?php echo $site_url ?>">
				Go to my platform
			</a>
		</div>
	</div>
<?php } ?>
</div>
</body>
<script>
	var B = D.querySelector('#back'),
		N = D.querySelector('#next'),
		L = D.querySelector('#install'),
		R = D.querySelector('#requirements'),
		I = D.querySelector('#installation'),
		S = D.querySelector('#installing'),
		A = D.querySelector('#alert-installation'),
		T = D.querySelector('#title'),
		E = D.querySelector('#subtitle'),
		C = function(e, t) {
			return "classList" in D.documentElement ? e.classList.add(t) : e.className += " " + t;
		};
	<?php if (!empty($ServerFails) || $page != 'finished') { ?>
		<?php if ($page != 'update-info' || !empty($ServerFails)) { ?>N.addEventListener('click', function() {
			var PI = '?page=installation';
			T.innerText = 'Installation';
			E.innerText = 'Complete the fields and make sure you enter the correct information';
			R.style.left = '-100%';
			setTimeout(function() {
				R.style.display = 'none', I.style.display = '', setTimeout(function() {
					I.style.left = '0';
				}, 350);
			}, 350);
			B.style.display = '';
			A.style.display = '';
			N.style.display = 'none';
			L.style.display = '';
			N.innerText = 'Install';
			window.history.pushState({
				state: 'new'
			}, '', '?page=installation');
		}), B.addEventListener('click', function() {
			var P = '?page=requirements';
			if (window.location.search == '?page=installing' || window.location.search == '?page=update-info') {
				T.innerText = 'Installation';
				E.innerText = 'Complete the fields and make sure you enter the correct information';
				S.style.left = '-100%';
				R.style.left = '-100%';
				setTimeout(function() {
					S.style.display = 'none', R.style.display = 'none', I.style.display = '', setTimeout(function() {
						I.style.left = '0';
					}, 350);
				}, 350);
				B.style.display = '';
				A.style.display = '';
				N.style.display = 'none';
				L.style.display = '';
				N.innerText = 'Install';
				P = '?page=installation';
			} else {
				T.innerText = 'Requirements';
				E.innerText = 'These are some things you need to make your platform work properly';
				I.style.left = '-100%';
				setTimeout(function() {
					R.style.display = '', I.style.display = 'none', setTimeout(function() {
						R.style.left = '0';
					}, 350);
				}, 350);
				A.style.display = 'none';
				B.style.display = 'none';
				N.style.display = '';
				L.style.display = 'none';
				N.innerText = 'Next';
			}
			window.history.pushState({
				state: 'new'
			}, '', P);
		}), <?php } ?>L.addEventListener('click', function() {
			C(L, 'tb_spinner-is-loading');
		});
	<?php } ?>
</script>

</html>