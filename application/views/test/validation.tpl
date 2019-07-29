{extends file='../layout.tpl'}
{block "title"}Testing{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Test validatie class{/block}
{assign "ckeditor" "true"}

{block "content"}
	<script>
		{literal}


		{/literal}
	</script>

	{include file='test/_sidebar.tpl' active='av'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-- Basic card -->
			<div class="card">
				<div class="card-body">

					<div class="row">
						<div class="col-lg-12">


							<table class="table table-striped">
								<thead>
									<tr>
										<th>Test</th>
										<th>Input</th>
										<th>Ouput</th>
										<th>Gewenste output</th>
										<th>Succes</th>
									</tr>
								</thead>
							</table>


						</div><!-- /col -->
					</div><!-- /row -->
				</div><!-- /card body -->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}