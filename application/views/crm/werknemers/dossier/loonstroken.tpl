{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}
{assign "datatable" "true"}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='loonstroken'}


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


						<table class="table table-striped text-nowrap datatable-basic no-footer" data-order="[[3,&quot;desc&quot; ]]">
							<thead>
								<tr role="row">
									<th></th>
									<th style="width: 75px">Tijdvak</th>
									<th style="width: 75px">Jaar</th>
									<th style="width: 75px">Periode</th>
									<th>Bestand</th>
									<th>Van</th>
									<th>Tot</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
                                {if $loonstroken != NULL}
                                    {foreach $loonstroken as $loonstrook}
										<tr role="row" class="odd">
											<td>{$loonstrook.loonstrook_id}</td>
											<td>{$loonstrook.tijdvak}</td>
											<td>{$loonstrook.jaar}</td>
											<td>{$loonstrook.periode}</td>
											<td>
												<a target="_blank" href="werknemer/loonstroken/downloadloonstrook/{$loonstrook.werknemer_id}/{$loonstrook.loonstrook_id}">
													loonstrook_{$loonstrook.jaar}_{$loonstrook.periode}.pdf
												</a>
											</td>
											<td>{$loonstrook.date_start|date_format: '%d-%m-%Y'}</td>
											<td>{$loonstrook.date_end|date_format: '%d-%m-%Y'}</td>
											<td></td>
										</tr>
                                    {/foreach}
                                {/if}
							</tbody>
						</table>


					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}