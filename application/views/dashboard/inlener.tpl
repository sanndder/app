{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-home2{/block}
{block "header-title"}Dashboard{/block}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">



			<div class="row">
				<!--------------------------------------------------------------------------- left ------------------------------------------------->
				<div class="col-md-3">

					<!----------------- Documenten --------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Documenten Abering</span>
							<div class="header-elements">
								<div class="list-icons">
									<a class="list-icons-item" data-action="collapse"></a>
								</div>
							</div>
						</div>

						<div class="card-body">

						</div>
					</div>

					<!----------------- Log  --------------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Laatste gebeurtenissen</span>
							<div class="header-elements">
								<div class="list-icons">
									<a class="list-icons-item" data-action="collapse"></a>
								</div>
							</div>
						</div>
						<div class="card-body">

						</div>
					</div>


				</div>
			    <!--------------------------------------------------------------------------- /left ------------------------------------------------->


			    <!--------------------------------------------------------------------------- right ------------------------------------------------->
				<div class="col-md-9">

					<!-- Basic card -->
					<div class="card">
						<div class="card-body">

							<fieldset class="mb-0 mt-0">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Omzet en marge</legend>
							</fieldset>

							<div class="row">
								<div class="col-md-12">
									<i>Geen data beschikbaar</i>
								</div>
							</div>
						</div><!-- /card body -->
					</div><!-- /basic card -->


					<!-- Basic card -->
					<div class="card">
						<div class="card-body">

							<fieldset class="mb-0 mt-0">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Gewerkte uren</legend>
							</fieldset>

							<div class="row">
								<div class="col-md-12">
									<i>Geen data beschikbaar</i>
								</div>
							</div>
						</div><!-- /card body -->
					</div><!-- /basic card -->

				</div>
				<!-- /col -->
			</div><!-- /row -->
			<!--------------------------------------------------------------------------- /right ------------------------------------------------->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}