{extends file='../../layout.tpl'}
{block "title"}Uitzenders{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzenders{/block}
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
									Actieve uitzenders
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
								<a href="crm/uitzenders" class="btn btn-light" style="width: 100%">
									<i class="icon-cross font-size-base mr-2"></i>
									Wissen
								</a>
							</div>
						</div><!-- /row -->
					</form>
				</div>


			</div><!-- /main navigation -->

			<!---------------------------------------------------------------------------------------------------------
			||Weergave instellingen
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

                {*
				<div class="bg-light rounded-top">
					<div class="navbar navbar-light bg-light navbar-expand-lg py-lg-2 rounded-top">
						<div class="text-center d-lg-none w-100">
							<button type="button" class="navbar-toggler w-100 h-100" data-toggle="collapse" data-target="#inbox-toolbar-toggle-read">
								<i class="icon-circle-down2"></i>
							</button>
						</div>

						<div class="navbar-collapse text-center text-lg-left flex-wrap collapse" id="inbox-toolbar-toggle-read">
							<div class="mt-3 mt-lg-0 mr-lg-3">
								<div class="btn-group">
									<a type="button" class="btn btn-light" href="crm/uitzenders/dossier/bedrijfsgegevens">
										<i class="icon-plus-circle2"></i>
										<span class="d-none d-lg-inline-block ml-2">Nieuwe uitzender</span>
									</a>
								</div>
							</div>

							<div class="navbar-text ml-lg-auto"></div>

						</div>
					</div>
				</div>*}

				<!-- header -->
				<!-- card  body-->
				<div class="card-body">
					<div class="media flex-column flex-md-row">
						<a href="javascript:void(0)" class="d-none d-md-block mr-md-3 mb-3 mb-md-0">
						<span class="btn bg-teal-400 btn-icon btn-lg rounded-round">
							<span class="letter-icon">U</span>
						</span>
						</a>

						<div class="media-body">
							<h6 class="mb-0">Uitzenderoverzicht</h6>
							<div class="letter-icon-title font-weight-semibold">{count($uitzenders)} uitzenders in tabel</div>
						</div>

						<div class="justify-content-between">
							<button data-target="#modal_email_uitzender" data-toggle="modal" class="btn bg-teal-400">
								<i class="icon-envelop2 icon mr-1"></i>
								<span>Aanmeldlink emailen</span>
							</button>
							<a href="crm/uitzenders/dossier/bedrijfsgegevens" class="btn btn-outline bg-teal-400 text-teal-400 border-teal-400">
								<i class="icon-pencil7 icon mr-1"></i>
								<span>Uitzender invoeren</span>
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
                    {if isset($uitzenders) && is_array($uitzenders) && count($uitzenders) > 0}
						<tbody>
                            {foreach $uitzenders as $u}
								<tr style="{if $u.complete == 0}background-color: #EEE;{/if}{if $u.archief == 1}color: #F44336;{/if}">
									<td>{$u.complete}</td>
									<td>{$u.uitzender_id}</td>
									<td>
                                        {if $u.complete == 0}
											<span class="badge bg-success  mr-1">NIEUW</span>
                                        {/if}
										<a style="{if $u.archief == 1}color: #F44336;{/if}" href="crm/uitzenders/dossier/overzicht/{$u.uitzender_id}">{$u.bedrijfsnaam}</a>
									</td>
									<td></td>
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
									<a href="crm/uitzenders/dossier/overzicht/{$visit.uitzender_id}">
										<div class="float-left" style="width: 45px;">{$visit.uitzender_id}</div>
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


	<!--------------------------------------------------------------------------- Algemene voorwaarden ------------------------------------------------->
	<div id="modal_email_uitzender" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header pl-4 pr-4">
					<h5 class="modal-title">Aanmeldlink sturen naar uitzender<span class="var-action"></span></h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body pl-4 pr-4">

					<form>

						<!-- voorletters -->
						<div class="form-group row mb-1">
							<label class="col-form-label col-sm-3">Emailadres:</label>
							<div class="col-sm-9">
								<input type="text" name="email" class="form-control aanmeld-email">
								<span class="form-text text-danger"></span>
							</div>
						</div>

					</form>

				</div>
				<div class="modal-footer pl-4 pr-4">
					<button onclick="sendAanmeldEmail()" type="button" class="btn btn-primary">
						<i class="icon-envelop mr-1"></i> Verzenden
					</button>
					<button type="button" class="btn btn-outline-primary" data-dismiss="modal"><i class="icon-cross mt-1"></i> Annuleren</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		
		function sendAanmeldEmail()
		{
			alert( $('.aanmeld-email').val() );
        }
		
	</script>
{/block}