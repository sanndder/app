{extends file='../../layout.tpl'}
{block "title"}Snelstart{/block}
{block "header-icon"}icon-books{/block}
{block "header-title"}Overzicht - Snelstart{/block}
{assign "datamask" "true"}

{block "content"}

	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

            {if isset($msg)}{$msg}{/if}

			<!---------------------------------------------------------------------------------------------------------
			|| Zijmenu
			---------------------------------------------------------------------------------------------------------->
			<div class="row">

				<div class="col-md-12">

					<div class="card">
						<div class="card-body">


						</div>
					</div>

				</div><!-- /col -->

			</div><!-- /row -->
		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}