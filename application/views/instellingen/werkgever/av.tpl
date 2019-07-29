{extends file='../../layout.tpl'}
{block "title"}Instellingen{/block}
{block "header-icon"}icon-cog{/block}
{block "header-title"}Instellingen werkgever{/block}
{assign "ckeditor" "true"}

{block "content"}
	<script>
		{literal}


		{/literal}
	</script>

	{include file='instellingen/werkgever/_sidebar.tpl' active='av'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-- Basic card -->
			<div class="card">
				<div class="card-body">

					<div class="row">
						<div class="col-lg-6">

							<textarea name="editor" id="editor"></textarea>

						</div><!-- /col -->
						<div class="col-lg-2">

							<h6 class="mb-0 font-weight-semibold">
								<em class="icon-pencil6 mr-2"></em>Variabelen invoegen</h6>
							<div class="dropdown-divider mb-2"></div>
							<span class="text-muted ml-1">Werkgever</span>
							<ul data-var-categorie="werkgever" class="list list-unstyled mb-0 list-hover ckeditor-vars mt-1 ml-2">
								<li data-var="bedrijfsnaam">Bedrijfsnaam</li>
								<li data-var="straatnaam">Straatnaam</li>
								<li data-var="huisnummer">Huisnummer</li>
								<li data-var="postcode">Postcode</li>
								<li data-var="kvknr">KvK nr</li>
								<li data-var="btwnr">BTW nummer</li>
								<li data-var="handtekening">Handtekening</li>
							</ul>


						</div><!-- /col -->
					</div><!-- /row -->
				</div><!-- /card body -->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}