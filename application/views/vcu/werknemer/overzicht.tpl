{extends file='../../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-home2{/block}
{block "header-title"}Dashboard{/block}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<div class="row">
				<!--------------------------------------------------------------------------- left ------------------------------------------------->
				<div class="col-md-2">
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-bold font-size-sm text-primary text-uppercase">Formulieren</span>
						</div>
						<div class="card-body">

							<a href="vcu/formulier/d08">
								<i class="icon-file-empty mr-1"></i>
								D08 - EVALUATIEFORMULIER UITZENDKRACHT
							</a>

						</div>
					</div>
				</div>

			    <!--------------------------------------------------------------------------- right ------------------------------------------------->
				<div class="col-md-9">

					<!----------------- Gegevens --------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-bold font-size-sm text-primary text-uppercase">Documenten</span>
						</div>

						<div class="card-body">

							<table class="table table-striped text-nowrap datatable-basic no-footer" data-order="[[4,&quot;desc&quot; ]]">
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
                                    {if isset($documenten) && $documenten != NULL}
                                        {foreach $loonstroken as $loonstrook}
											<tr role="row" class="odd">
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


						</div>
					</div>

				</div><!-- /col -->
			</div><!-- /row -->
			<!--------------------------------------------------------------------------- /right ------------------------------------------------->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}