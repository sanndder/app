{extends file='../../layout.tpl'}
{block "title"}Overzicht facturen{/block}
{block "header-icon"}mi-euro-symbol{/block}
{block "header-title"}Overzicht - Facturen{/block}
{assign "datamask" "true"}

{block "content"}
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
				<div class="col-md-3">
					<div class="card">
						<div class="card-header bg-white header-elements-inline p-0">
							<a href="overzichten/facturenexport/index" class="btn {if !isset($smarty.get.exportbestanden)}btn-primary{/if} m-0" style="padding:15px 0; width: 50%; border-radius: 0.1875rem 0 0 0.1875rem">Facturen</a>
							<a href="overzichten/facturenexport/index?exportbestanden" class="btn {if isset($smarty.get.exportbestanden)}btn-primary{/if} m-0" style="padding:15px 0; width: 50%; border-radius:0 0.1875rem 0.1875rem 0">Exportbestanden</a>
						</div>
					</div>
				</div>

				<div class="col-md-9">
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
												<td style="width: 350px">
													<select name="inlener_id" class="form-control select-search" style="width:100%">
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
												<td style="width: 350px; padding-left: 25px">

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
					<div class="card" style="position: sticky; top: 65px">

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
												<option value="iban">IBAN FlexxOffice</option>
												<option value="grekening">G-rekening FlexxOffice</option>
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

                        {if !isset($smarty.get.exportbestanden)}
							<!------------------------------------------------------ tabel ------------------------------------------------->
							<table class="table table-striped text-nowrap table-facturen-overzicht" style="font-size: 12px">
								<thead>
									<tr>
										<th style="width: 10px"></th>
										<th style="width: 10px" class="px-1">
											<i class="icon-file-excel export-factoring" data-title="Excel export genereren" data-popup="tooltip" data-placement="top" style="cursor:pointer"></i>
										</th>
										<th style="width: 55px" class="px-1">Vervalt</th>
										<th style="width: 25px;" class="px-1" c>Periode</th>
										<th>Inlener</th>
										<th style="width: 80px" class="text-right px-0">Factuur nr</th>
										<th style="width: 80px" class="text-right px-0">Bijlages</th>
										<th style="width: 100px" class="text-right">Totaal (€)</th>
										<th style="width: 100px" class="text-right">G-rekening (€)</th>
										<th style="width: 100px">Factris</th>
										<th style="width: 100px">Verzonden</th>
										<th style="width: 10px"></th>
									</tr>
								</thead>
                                {if $facturen != NULL}
									<tbody>
                                        {foreach $facturen as $f}
                                            {if $f.verkoop.voldaan == 0 AND $f.verkoop.factoring == 1 || (($f.verkoop.factoring == 0 && $f.verkoop.send_on == NULL) && $f.verkoop.bedrag_incl != 0)}
												<tr data-id="{$f.verkoop.factuur_id}">
													<td class="td-selected p-0 m-0"></td>
													<td class="check-factuur px-1">
                                                        {if $f.verkoop.factoring == 1}
                                                            {if $f.verkoop.to_factoring_on == NULL}
																<input type="checkbox" name="select-factuur" value="{$f.verkoop.factuur_id}"/>
                                                            {/if}
                                                            {if $f.verkoop.export_file_id !== NULL}
	                                                            <a target="_blank" href="overzichten/facturenexport/downloadexport/{$f.verkoop.export_file_id}">{$f.verkoop.export_file_id}</a>
                                                            {/if}
                                                        {/if}
													</td>
													<td class="px-1">({$f.verkoop.verval_dagen})</td>
													<td class="px-1">{$f.verkoop.periode}</td>
													<td style="width: 1px; white-space: nowrap;">
                                                        {$f.verkoop.bedrijfsnaam}
                                                        {if $f.verkoop.project != NULL}
															- {$f.verkoop.project}
                                                        {/if}
													</td>
													<td class="text-right px-0">
                                                        {$f.verkoop.factuur_nr}
													</td>
													<td class="text-right px-0">
                                                        {if !isset($f.verkoop.count_bijlages) || $f.verkoop.count_bijlages == 0}
	                                                        <i class="icon-alert text-danger"></i> <span class="text-danger"> 0</span>
	                                                    {else}
                                                            {$f.verkoop.count_bijlages}
                                                        {/if}

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
                                                        {if $f.verkoop.wachtrij == 0 || ($f.verkoop.wachtrij == 1 && $f.verkoop.wachtrij_akkoord == 1)}
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
                                                        {else}
															<i class="icon-hour-glass mr-1"></i>
															in wachtrij
                                                        {/if}

													</td>
													<td>
                                                        {if $f.verkoop.wachtrij == 0 || ($f.verkoop.wachtrij == 1 && $f.verkoop.wachtrij_akkoord == 1)}
                                                            {if $f.verkoop.send_on == NULL}
                                                                {if $f.verkoop.bedrag_incl != 0}
																	<a href="crm/uitzenders/dossier/facturen/{$f.verkoop.uitzender_id}?email={$f.verkoop.factuur_id}" target="_blank" class="btn btn-outline-primary btn-sm px-1 py-0">
																		<i class="icon-envelope mr-1"></i> Emailen
																	</a>
                                                                {/if}
                                                            {else}
                                                                {$f.verkoop.send_on|date_format: '%d-%m-%Y'}
                                                            {/if}
                                                        {else}
															<i class="icon-hour-glass mr-1"></i>
															in wachtrij
                                                        {/if}
													</td>
													<td class="p-0 m-0">
														<a href="facturatie/factuur/details/{$f.verkoop.factuur_id}" target="_blank">
															<i class="icon-file-text2 mr-1"></i> details
														</a>
													</td>
												</tr>
                                            {/if}
                                        {/foreach}
									</tbody>
                                {/if}
							</table>
                        {else}
							<table class="table table-striped text-nowrap table-facturen-overzicht" style="font-size: 12px">
								<thead>
									<tr>
										<th style="width: 20px">ID</th>
										<th style="width: 60px">Datum/tijd</th>
										<th>Bestand</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
                                    {if isset($exportbestanden) && $exportbestanden !== NULL}
                                        {foreach $exportbestanden as $b}
											<tr>
												<td>{$b.id}</td>
												<td>{$b.timestamp|date_format: '%d-%m-%Y om %R:%S'}</td>
												<td>
													<a target="_blank" href="overzichten/facturenexport/downloadexport/{$b.id}">{$b.file_name}</a>
												</td>
												<td></td>
											</tr>
                                        {/foreach}
                                    {/if}
								</tbody>
							</table>
                        {/if}


					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}