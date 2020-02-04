{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}

{block "content"}

	{include file='crm/inleners/dossier/_sidebar.tpl' active='facturen'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

{*
			<div class="row">
				<div class="col-xl-12">

					<!-- Default tabs -->
					<div class="card">

						<!-- header -->
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Facturen</span>
							<div class="header-elements">

							</div>
						</div>

						<div class="bg-light">
							<ul class="nav nav-tabs nav-tabs-bottom mb-0">
								<li class="nav-item">
									<a href="#card-toolbar-tab1" class="nav-link active show" data-toggle="tab">
										2019
									</a>
								</li>
								<li class="nav-item">
									<a href="#card-toolbar-tab2" class="nav-link" data-toggle="tab">
										2018
									</a>
								</li>
								<li class="nav-item">
									<a href="#card-toolbar-tab2" class="nav-link" data-toggle="tab">
										2017
									</a>
								</li>
							</ul>
						</div>

						<div class="card-body tab-content">
							<div class="tab-pane fade show active" id="card-toolbar-tab1">
								This is the first card tab content
							</div>

							<div class="tab-pane fade" id="card-toolbar-tab2">
								This is the second card tab content
							</div>

							<div class="tab-pane fade" id="card-tab3">
								This is the third card tab content
							</div>

							<div class="tab-pane fade" id="card-tab4">
								This is the fourth card tab content
							</div>
						</div>
					</div>
					<!-- /default tabs -->

				</div><!-- /col -->
			</div><!-- /row -->
*}

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}