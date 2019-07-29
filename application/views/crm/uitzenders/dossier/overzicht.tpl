{extends file='../../../layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzender - {$uitzender->bedrijfsnaam}{/block}
{assign "datatable" "true"}

{block "content"}

	{include file='crm/uitzenders/dossier/_sidebar.tpl' active='overzicht'}


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
					<span class="text-uppercase font-size-sm font-weight-semibold">{$uitzender->uitzender_id} - {$uitzender->bedrijfsnaam}</span>
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