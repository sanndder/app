{extends file='../../../layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzender - {$uitzender->bedrijfsnaam}{/block}
{assign "datatable" "true"}
{block "content"}

    {include file='crm/uitzenders/dossier/_sidebar.tpl' active='documenten'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- msg -->
            {if isset($msg)}
				<div class="row">
					<div class="col-xl-12">
                        {$msg}
					</div><!-- /col -->
				</div>
				<!-- /row -->
            {/if}

			<div class="row">
				<div class="col-xl-10">

					<!-- Basic card -->
					<div class="card">

						<!-- header -->
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Documenten</span>
							<div class="header-elements">
							</div>
						</div>

						<!------------------------------------------------------ tabel ------------------------------------------------->
						<table class="table table-striped text-nowrap datatable-basic no-footer" data-order="[[1,&quot;asc&quot; ]]">
							<thead>
								<tr role="row">
									<th></th>
									<th>Document</th>
									<th>Categorie</th>
									<th>Taal</th>
									<th>Ondertekend</th>
									<th>Acties</th>
								</tr>
							</thead>
							<tbody>
                                {foreach $documenten as $d}
									<tr role="row" class="odd">
										<td></td>
										<td>
											<div class="media">
												<div class="mr-3 mt-1">
													<i class="icon-file-pdf" style="font-size: 26px"></i>
												</div>

												<div class="media-body align-self-center">
													<a target="_blank" href="documenten/pdf/view/{$d.document_id}" class="font-weight-semibold">{$d.template_name}</a>
													<div class="text-muted font-size-sm">
                                                        {$d.aangemaakt|date_format: '%d-%m-%Y om %R:%S'}
													</div>
												</div>
											</div>
										</td>
										<td class="align-text-top">{$d.categorie}</td>
										<td class="align-text-top">{$d.lang}</td>
										<td class="align-text-top">
                                            {if $d.signed == 1}
	                                            <i class="icon-check"></i>
											{else}
	                                           <span class="text-muted"><i class="icon-hour-glass2"></i> wachten op ondertekening....</span>
                                            {/if}
										</td>
										<td class="text-right sorting_disabled">

											<div class="list-icons">
												<div class="dropdown">
													<a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown">
														<i class="icon-menu7"></i>
													</a>

													<div class="dropdown-menu dropdown-menu-right">
														<a href="javascript:void(0)" class="dropdown-item">
															<i class="icon-menu7"></i> Details
														</a>
														<a href="documenten/pdf/download/{$d.document_id}" class="dropdown-item">
															<i class="icon-download"></i> Downloaden
														</a>
														<a href="javascript:void(0)" class="dropdown-item">
															<i class="icon-envelop3"></i> Emailen
														</a>
														<div class="dropdown-divider"></div>
														<a href="javascript:void(0)" class="dropdown-item">
															<i class="icon-trash"></i> Verwijderen
														</a>
													</div>
												</div>
											</div>

										</td>
									</tr>
                                {/foreach}
							</tbody>
						</table>


					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}