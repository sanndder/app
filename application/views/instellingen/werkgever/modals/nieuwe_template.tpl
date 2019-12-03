<!-- load custom javascript -->
<script src="recources/js/modals/documenten/pdf_templates.js" type="text/javascript"></script>

<!-- Contactpersonen form -->
<div id="modal_new_template" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-md">


		<div class="modal-content">
			<form method="post" action="">

				<input type="hidden" name="set" value="add_pdf_template" />

				<div class="modal-header bg-info">
					<h5 class="modal-title">Nieuw document aanmaken</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body">

					<!-- Categorie -->
					<div class="row">

						<label class="col-md-4 pt-2 font-weight-bold">Categorie</label><!-- /col -->
						<div class="col-md-8 form-group">

							<select name="categorie_id" class="form-control" required>
                                {if isset($categorieen) && is_array($categorieen)}
									<option value=""></option>
                                    {foreach $categorieen as $c}
										<option value="{$c.categorie_id}">{$c.categorie|ucfirst}</option>
                                    {/foreach}
                                {/if}
							</select>
						</div><!-- /col -->
					</div><!-- /row -->

					<!-- Gebruiker -->
					<div class="row">

						<label class="col-md-4 pt-2 font-weight-bold">Gebruiker</label><!-- /col -->
						<div class="col-md-8 form-group">

							<select name="owner" class="form-control">
								<option value=""></option>
								<option value="uitzender">Uitzender</option>
								<option value="inlener">Inlener</option>
								<option value="werknemer">Werknemer</option>
							</select>
						</div><!-- /col -->
					</div><!-- /row -->

					<!-- Naam -->
					<div class="row">
						<label class="col-md-4 pt-2 font-weight-bold">Naam document</label><!-- /col -->
						<div class="col-md-8 form-group">
							<input name="template_name" type="text" class="form-control">
						</div><!-- /col -->
					</div><!-- /row -->

					<!-- Naam -->
					<div class="row">
						<label class="col-md-4 pt-2 font-weight-bold">Taal (code 2 tekens)</label><!-- /col -->
						<div class="col-md-8 form-group">
							<input name="lang" type="text" class="form-control" maxlength="2" minlength="2" style="width: 45px;" >
						</div><!-- /col -->
					</div><!-- /row -->

					<!-- Code -->
					<div class="row">

						<label class="col-md-4 pt-2">Nummer of code (optioneel)</label><!-- /col -->
						<div class="col-md-8 form-group">
							<input name="template_code" type="text" class="form-control">
						</div><!-- /col -->
					</div><!-- /row -->
				</div>

				<div class="modal-footer">
					<button type="button" onclick=" return validateTemplateInput( this )" class="btn bg-info">
						<i class="icon-checkmark2 mr-1"></i>Toevoegen
					</button>
					<button type="button" class="btn btn-link" data-dismiss="modal">Annuleren</button>
				</div>

			</form>

		</div>
	</div>
</div>
<!-- /horizontal form modal -->