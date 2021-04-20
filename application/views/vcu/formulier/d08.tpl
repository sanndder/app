{extends file='../../layout.tpl'}
{block "title"}D08 - EVALUATIEFORMULIER UITZENDKRACHT{/block}
{block "header-icon"}icon-file-empty{/block}
{block "header-title"}D08 - EVALUATIEFORMULIER UITZENDKRACHT{/block}

{block "content"}
	<script src="recources/plugins/pdfobject.min.js"></script>
	<script src="recources/js/modals/documenten/sign_document.js?2" type="text/javascript"></script>
	<script src="recources/plugins/signature-html5/signature.min.js" type="text/javascript"></script>
	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<div class="row">
				<!--------------------------------------------------------------------------- left ------------------------------------------------->
				<div class="col-md-8">
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-bold font-size-sm text-primary text-uppercase">D08 - EVALUATIEFORMULIER UITZENDKRACHT</span>
						</div>
						<div class="card-body">

							<form method="post" action="">

								<!------- Info -------->
								<table style="width: 100%">
									<tr>
										<td style="width: 200px;">Datum</td>
										<td>
											<input name="" value="" type="text" class="form-control"/>
										</td>
									</tr>
									<tr>
										<td>Aanvraagnummer (referentie)</td>
										<td>
											<input name="" value="" type="text" class="form-control"/>
										</td>
									</tr>
									<tr>
										<td>Naam uitzenkracht</td>
										<td>
											<input name="" value="{$werknemer.naam}" type="text" class="form-control" readonly/>
										</td>
									</tr>
									<tr>
										<td>Functie</td>
										<td>
											<input name="" value="" type="text" class="form-control"/>
										</td>
									</tr>
									<tr>
										<td>Naam inlener, afdeling en werklocatie</td>
										<td>
											<input name="" value="" type="text" class="form-control"/>
										</td>
									</tr>
								</table>


								<!------- Informatie en instructie -------->
								<table style="width: 100%" class="mt-5">
									<thead>
										<tr>
											<th>INFORMATIE EN INSTRUCTIE</th>
											<th class="text-center" style="width: 125px">Slecht</th>
											<th class="text-center" style="width: 125px">Matig</th>
											<th class="text-center" style="width: 125px">Normaal</th>
											<th class="text-center" style="width: 125px">Goed</th>
										</tr>
									</thead>
									<tr>
										<td class="pt-2">Informatie door UZB over uitzending</td>
										<td class="text-center pt-2">
											<input type="radio" name="i1"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i1"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i1"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i1"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Introductie bij aanvang uitzendperiode</td>
										<td class="text-center pt-2">
											<input type="radio" name="i2"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i2"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i2"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i2"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Detailinstructies m.b.t. veilig werken</td>
										<td class="text-center pt-2">
											<input type="radio" name="i3"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i3"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i3"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i3"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Communicatie met contactpersoon</td>
										<td class="text-center pt-2">
											<input type="radio" name="i4"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i4"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i4"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i4"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Deelname aan veiligheidsbijeenkomsten</td>
										<td class="text-center pt-2">
											<input type="radio" name="i5"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i5"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i5"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i5"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Op de hoogte van functie- en bedrijfsrisico’s</td>
										<td class="text-center pt-2">
											<input type="radio" name="i6"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i6"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i6"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i6"/>
										</td>
									</tr>
									<tr>
										<td class="pt-3">Toelichting</td>
										<td colspan="4" class="pt-3">
											<input name="" value="" type="text" class="form-control"/>
										</td>
									</tr>
									<thead>
										<tr>
											<th class="pt-4">PERSOONLIJKE BESCHERMINGSMIDDELEN</th>
											<th class="text-center pt-4" style="width: 125px">Slecht</th>
											<th class="text-center pt-4" style="width: 125px">Matig</th>
											<th class="text-center pt-4" style="width: 125px">Normaal</th>
											<th class="text-center pt-4" style="width: 125px">Goed</th>
										</tr>
									</thead>
									<tr>
										<td class="pt-2">Op de hoogte van de noodzakelijke PBM’s</td>
										<td class="text-center pt-2">
											<input type="radio" name="i21"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i21"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i21"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i21"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Verstrekking van PBM’s</td>
										<td class="text-center pt-2">
											<input type="radio" name="i22"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i22"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i22"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i22"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Instructie wanneer / hoe te gebruiken</td>
										<td class="text-center pt-2">
											<input type="radio" name="i23"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i23"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i23"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i23"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Toezicht op juist gebruik</td>
										<td class="text-center pt-2">
											<input type="radio" name="i24"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i24"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i24"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i24"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Mogelijkheid om te ruilen</td>
										<td class="text-center pt-2">
											<input type="radio" name="i25"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i25"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i25"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i25"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Onderhoud en beheer van PBM’s</td>
										<td class="text-center pt-2">
											<input type="radio" name="i26"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i26"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i26"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i26"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Kwaliteit van PBM’s</td>
										<td class="text-center pt-2">
											<input type="radio" name="i27"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i27"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i27"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i27"/>
										</td>
									</tr>
									<tr>
										<td class="pt-3">Toelichting</td>
										<td colspan="4" class="pt-3">
											<input name="" value="" type="text" class="form-control"/>
										</td>
									</tr>
									<thead>
										<tr>
											<th class="pt-4">WERKOMSTANDIGHEDEN</th>
											<th class="text-center pt-4" style="width: 125px">Slecht</th>
											<th class="text-center pt-4" style="width: 125px">Matig</th>
											<th class="text-center pt-4" style="width: 125px">Normaal</th>
											<th class="text-center pt-4" style="width: 125px">Goed</th>
										</tr>
									</thead>
									<tr>
										<td class="pt-2">Aandacht voor veiligheid op de werkvloer</td>
										<td class="text-center pt-2">
											<input type="radio" name="i31"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i31"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i31"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i31"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Orde en netheid</td>
										<td class="text-center pt-2">
											<input type="radio" name="i32"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i32"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i32"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i32"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Voorzieningen (sanitair,kantine,kleedruimte)</td>
										<td class="text-center pt-2">
											<input type="radio" name="i33"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i33"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i33"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i33"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Werktijden en rustpauzes</td>
										<td class="text-center pt-2">
											<input type="radio" name="i34"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i34"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i34"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i34"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Werktempo / werkdruk</td>
										<td class="text-center pt-2">
											<input type="radio" name="i35"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i35"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i35"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i35"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Toezicht op naleving van veiligheidsregels</td>
										<td class="text-center pt-2">
											<input type="radio" name="i36"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i36"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i36"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i36"/>
										</td>
									</tr>
									<tr>
										<td class="pt-2">Omgang met collega’s en leidinggevenden</td>
										<td class="text-center pt-2">
											<input type="radio" name="i37"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i37"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i37"/>
										</td>
										<td class="text-center pt-2">
											<input type="radio" name="i37"/>
										</td>
									</tr>
									<tr>
										<td class="pt-3">Toelichting</td>
										<td colspan="4" class="pt-3">
											<input name="" value="" type="text" class="form-control"/>
										</td>
									</tr>
									<thead>
										<tr>
											<th class="pt-4" colspan="5">WERKZAAMHEDEN</th>
										</tr>
										<tr>
											<td class="pt-3">Werkzaamheden die worden of zijn verricht:</td>
											<td colspan="4" class="pt-3">
												<input name="" value="" type="text" class="form-control"/>
											</td>
										</tr>
										<tr>
											<td class="pt-3">Komen de werkzaamheden overeen met de aanvraag?</td>
											<td colspan="4" class="pt-3">
												<input type="radio" name="w1"/>
												Ja
												<input type="radio" name="w1" class="ml-4"/>
												Nee
											</td>
										</tr>
										<tr>
											<td class="pt-3">Werk je graag bij dit bedrijf?</td>
											<td colspan="4" class="pt-3">
												<input type="radio" name="w2"/>
												Ja
												<input type="radio" name="w2" class="ml-4"/>
												Nee
											</td>
										</tr>
										<tr>
											<td class="pt-3">Wil je opnieuw worden uitgezonden naar dit bedrijf?</td>
											<td colspan="4" class="pt-3">
												<input type="radio" name="w3"/>
												Ja
												<input type="radio" name="w3" class="ml-4"/>
												Nee
											</td>
										</tr>
										<tr>
											<td class="pt-3">Toelichting</td>
											<td colspan="4" class="pt-3">
												<input name="" value="" type="text" class="form-control"/>
											</td>
										</tr>
										<tr>
											<td class="pt-3">Beoordelaar</td>
											<td colspan="4" class="pt-3">
												<input name="{$werknemer.naam}" value="" type="text" class="form-control"/>
											</td>
										</tr>
										<tr>
											<td class="pt-3">Datum</td>
											<td colspan="4" class="pt-3">
												<input name="" value="" type="text" class="form-control"/>
											</td>
										</tr>
										<tr>
											<td class="pt-3">Paraaf</td>
											<td class="pt-4">
												<div id="signature-pad" style="text-align: center;" class="mb-2">
													<canvas width="350" height="80" style="border: 1px solid #999; border-radius: 6px" class="canvas cursor-pencil"></canvas>
												</div>
												<script>
                                                    {literal}
													wrapper  = document.getElementById("signature-pad");
													canvas = wrapper.querySelector("canvas");
													clearButton = wrapper.querySelector("[data-action=clear]");
													signButton = wrapper.querySelector("[data-action=sign]");
													signaturePad = new SignaturePad(canvas, {backgroundColor: 'rgb(255, 255, 255)'});
                                                    {/literal}
												</script>
											</td>
										</tr>
									</thead>
								</table>
							</form>
						</div>
					</div>
				</div>

			</div><!-- /row -->
			      <!--------------------------------------------------------------------------- /right ------------------------------------------------->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}