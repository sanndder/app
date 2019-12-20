{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-calculator2{/block}
{block "header-title"}Proforma{/block}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

            {if isset($msg)}{$msg}{/if}

			<div class="card">

				<div class="card-body">
					<form method="post" action="">

						<div class="row">

							<!----------------------------------------------------------------
							// Instellingen
							----------------------------------------------------------------->
							<div class="col-md-3">
								<h4 class="panel-title" style="border-bottom:2px solid #428BCA; margin:0 5px 15px 5px; font-size: 18px !important;">Instellingen</h4>


								<table class="proforma-input">
									<tr>
										<td>Bruto uurloon (€)</td>
										<td><input value="{if isset($smarty.post.bruto) && is_numeric($smarty.post.bruto)}{$smarty.post.bruto|number_format:2:',':'.'}{else}{$smarty.post.bruto}{/if}" name="bruto" class="form-control" /></td>
									</tr>
									<tr>
										<td>Prestatietoeslag (€)</td>
										<td><input value="{if isset($smarty.post.prestatietoeslag) && is_numeric($smarty.post.prestatietoeslag)}{$smarty.post.prestatietoeslag|number_format:2:',':'.'}{else}{$smarty.post.prestatietoeslag}{/if}" name="prestatietoeslag" class="form-control" /></td>
									</tr>
									<tr>
										<td>Kilometers (á €0,19)</td>
										<td><input value="{if isset($smarty.post.km) && is_numeric($smarty.post.km)}{$smarty.post.km|number_format:2:',':'.'}{else}{$smarty.post.km}{/if}" name="km" class="form-control" /></td>
									</tr>
									<tr>
										<td class="pr-lg">Loonheffingskorting</td>
										<td>
											<div class="rdio rdio-primary pull-left">
												<input name="loonheffingskorting" id="radioDefault1" value="1" {if $smarty.post.loonheffingskorting == 1} checked="checked"{/if} type="radio">
												<label for="radioDefault1">Ja</label>
											</div>
											<div class="rdio rdio-primary pull-left ml-xl">
												<input name="loonheffingskorting" id="radioDefault0" value="0" {if $smarty.post.loonheffingskorting == 0} checked="checked"{/if} type="radio">
												<label for="radioDefault0">Nee</label>
											</div>
										</td>
									</tr>
									<tr>
										<td>Geboortejaar</td>
										<td><input value="{$smarty.post.geboortejaar}" name="geboortejaar" class="form-control" type="number" /></td>
									</tr>
									<tr>
										<td>CAO</td>
										<td>
											<select name="cao" class="form-control">
												<option {if $smarty.post.cao == 'NBBU'} selected="selected"{/if} value="NBBU">NBBU</option>
												<option {if $smarty.post.cao == 'bouw'} selected="selected"{/if} value="bouw">Bouw</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>Pensioen</td>
										<td>
											<div class="rdio rdio-primary pull-left">
												<input name="pensioen" id="pensioenradioDefault1" value="1" {if $smarty.post.pensioen == 1} checked="checked"{/if} type="radio">
												<label for="pensioenradioDefault1">Ja</label>
											</div>
											<div class="rdio rdio-primary pull-left ml-xl">
												<input name="pensioen" id="pensioenradio0" value="0" {if $smarty.post.pensioen == 0} checked="checked"{/if} type="radio">
												<label for="pensioenradio0">Nee</label>
											</div>
										</td>
									</tr>
									<tr>
										<td>Verloningstijdvak</td>
										<td>
											<select name="frequentie" class="form-control">
												<option {if $smarty.post.frequentie == 'w'} selected="selected"{/if} value="w">Week</option>
												<option {if $smarty.post.frequentie == '4w'} selected="selected"{/if} value="4w">4 weken</option>
												<option {if $smarty.post.frequentie == 'm'} selected="selected"{/if} value="m">Maand</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>Uren</td>
										<td><input value="{$smarty.post.uren}" name="uren" class="form-control" type="text" /></td>
									</tr>
									<tr>
										<td>Dagen</td>
										<td><input value="{$smarty.post.dagen}" name="dagen" class="form-control" type="number" /></td>
									</tr>
									<tr>
										<td>Vakantieuren direct uitkeren</td>
										<td>
											<div class="rdio rdio-primary pull-left">
												<input name="vakantieuren_direct" id="VakantieurenradioDefault1" value="1" {if $smarty.post.vakantieuren_direct == 1} checked="checked"{/if} type="radio">
												<label for="VakantieurenradioDefault1">Ja</label>
											</div>
											<div class="rdio rdio-primary pull-left ml-xl">
												<input name="vakantieuren_direct" id="VakantieurenradioDefault0" value="0" {if $smarty.post.vakantieuren_direct == 0} checked="checked"{/if} type="radio">
												<label for="VakantieurenradioDefault0">Nee</label>
											</div>
										</td>
									</tr>
									<tr>
										<td>Vakantiegeld direct uitkeren</td>
										<td>
											<div class="rdio rdio-primary pull-left">
												<input name="vakantiegeld_direct" id="VakantiegeldradioDefault1" value="1" {if $smarty.post.vakantiegeld_direct == 1} checked="checked"{/if} type="radio">
												<label for="VakantiegeldradioDefault1">Ja</label>
											</div>
											<div class="rdio rdio-primary pull-left ml-xl">
												<input name="vakantiegeld_direct" id="VakantiegeldradioDefault0" value="0" {if $smarty.post.vakantiegeld_direct == 0} checked="checked"{/if} type="radio">
												<label for="VakantiegeldradioDefault0">Nee</label>
											</div>
										</td>
									</tr>

									<tr class="tr-atv-direct" style="{if isset($smarty.post.cao) && $smarty.post.cao != 'bouw'}display: none{/if}">
										<td>ATV-uren direct uitkeren</td>
										<td>
											<div class="rdio rdio-primary pull-left">
												<input name="atv_direct" id="atvradioDefault1" value="1" {if $smarty.post.atv_direct == 1} checked="checked"{/if} type="radio">
												<label for="atvradioDefault1">Ja</label>
											</div>
											<div class="rdio rdio-primary pull-left ml-xl">
												<input name="atv_direct" id="atvradioDefault0" value="0" {if $smarty.post.atv_direct == 0} checked="checked"{/if} type="radio">
												<label for="atvradioDefault0">Nee</label>
											</div>
										</td>
									</tr>

									<tr><td colspan="2" style="height: 10px;"></td> </tr>

									<tr>
										<td>ET regeling</td>
										<td>
											<div class="rdio rdio-primary pull-left">
												<input name="et_regeling" id="etradioDefault1" value="1" {if $smarty.post.et_regeling == 1} checked="checked"{/if} type="radio">
												<label for="etradioDefault1">Ja</label>
											</div>
											<div class="rdio rdio-primary pull-left ml-xl">
												<input name="et_regeling" id="etradioDefault0" value="0" {if $smarty.post.et_regeling == 0} checked="checked"{/if} type="radio">
												<label for="etradioDefault0">Nee</label>
											</div>
										</td>
									</tr>

									<tr class="td-et" {if $smarty.post.et_regeling == 0} style="display: none"{/if}>
										<td>Huisvesting</td>
										<td><input value="{$smarty.post.et_huisvesting}" name="et_huisvesting" class="form-control" /></td>
									</tr>

									<tr class="td-et" {if $smarty.post.et_regeling == 0} style="display: none"{/if}>
										<td>Kilometers</td>
										<td><input value="{$smarty.post.et_km}" name="et_km" class="form-control" /></td>
									</tr>

									<tr class="td-et" {if $smarty.post.et_regeling == 0} style="display: none"{/if}>
										<td>Verschil levensstandaard</td>
										<td>
											<select name="et_verschil_leven" class="form-control select-cola">
												<option value="0">Selecteer een land</option>
	                                            {if isset($cola_lijst) && is_array($cola_lijst)}
	                                                {foreach $cola_lijst as $land}
														<option value="{$land.cola}" {if isset($smarty.post.et_verschil_leven) &&  $smarty.post.et_verschil_leven == $land.cola} selected{/if}>{$land.land}</option>
	                                                {/foreach}
	                                            {/if}
											</select>
										</td>
									</tr>

									<tr class="nettoloon">
										<td>Netto loon</td>
										<td>
											<input value="{if isset($smarty.post.uitbetaald) && is_numeric($smarty.post.uitbetaald)}{$smarty.post.uitbetaald|number_format:2:',':'.'}{/if}" name="netto_loon" class="form-control" />
										</td>
	                                    {*
										<td>
											<input value="{if isset($smarty.post.heffing) && is_numeric($smarty.post.heffing)}{$smarty.post.heffing|number_format:2:',':'.'}{/if}" name="" class="form-control" />
										</td>*}
									</tr>
								</table>
								<button type="submit" class="btn btn-success  mt-1" name="go">
									Bruto uurloon <em class="fa fa-arrow-right"></em>Netto loon
								</button><br />
								<button type="submit" class="btn btn-primary  mt-1" name="netto_bruto">
									Netto loon <em class="fa fa-arrow-right"></em>Bruto uurloon
								</button><br />
	                            {if isset($id)}
									<a target="_blank" style="width: 205px;" class="btn btn-outline-primary mt-1" href="proforma/loonstrook/{$id}">
										<em class="fa fa-file-pdf-o mr-sm"></em> Toon loonstrook
									</a>
									<br />
	                                {if isset($smarty.post.verkooptarief) && $smarty.post.verkooptarief != '0,00'}
										<a target="_blank" style="width: 205px;" class="btn btn-bordered btn-default mt" href="proforma/kostenoverzicht/{$id}">
											<em class="fa fa-euro mr-sm"></em> Toon kostenoverzicht
										</a>
	                                {/if}
	                            {/if}


							</div>
							<div class="col-md-3">
								<h4 class="panel-title" style="border-bottom:2px solid #428BCA; margin:0 5px 15px 5px; font-size: 18px !important;">Kosten</h4>

								<table class="proforma-input">
									<tr>
										<td>Verkooptarief</td>
										<td>
											<input value="{if isset($smarty.post.verkooptarief) && is_numeric($smarty.post.verkooptarief)}{$smarty.post.verkooptarief|number_format:2:',':'.'}{else}{$smarty.post.verkooptarief}{/if}" name="verkooptarief" class="form-control" />
										</td>
									</tr>
									<tr>
										<td>Factor standaard</td>
										<td>
											<input value="{if isset($smarty.post.factor_belast) && is_numeric($smarty.post.factor_belast)}{$smarty.post.factor_belast|number_format:3:',':'.'}{else}{$smarty.post.factor_belast}{/if}" name="factor_belast" class="form-control" />
										</td>
									</tr>
									<tr>
										<td>Factor toeslag</td>
										<td>
											<input value="{if isset($smarty.post.factor_onbelast) && is_numeric($smarty.post.factor_onbelast)}{$smarty.post.factor_onbelast|number_format:3:',':'.'}{else}{$smarty.post.factor_onbelast}{/if}" name="factor_onbelast" class="form-control" />
										</td>
									</tr>
									<tr>
										<td class="pr-lg">Doelgroepverklaring</td>
										<td>
											<div class="rdio rdio-primary pull-left">
												<input name="doelgroepverklaring" id="radioDefault111" value="1" {if $smarty.post.doelgroepverklaring == 1} checked="checked"{/if} type="radio">
												<label for="radioDefault111">Ja</label>
											</div>
											<div class="rdio rdio-primary pull-left ml-xl">
												<input name="doelgroepverklaring" id="radioDefault011" value="0" {if $smarty.post.doelgroepverklaring == 0} checked="checked"{/if} type="radio">
												<label for="radioDefault011">Nee</label>
											</div>
										</td>
									</tr>
								</table>
							</div>

						</div>
					</form>

				</div>
			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->
	<script>
		{literal}


        $('[name=frequentie]').on( 'change', function() {
            f = $( '[name=frequentie] option:selected').val();

            if( f == 'w' ){uren = 40 }
            if( f == '4w' ){uren = 160 }
            if( f == 'm' ){uren = 173.3 }


            $( '[name=uren]').val( uren );
        });

        $('[name=cao]').on( 'change', function() {
            cao = $( '[name=cao] option:selected').val();

            if( cao == 'bouw' )
                $('.tr-atv-direct').show();
            else
                $('.tr-atv-direct').hide();

        });

        $('[name=et_regeling]').on( 'change', function() {

            et = $( '[name=et_regeling]:checked').val();

            if( et == '1' )
                $('.td-et').show();
            else
                $('.td-et').hide();
        });

		{/literal}
	</script>

{/block}