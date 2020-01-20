<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="{$base_url}/recources/img/letter_blauw_klein.gif">

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
                        {if !isset($success)}
							<div class="text-center mb-3">
								<i class="icon-spinner11 icon-2x text-warning border-warning border-3 rounded-round p-3 mb-3 mt-1"></i>
								<h5 class="mb-0">Wachtwoord herstel</h5>
								<span class="d-block text-muted">Wij sturen u instructies via email</span>
							</div>
                            {if isset($msg)}{$msg}{/if}
							<form method="post" action="">
								<div class="form-group form-group-feedback form-group-feedback-right">
									<input type="text" name="email" class="form-control" placeholder="Emailadres" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}">
									<div class="form-control-feedback">
										<i class="icon-mail5 text-muted"></i>
									</div>
								</div>

								<button type="submit" class="btn bg-blue btn-block" style="background-color: #002E65">
									<i class="icon-spinner11 mr-2"></i> Nieuw wachtwoord
								</button>
							</form>
                        {else}
							<div class="text-center mb-3">
								<i class="icon-check icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>
								<h5 class="mb-0">Er is een email onderweg met instructies!</h5>
								<span class="d-block mt-2">Na het instellen kunt u inloggen met uw nieuwe wachtwoord.</span>

								<a href="{$base_url}/login" class="btn bg-teal-400 px-4 mt-4">Naar het inlogscherm
									<i class="icon-circle-right2 ml-2"></i>
								</a>

							</div>
                        {/if}

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
