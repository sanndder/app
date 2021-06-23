{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='ziekmeldingen'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- msg -->
            {if isset($msg)}
				<div class="row">
					<div class="col-xl-10">
                        {$msg}
					</div><!-- /col -->
				</div>
				<!-- /row -->
            {/if}

			<div class="row">
				<div class="col-xl-12">

					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							{if !isset($ziekmeldingen[0])}
								<a class="btn btn-primary" href="crm/werknemers/dossier/ziekmelding/{$werknemer->werknemer_id}">
									<i class="icon-folder-plus2 mr-1"></i> Ziekmelden
								</a>
                            {/if}

							<fieldset class="mt-3">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Openstaande ziekmeldingen</legend>
							</fieldset>

                            {if !isset($ziekmeldingen[0])}
                                <i>Geen openstaande ziekmeldingen gevonden</i>
                            {else}
	                            <table class="table mb-3" style="font-size: 11px">
		                            <thead>
			                            <th style="width: 25px">ID</th>
			                            <th>Datum melding</th>
			                            <th>Start ziekte</th>
			                            <th>Einde ziekte</th>
			                            <th>Ongeval</th>
			                            <th>Toegevoegd door</th>
			                            <th>Toegevoegd op</th>
			                            <th>Verwerkt door</th>
			                            <th>Verwerkt op</th>
			                            <th>Beter gemeld op</th>
			                            <th>Beter gemeld door</th>
			                            <th></th>
		                            </thead>
		                            <tbody>
			                            {foreach $ziekmeldingen[0] as $z}
				                            <tr>
												<td>{$z.melding_id}</td>
												<td>{$z.datum_melding|date_format: '%d-%m-%Y'}</td>
												<td>{$z.datum_start_ziek|date_format: '%d-%m-%Y'}</td>
												<td>-</td>
												<td>{if $z.ongeval == 0}Nee{else}ja{/if}</td>
					                            <td>{$z.user}</td>
					                            <td>{$z.timestamp|date_format: '%d-%m-%Y om %R:%S'}</td>
					                            <td>{if $z.verwerkt_user_id == NULL}-{else}{$z.verwerkt_user}{/if}</td>
					                            <td>{if $z.verwerkt_user_id == NULL}-{else}{$z.verwerkt_datum|date_format: '%d-%m-%Y'}{/if}</td>
					                            <td>-</td>
					                            <td>-</td>
					                            <td></td>
				                            </tr>
			                            {/foreach}
		                            </tbody>
	                            </table>
							{/if}


							<fieldset>
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Afgesloten ziekmeldingen</legend>
							</fieldset>

                            {if !isset($ziekmeldingen[1])}
								<i>Geen afgesloten ziekmeldingen gevonden</i>
                            {else}
								<table class="table" style="font-size: 11px">
	                            <thead>
		                            <th style="width: 25px">ID</th>
		                            <th>Datum melding</th>
		                            <th>Start ziekte</th>
		                            <th>Einde ziekte</th>
		                            <th>Ongeval</th>
		                            <th>Toegevoegd door</th>
		                            <th>Toegevoegd op</th>
		                            <th>Verwerkt door</th>
		                            <th>Verwerkt op</th>
		                            <th>Beter gemeld op</th>
		                            <th>Beter gemeld door</th>
		                            <th></th>
	                            </thead>
	                            <tbody>
                                    {foreach $ziekmeldingen[0] as $z}
			                            <tr>
				                            <td>{$z.melding_id}</td>
				                            <td>{$z.datum_melding|date_format: '%d-%m-%Y'}</td>
				                            <td>{$z.datum_start_ziek|date_format: '%d-%m-%Y'}</td>
				                            <td>-</td>
				                            <td>{if $z.ongeval == 0}Nee{else}ja{/if}</td>
				                            <td>{$z.user}</td>
				                            <td>{$z.timestamp|date_format: '%d-%m-%Y om %R:%S'}</td>
				                            <td>{if $z.verwerkt_user_id == NULL}-{else}{$z.verwerkt_user}{/if}</td>
				                            <td>{if $z.verwerkt_user_id == NULL}-{else}{$z.verwerkt_datum|date_format: '%d-%m-%Y'}{/if}</td>
				                            <td>{if $z.betermelding_user_id == NULL}-{else}{$z.betermelding_user}{/if}</td>
				                            <td>{if $z.betermelding_user_id == NULL}-{else}{$z.betermelding_datum|date_format: '%d-%m-%Y'}{/if}</td>
				                            <td></td>
			                            </tr>
                                    {/foreach}
	                            </tbody>
                            </table>
                            {/if}

						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}