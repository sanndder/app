{extends file='../../layout.tpl'}
{block "title"}Inleners{/block}
{block "header-icon"}icon-folder-search{/block}
{block "header-title"}Kredietaanvraag{/block}
{assign "datatable" "true"}

{block "content"}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-md-12">

					<!-- Basic card -->
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Aanvraag kredietlimiet nieuwe klant</h5>
						</div>
						<span class="card-body pb-4">

							<p class="mb-4">U hoef alleen het KvK nummer van de klant in te voeren. Wij halen automatisch de bijbehorende gegevens op.</p>

							<div class="row">
								<div class="alert alert-warning alert-styled-left alert-dismissible col-xxl-4 col-lg-6" style="display: none">
									<span class="font-weight-semibold"></span>
								</div>
							</div>

							76504069

							<form method="post" action="">

								<div class="input-group row mt-1">
									<label class="col-xxl-1 col-lg-2 pt-1 font-weight-bold">KvK nummer:</label>
									<input class="form-control col-xxl-3 col-lg-4 input-kvk-credit-check" type="text" name="kvk" placeholder="Vul een geldig KvK nummer in" />
								</div>

								<fieldset class="mt-4">
									<legend class="text-uppercase font-size-sm font-weight-bold">
										<i class="icon-search4 mr-1"></i> Gevonden bedrijfsinformatie
									</legend>
									<div class="info">
										<span class="status-wachten font-italic"> Wachten op invoer KvK nummer.......</span>
										<span class="status-zoeken font-italic" style="display: none"> <i class="icon-spinner spinner mr-1"></i>Informatie ophalen</span>
									</div>

								</fieldset>

							</form>



						</div>
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div><!-- /main content -->

{/block}