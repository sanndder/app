{extends file='../../layout.tpl'}
{block "title"}ZZP'er{/block}
{block "header-icon"}icon-user-check{/block}
{block "header-title"}ZZP'er toegevoegd{/block}

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
							<p class="mb-4">De ZZP'er is toegevoegd. Wij controleren deze zo spoedig mogelijk.</p>

							<a href="{$base_url}/crm/zzp/dossier/bedrijfsgegevens" class="btn btn-sm btn-primary">
								<i class="icon-plus-circle2 mr-1"></i> Nog een ZZP'er invoeren
							</a>
							<a href="{$base_url}/crm/zzp" class="btn btn-sm btn-outline-primary">
								<i class="icon-arrow-left7 mr-1"></i> Terug naar ZZP'ers
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