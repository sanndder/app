{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}
{assign "datatable" "true"}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='urenbriefjes'}


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
                                    {if $urenbriefjes != NULL}
                                        {foreach $urenbriefjes as $urenbriefje}
											<tr role="row" class="odd">
												<td>{$urenbriefje.tijdvak}</td>
												<td>{$urenbriefje.jaar}</td>
												<td>{$urenbriefje.periode}</td>
												<td>
													<a target="_blank" href="werknemer/urenbriefjes/downloadurenbriefje/{$urenbriefje.werknemer_id}/{$urenbriefje.tijdvak}/{$urenbriefje.jaar}/{$urenbriefje.periode}">
														urenbriefje_{$urenbriefje.jaar}_{$urenbriefje.periode}.pdf
													</a>
												</td>
												<td>{$urenbriefje.date_start|date_format: '%d-%m-%Y'}</td>
												<td>{$urenbriefje.date_end|date_format: '%d-%m-%Y'}</td>
												<td></td>
											</tr>
                                        {/foreach}
                                    {/if}
								</tbody>
							</table>

						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}