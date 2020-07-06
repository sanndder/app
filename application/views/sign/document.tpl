{extends file='../layout.tpl'}
{block "title"}Welkom{/block}
{block "header-icon"}{/block}
{block "header-title"}{/block}
{assign "hide_menu" "true"}

{block "content"}

    {include file='_modals/signdocument.tpl'}

	<!-- Main content -->
	<div class="content-wrapper" style="margin-top: -40px;">
		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-xl-6 offset-xl-3">

					<!--------------------------------------------------------------------------- Welkom ------------------------------------------------->
					<div class="card">

						<div class="card-header header-elements-inline">
							<h4 class="card-title">Document ondertekenen</h4>
						</div>

						<div class="card-body">

							<div>
								U kunt hier digitaal uw document onder tekenen. Klik op de knop om uw document te bekijken en te tekenen.
							</div>

							{if $document.signed == 0}
							<button type="button" onclick="modalSignDocumentExternal( {$document.document_id} )" class="btn btn-sm btn-success mt-3 mr-3 btn-sign" style="width: 180px">
								<i class="icon-eye mr-2"></i>document openen
							</button>
                            {/if}

							<div class="mt-2 font-size-lg signed" style="{if $document.signed == 0}display: none{/if}">
								<i class="icon-checkmark-circle mr-1 text-green"></i> Uw document is getekend!
							</div>

						</div>
					</div>

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}