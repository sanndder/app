<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="{$base_url}/recources/img/letter_blauw_klein.gif">
	<title>{$app_name} - {block name='title'}{/block}</title>

	<base href="{$base_url}/"/>

	<link href="recources/css/aanmelden.css?{$time}" rel="stylesheet" type="text/css">
	<link href="template/global_assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="template/global_assets/css/icons/material/styles.min.css" rel="stylesheet" type="text/css">
	<link href="template/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="template/assets/css/bootstrap_limitless.css" rel="stylesheet" type="text/css">
	<link href="template/assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="template/assets/css/colors.min.css" rel="stylesheet" type="text/css">
	<link href="recources/css/font-awesome-4.7.0/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="recources/css/custom.css?1614257081" rel="stylesheet" type="text/css">	<!-- /global stylesheets -->

	<script src="template/global_assets/js/main/jquery.min.js"></script>
	<script src="template/global_assets/js/main/bootstrap.bundle.min.js"></script>
	<script src="template/global_assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script src="recources/js/modules.js?{$time}"></script>

</head>
<body>
<div class="row">
	<div class="col-lg-12 text-center pt-3">
		<div class="logo">
			<img src="recources/img/logo.png"/>
		</div>
	</div>
</div>

{*
<div>
	<div class="d-block d-sm-none">xs</div>
	<div class="d-none d-sm-block d-md-none">small</div>
	<div class="d-none d-md-block d-lg-none">medium</div>
	<div class="d-none d-lg-block d-xl-none">large</div>
	<div class="d-none d-xl-block">x large</div>
</div>
*}

<div class="row">
	<div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
		<div class="center-wrapper">
			<div class="div-header text-center">
				<span>Aanleveren bedrijfsgegevens</span>
			</div>

			<!----------------------------------- body -------------------------->
			<div class="div-card">

				<div class="row">

					<!----------------------------------- left column -------------------------->
					<div class="col-lg-3 order-lg-2 text-right">
						<div class="samenwerking">
							<div class="d-none d-lg-block d-xl-block">In samenwerking met:</div>
							<img src="recources/img/logolimburg.jpg"/>
						</div>
					</div><!-- / right column -->

					<!----------------------------------- right column -------------------------->
					<div class="col-lg-9 order-lg-1">

						<form method="post" action="">
							<!----------------------------------- titel -------------------------->
							<div class="row mt-3">
								<div class="col-md-12 d-flex">
									<div class="circle-white"><!---------- Cirkel ------------->
										<div class="circle-blue">1</div>
									</div>
									<div class="title">Uw bedrijfsgegevens</div>
								</div>
							</div><!---------- / titel ------------->


							<div class="row mt-3">
								<div class="col-xl-8 col-lg-10 col-md-11 col-sm-11 col-11">

									<div class="form-group ml-4">
										<label class="aanmeld-label">KvK nummer</label>
										<input name="" value="" type="text" class="form-control"/>
									</div>

									<div class="form-group ml-4">
										<label class="aanmeld-label">Straat</label>
										<input name="" value="" type="text" class="form-control"/>
									</div>

									<div class="form-group ml-4">
										<label class="aanmeld-label">Huisnummer + toevoeging</label>
										<input name="" value="" type="text" class="form-control" style="width: 100px"/>
									</div>

									<div class="form-group ml-4">
										<label class="aanmeld-label">Postcode</label>
										<input name="" value="" type="text" class="form-control" style="width: 100px"/>
									</div>

									<div class="form-group ml-4">
										<label class="aanmeld-label">Plaats</label>
										<input name="" value="" type="text" class="form-control"/>
									</div>

									<div class="form-group ml-4">
										<label class="aanmeld-label">Telefoonnummer</label>
										<input name="" value="" type="text" class="form-control"/>
									</div>

									<div class="form-group ml-4">
										<label class="aanmeld-label">Emailadres</label>
										<input name="" value="" type="text" class="form-control"/>
									</div>

								</div>
							</div>

							<!----------------------------------- titel -------------------------->
							<div class="row mt-3">
								<div class="col-md-12 d-flex">
									<div class="circle-white"><!---------- Cirkel ------------->
										<div class="circle-blue">1</div>
									</div>
									<div class="title">Factuurgegevens</div>
								</div>
							</div><!---------- / titel ------------->

							<div class="row mt-3">
								<div class="col-xl-8 col-lg-10 col-md-11 col-sm-11 col-11">

									<div class="form-group ml-4">
										<label class="aanmeld-label">BTW nummer</label>
										<input name="" value="" type="text" class="form-control"/>
									</div>

									<div class="form-group ml-4">
										<label class="aanmeld-label">BTW verleggen</label>
										<div class="col-xl-8 col-md-8 pt-2">

											<div class="form-check form-check-inline">
												<label class="form-check-label"><span class=""><input value="1" type="radio" class="form-input-styled" name="btw_verleggen"></span>Ja</label>
											</div>
											<div class="form-check form-check-inline">
												<label class="form-check-label"><span class="checked"><input value="0" type="radio" class="form-input-styled" name="btw_verleggen"></span>Nee</label>
											</div>
										</div>

									</div>



								</div>
							</div>

						</form>

					</div><!-- / left column -->


				</div>


			</div>
		</div>
	</div>
</div>

</body>
</html>