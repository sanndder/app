<?php
/* Smarty version 3.1.33, created on 2019-12-17 15:18:44
  from 'C:\xampp\htdocs\app\application\views\login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5df8e3c4480ed3_48280565',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fcc17511ae5d564a1c68ca780f98fefe3d2b6698' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\login.tpl',
      1 => 1576592323,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5df8e3c4480ed3_48280565 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>App - Login</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/template/global_assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/template/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/template/assets/css/bootstrap_limitless.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/template/assets/css/layout.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/template/assets/css/layout.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/template/assets/css/components.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/template/assets/css/colors.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

</head>

<body>

<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark">
	<div class="navbar-brand">
		<img src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/template/global_assets/images/logo_light.png" alt="">
	</div>

	<div class="d-md-none">
	</div>

	<div class="collapse navbar-collapse" id="navbar-mobile"></div>
</div>
<!-- /main navbar -->


<!-- Page content -->
<div class="page-content">

	<!-- Main content -->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content d-flex justify-content-center align-items-center">

			<!-- Login form -->
			<form method="post" class="login-form" action="">
				<div class="card mb-0">
					<div class="card-body">
						<div class="text-center mb-3">

							<img src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/template/global_assets/images/abering_blauw_sm.png" style="width: 70px">

							<h5 class="mb-0">Login bij Abering Online</h5>
							<span class="d-block text-muted">Vul je gegevens in</span>
						</div>

						<?php if (isset($_smarty_tpl->tpl_vars['msg']->value)) {
echo $_smarty_tpl->tpl_vars['msg']->value;
}?>

						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="username" type="text" class="form-control" placeholder="Emailadres">
							<div class="form-control-feedback">
								<i class="icon-user text-muted"></i>
							</div>
						</div>

						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="password" type="password" class="form-control" placeholder="Wachtwoord">
							<div class="form-control-feedback">
								<i class="icon-lock2 text-muted"></i>
							</div>
						</div>

						<div class="form-group">
							<button name="login" type="submit" style="background-color: #002E65" class="btn btn-primary btn-block">Inloggen <i class="icon-circle-right2 ml-2"></i></button>
						</div>

						<div class="text-center">
							<a href="login_password_recover.html">Wachtwoord vergeten?</a>
						</div>
					</div>
				</div>
			</form>
			<!-- /login form -->

		</div>
		<!-- /content area -->

	</div>
	<!-- /main content -->

</div>
<!-- /page content -->

</body>
</html>
<?php }
}
