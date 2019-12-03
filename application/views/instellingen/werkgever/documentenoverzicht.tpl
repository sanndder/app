{extends file='../../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Instellingen werkgever{/block}
{assign "datatable" "true"}

{block "content"}
	<script>
		{literal}


		{/literal}
	</script>

	{include file='instellingen/werkgever/_sidebar.tpl' active='documenten'}
	{include file='instellingen/werkgever/modals/nieuwe_template.tpl'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-xl-12">

                    {include file='instellingen/werkgever/_topbar.tpl'}

					<!-- Basic card -->
					<div class="card">

						<div class="bg-light rounded-top">
							<div class="navbar navbar-light bg-light navbar-expand-lg py-lg-2 rounded-top justify-content-between">

								<div class="form-group form-group-feedback form-group-feedback-left mb-0 pb-0">
									<input id="datatable-search" type="search" class="form-control" placeholder="Tabel doorzoeken...">
									<div class="form-control-feedback">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

								<div class="pull-right">
									<a class="btn btn-primary btn" href="javascript:void(0)" data-toggle="modal" data-target="#modal_new_template">
										<i class="icon-plus-circle2 mr-1"></i>
										Nieuw document aanmaken
									</a>
								</div>

							</div>
						</div>

						<!-- table -->
						<table class="table datatable-basic table-striped table-hover table-xs" data-page-length="25" data-order="[[1,&quot;asc&quot; ],[5,&quot;asc&quot; ]]">
							<thead>
								<tr>
									<th></th>
									<th style="width: 50px;">ID</th>
									<th style="width: 50px;">Nr</th>
									<th>Categorie</th>
									<th style="width: 75px;">Gebruiker</th>
									<th style="width: 75px;">Taal</th>
									<th>Document</th>
									<th class="text-center">Actions</th>
								</tr>
							</thead>
                            {if isset($templates) && is_array($templates) && count($templates) > 0}
								<tbody>
                                    {foreach $templates as $t}
										<tr>
											<td></td>
											<td>{$t.template_id}</td>
											<td>{$t.template_code}</td>
											<td>{$t.categorie}</td>
											<td>{$t.owner}</td>
											<td>{$t.lang}</td>
											<td>
												<a href="instellingen/werkgever/documentenedit/{$t.template_id}">
												{$t.template_name}
												</a>
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