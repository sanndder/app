{extends file='../../layout.tpl'}
{block "title"}Gebruikers{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Gebruikers{/block}
{assign "datatable" "true"}

{block "content"}

    {if $usertype == 'werkgever'}{include file='instellingen/werkgever/_sidebar.tpl' active='users'}{/if}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-xl-12">

					<!-- Basic card -->
					<div class="card">

						<div class="bg-light rounded-top">
							<div class="navbar navbar-light bg-light navbar-expand-lg py-lg-2 rounded-top">

								<div class="form-group form-group-feedback form-group-feedback-left mb-0 pb-0">
									<input id="datatable-search" type="search" class="form-control" placeholder="Tabel doorzoeken...">
									<div class="form-control-feedback">
										<i class="icon-search4 text-muted"></i>
									</div>
								</div>

							</div>
						</div>

						<!-- table -->
						<table class="table datatable-basic table-striped table-hover table-xs" data-page-length="15" data-order="[[0,&quot;asc&quot; ],[2,&quot;asc&quot; ]]">
							<thead class="">
								<tr>
									<th></th>
									<th style="width: 75px;">ID</th>
									<th style="width: 75px;">Type</th>
									<th style="width: 75px;">Admin</th>
									<th>Gebruikersnaam</th>
									<th>Naam</th>
									<th>Email</th>
									<th class="text-center">Actions</th>
								</tr>
							</thead>
                            {if isset($users) && is_array($users) && count($users) > 0}
								<tbody>
                                    {foreach $users as $u}
										<tr>
											<td></td>
											<td>{$u.user_id}</td>
											<td>{$u.user_type}</td>
											<td>{$u.admin}</td>
											<td>{$u.username}</td>
											<td>{$u.naam}</td>
											<td>{$u.email}</td>
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