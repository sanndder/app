{extends file='../../../layout.tpl'}
{block "title"}ZZP'er{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}ZZP'er - {$zzp->naam}{/block}

{block "content"}

	{include file='crm/zzp/dossier/_sidebar.tpl' active='overzicht'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">


			<div class="row">
				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| Left side
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-9">

					<!----------------------------------------------- card: Bedrijfsbedrijfsgegevens ------------------------------------------------------------->
					<div class="card">
						<div class="card-body">

							<div class="media">

								<img style="max-width: 300px; max-height: 120px;" class="align-self-start mr-4 d-none d-lg-block" src="">

								<div class="media-body">
									<div class="row">
										<div class="col-md-12">
											<h5 class="mt-0">{$zzp->naam}</h5>
										</div><!-- /col -->
									</div><!-- /row -->

									<div class="row">
										<div class="col-md-6 col-xxl-3">

											<ul class="list-unstyled">
												<li>{$bedrijfsgegevens.straat} {$bedrijfsgegevens.huisnummer} {$bedrijfsgegevens.huisnummer_toevoeging}</li>
												<li>{$bedrijfsgegevens.postcode} {$bedrijfsgegevens.plaats}</li>
												<li class="mt-2"></li>
												<li>{$bedrijfsgegevens.telefoon}</li>
												<li>{$bedrijfsgegevens.email}</li>
											</ul>

										</div><!-- /col -->
										<div class="col-md-6 col-xxl-3">

										</div>
									</div><!-- /row -->

								</div>
							</div>


						</div><!-- /card body-->
					</div><!-- /card: Bedrijfsbedrijfsgegevens  -->
				</div><!-- / left side -->

				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| Right side
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-3">

					<!------------------------------------------------------- card: Accountmanager ------------------------------------------------------>
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Uitzender</span>
							<div class="header-elements">
								<div class="list-icons">
									<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Wijzig accountmanager">
										<i class="icon-pencil7"></i>
									</a>
								</div>
							</div>
						</div>

						<div class="card-body">

						</div>
					</div><!-- /card: Accountmanager  -->

					<!------------------------------------------------------- card: Accountmanager ------------------------------------------------------>
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Inleners</span>
							<div class="header-elements">
								<div class="list-icons">
									<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Wijzig accountmanager">
										<i class="icon-pencil7"></i>
									</a>
								</div>
							</div>
						</div>

						<div class="card-body">

						</div>
					</div><!-- /card: Accountmanager  -->

					<!------------------------------------------------------- card: Gebruikers --------------------------------------------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Users</span>
							<div class="header-elements">
								<div class="list-icons">
									<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Usermanagement">
										<i class="icon-pencil7"></i>
									</a>
								</div>
							</div>
						</div>

						<div class="card-body">

							<ul class="media-list">
								<li class="media mt-0">
									<div class="media-body">
										<a href="#" class="media-title font-weight-semibold">hsmeijering</a>
										<div class="font-size-sm text-muted">Sander Meijering</div>
									</div>
									<div class="ml-3 align-self-center">
										<a href="javascript:void()" data-popup="tooltip" data-placement="top" data-title="Login als">
											<i class="icon-enter"></i>
										</a>
									</div>
								</li>


							</ul>

						</div>
					</div><!-- /card: Accountmanager  -->

				</div><!-- / Right side -->
			</div><!-- /einde main row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}