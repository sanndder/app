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
				<span>Onze bedrijfsgegevens</span>
			</div>

			<!----------------------------------- body -------------------------->
			<div class="div-card">

				<div class="row">

					<!----------------------------------- right column -------------------------->
					<div class="col-lg-12 order-lg-1">

						<div class="row mt-4">
							<div class="col-md-4 offset-1 pb-4">
								<h3>{$bedrijfsgegevens.bedrijfsnaam}</h3>

								<table>
									<tr>
										<td class="pr-2">
											<i class="icon-office" style="font-size: 22px"></i>
										</td>
										<td>
                                            {$bedrijfsgegevens.straat} {$bedrijfsgegevens.huisnummer}<br />
                                            {$bedrijfsgegevens.postcode} {$bedrijfsgegevens.plaats}
										</td>
									</tr>
									<tr>
										<td colspan="2" style="height: 9px;"></td>
									</tr>
									<tr>
										<td class="pr-2">
											<i class="icon-phone"></i>
										</td>
										<td>
                                            {$bedrijfsgegevens.telefoon}
										</td>
									</tr>
									<tr>
										<td class="pr-2">
											<i class="icon-envelop"></i>
										</td>
										<td>
                                            {$bedrijfsgegevens.email}
										</td>
									</tr>
									<tr>
										<td colspan="2" style="height: 20px;"></td>
									</tr>
									<tr>
										<td colspan="2">
											KvK nummer: {$bedrijfsgegevens.kvknr}
										</td>
									</tr>
									<tr>
										<td colspan="2">
											BTW nummer: {$bedrijfsgegevens.btwnr}
										</td>
									</tr>
								</table>

								<h5 class="mt-4">Bankgegevens</h5>

								IBAN FlexxOffice Uitzend B.V. <br/>
								NL 49 INGB 0007 2918 89 <br/><br/>

								IBAN G-rekening <br/>
								NL 93 INGB 0990 3336 20 <br/><br/>

								IBAN Factoring <br/>
								NL 30 INGB 0674 5636 70<br/><br/>
							</div><!-- /col -->

							<div class="col-md-6 offset-0">


								<table class="table-documenten">
									<tr>
										<td style="font-size: 11px; text-transform: uppercase; font-weight: bold">Document</td>
										<td style="font-size: 11px; text-transform: uppercase; font-weight: bold">Dagtekening</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td class="td-grey">Algemene voorwaarden</td>
										<td class="td-grey">{$date|date_format: '%d-%m-%Y'}</td>
										<td class="td-blue">
											<a href="{$base_url}/bedrijfsinformatie/av/v?wid={$wid}" target="_blank">
												<i class="icon-eye"></i>
											</a>
										</td>
										<td class="td-blue">
											<a href="{$base_url}/bedrijfsinformatie/av/d?wid={$wid}" target="_blank">
												<i class="icon-file-download2"></i>
											</a>
										</td>
									</tr>
									{if isset($documenten)}
										{foreach $documenten as $d}
											<tr>
												<td class="td-grey">{$d.naam}</td>
												<td class="td-grey">{$d.dagtekening|date_format: '%d-%m-%Y'}</td>
												<td class="td-blue">
													<a href="{$base_url}/bedrijfsinformatie/document/v/{$d.file_id}?wid={$wid}" target="_blank">
														<i class="icon-eye"></i>
													</a>
												</td>
												<td class="td-blue">
													<a href="{$base_url}/bedrijfsinformatie/document/d/{$d.file_id}?wid={$wid}" target="_blank">
														<i class="icon-file-download2"></i>
													</a>
												</td>
											</tr>
										{/foreach}
									{/if}
								</table>

							</div>


						</div><!-- /row -->

					</div><!-- / left column -->


				</div>


			</div>
		</div>
	</div>
</div>

</body>
</html>