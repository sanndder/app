{extends file='../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user-check{/block}
{block "header-title"}Werknemer toegevoegd{/block}

{block "content"}

	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-md-10">

					<!-- Basic card -->
					<div class="card mb-2">

						<div class="card-body">
							<p class="mb-4">De werknemer is toegevoegd. Wij controleren deze zo spoedig mogelijk.</p>

							<a href="{$base_url}/crm/werknemers/dossier/gegevens" class="btn btn-sm btn-primary">
								<i class="icon-plus-circle2 mr-1"></i> Nog een werknemer invoeren
							</a>
							<a href="{$base_url}/crm/werknemers" class="btn btn-sm btn-outline-primary">
								<i class="icon-arrow-left7 mr-1"></i> Terug naar werknemers
							</a>

						</div>
					</div>
				</div>
			</div>

			<!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}