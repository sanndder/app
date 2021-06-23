<!-- load custom javascript -->
<div id="modal_copy" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Kopieer inlener naar andere onderneming <span class="var-action"></span></h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<form method="post" action="">

				<div class="modal-body pt-4">

                    {foreach $ondernemingen as $o}
						<div class="row mb-3">
							<div class="col-md-12">
                                {if isset($o.bedrijfsnaam)}
									<i style="font-size: 21px" class="icon-checkmark-circle text-success mr-1"></i>
                                    {$o.name}
                                {else}
									<div class="form-check">

										<label class="form-check-label">
										<span class="">
											<input value="{$o.werkgever_id}" type="radio" class="form-input-styled" name="werkgever_id" required>
										</span>
                                            {$o.name}
										</label>

									</div>
                                {/if}
							</div>
						</div>
                    {/foreach}

				</div>

				<div class="modal-footer">
					<button type="submit" class="btn bg-primary">
						<i class="icon-copy4 mr-1"></i>Kopiëren
					</button>
					<button type="button" class="btn btn-link" data-dismiss="modal">Annuleren</button>
				</div>

			</form>

		</div>
	</div>
</div>
<!-- /horizontal form modal -->