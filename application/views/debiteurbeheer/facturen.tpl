{extends file='../layout.tpl'}
{block "title"}Overzicht facturen{/block}
{block "header-icon"}mi-euro-symbol{/block}
{block "header-title"}Debiteurbeheer - Facturen{/block}
{assign "datamask" "true"}

{block "content"}
	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<div class="row">
				<div class="col-md-12">

					<div class="card card-body">


						<div>€ {$totaal|number_format:2:',':'.'}</div>

						<table>

								{foreach $totaal_uitzenders as $u}
									<tr>
										<td style="width: 100px">{$u@key}</td>
										<td style="width: 300px">{$uitzenders[$u@key]}</td>
										<td style="width: 150px; text-align: right">€ {$u|number_format:2:',':'.'}</td>
										<td></td>
									</tr>
								{/foreach}

						</table>

						{foreach $eigenfacturen as $f}
							<table class="mt-4">
								<thead>
									<tr style="background-color: #2DA4DC; color:#FFF;font-size: 14px" >
										<th colspan="5" style="width: 350px;font-weight: normal" class="px-2 py-1"><a style="color: #FFF" target="_blank" href="crm/inleners/dossier/facturen/{$f.inlener_id}">{$f.inlener_id} - {$f.inlener}</a></th>
										<th></th>
										<th colspan="3" style="width: 100px;font-weight: normal" class="px-2 py-1">€{$f.totaal|number_format:2:',':'.'}</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
                                    {foreach $f.facturen as $factuur}
									<tr>
										<td style="width: 75px" class="pl-2 py-1">{$factuur.factuur_nr}</td>
										<td style="width: 55px" class="pl-2 py-1">{$factuur.periode}</td>
										<td style="width: 95px" class="pl-2 py-1">{$factuur.factuur_datum|date_format: '%d-%m-%Y'}</td>
										<td style="width: 95px" class="pl-2 py-1">{$factuur.verval_datum|date_format: '%d-%m-%Y'}</td>
										<td style="width: 55px" class="pl-2 py-1">{$factuur.betaaltermijn}</td>
										<td style="width: 75px" class="pl-2 py-1">{$factuur.bedrag_incl|number_format:2:',':'.'}</td>
										<td style="width: 75px" class="pl-2 py-1">{$factuur.bedrag_openstaand|number_format:2:',':'.'}</td>
										<td>
											<a  target="_blank" href="facturatie/factuur/details/{$factuur.factuur_id}"> details</a>
										</td>
										<td></td>
										<td></td>
									</tr>
                                    {/foreach}
								</tbody>
							</table>
						{/foreach}

					</div>

				</div><!-- /col -->
			</div><!-- /row -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}