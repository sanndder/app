{extends file='../layout.tpl'}
{block "title"}Factuur{/block}
{block "header-icon"}mi-euro-symbol{/block}
{block "header-title"}Factuur #{$factuur.factuur_nr}{/block}
{assign "uploader" "true"}
{assign "datamask" "true"}

{block "content"}
	<link href="recources/css/verloning_input.css" rel="stylesheet" type="text/css">
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


            {if isset($msg)}{$msg}{/if}

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

				<div class="col-md-4">

					<!---------------------------------------------------------------------------------------------------------
					|| Bedragen
					---------------------------------------------------------------------------------------------------------->
					<div class="card">
						<div class="card-header bg-white header-elements-inline">
							<h6 class="card-title py-0">Factuurbedragen</h6>
						</div>
						<div class="card-body">

							<table>
								<tr>
									<th></th>
									<th class="text-right pr-3">Factuur</th>
									<th class="text-right pr-3">Voldaan</th>
									<th class="text-right">Openstaand</th>
								</tr>
                                {** incl **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Totaal incl.:</td>
									<td class=" pr-3 pt-1 text-right {if $factuur.bedrag_incl == ($factuur.bedrag_incl - $factuur.bedrag_openstaand)} text-success font-weight-bold{/if}">€ {$factuur.bedrag_incl|number_format:2:',':'.'}</td>
									<td class=" pr-3 pt-1 text-right">€ {abs($factuur.bedrag_incl) - $factuur.bedrag_openstaand|number_format:2:',':'.'}</td>
									<td class="pt-1 text-right">€ {$factuur.bedrag_openstaand|number_format:2:',':'.'}</td>
								</tr>
                                {** incl **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Totaal vrij:</td>
									<td class="pr-3 pt-1 text-right {if ($factuur.bedrag_incl - $factuur.bedrag_grekening) == $betaald_vrij} text-success font-weight-bold{/if}">€ {$factuur.bedrag_incl - $factuur.bedrag_grekening|number_format:2:',':'.'}</td>
									<td class="pr-3 pt-1 text-right">€ {$betaald_vrij|number_format:2:',':'.'}</td>
									<td class="pt-1 text-right">€ {abs($factuur.bedrag_incl) - $factuur.bedrag_grekening - $betaald_vrij|number_format:2:',':'.'}</td>
								</tr>
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Totaal G-rekening:</td>
									<td class="pr-3 pt-1 text-right {if $factuur.bedrag_grekening == $betaald_g} text-success font-weight-bold{/if}">€ {$factuur.bedrag_grekening|number_format:2:',':'.'}</td>
									<td class="pr-3 pt-1 text-right">€ {$betaald_g|number_format:2:',':'.'}</td>
									<td class="pt-1 text-right">€ {$factuur.bedrag_grekening - $betaald_g|number_format:2:',':'.'}</td>
								</tr>

								<tr>
									<td colspan="4" style="height: 15px;"></td>
								</tr>

                                {** excl **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Totaal excl.:</td>
									<td class=" pr-3 pt-1 text-right">€ {$factuur.bedrag_excl|number_format:2:',':'.'}</td>
									<td></td>
									<td></td>
								</tr>
                                {** btw **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Totaal btw:</td>
									<td class=" pr-3 pt-1 text-right">{if $factuur.bedrag_btw === NULL}verlegd{else}€ {$factuur.bedrag_btw|number_format:2:',':'.'}{/if} </td>
									<td></td>
									<td></td>
								</tr>
							</table>

						</div>
					</div>

					<!---------------------------------------------------------------------------------------------------------
					|| Details
					---------------------------------------------------------------------------------------------------------->
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
                                {** status **}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Status:</td>
									<td class="pt-1">
                                        {if $factuur.voldaan == 0}<span class="text-warning font-weight-bold">openstaand</span>{/if}
                                        {if $factuur.voldaan == 1}<span class="text-success font-weight-bold">voldaan</span>{/if}
									</td>
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
                                    {** voldaan op **}
	                                <tr>
		                                <td class="pr-5 pt-1 font-weight-bold">Voldaan op:</td>
		                                <td class="pt-1">{if $factuur.voldaan == 1}{$factuur.voldaan_op|date_format: '%d-%m-%Y'} {/if}</td>
	                                </tr>
                                    {** Betaaltermijn **}
									<tr>
										<td class="pr-5 pt-1 font-weight-bold">Betaaltermijn:</td>
										<td class="pt-1">{$factuur.betaaltermijn} dagen</td>
									</tr>
                                    {** vervallen **}
                                    {if $factuur.voldaan == 0}
									<tr>
										<td class="pr-5 pt-1 font-weight-bold">Dagen vervallen:</td>
										<td class="pt-1"><span class="{if $factuur.verval_dagen > 0}text-danger font-weight-bold{/if}">
											{if $factuur.verval_dagen > 0}+{/if}{$factuur.verval_dagen} dagen
										</span></td>
									</tr>
                                    {else}
	                                    <tr>
		                                    <td class="pr-5 pt-1 font-weight-bold">DSO:</td>
		                                    <td class="pt-1">{$factuur.opengestaan} dagen</td>
	                                    </tr>
                                    {/if}
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
										<td class="pt-1">{$factuur.user|default:''}</td>
									</tr>
                                {/if}
								<tr>
									<td class="pr-5 pt-1 font-weight-bold">Filename:</td>
									<td class="pt-1">{$factuur.file_name}</td>
								</tr>
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
							<h6 class="card-title py-0">Betalingen</h6>
						</div>

						<div class="card-body">

							<div class="row">

								<!--- Overzicht ------------------------------------------------------------------------------------------------------>
								<div class="col-lg-7 mb-2">

									<table class="table table-xs">
										<tr>
											<th></th>
											<th class="pl-1">Datum</th>
											<th class="text-right">Bedrag</th>
											<th>Type</th>
											<th>Bank</th>
											<th>Door</th>
											<th>Op</th>
										</tr>
                                        {if isset($betalingen) && is_array($betalingen)}
                                            {foreach $betalingen as $b}
												<tr {if $b.deleted == 1}style="text-decoration: line-through; color: #888; display: none" class="tr-deleted" {/if}>
													<td style="width: 20px" class="p-0 pl-2">
														<a href="facturatie/factuur/details/{$factuur_id}?delbetaling={$b.id}" onclick="return confirm('Betaling verwijderen?')" class="text-warning">
															<i class="icon-trash p-0 m-0"></i>
														</a>
													</td>
													<td class="pl-1">{$b.betaald_op|date_format: '%d-%m-%Y'}</td>
													<td class="text-right">€ {$b.bedrag|number_format:2:',':'.'}</td>
													<td>
                                                        {if isset($betaling_categorien[$b.categorie_id])}{$betaling_categorien[$b.categorie_id]}{/if}
													</td>
													<td>
                                                        {$b.transactie_id}
													</td>
													<td>{$b.user|default:''}</td>
													<td>{$b.timestamp|date_format: '%d-%m-%Y'}</td>
												</tr>
                                            {/foreach}
                                        {/if}
									</table>

									<div class="mt-2 text-primary toggle-deleted" style="cursor:pointer;"><i class="icon-eye mr-1"></i> Verwijderd tonen/verbergen</div>
									<script>
										$('.toggle-deleted').on('click', function(){ $('.tr-deleted').toggle() })
									</script>

								</div><!-- /col -->


							    <!--- Toevoegen ------------------------------------------------------------------------------------------------------>
								<div class="col-lg-4 offset-lg-1">

									<form method="post" action="">
										<table>
											<tr>
												<td class="pr-3 pb-1">Type</td>
												<td class="pb-1">
													<select name="categorie_id"  class="form-control">
														{if isset($betaling_categorien) && is_array($betaling_categorien)}
															{foreach $betaling_categorien as $c}
																<option value="{$c@key}">{$c}</option>
															{/foreach}
														{/if}
													</select>
												</td>
											</tr>
											<tr>
												<td class="pr-3 pb-1">Bedrag</td>
												<td class="pb-1">
													<input name="bedrag" value="" type="text" class="form-control text-right" required />
												</td>
											</tr>
											<tr>
												<td class="pr-3 pb-1">Datum</td>
												<td class="pb-1">
													<input name="datum" value="" type="text" class="form-control" placeholder="dd-mm-jjjj" data-mask="99-99-9999" required />
												</td>
											</tr>
											<tr>
												<td class="pt-2" colspan="2">
													<button name="add_betaling" type="submit" class="btn btn-sm btn-success">
														<i class="icon-add mr-1"></i>Toevoegen
													</button>
												</td>
											</tr>
										</table>
									</form>

								</div><!-- /col -->
							</div><!-- /row -->


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
												<td>{$b.user|default:''}</td>
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
												<td>{$b.user|default:''}</td>
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
											$('#fileupload').on('fileuploaded', function()
											{
												window.location.reload();
											});
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