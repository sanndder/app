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

			{if isset($msg)}{$msg}{/if}

            {include file='instellingen/werkgever/_topbar.tpl'}

			<!-- Basic card -->
			<div class="card">
				<div class="card-body">

					<form method="post" action="">

						<div class="row mb-2">
							<div class="col-lg-12">

								<button type="submit" name="set" value="save" class="btn btn-success">
									<i class="icon-check mr-1"></i>Wijzigingen opslaan
								</button>

								<button type="submit" name="set" value="activate" class="btn btn-primary">
									<i class="icon-file-check2 mr-1"></i>Publiceren
								</button>

							</div>
						</div>


						<div class="row">
							<div class="col-lg-6">

								<textarea name="editor" id="editor">{$av}</textarea>

							</div><!-- /col -->
							<div class="col-lg-2">

								{*
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
	*}


							</div><!-- /col -->
						</div><!-- /row -->

					</form>
				</div><!-- /card body -->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}