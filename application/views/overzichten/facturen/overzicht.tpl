{extends file='../../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}mi-euro-symbol{/block}
{block "header-title"}Overzicht - Facturen{/block}
{assign "datamask" "true"}
{assign "select2" "true"}

{block "content"}
	<script src="recources/js/config.js?{$time}"></script>
	<script src="recources/js/facturen/overzicht.js?{$time}"></script>
	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!---------------------------------------------------------------------------------------------------------
			|| Zoeken
			---------------------------------------------------------------------------------------------------------->
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header bg-white header-elements-inline">
							<h6 class="card-title py-0">Filteren</h6>
							<div class="header-elements">
								<div class="list-icons">
									<a class="list-icons-item" data-action="collapse"></a>
								</div>
							</div>
						</div>

						<div class="card-body" {if !isset($smarty.get.filter)}style="display:none"{/if}>
							<form method="get" action="">

								<div class="row">
									<div class="col-md-2">

										<table>
											<tr>
												<td colspan="2">Tonen</td>
											</tr>
											<tr>
												<td>
										<span class="checked">
											<input id="factoring" name="factoring" value="1" type="checkbox" class="form-input-styled" checked="checked">
										</span>
												</td>
												<td class="pl-1 pt-2">
													<label for="factoring">Factoring</label>
												</td>
											</tr>
											<tr>
												<td>
										<span class="checked">
											<input id="geenfactoring" name="geenfactoring" value="1" type="checkbox" class="form-input-styled" checked="checked">
										</span>
												</td>
												<td class="pl-1 pt-2">
													<label for="geenfactoring">Geen factoring</label>
												</td>
											</tr>
										</table>

									</div>
									<div class="col-md-2">

										<table>
											<tr>
												<td colspan="2">Inlener</td>
											</tr>
											<tr>
												<td style="width: 200px">

													<select name="inlener_id" class="form-control select-search" style="width: 200px">
														<option value="">Alle Inleners</option>
                                                        {if $inleners !== NULL}
                                                            {foreach $inleners as $i}
																<option {if isset($smarty.get.inlener_id) && $smarty.get.inlener_id == $i@key}selected{/if} value="{$i@key}">{$i@key} - {$i}</option>
                                                            {/foreach}
                                                        {/if}
													</select>

												</td>
											</tr>
										</table>

									</div>
									<div class="col-md-2">

										<table>
											<tr>
												<td colspan="2">Uitzender</td>
											</tr>
											<tr>
												<td style="width: 200px">

													<select name="uitzender_id" class="form-control select-search">
														<option value="">Alle Uitzenders</option>
                                                        {if $uitzenders !== NULL}
                                                            {foreach $uitzenders as $u}
																<option {if isset($smarty.get.uitzender_id) && $smarty.get.uitzender_id == $u@key}selected{/if} value="{$u@key}">{$u@key} - {$u}</option>
                                                            {/foreach}
                                                        {/if}
													</select>

												</td>
											</tr>
										</table>
									</div>
									<div class="col-md-2">
									</div>
									<div class="col-md-2">
									</div>

									<div class="col-md-2 text-right">
										<button type="submit" name="filter" class="btn btn-success" style="width: 150px">
											<i class="icon-search4 mr-2"></i>Filter
										</button>
										<br/>
										<a href="overzichten/facturen/index" class="btn btn-danger mt-2" style="width: 150px">
											<i class="icon-cross mr-2"></i>Wissen
										</a>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>
			</div>


			<!---------------------------------------------------------------------------------------------------------
			|| Zijmenu
			---------------------------------------------------------------------------------------------------------->
			<div class="row">
				<div class="col-md-3">

					<!-------------------------------------------------- Details -------------------------------------------------------------->
					<div class="card">

						<!-- header -->
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Factuur #<span class="factuur-nr"></span> </span>
						</div>

						<!-- Zoekvelden -->
						<div class="card-body">

							<div class="wait" style="display: none">
								<i class="spinner icon-spinner3"></i> gegevens laden...
							</div>

							<div class="details" style="display: none">
								<table class="table-factuurdetails mb-3">
									<tr>
										<td>Bestand</td>
										<td class="td-link"></td>
										<td></td>
									</tr>
									<tr>
										<td>Factuurdatum</td>
										<td class="td-factuurdatum">
										</td>
										<td></td>
									</tr>
									<tr>
										<td>Vervaldatum</td>
										<td class="td-vervaldatum">
										</td>
										<td></td>
									</tr>
									<tr>
										<td>Betaaltermijn</td>
										<td class="td-betaaltermijn">
										</td>
										<td></td>
									</tr>
									<tr>
										<td colspan="3" style="height: 15px"></td>
									</tr>
									<tr>
										<td></td>
										<td class="text-right">Openstaand</td>
										<td class="text-right">Totaal</td>
									</tr>
									<tr class="font-weight-bold">
										<td>Factuur (€)</td>
										<td class="td-totaal-o text-right"></td>
										<td class="td-totaal text-right"></td>
									</tr>
									<tr>
										<td>Vrije deel (€)</td>
										<td class="td-vrij-o text-right"></td>
										<td class="td-vrij text-right"></td>
									</tr>
									<tr>
										<td>G-rekening(€)</td>
										<td class="td-grekening-o text-right"></td>
										<td class="td-grekening text-right"></td>
									</tr>
									<tr>
										<td colspan="3" style="height: 8px"></td>
									</tr>
									<tr>
										<td>Kosten factoring(€)</td>
										<td></td>
										<td class="td-kosten text-right"></td>
									</tr>
								</table>

								<span class="font-weight-bold text-primary">Betalingen</span>
								<table class="table-betalingen mt-2 mb-3">
									<thead>
										<tr>
											<th>Type</th>
											<th>Bedrag</th>
											<th>Datum</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>

								<span class="font-weight-bold text-primary">Betaling toevoegen</span>
								<table class="table-betaling mt-2">
									<tr>
										<td>
											<select class="form-control" name="type">
												<option></option>
												<option value="iban">IBAN Abering</option>
												<option value="grekening">G-rekening Abering</option>
												<option value="aankoop">Aankoop Factoring</option>
												<option value="eind">Eind Factoring</option>
												<option value="kosten">Kosten Factoring</option>
											</select>
										</td>
										<td>
											<input value="" name="datum" type="text" placeholder="dd-mm-jjjj" data-mask="99-99-9999" class="form-control" autocomplete="off" style="width: 95px;">
										</td>
										<td>
											<input value="" name="bedrag" type="text" class="form-control text-right" placeholder="bedrag" autocomplete="off" style="width: 75px;">
										</td>
										<td>
											<button type="button" class="btn btn-success btn-add-betaling">
												<i class="icon-check"></i>
											</button>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>

				</div><!-- /col -->


				<div class="col-md-9">

					<!-- Basic card -->
					<div class="card">

						<!------------------------------------------------------ tabel ------------------------------------------------->
						<table class="table table-striped text-nowrap table-facturen-overzicht" style="font-size: 12px">
							<thead>
								<tr>
									<th style="width: 10px"></th>
									<th style="width: 10px">
										<i class="icon-file-excel export-factoring"></i>
									</th>
									<th style="width: 100px">Status</th>
									<th style="width: 100px">Vervalt</th>
									<th style="width: 25px;">Periode</th>
									<th>Inlener</th>
									<th style="width: 100px" class="text-right">Factuur nr</th>
									<th style="width: 120px" class="text-right">Totaal (€)</th>
									<th style="width: 120px" class="text-right">G-rekening (€)</th>
									<th style="width: 100px">Factris</th>
									<th style="width: 100px">Verzonden</th>
									<th style="width: 10px"></th>
								</tr>
							</thead>
                            {if $facturen != NULL}
								<tbody>
                                    {foreach $facturen as $f}
										<tr data-id="{$f.verkoop.factuur_id}">
											<td class="td-selected p-0 m-0"></td>
											<td class="check-factuur">
                                                {if $f.verkoop.factoring == 1}
                                                    {if $f.verkoop.to_factoring_on == NULL}
														<input type="checkbox" name="select-factuur" value="{$f.verkoop.factuur_id}"/>
                                                    {/if}
                                                {/if}
											</td>
											<td>
                                                {if $f.verkoop.voldaan == 0}
													<span class="text-warning font-weight-bold">openstaand</span>
                                                {else}
													<span class="text-success font-weight-bold">voldaan</span>
                                                {/if}
											</td>
											<td>({$f.verkoop.verval_dagen})</td>
											<td>{$f.verkoop.periode}</td>
											<td style="width: 1px; white-space: nowrap;">
                                                {$f.verkoop.bedrijfsnaam}
                                                {if $f.verkoop.project != NULL}
													- {$f.verkoop.project}
                                                {/if}
											</td>
											<td class="text-right">
                                                {$f.verkoop.factuur_nr}
											</td>
											<td class="text-right">
												€ {$f.verkoop.bedrag_incl|number_format:2:',':'.'}
                                                {*/
												{if $f.verkoop.bedrag_openstaand > 0}
												   <span class="text-warning font-weight-bold">{$f.verkoop.bedrag_openstaand|number_format:2:',':'.'}</span>
													{else}
													-
												{/if}*}


											</td>
                                            {*
											<td class="text-right">
												€ {($f.verkoop.bedrag_incl-$f.verkoop.bedrag_grekening)|number_format:2:',':'.'}
											</td>
											*}
											<td class="text-right">
												€ {$f.verkoop.bedrag_grekening|number_format:2:',':'.'}
											</td>
											<td>
                                                {if $f.verkoop.factoring == 1}
                                                    {if $f.verkoop.to_factoring_on == NULL}
														<span class="btn btn-outline-primary btn-factoring btn-sm px-1 py-0">
														<i class="icon-check mr-1"></i> Geupload
													</span>
                                                    {else}
                                                        {$f.verkoop.to_factoring_on|date_format: '%d-%m-%Y'}
                                                    {/if}
                                                {else}
													Geen factoring
                                                {/if}
											</td>
											<td>
                                                {if $f.verkoop.send_on == NULL}
													<a href="crm/uitzenders/dossier/facturen/{$f.verkoop.uitzender_id}?email={$f.verkoop.factuur_id}" target="_blank" class="btn btn-outline-primary btn-sm px-1 py-0">
														<i class="icon-envelope mr-1"></i> Emailen
													</a>
                                                {else}
                                                    {$f.verkoop.send_on|date_format: '%d-%m-%Y'}
                                                {/if}
											</td>
											<td class="p-0 m-0">
												<a href="facturatie/factuur/details/{$f.verkoop.factuur_id}" target="_blank">
													<i class="icon-file-text2 mr-1"></i> details
												</a>
											</td>
										</tr>
                                    {/foreach}
								</tbody>
                            {/if}
						</table>


					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}