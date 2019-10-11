{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}

{block "content"}

	{include file='crm/inleners/dossier/_sidebar.tpl' active='notities'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- msg -->
			{if isset($msg)}
				<div class="row">
					<div class="col-xl-11">
						{$msg}
					</div><!-- /col -->
				</div>
				<!-- /row -->
			{/if}

			<div class="row">
				<div class="col-md-11">

					<!-- Basic card -->
					<div class="card mb-2">

						<div class="bg-light rounded-top">
							<div class="navbar navbar-light bg-light navbar-expand-sm py-header rounded-top">

								<div class="navbar-collapse text-center text-lg-left flex-wrap collapse show" id="inbox-toolbar-toggle-read">
									<div class="mt-3 mt-lg-0 mr-lg-3">
										<div class="btn-group">
											<button type="button" class="btn btn-light btn-sm" data-id="0" onclick="modalContact(this, 'inlener', {$inlener->inlener_id})">
												<i class="icon-plus-circle2"></i>
												<span class="d-none d-inline-block ml-2">Notitie toevoegen</span>
											</button>
										</div>
									</div>

									<div class="navbar-text ml-lg-auto"></div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xl-11">

					<div class="card border-left-3 border-left-blue-400 rounded-left-0 mb-2">
						<div class="card-body">
							<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
								<div>
									<p class="mb-0">Inlener geeft aan dat hij NIET gebeld wil worden, alleen emailen.	</p>
								</div>
							</div>
						</div>

						<div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">
							<div>
								<span>Door </span><span class="font-weight-semibold">Sander Meijering </span><span>op</span><span class="font-weight-semibold"> 28 Augustus</span>
							</div>

							<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">

								<ul class="list-inline mb-0 mt-2 mt-sm-0">
									<li class="list-inline-item dropdown">
										<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

										<div class="dropdown-menu dropdown-menu-right">
											<a href="#" class="dropdown-item"><i class="icon-pencil7"></i> Wijzigen</a>
											<a href="#" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
										</div>
									</li>
								</ul>

							</div>


						</div>
					</div>


				</div><!-- /col -->
			</div><!-- /row -->

			<div class="row">
				<div class="col-xl-11">

					<div class="card border-left-3 border-left-blue-400 rounded-left-0">
						<div class="card-body">
							<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
								<div>
									<p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at lorem magna. Nam vulputate semper ligula, in hendrerit urna. Morbi ultrices tellus sed accumsan congue. Nam maximus mattis nisl et luctus. Phasellus pharetra justo in bibendum semper. Proin vel lacus accumsan, mattis velit nec, aliquet velit. Aliquam ut efficitur justo. Curabitur quis leo dui.</p>
								</div>
							</div>
						</div>

						<div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">
							<div>
								<span>Door </span><span class="font-weight-semibold">Sander Meijering </span><span>op</span><span class="font-weight-semibold"> 28 Augustus</span>
							</div>

							<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">

								<ul class="list-inline mb-0 mt-2 mt-sm-0">
									<li class="list-inline-item dropdown">
										<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

										<div class="dropdown-menu dropdown-menu-right">
											<a href="#" class="dropdown-item"><i class="icon-pencil7"></i> Wijzigen</a>
											<a href="#" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
										</div>
									</li>
								</ul>

							</div>


						</div>
					</div>


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}