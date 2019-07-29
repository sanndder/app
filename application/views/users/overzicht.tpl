{extends file='../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Instellingen {$usertype}{/block}

{block "content"}

	{if $usertype == 'werkgever'}{include file='instellingen/werkgever/_sidebar.tpl' active='users'}{/if}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-xl-12">

					<!-- Basic card -->
					<div class="card">
						<div class="card-body">



						</div><!-- /card body -->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}