{extends file='../../../layout.tpl'}
{block "title"}Werknemer{/block}
{block "header-icon"}icon-user{/block}
{block "header-title"}Werknemer - {$werknemer->naam}{/block}

{block "content"}

    {include file='crm/werknemers/dossier/_sidebar.tpl' active='ziekmeldingen'}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- msg -->
            {if isset($msg)}
				<div class="row">
					<div class="col-xl-10">
                        {$msg}
					</div><!-- /col -->
				</div>
				<!-- /row -->
            {/if}

			<div class="row">
				<div class="col-xl-10">

					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<form method="post" action="">

								<fieldset>
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Vul de gegevens van het ziekteverzuim in</legend>
								</fieldset>

								<div class="form-group row mt-1">
									<label class="col-lg-3 col-md-4 pt-2 font-weight-bold">Datum melding door werknemer *</label>
									<div class="col-md-4">
										<input required name="datum_melding" value="{if isset($smarty.post.datum_melding)}{$smarty.post.datum_melding|date_format: '%d-%m-%Y'}{else}{$vandaag}{/if}" type="text" class="form-control pickadate-sick" style="width: 100px"/>
									</div>
								</div>

								<div class="form-group row mt-3">
									<label class="col-lg-3 col-md-4 pt-2 font-weight-bold">Start ziekteverzuim *</label>
									<div class="col-md-4">
										<input required name="datum_start_ziek" value="{if isset($smarty.post.datum_start_ziek)}{$smarty.post.datum_start_ziek|date_format: '%d-%m-%Y'}{/if}" type="text" class="form-control pickadate-sick" style="width: 100px"/>
									</div>
								</div>

								<div class="form-group row mt-3">
									<label class="col-lg-3 col-md-4 pt-2 font-weight-bold">Is er sprake van een ongeval tijdens werktijd? *</label>
									<div class="col-md-4">
										<select class="form-control" name="ongeval" required style="width: 100px;">
											<option></option>
											<option value="0" {if isset($smarty.post.ongeval) && $smarty.post.ongeval == 0} selected{/if}>Nee</option>
											<option value="1" {if isset($smarty.post.ongeval) && $smarty.post.ongeval == 1} selected{/if}>Ja</option>
										</select>
									</div>
								</div>

								<div class="form-group row mt-3">
									<label class="col-lg-3 col-md-4 pt-2">Aantal uren gewerkt op de eerste verzuimdag</label>
									<div class="col-md-4">
										<input name="uren_eerste_verzuimdag" value="{if isset($smarty.post.uren_eerste_verzuimdag)}{$smarty.post.uren_eerste_verzuimdag}{else}0{/if}" type="text" class="form-control text-right" style="width: 40px"/>
									</div>
								</div>

								<div class="form-group row mt-3">
									<label class="col-lg-3 col-md-4 pt-2">Opmerking</label>
									<div class="col-md-4">
										<textarea class="form-control" name="opmerking">{if isset($smarty.post.opmerking)}{$smarty.post.opmerking}{/if}</textarea>
									</div>
								</div>

								<div class="mt-4">
									<button type="submit" name="opslaan" class="btn-success btn btn-sm">
										<i class="icon-check2 mr-1"></i> Ziekmelding toevoegen
									</button>
									<a class="btn btn-outline-danger btn-sm" href="crm/werknemers/dossier/ziekmeldingen/{$werknemer->werknemer_id}">
										<i class="icon-cross mr-1"></i> Annuleren
									</a>
								</div>

							</form>

						</div><!-- /card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}