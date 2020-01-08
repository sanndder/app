{extends file='../../layout.tpl'}
{block "title"}Inleners{/block}
{block "header-icon"}icon-user-tie{/block}
{block "header-title"}Inleners{/block}
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
							<input name="q1" value="{if isset($smarty.get.q1)}{$smarty.get.q1}{/if}" type="search" class="form-control" placeholder="ID of bedrijfsnaam">
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
									Actieve inleners
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
								<a href="crm/inleners" class="btn btn-light" style="width: 100%">
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

				<!-- card  body-->
				<div class="card-body">
					<div class="media flex-column flex-md-row">
						<a href="#" class="d-none d-md-block mr-md-3 mb-3 mb-md-0">
						<span class="btn bg-teal-400 btn-icon btn-lg rounded-round">
							<span class="letter-icon">I</span>
						</span>
						</a>

						<div class="media-body">
							<h6 class="mb-0">Inleneroverzicht</h6>
							<div class="letter-icon-title font-weight-semibold">{count($inleners)} inleners in tabel</div>
						</div>

						<div class="justify-content-between">
							<a href="crm/inleners/kredietlimiet" class="btn bg-teal-400">
								<i class="icon-plus-circle2 icon mr-1"></i>
								<span>Kredietlimiet aanvragen</span>
							</a>
							{if $user_type == 'werkgever'}
							<a href="crm/inleners/dossier/bedrijfsgegevens" class="btn btn-outline bg-teal-400 text-teal-400 border-teal-400">
								<i class="icon-pencil7 icon mr-1"></i>
								<span>Inlener invoeren</span>
							</a>
							{/if}
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
							<th>Uitzender</th>
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
                                        {* moet er een badge voor? *}
										{if $i.complete == 0}
											{if isset($i.krediet)}
                                                {* krediet badge *}
												<span class="badge bg-primary  mr-1">KREDIET</span>
											{else}
                                                {* nieuw badge *}
		                                        <span class="badge bg-success  mr-1">NIEUW</span>
                                            {/if}
                                        {/if}

                                        {* welke link moet er verschijnenen *}
                                        {if !isset($i.krediet)}
                                            {* standaard link *}
	                                        <a style="{if $i.archief == 1}color: #F44336;{/if}" href="crm/inleners/dossier/overzicht/{$i.inlener_id}">{$i.bedrijfsnaam}</a>
                                        {else}
                                            {* links naar kredietoverzicht *}
                                            {if $i.inlener_id === NULL }
                                                {* nog geen inlener *}
	                                            <a style="{if $i.archief == 1}color: #F44336;{/if}" href="crm/inleners/dossier/kredietoverzicht/k{$i.id}">{$i.bedrijfsnaam}</a>
                                            {else}
                                                {* al wel inlener *}
	                                            <a style="{if $i.archief == 1}color: #F44336;{/if}" href="crm/inleners/dossier/kredietoverzicht/{$i.inlener_id}">{$i.bedrijfsnaam}</a>
                                            {/if}
                                        {/if}

									</td>
									<td>
										<a href="crm/uitzenders/dossier/overzicht/{$i.uitzender_id}">{$i.uitzender}</a>
									</td>
									<td>
										{if $ENV == 'development'}
											<a href="{$base_url}/crm/inleners?del={$i.inlener_id}"><i class="icon-trash font-size-sm"></i></a>
                                        {/if}
									</td>
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
									<a href="crm/inleners/dossier/overzicht/{$visit.inlener_id}">
										<div class="float-left" style="width: 45px;">{$visit.inlener_id}</div>
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