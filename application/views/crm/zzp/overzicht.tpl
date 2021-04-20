{extends file='../../layout.tpl'}
{block "title"}ZZP'ers{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}ZZP'ers{/block}
{assign "datatable" "true"}

{block "content"}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main sidebar
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="sidebar sidebar-light sidebar-main sidebar-sections sidebar-expand-lg align-self-start">

		<!-- Sidebar mobile toggler -->
		<div class="sidebar-mobile-toggler text-center">
			<a href="#" class="sidebar-mobile-main-toggle">
				<i class="icon-arrow-left8"></i>
			</a>
			<span class="font-weight-semibold">Zijmenu</span>
			<a href="#" class="sidebar-mobile-expand">
				<i class="icon-screen-full"></i>
				<i class="icon-screen-normal"></i>
			</a>
		</div>
		<!-- /sidebar mobile toggler -->

		<!-- Sidebar content -->
		<div class="sidebar-content">

			<!---------------------------------------------------------------------------------------------------------
			||Snel zoeken
			---------------------------------------------------------------------------------------------------------->
			<div class="card card-sidebar-mobile d-none d-lg-block d-xl-block">

				<!-- header -->
				<div class="card-header bg-transparent header-elements-inline">
					<span class="text-uppercase font-size-sm font-weight-semibold">Snel Zoeken</span>
				</div>

				<!-- Zoekvelden -->
				<div class="card-body">

					<div class="form-group form-group-feedback form-group-feedback-left">
						<input id="datatable-search" type="search" class="form-control" placeholder="Tabel doorzoeken...">
						<div class="form-control-feedback">
							<i class="icon-search4 text-muted"></i>
						</div>
					</div>

				</div>
			</div>


			<!---------------------------------------------------------------------------------------------------------
			||zoeken
			---------------------------------------------------------------------------------------------------------->
			<div class="card card-sidebar-mobile">

				<!-- header -->
				<div class="card-header bg-transparent header-elements-inline">
					<span class="text-uppercase font-size-sm font-weight-semibold">Uitgebreid Zoeken</span>
				</div>

				<!-- Zoekvelden -->
				<div class="card-body">

					<form action="" method="get">
						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="q1" value="{if isset($smarty.get.q1)}{$smarty.get.q1}{/if}" type="search" class="form-control" placeholder="ID, bedrijfsnaam of achternaam">
							<div class="form-control-feedback">
								<i class="icon-office text-muted"></i>
							</div>
						</div>

						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="q2" value="{if isset($smarty.get.q2)}{$smarty.get.q2}{/if}" type="search" class="form-control" placeholder="Overige zoektermen">
							<div class="form-control-feedback">
								<i class="icon-search4 text-muted"></i>
							</div>
						</div>

						<div class="form-group">

							<div class="form-check">
								<label class="form-check-label">
									<input name="actief" value="1" type="checkbox" class="form-input-styled" {if isset($smarty.get.actief) || !isset($smarty.get.q1)} checked="checked"{/if}>
									Actieve ZZP'ers
								</label>
							</div>

							<div class="form-check">
								<label class="form-check-label text-danger">
									<input name="archief" value="1" type="checkbox" class="form-input-styled-danger" {if isset($smarty.get.archief)} checked="checked"{/if} data-fouc="">
									Archief
								</label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<button type="submit" class="btn bg-blue btn-block">
									<i class="icon-search4 font-size-base mr-2"></i>
									Zoeken
								</button>
							</div><!-- /col -->
							<div class="col-md-6">
								<a href="crm/zzp" class="btn btn-light" style="width: 100%">
									<i class="icon-cross font-size-base mr-2"></i>
									Wissen
								</a>
							</div>
						</div><!-- /row -->
					</form>
				</div>


			</div><!-- /main navigation -->

			<!---------------------------------------------------------------------------------------------------------
			||Snel zoeken
			---------------------------------------------------------------------------------------------------------->
			<div class="card card-sidebar-mobile d-none d-lg-block d-xl-block">

				<!-- header -->
				<div class="card-header bg-transparent header-elements-inline">
					<span class="text-uppercase font-size-sm font-weight-semibold">Weergave instellingen</span>
				</div>

				<!-- Zoekvelden -->
				<div class="card-body" style="height: 80px; ">

					<div id="move-length-dropdown" style="float: left; margin-left: -20px;">

					</div>

				</div>
			</div>


		</div>
		<!-- /sidebar content -->

	</div>
	<!-- /main sidebar  -->

	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- Basic card -->
			<div class="card">

				<!-- header -->
				<!-- card  body-->
				<div class="card-body">
					<div class="media flex-column flex-md-row">
						<a href="#" class="d-none d-md-block mr-md-3 mb-3 mb-md-0">
						<span class="btn bg-blue btn-icon btn-lg rounded-round">
							<span class="letter-icon">U</span>
						</span>
						</a>

						<div class="media-body">
							<h6 class="mb-0">ZZP'er overzicht</h6>
							<div class="letter-icon-title font-weight-semibold">{count($zzpers)} ZZP'ers in
								tabel
							</div>
						</div>

						<div class="justify-content-between">
							<a href="crm/zzp/dossier/bedrijfsgegevens" class="btn bg-blue text-blue border-blue">
								<i class="icon-pencil7 icon mr-1"></i>
								<span>ZZP'er invoeren</span>
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
							<th>Bedrijf</th>
							<th>Naam</th>
							<th>Uitzender</th>
							<th class="text-center">Actions</th>
						</tr>
					</thead>
                    {if isset($zzpers) && is_array($zzpers) && count($zzpers) > 0}
						<tbody>
                            {foreach $zzpers as $u}
								<tr style="{if $u.complete == 0}background-color: #EEE;{/if}{if $u.archief == 1}color: #F44336;{/if}">
									<td>{$u.complete}</td>
									<td>{$u.zzp_id}</td>
									<td>
                                        {if $u.complete == 0}
											<span class="badge bg-success  mr-1">NIEUW</span>
                                        {/if}
										<a style="{if $u.archief == 1}color: #F44336;{/if}" href="crm/zzp/dossier/overzicht/{$u.zzp_id}">{$u.bedrijfsnaam}</a>
									</td>
									<td>
										<a style="{if $u.archief == 1}color: #F44336;{/if}" href="crm/uitzenders/dossier/overzicht/{$u.zzp_id}">{$u.naam}</a>
									</td>
									<td>
										<a style="{if $u.archief == 1}color: #F44336;{/if}" href="crm/uitzenders/dossier/overzicht/{$u.uitzender_id}">{$u.uitzender}</a>
									</td>
                                    {if $user_type == 'werkgever' || $user_type == 'uitzender'}
										<td style="white-space: nowrap">
                                            {if $ENV == 'development'}
												<a href="{$base_url}/crm/zzp?del={$u.zzp_id}">
													<i class="icon-trash font-size-sm"></i></a>
                                            {/if}

                                            {if isset($u.user)}
												<a href="{$base_url}/dashboard/zzp?loginals=zzp&id={$u.zzp_id}" class="ml-2">
													<i class="icon-enter mr-1"></i> Login als
												</a>
                                            {else}
												<a href="{$base_url}/instellingen/werkgever/users/add?id={$u.zzp_id}&user_type=zzp" class="ml-2 text-danger">
													<i class="icon-warning2 mr-1"></i>user aanmaken
												</a>
                                            {/if}

										</td>
                                    {/if}
								</tr>
                            {/foreach}
						</tbody>
                    {/if}
				</table>


			</div>
			<!-- /basic card -->
		</div>
		<!-- /content area -->
	</div>
	<!-- /main content -->

	<div class="sidebar sidebar-light sidebar-main d-none d-xxl-block sidebar-sections sidebar-expand-lg align-self-start">

		<!-- Sidebar content -->
		<div class="sidebar-content">

			<!-- Latest updates -->
			<div class="card">
				<div class="card-header bg-transparent header-elements-inline">
					<span class="text-uppercase font-size-sm font-weight-semibold">Laatst bezocht</span>
				</div>

				<div class="card-body">
					<ul class="media-list">
						<li class="media">
							<div class="media-body">
                                {foreach $last_visits as $visit}
									<a href="crm/zzp/dossier/overzicht/{$visit.zzp_id}">
										<div class="float-left" style="width: 45px;">{$visit.zzp_id}</div>
										<div class="mb-1">{$visit.bedrijfsnaam|truncate:28:'...':true}</div>
									</a>
                                {/foreach}
							</div>
						</li>

					</ul>
				</div>
			</div>
			<!-- /latest updates -->

		</div>
		<!-- /sidebar content -->

	</div>

{/block}