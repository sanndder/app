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

								<button type="submit" name="set" value="save_document" class="btn btn-success mt-1">
									<i class="icon-check mr-1"></i>Document opslaan
								</button>
								<a target="_blank" href="instellingen/werkgever/documentenpreview/{$settings.template_id}" class="btn btn-light mt-1">
									<i class="icon-file-pdf mr-1"></i>PDF Voorbeeld
								</a>

								<input type="text" name="titel" value="{$titel}" class="form-control mb-3 mt-3" required placeholder="Titel van document">

								<textarea name="editor" id="editor">{$body}</textarea>

							</div><!-- /col -->
							<div class="col-lg-6">

								<div class="row">
									<div class="col-lg-12">

										<h6 class="mb-0 font-weight-semibold">
											<em class="icon-pencil6 mr-2"></em>Variabelen invoegen</h6>
										<div class="dropdown-divider mb-2"></div>

									</div>
								</div>

								<div class="row">
									<div class="col-lg-3">
										<span class="text-muted ml-1">Werkgever</span>

										<ul data-var-categorie="werkgever" class="list list-unstyled mb-3 list-hover ckeditor-vars mt-1 ml-2">
											<li data-var="bedrijfsnaam">Bedrijfsnaam</li>
											<li data-var="straat">Straatnaam</li>
											<li data-var="huisnummer">Huisnummer</li>
											<li data-var="postcode">Postcode</li>
											<li data-var="plaats">Plaats</li>
											<li data-var="kvknr">KvK nr</li>
											<li data-var="btwnr">BTW nummer</li>
											<li data-var="handtekening">Handtekening</li>
										</ul>

										<span class="text-muted ml-1">Datum en tijd</span>
										<ul data-var-categorie="datum" class="list list-unstyled mb-3 list-hover ckeditor-vars mt-1 ml-2">
											<li data-var="datum">28-07-2019</li>
										</ul>

									</div><!-- /col -->
									<div class="col-lg-3">
										<span class="text-muted ml-1">Uitzender</span>

										<ul data-var-categorie="uitzender" class="list list-unstyled mb-0 list-hover ckeditor-vars mt-1 ml-2">
											<li data-var="bedrijfsnaam">Bedrijfsnaam</li>
											<li data-var="straat">Straatnaam</li>
											<li data-var="huisnummer">Huisnummer</li>
											<li data-var="postcode">Postcode</li>
											<li data-var="plaats">Plaats</li>
											<li data-var="kvknr">KvK nr</li>
											<li data-var="btwnr">BTW nummer</li>
											<li data-var="contactpersoon.aanhef">Contactpersoon aanhef</li>
											<li data-var="contactpersoon.naam">Contactpersoon naam volledig</li>
											<li data-var="handtekening">Handtekening</li>
										</ul>

									</div>
									<div class="col-lg-3">
										<span class="text-muted ml-1">Inlener</span>

										<ul data-var-categorie="inlener" class="list list-unstyled mb-0 list-hover ckeditor-vars mt-1 ml-2">
											<li data-var="bedrijfsnaam">Bedrijfsnaam</li>
											<li data-var="straat">Straatnaam</li>
											<li data-var="huisnummer">Huisnummer</li>
											<li data-var="postcode">Postcode</li>
											<li data-var="plaats">Plaats</li>
											<li data-var="kvknr">KvK nr</li>
											<li data-var="btwnr">BTW nummer</li>
											<li data-var="handtekening">Handtekening</li>

											<li data-var="factuurgegevens.termijn">Betaaltermijn</li>
											<li data-var="factuurgegevens.g_rekening_percentage">Percentage G-rekening</li>
										</ul>

									</div><!-- /col -->
								</div><!-- /row -->
								<div class="row">
									<div class="col-lg-3">

										<span class="text-muted ml-1">Werkgever</span>

										<ul data-var-categorie="werknemer" class="list list-unstyled mb-0 list-hover ckeditor-vars mt-1 ml-2">
											<li data-var="naam">Volledige naam</li>
											<li data-var="gb_datum">Geboorte datum</li>
											<li data-var="voorletters">Voorletters</li>
											<li data-var="tussenvoegsel">Tussenvoegsel</li>
											<li data-var="voornaam">Voornaam</li>
											<li data-var="achternaam">Achternaam</li>
											<li data-var="geslacht">Geslacht</li>
											<li data-var="straat">Straatnaam</li>
											<li data-var="huisnummer">Huisnummer</li>
											<li data-var="huisnummer_toevoeging">huisnummer toevoeging</li>
											<li data-var="postcode">Postcode</li>
											<li data-var="plaats">Plaats</li>
											<li data-var="iban">IBAN</li>
										</ul>

									</div>
								</div>

							</div><!-- /col -->
						</div><!-- /row -->

						<button type="submit" name="set" value="save_document" class="btn btn-success mt-2">
							<i class="icon-check mr-1"></i>Document opslaan
						</button>
						<a target="_blank" href="instellingen/werkgever/documentenpreview/{$settings.template_id}" class="btn btn-light mt-2">
							<i class="icon-file-pdf mr-1"></i>PDF Voorbeeld
						</a>

					</form>
				</div><!-- /card body -->
			</div><!-- /basic card -->
			<!------------------------------------------------------------- /Body ----------------------------------------------------------------------->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}