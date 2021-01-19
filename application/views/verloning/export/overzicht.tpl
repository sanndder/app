{extends file='../../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-download{/block}
{block "header-title"}Export{/block}

{block "content"}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-xxl-6 col-xl-6 col-lg-10">


					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Loonstroken uploaden
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<div class="card">
						<div class="card-header header-elements-inline">
							<h5 class="card-title text-primary">Export</h5>
						</div>

						<div class="card-body">

							<div class="row">
								<div class="col-lg-10">

									<form method="post" action="">
										<button type="submit" name="go" class="btn btn-primary">Export</button>
										<button type="submit" name="update" class="btn btn-primary">Werknemers Update</button>
									</form>

								</div>
						</div><!-- /card body -->
					</div><!-- /basic card -->

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Loonstroken wachtrij
					-------------------------------------------------------------------------------------------------------------------------------------------------->



				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}