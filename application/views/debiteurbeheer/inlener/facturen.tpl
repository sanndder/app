{extends file='../../layout.tpl'}
{block "title"}Overzicht facturen{/block}
{block "header-icon"}mi-euro-symbol{/block}
{block "header-title"}Debiteurbeheer - {$inlener.bedrijfsnaam}{/block}
{assign "datamask" "true"}

{block "content"}
	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">

				<!----------------------------------------------- links ------------------------------------->
				<div class="col-md-4 col-xl-3">

                    {include file='debiteurbeheer/inlener/_sidebar.tpl' active='facturen'}

				</div><!---- /col -->
			</div><!---- /row -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}