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
    {include file='instellingen/werkgever/_sidebar.tpl' active='documenten'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<!------------------------------------------------------------- Instellingen --------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Instellingen document</h5>
					<div class="header-elements">
						<div class="list-icons">
							<a class="list-icons-item" data-action="collapse"></a>
						</div>
					</div>
				</div>

				<!----------- card body ------->
				<div class="card-body">


				</div><!-- /card body -->
			</div><!-- /basic card -->
			<!------------------------------------------------------------- /Instellingen --------------------------------------------------------------->


			<!------------------------------------------------------------- Body ------------------------------------------------------------------------>
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Document inhoud</h5>
				</div>
				<div class="card-body">

					<form action="" method="post">

						<div class="row">
							<div class="col-lg-6">

								<input type="text" name="titel" value="{$titel}" class="form-control mb-3" required placeholder="Titel van document">

								<textarea name="editor" id="editor">{$body}</textarea>

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

						<button type="submit" name="set" value="save_document" class="btn btn-success mt-2">
							<i class="icon-check mr-1"></i>Document opslaan
						</button>

					</form>
				</div><!-- /card body -->
			</div><!-- /basic card -->
			<!------------------------------------------------------------- /Body ----------------------------------------------------------------------->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}