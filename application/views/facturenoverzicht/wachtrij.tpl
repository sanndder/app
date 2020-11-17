{extends file='../layout.tpl'}
{block "title"}Facturen wachtrij{/block}
{block "header-icon"}icon-hour-glass{/block}
{block "header-title"}Facturen wachtrij{/block}
{assign "uploader" "true"}

{block "content"}
	<script src="recources/js/config.js?{$time}"></script>
	<script src="recources/js/facturenoverzicht/wachtrij.js?{$time}"></script>
	<link href="recources/css/verloning_input.css" rel="stylesheet" type="text/css">

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">

				<div class="col-md-4">

					<!---------------------------------------------------------------------------------------------------------
					|| filter
					---------------------------------------------------------------------------------------------------------->
					<div class="navbar navbar-expand-lg navbar-light navbar-component rounded">
						<div class="text-center d-lg-none w-100">
							<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-filter">
								<i class="icon-unfold mr-2"></i>
								Filters
							</button>
						</div>

						<div class="navbar-collapse collapse" id="navbar-filter">
							<span class="navbar-text font-weight-semibold mr-3">
								Filteren:
							</span>
						</div>
					</div>

					<!---------------------------------------------------------------------------------------------------------
					|| facturen lijst
					---------------------------------------------------------------------------------------------------------->
                    {if $facturen !== NULL}
                        {foreach $facturen as $factuur}
                            {assign "f" $factuur.verkoop}
							<div data-id="{$f.factuur_id}" class="card border-left-3 card-factuur {if $f.wachtrij_akkoord == 0}border-left-grey{else}border-left-success-400{/if} rounded-left-0 mb-2">
								<a href="facturenoverzicht/wachtrij/{$f.factuur_id}/#{$f.factuur_id}" class="{if $factuur_id == $f.factuur_id}card-active{else}card-hover-grey{/if}">
									<div class="card-body p-2 text-default">
										<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
											<div>
												<h6 class="mb-0 pb-0">
													<span class="text-blue" style="cursor: pointer">{$f.bedrijfsnaam} - #{$f.factuur_nr}</span>
												</h6>
                                                {if $f.tijdvak == 'w'}week {$f.periode}{/if}
                                                {if $f.tijdvak == '4w'}periode {$f.periode}{/if}
											</div>

											<ul class="list list-unstyled mb-0 mt-3 mt-sm-0 ml-auto">
												Status:
                                                {if $f.wachtrij_akkoord == 0}
													<span class="badge bg-grey-400 align-top">{*<i class="icon-hour-glass3" style="font-size: 11px"></i>*} Wachten....</span>
                                                {else}
													<span class="badge bg-success-400 align-top">Verzenden</span>
                                                {/if}
											</ul>
										</div>
									</div>
								</a>
								<a id="{$f.factuur_id}" style="position:relative; display: block; top: -600px; visibility: hidden;"></a>
							</div>
                        {/foreach}
                    {else}
						<div class="card card-body rounded-left-0">
							Geen facturen in wachtrij!
						</div>
                    {/if}


				</div><!-- /col 4-->

				<!---------------------------------------------------------------------------------------------------------
				|| facturen details
				---------------------------------------------------------------------------------------------------------->
				<div class="col-md-8" style="">

                    {if isset($msg)}{$msg}{/if}

					<!-- Basic card -->
                    {if isset($details)}
					<div class="card card-details" {if !isset($details)}style="display: none" {/if}>

						<div class="card-body">

							<!-------------------------------- Status -------------------------------------------------------------->
							<fieldset class="">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Factuurstatus</legend>
							</fieldset>

								<a href="{if $details.wachtrij_akkoord == 1}javascript:void(0){else}facturenoverzicht/wachtrij/{$details.factuur_id}/?status=ready#{$details.factuur_id}{/if}" class="btn btn-sm {if $details.wachtrij_akkoord == 1}btn-success{else}btn-outline-success{/if}">
									<i class="icon-checkmark3 mr-1"></i> Klaar voor verzenden
								</a>
								<a href="facturenoverzicht/wachtrij/{$details.factuur_id}/?status=wait#{$details.factuur_id}" class="ml-2" style="font-size: 12px">
									<i class="icon-cross2" style="font-size: 11px"></i> verzenden annuleren
								</a>

							<!-------------------------------- details -------------------------------------------------------------->
							<fieldset class="mt-3">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Factuurdetails</legend>
							</fieldset>

							<div class="row">
								<div class="col-md-4">

									<table>
										<tr>
											<td class="font-weight-bold pb-1 pr-4">Factuurnummer</td>
											<td>{$details.factuur_nr}</td>
										</tr>
										<tr>
											<td class="font-weight-bold pb-1">Inlener</td>
											<td>{$details.inlener}</td>
										</tr>
										<tr>
											<td class="font-weight-bold pb-1">Periode</td>
											<td>
												{$details.jaar} -
												{if $details.tijdvak == 'w'}week {$details.periode}{/if}
												{if $details.tijdvak == '4w'}periode {$details.periode}{/if}
											</td>
										</tr>
										<tr>
											<td class="font-weight-bold">Bestand</td>
											<td>
												<a href="facturatie/factuur/view/{$factuur_id}" target="_blank">
													<i class="icon-file-pdf"></i> factuur_{$details.factuur_nr}_{$details.jaar}_{$details.periode}.pdf
												</a>
											</td>
										</tr>
									</table>

								</div><!-- /col -->
								<div class="col-md-4">

									<table>
										<tr>
											<td class="font-weight-bold pb-1 pr-4">Factuurdatum</td>
											<td>{$details.factuur_datum|date_format: '%d-%m-%Y'}</td>
										</tr>
										<tr>
											<td class="font-weight-bold pb-1">Vervaldatum</td>
											<td>{$details.verval_datum|date_format: '%d-%m-%Y'}</td>
										</tr>
										<tr>
											<td class="font-weight-bold pb-1">Betaaltermijn</td>
											<td>{$details.betaaltermijn} dagen</td>
										</tr>
									</table>
								</div>
							</div><!-- /row -->


							<!-------------------------------- bijlages -------------------------------------------------------------->
							<fieldset class="mt-4">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Projectnummer/-naam</legend>
							</fieldset>

							<div class="row">
								<div class="col-md-6 col-lg-4 col-xl-4 xol-xxl-4">

									<form method="post" action="">
										<div class="input-group">
											<input type="text" name="project" class="form-control" autocomplete="off" value="{if $details.wachtrij_project != NULL && $details.wachtrij_project != ''}{$details.wachtrij_project}{/if}">
											<button type="submit" class="btn btn-sm btn-success">
												<i class="icon-checkmark3"></i>
											</button>
										</div>
									</form>

								</div><!-- /col -->
							</div><!-- /row -->


							<!-------------------------------- bijlages -------------------------------------------------------------->
							<fieldset class="mt-4">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Bijlages</legend>
							</fieldset>

							<table class="table-vi-bijlages">
								<thead>
									<tr>
										<th style="width: 35px"></th>
										<th>Bestand</th>
										<th>Grootte</th>
										<th>Geupload op</th>
										<th>Geupload door</th>
										<th>Geupload bij</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
                                    {if $invoer_bijlages !== NUL}
                                        {foreach $invoer_bijlages as $b}
											<tr data-id="30">
												<td>
													<img class="file-icon" src="recources/img/icons/{$b.icon}">
												</td>
												<td>
													<a href="ureninvoer/bijlage/{$b.file_id}" target="_blank">
                                                        {$b.file_name_display}
													</a>
												</td>

												<td class="text-right">{$b.file_size}</td>
												<td>{$b.timestamp|date_format: '%d-%m-%Y om %R:%S'}</td>
												<td>{$b.user}</td>
												<td>Invoer</td>
											</tr>
                                        {/foreach}
                                    {/if}
                                    {if $extra_bijlages !== NUL}
                                        {foreach $extra_bijlages as $b}
											<tr data-id="30">
												<td>
													<img class="file-icon" src="recources/img/icons/{$b.icon}">
												</td>
												<td>
													<a href="ureninvoer/bijlage/{$b.file_id}?extra" target="_blank">
                                                        {$b.file_name_display}
													</a>
												</td>

												<td class="text-right">{$b.file_size}</td>
												<td>{$b.timestamp|date_format: '%d-%m-%Y om %R:%S'}</td>
												<td>{$b.user}</td>
												<td>Wachtrij</td>
												<td>
													<a href="facturenoverzicht/wachtrij/{$details.factuur_id}/?delbijlage={$b.file_id}#{$details.factuur_id}" class="text-danger" onclick="return confirm('Bijlage verwijderen?')">
														<i class="icon-trash text-danger"></i> verwijderen
													</a>
												</td>
											</tr>
                                        {/foreach}
                                    {/if}
								</tbody>
							</table>

							<!-------------------------------- bijlage toevoegen -------------------------------------------------------------->
							<fieldset class="mt-4">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Bijlage toevoegen</legend>
							</fieldset>

							<div class="row">
								<div class="col-md-6">

									<script>
                                        {literal}
										$(document).ready(function()
										{
											$('#fileupload').fileinput('refresh', {uploadUrl:'upload/uploadfactuurbijlage/{/literal}{$factuur_id}{literal}'});
											$('#fileupload').on('fileuploaded', function(){window.location.reload();});
										});
                                        {/literal}
									</script>

									<form action="#">
										<input name="file" type="file" id="fileupload" class="file-input">
									</form>

								</div><!-- /col -->
							</div><!-- /row -->

						</div>
					</div><!-- /card details-->
					{/if}

					<!-- card keuze-->
					<div class="card card-body card-wait" style="display: none">
						<div>
							<i class="spinner icon-spinner3"></i> Factuurgegevens laden...
						</div>
					</div><!-- /card keuze-->

					<!-- card keuze-->
					<div class="card card-body card-keuze" {if isset($details)}style="display: none" {/if}>
						Selecteer een factuur....
					</div><!-- /card keuze-->

				</div><!-- /col 8-->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}