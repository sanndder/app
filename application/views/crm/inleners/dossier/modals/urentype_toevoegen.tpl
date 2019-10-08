<!-- load custom javascript -->
<script src="recources/js/modals/urentype_toevoegen.js" type="text/javascript"></script>

<!-- Contactpersonen form -->
<div id="modal_add_urentype" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-md">


		<div class="modal-content">
			<form method="post" action="">

				<input type="hidden" name="set" value="add_urentype_to_inlener" />

				<div class="modal-header bg-info">
					<h5 class="modal-title">Urentype toevoegen aan inlener </h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body">

					<!-- urentype -->
					<div class="row">

						<label class="col-md-4 pt-2 font-weight-bold">Urentype</label><!-- /col -->
						<div class="col-md-8 form-group">

							<select name="urentype_id" class="form-control select-search" required>
                                {if isset($urentypes) && is_array($urentypes)}
									<option value="">Selecteer een urentype</option>
                                    {foreach $urentypes as $categorie}
										<optgroup label="{$categorie@key}">
                                            {foreach $categorie as $u}
												<option value="{$u.urentype_id}">{$u.naam}</option>
                                            {/foreach}
										</optgroup>
                                    {/foreach}
                                {/if}
							</select>

						</div><!-- /col -->
					</div><!-- /row -->

					<!-- doorbelasten uitzender -->
					<div class="row">
						<label class="col-md-4 pt-0 font-weight-bold">Doorbelasten naar uitzender</label><!-- /col -->
						<div class="col-md-8 form-group">

							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<span class="checked">
										<input required value="1" type="radio" class="form-input-styled" name="doorbelasten_uitzender">
									</span>
									Ja
								</label>
							</div>
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<span class="">
										<input required value="0" type="radio" class="form-input-styled" name="doorbelasten_uitzender">
									</span>
									Nee
								</label>
							</div>

						</div><!-- /col -->
					</div><!-- /row -->

					<!-- label -->
					<div class="row">

						<label class="col-md-4 pt-2">Afwijkende naam (optioneel)</label><!-- /col -->
						<div class="col-md-8 form-group">

							<input name="label" type="text" class="form-control">

						</div><!-- /col -->
					</div><!-- /row -->

					<!-- standaard verkooptarief -->
					<div class="row">
						<label class="col-md-4 pt-2">Standaard verkooptarief (optioneel)</label><!-- /col -->
						<div class="col-md-8 form-group">

							<input style="width: 75px;" name="standaard_verkooptarief" type="text" class="form-control text-right">

						</div><!-- /col -->
					</div><!-- /row -->
				</div>

				<div class="modal-footer">
					<button type="button" onclick=" return validateUrentypeInput( this )" class="btn bg-info">
						<i class="icon-checkmark2 mr-1"></i>Toevoegen
					</button>
					<button type="button" class="btn btn-link" data-dismiss="modal">Annuleren</button>
				</div>

			</form>

		</div>
	</div>
</div>
<!-- /horizontal form modal -->