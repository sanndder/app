<!-- load custom javascript -->
<script src="recources/js/modals/vergoeding_toevoegen.js" type="text/javascript"></script>

<!-- Contactpersonen form -->
<div id="modal_add_vergoeding" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-md">


		<div class="modal-content">
			<form method="post" action="">

				<input type="hidden" name="set" value="add_vergoeding_to_inlener" />

				<div class="modal-header bg-info">
					<h5 class="modal-title">Vergoeding toevoegen aan inlener </h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body">

					<!-- vergoeding -->
					<div class="row">

						<label class="col-md-4 pt-2 font-weight-bold">Vergoeding</label><!-- /col -->
						<div class="col-md-8 form-group">

							<select name="vergoeding_id" class="form-control select-search" required>
                                {if isset($vergoedingen) && is_array($vergoedingen)}
									<option value="">Selecteer een vergoeding</option>
                                    {foreach $vergoedingen as $v}
										<option value="{$v.vergoeding_id}">{$v.naam} - {if $v.belast == 1}belast{else}onbelast{/if}</option>
                                    {/foreach}
                                {/if}
							</select>

						</div><!-- /col -->
					</div><!-- /row -->

					<!-- doorbelasten uitzender -->
					<div class="row">
						<label class="col-md-4 pt-0 font-weight-bold">Doorbelasten naar</label><!-- /col -->
						<div class="col-md-8 form-group">
							<div class="form-check form-check">
								<label class="form-check-label">
									<span class="checked">
										<input required value="0" type="radio" class="form-input-styled" name="doorbelasten">
									</span>
									Keuze bij invoer
								</label>
							</div>

							<div class="form-check form-check">
								<label class="form-check-label">
									<span class="checked">
										<input required value="uitzender" type="radio" class="form-input-styled" name="doorbelasten">
									</span>
									Uitzender
								</label>
							</div>
							<div class="form-check form-check">
								<label class="form-check-label">
									<span class="">
										<input required value="inlener" type="radio" class="form-input-styled" name="doorbelasten">
									</span>
									Inlener
								</label>
							</div>

						</div><!-- /col -->
					</div><!-- /row -->

					<!-- doorbelasten uitzender -->
					<div class="row mt-1">
						<label class="col-md-4 pt-0 font-weight-bold">Type</label><!-- /col -->
						<div class="col-md-8 form-group">
							<div class="form-check form-check">
								<label class="form-check-label">
									<span class="checked">
										<input required value="vast" type="radio" class="form-input-styled" name="vergoeding_type">
									</span>
									Vast bedrag per uur
								</label>
							</div>

							<div class="form-check form-check">
								<label class="form-check-label">
									<span class="checked">
										<input required value="variabel" type="radio" class="form-input-styled" name="vergoeding_type">
									</span>
									Variabel (Vrij in te vullen bij invoer)
								</label>
							</div>

						</div><!-- /col -->
					</div><!-- /row -->


					<!-- bedrag per uur -->
					<div class="row row-bedrag" style="display: none">

						<label class="col-md-4 pt-2 font-weight-bold">Bedrag per uur</label><!-- /col -->
						<div class="col-md-8 form-group">

							<input style="width: 90px; text-align: right" name="bedrag_per_uur" type="text" class="form-control">

						</div><!-- /col -->
					</div><!-- /row -->

					<!-- doorbelasten uitzender -->
					<div class="row mt-1">
						<label class="col-md-4 pt-0 font-weight-bold">Uitkeren aan werknemer</label><!-- /col -->
						<div class="col-md-8 form-group">
							<div class="form-check form-check">
								<label class="form-check-label">
									<span class="checked">
										<input required value="1" type="radio" class="form-input-styled" name="uitkeren_werknemer">
									</span>
									Ja
								</label>
							</div>

							<div class="form-check form-check">
								<label class="form-check-label">
									<span class="checked">
										<input required value="0" type="radio" class="form-input-styled" name="uitkeren_werknemer">
									</span>
									Nee
								</label>
							</div>

						</div><!-- /col -->
					</div><!-- /row -->

				</div>

				<div class="modal-footer">
					<button type="button" onclick=" return validateVergoedingInput( this )" class="btn bg-info">
						<i class="icon-checkmark2 mr-1"></i>Toevoegen
					</button>
					<button type="button" class="btn btn-link" data-dismiss="modal">Annuleren</button>
				</div>

			</form>

		</div>
	</div>
</div>
<!-- /horizontal form modal -->