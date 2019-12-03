{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}
{assign "datatable" "true"}

{block "content"}

    {include file='crm/inleners/dossier/_sidebar.tpl' active='werknemers'}


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
							<div class="media flex-column flex-md-row">
								<a href="javascript:void(0)" class="d-none d-md-block mr-md-3 mb-3 mb-md-0">
									<span class="btn bg-teal-400 btn-icon btn-lg rounded-round">
										<span class="letter-icon">W</span>
									</span>
								</a>

								<div class="media-body">
									<h6 class="mb-0">Werknemers geplaatst bij {$inlener->bedrijfsnaam}</h6>
									<div class="letter-icon-title font-weight-semibold">{count($werknemers)} werknemers in tabel</div>
								</div>

							</div>


						</div><!-- /card body-->


						<!-- table -->
						<table class="table datatable-basic table-striped table-hover table-xs" data-page-length="15" data-order="[[0,&quot;asc&quot; ],[2,&quot;asc&quot; ]]">
							<thead class="">
								<tr>
									<th></th>
									<th style="width: 75px;">ID</th>
									<th>Naam</th>
									<th>Uitzender</th>
									<th class="text-center">Actions</th>
								</tr>
							</thead>
                            {if isset($werknemers) && is_array($werknemers) && count($werknemers) > 0}
								<tbody>
                                    {foreach $werknemers as $u}
										<tr style="{if $u.complete == 0}background-color: #EEE;{/if}{if $u.archief == 1}color: #F44336;{/if}">
											<td>{$u.complete}</td>
											<td>{$u.werknemer_id}</td>
											<td>
                                                {if $u.complete == 0}
													<span class="badge bg-success  mr-1">NIEUW</span>
                                                {/if}
												<a style="{if $u.archief == 1}color: #F44336;{/if}" href="crm/werknemers/dossier/overzicht/{$u.werknemer_id}">{$u.naam}</a>
											</td>
											<td>
												{*<a style="{if $u.archief == 1}color: #F44336;{/if}" href="crm/uitzenders/dossier/overzicht/{$u.uitzender_id}">{$u.uitzender}</a>*}
											</td>
											<td></td>
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