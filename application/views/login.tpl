<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>App - Login</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{$base_url}/template/global_assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="{$base_url}/template/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="{$base_url}/template/assets/css/bootstrap_limitless.css" rel="stylesheet" type="text/css">
	<link href="{$base_url}/template/assets/css/layout.css" rel="stylesheet" type="text/css">
	<link href="{$base_url}/template/assets/css/layout.css" rel="stylesheet" type="text/css">
	<link href="{$base_url}/template/assets/css/components.min.css" rel="stylesheet" type="text/css">
	<link href="{$base_url}/template/assets/css/colors.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

</head>

<body>

<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark">
	<div class="navbar-brand">
		<img src="{$base_url}/template/global_assets/images/logo_light.png" alt="">
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

							<img src="{$base_url}/template/global_assets/images/abering_blauw_sm.png" style="width: 70px">

							<h5 class="mb-0">Login bij Abering Online</h5>
							<span class="d-block text-muted">Vul je gegevens in</span>
						</div>

						{if isset($msg)}{$msg}{/if}

						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="username" type="text" class="form-control" placeholder="Username">
							<div class="form-control-feedback">
								<i class="icon-user text-muted"></i>
							</div>
						</div>

						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="password" type="password" class="form-control" placeholder="Password">
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
