{extends file='../../../layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzender - {$uitzender->bedrijfsnaam}{/block}
{assign "datatable" "true"}

{block "content"}

    {include file='crm/uitzenders/dossier/_sidebar.tpl' active='inleners'}


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
								<a href="#" class="d-none d-md-block mr-md-3 mb-3 mb-md-0">
									<span class="btn bg-teal-400 btn-icon btn-lg rounded-round">
										<span class="letter-icon">I</span>
									</span>
								</a>

								<div class="media-body">
									<h6 class="mb-0">Inleners voor {$uitzender->bedrijfsnaam}</h6>
									<div class="letter-icon-title font-weight-semibold">{count($inleners)} inleners in tabel</div>
								</div>

								<div class="justify-content-between">
									<a href="crm/inleners/dossier/bedrijfsgegevens?uitzender_id={$uitzender->uitzender_id}" class="btn bg-teal-400">
										<i class="icon-plus-circle2 icon mr-1"></i>
										<span>Nieuwe inlener</span>
									</a>
								</div>
							</div>


						</div><!-- /card body-->

						<!-- table -->
						<table class="table datatable-basic table-striped table-hover table-xs" data-page-length="15" data-order="[[0,&quot;asc&quot; ],[2,&quot;asc&quot; ]]">
							<thead class="">
								<tr>
									<th></th>
									<th style="width: 75px;">ID</th>
									<th>Bedrijfsnaam</th>
									<th class="text-center">Actions</th>
								</tr>
							</thead>
                            {if isset($inleners) && is_array($inleners) && count($inleners) > 0}
								<tbody>
                                    {foreach $inleners as $i}
										<tr style="{if $i.complete == 0}background-color: #EEE;{/if}{if $i.archief == 1}color: #F44336;{/if}">
											<td>{$i.complete}</td>
											<td>{$i.inlener_id}</td>
											<td>
                                                {if $i.complete == 0}
													<span class="badge bg-success  mr-1">NIEUW</span>
                                                {/if}
												<a style="{if $i.archief == 1}color: #F44336;{/if}" href="crm/inleners/dossier/overzicht/{$i.inlener_id}">{$i.bedrijfsnaam}</a>
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