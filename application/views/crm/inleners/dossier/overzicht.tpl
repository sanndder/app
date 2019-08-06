{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}

{block "content"}

	{include file='crm/inleners/dossier/_sidebar.tpl' active='overzicht'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- Basic card -->
			<div class="card">

				<!-- header -->
				<div class="card-header bg-transparent header-elements-inline">
					<span class="text-uppercase font-size-sm font-weight-semibold">{$inlener->inlener_id} - {$inlener->bedrijfsnaam}</span>
					<div class="header-elements">

					</div>
				</div>

				<!-- card  body-->
				<div class="card-body">



				</div><!-- /card body-->


			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>	<!-- /main content -->


{/block}