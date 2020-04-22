{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='reserveringen'}


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
				<div class="col-xl-10">

					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<fieldset class="mb-1">
								<legend class="text-uppercase font-size-sm font-weight-bold">Stand reserveringen per {if isset($stand.datum)}{$stand.datum|date_format: '%d-%m-%Y'}{/if}</legend>
							</fieldset>

							<table>
								<tr>
									<td class="pr-5">Vakantiegeld</td>
									<td class="text-right">€ {if isset($stand.vakantiegeld)}{$stand.vakantiegeld|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
								<tr>
									<td class="pr-5">Vakantieuren F12</td>
									<td class="text-right">€ {if isset($stand.vakantieuren_F12)}{$stand.vakantieuren_F12|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
								<tr>
									<td class="pr-5">Feestdagen</td>
									<td class="text-right">€ {if isset($stand.feestdagen)}{$stand.feestdagen|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
								<tr>
									<td class="pr-5">Kort verzuim</td>
									<td class="text-right">€ {if isset($stand.kort_verzuim)}{$stand.kort_verzuim|number_format:2:',':'.'}{else}0,00{/if}</td>
								</tr>
							</table>

						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}