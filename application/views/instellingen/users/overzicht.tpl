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

								<div class="justify-content-between mr-2">
									<a href="instellingen/werkgever/users/add" class="btn bg-teal-400">
										<i class="icon-plus-circle2 icon mr-1"></i>
										<span>Nieuwe gebruiker</span>
									</a>
								</div>

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
							<thead>
								<tr>
									<th></th>
									<th style="width: 75px;">ID</th>
									<th style="width: 155px;">Status</th>
									<th style="width: 75px;">Type</th>
									<th style="width: 75px;">Admin</th>
									<th>Gebruikersnaam</th>
									<th>Naam</th>
									<th>Email</th>
									<th>Link verloopt op</th>
									<th class="text-center">Actions</th>
								</tr>
							</thead>
                            {if isset($users) && is_array($users) && count($users) > 0}
								<tbody class="rowlink" data-link="row">
                                    {foreach $users as $u}
										<tr>
											<td></td>
											<td>{$u.user_id}</td>
											<td>
												{if $u.password === NULL }
													wacht op activeren
												{else}
													actief
												{/if}
											</td>
											<td>{$u.user_type}</td>
											<td>{$u.admin}</td>
											<td>{$u.username}</td>
											<td class="link">
												<a href="instellingen/werkgever/users/view/{$u.user_id}">
													<div class="letter-icon-title text-default">{$u.naam}</div>
												</a>
											</td>
											<td>{$u.email}</td>
											<td>
												{if $u.new_key_expires != NULL}{$u.new_key_expires}{/if}
											</td>
											<td></td>
										</tr>
                                    {/foreach}
								</tbody>
                            {/if}
						</table>

						<script src="template/global_assets/js/plugins/extensions/rowlink.js"></script>
						<script>
                            // Initialize
                            $('tbody.rowlink').rowlink({
                                target: '.link > a'
                            });
						</script>

					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}