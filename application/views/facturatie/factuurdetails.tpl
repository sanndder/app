{extends file='../layout.tpl'}
{block "title"}Factuur{/block}
{block "header-icon"}mi-euro-symbol{/block}
{block "header-title"}Factuur #{$factuur.factuur_nr}{/block}
{assign "uploader" "true"}

{block "content"}
	<link href="recources/css/verloning_input.css" rel="stylesheet" type="text/css">
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<!---------------------------------------------------------------------------------------------------------
			|| Actions
			---------------------------------------------------------------------------------------------------------->
			<div class="row">
				<div class="col-md-12">

					<div class="navbar navbar-light navbar-component rounded px-0">
						<ul class="navbar-nav flex-row">
                            {if isset($ref) && $ref !== NULL}
								<li class="nav-item">
									<a href="{$ref}" class="navbar-nav-link px-3">
										<i class="icon-undo2"></i> Terug
									</a>
								</li>
                            {/if}

							<li class="nav-item">
								<a href="facturatie/factuur/view/{$factuur_id}" class="navbar-nav-link px-3" target="_blank">
									<i class="icon-file-pdf"></i> PDF
								</a>
							</li>
							<li class="nav-item">
								<a href="facturatie/factuur/download/{$factuur_id}" class="navbar-nav-link px-3" target="_blank">
									<i class="icon-download"></i> Download
								</a>
							</li>
						</ul>
					</div>
				</div>

			</div>

			<div class="row">

				<!---------------------------------------------------------------------------------------------------------
				|| Details
				---------------------------------------------------------------------------------------------------------->
				<div class="col-md-4">

					<div class="card">
						<div class="card-header bg-white header-elements-inline">
							<h6 class="card-title py-0">Factuurdetails</h6>
						</div>

						<div class="card-body">

							<table>
                                {** Nummer **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Factuur nummer:</td>
									<td class="pt-1">{$factuur.factuur_nr}</td>
								</tr>
                                {** Type **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Type:</td>
									<td class="pt-1">
                                        {if $factuur.marge == 1}
											Marge
                                        {else}
											Verkoop
                                        {/if}
									</td>
								</tr>

								<tr>
									<td class="pt-2" colspan="2"></td>
								</tr>

                                {** Inlener **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Inlener:</td>
									<td class="pt-1">
										<a href="crm/inleners/dossier/overzicht/{$factuur.inlener_id}">
                                            {$factuur.inlener}
										</a>
									</td>
								</tr>

                                {if $factuur.project_id != NULL}
                                    {** Project **}
									<tr>
										<td class="pr-5 pt-1 font-weight-bold">Project:</td>
										<td class="pt-1">{$factuur.project_label}</td>
									</tr>
                                {/if}

								<tr>
									<td class="pt-2" colspan="2"></td>
								</tr>

                                {** Factuurdatum **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Factuurdatum:</td>
									<td class="pt-1">{$factuur.factuur_datum|date_format: '%d-%m-%Y'}</td>
								</tr>

                                {if $factuur.marge == 0}
                                    {** Vervaldatum **}
									<tr>
										<td class="pr-5 pt-1 font-weight-bold">Vervaldatum:</td>
										<td class="pt-1">{$factuur.verval_datum|date_format: '%d-%m-%Y'}</td>
									</tr>
                                    {** Betaaltermijn **}
									<tr>
										<td class="pr-5 pt-1 font-weight-bold">Betaaltermijn:</td>
										<td class="pt-1">{$factuur.betaaltermijn} dagen</td>
									</tr>
                                    {** vervallen **}
									<tr>
										<td class="pr-5 pt-1 font-weight-bold">Dagen vervallen:</td>
										<td class="pt-1"><span class="{if $factuur.verval_dagen > 0}text-danger font-weight-bold{/if}">
											{if $factuur.verval_dagen > 0}+{/if}{$factuur.verval_dagen} dagen
										</span></td>
									</tr>
                                {/if}

								<tr>
									<td class="pt-2" colspan="2"></td>
								</tr>

                                {** Tijdvak **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Tijdvak:</td>
									<td class="pt-1">{if $factuur.tijdvak == 'w'}week{/if}</td>
									<td class="pt-1">{if $factuur.tijdvak == '4w'}4 weken{/if}</td>
									<td class="pt-1">{if $factuur.tijdvak == 'm'}maand{/if}</td>
								</tr>
                                {** Jaar **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Jaar:</td>
									<td class="pt-1">{$factuur.jaar}</td>
								</tr>
                                {** Periode **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Periode:</td>
									<td class="pt-1">{$factuur.periode}</td>
								</tr>

                                {if $factuur.marge != 1}
									<tr>
										<td class="pt-2" colspan="2"></td>
									</tr>
                                    {** Kostenoverzicht **}
									<tr>
										<td class="pr-5 pt-1 font-weight-bold">Kostenoverzicht:</td>
										<td class="pt-1">
											<a href="facturatie/factuur/viewkosten/{$factuur_id}" target="_blank">
												kostenoverzicht.pdf
											</a>
										</td>
									</tr>
                                    {** Margefactuur **}
									<tr>
										<td class="pr-5 pt-1 font-weight-bold">Margefactuur:</td>
										<td class="pt-1">
                                            {if $marge_id == NULL}
												<i>Margefactuur niet gevonden</i>
                                            {else}
												<a href="facturatie/factuur/view/{$marge_id}" target="_blank">
													marge.pdf
												</a>
                                            {/if}
										</td>
									</tr>
                                {else}
									<tr>
										<td class="pt-2" colspan="2"></td>
									</tr>
                                    {** Kostenoverzicht **}
									<tr>
										<td class="pr-5 pt-1 font-weight-bold">Verkoopfactuur:</td>
										<td class="pt-1">
											<a href="facturatie/factuur/view/{$factuur.parent_id}" target="_blank">
												verkoopfactuur.pdf
											</a>
										</td>
									</tr>
                                    {** Margefactuur **}
									<tr>
										<td class="pr-5 pt-1 font-weight-bold">Kostenoverzicht:</td>
										<td class="pt-1">
											<a href="facturatie/factuur/viewkosten/{$factuur.parent_id}" target="_blank">
												kostenoverzicht.pdf
											</a>
										</td>
									</tr>
                                {/if}
								<tr>
									<td class="pt-2" colspan="2"></td>
								</tr>
                                {** Timestamp **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Aangemaakt:</td>
									<td class="pt-1">{$factuur.timestamp|date_format: '%d-%m-%Y om %R:%S'}</td>
								</tr>
                                {** Door **}
                                {if isset($factuur.user)}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Door:</td>
									<td class="pt-1">{$factuur.user}</td>
								</tr>
								{/if}
							</table>

						</div>
					</div>
				</div>

				<!---------------------------------------------------------------------------------------------------------
				|| Bedragen
				---------------------------------------------------------------------------------------------------------->

				<div class="col-md-8">

					<div class="card">
						<div class="card-header bg-white header-elements-inline">
							<h6 class="card-title py-0">Bedragen & Betalingen</h6>
						</div>

						<div class="card-body">
						</div><!-- /card body -->


					</div><!-- /card -->

					      <!---------------------------------------------------------------------------------------------------------
						  || Bijlages
						  ---------------------------------------------------------------------------------------------------------->
					<div class="card">
						<div class="card-header bg-white header-elements-inline">
							<h6 class="card-title py-0">Bijlages</h6>
						</div>

						<div class="card-body">
							<table class="table-vi-bijlages">
								<thead>
									<tr>

										<th style="width: 35px"></th>
										<th>Bestand</th>
										<th>Grootte</th>
										<th>Geupload op</th>
										<th>Geupload door</th>
										<th>Geupload bij</th>
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
												<td>Details</td>
											</tr>
                                        {/foreach}
                                    {/if}
								</tbody>
							</table>


							<div class="row mt-4 mb-0">
								<div class="col-md-6">
									<h6 class="card-title py-0 mb-2">Bijlage toevoegen</h6>
								</div>
							</div>
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
					</div><!-- /card -->


				</div><!-- /col -->

			</div> <!-- /row -->
		</div> <!-- /content area -->
	</div>
	<!-- /main content -->

{/block}