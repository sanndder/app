{extends file='../layout.tpl'}
{block "title"}Uitzender{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Uitzender - {/block}

{block "content"}

{literal}
	<script src="recources/plugins/pdfobject.min.js"></script>
{/literal}

    {include file='_modals/signdocument.tpl'}

	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">


			<div class="row">
				<div class="col-xl-12">

					<!-- Default tabs -->
					<div class="card">

						<!-- header -->
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Ondertekenen</span>
							<div class="header-elements">

							</div>
						</div>

						<div class="card-body tab-content">

							<button onclick="modalSignDocument()" class="btn btn-sm btn-primary">
								<em class="fa fa-check"></em> Klikken
							</button>

						</div>

					</div>
					<!-- /default tabs -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}