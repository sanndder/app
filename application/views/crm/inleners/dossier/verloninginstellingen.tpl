{extends file='../../../layout.tpl'}
{block "title"}Inlener{/block}
{block "header-icon"}icon-office{/block}
{block "header-title"}Inlener - {$inlener->bedrijfsnaam}{/block}
{assign "select2" "true"}

{block "content"}

    {include file='crm/inleners/dossier/_sidebar.tpl' active='verloninginstellingen'}
    {include file='crm/inleners/dossier/modals/urentype_toevoegen.tpl'}


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

					<!-------------------------------------------------------------------------------------------------------------------------------------------------
					|| Tabs
					-------------------------------------------------------------------------------------------------------------------------------------------------->
					<!-- Basic card -->
					<div class="card">

						<div class="card-header bg-light pb-0 pt-sm-0 header-elements-sm-inline">
							<div class="header-elements">
								<ul class="nav nav-tabs nav-tabs-highlight card-header-tabs">
									<li class="nav-item">
										<a href="#tab-factoren" class="nav-link {if !isset($smarty.get.tab) || $smarty.get.tab == 'tab-factoren'}active{/if}" data-toggle="tab">
											Factoren
										</a>
									</li>
									<li class="nav-item">
										<a href="#tab-urentypes" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-urentypes'}active{/if}" data-toggle="tab">
											Urentypes
										</a>
									</li>
									<li class="nav-item">
										<a href="#tab-vergoedingen" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-vergoedingen'}active{/if}" data-toggle="tab">
											Vergoedingen
										</a>
									</li>
									<li class="nav-item">
										<a href="#tab-staffelkorting" class="nav-link {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-staffelkorting'}active{/if}" data-toggle="tab">
											Staffelkorting
										</a>
									</li>
								</ul>
							</div>
						</div>

						<!-- card  body-->
						<div class="card-body tab-content">

							<!-------------------------------------------------------------------------------------------------------------------------------------------------
							|| Factoren
							-------------------------------------------------------------------------------------------------------------------------------------------------->
							<div class="tab-pane fade {if !isset($smarty.get.tab) || $smarty.get.tab == 'tab-factoren'}show active{/if}" id="tab-factoren">

								<form method="post" action="">
									<fieldset class="mb-3">
										<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Factoren toevoegen</legend>

										<table>
											<tr>
												<td style="width: 300px;">Omschrijving</td>
												<td style="width: 110px;">Factor uren</td>
												<td style="width: 110px;">Factor overuren</td>
												<td></td>
											</tr>
											<tr>
												<td class="pr-2">
													<input name="omschrijving" value="" type="text" class="form-control"/>
												</td>
												<td class="pr-2">
													<input name="factor_hoog" value="" type="text" class="form-control text-right"/>
												</td>
												<td class="pr-2">
													<input name="factor_laag" value="" type="text" class="form-control text-right"/>
												</td>
												<td>
													<button type="submit" name="set" value="inleners_factoren" class="btn btn-outline-success btn-sm">
														<i class="icon-plus-circle2 mr-1"></i>Toevoegen
													</button>
												</td>
											</tr>
										</table>
									</fieldset>
								</form>


								<fieldset class="mb-3  mt-5">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Factoren overzicht</legend>
								</fieldset>
								<table>
									<tr>
										<td style="width: 300px;">Omschrijving</td>
										<td style="width: 110px;">Factor uren</td>
										<td style="width: 110px;">Factor overuren</td>
										<td></td>
									</tr>
                                    {foreach $factoren as $factor}
										<form method="post" action="">
											<tr>
												<td class="pr-2">
													<input name="default_factor[{$factor.factor_id}]" value="{$factor.default_factor}" type="hidden"/>
													<input name="omschrijving[{$factor.factor_id}]" {if $factor.default_factor}readonly{/if} value="{$factor.omschrijving}" type="text" class="form-control"/>
												</td>
												<td class="pr-2">
													<input name="factor_hoog[{$factor.factor_id}]" value="{$factor.factor_hoog|number_format:3:',':'.'}" type="text" class="form-control text-right"/>
												</td>
												<td class="pr-2">
													<input name="factor_laag[{$factor.factor_id}]" value="{$factor.factor_laag|number_format:3:',':'.'}" type="text" class="form-control text-right"/>
												</td>
												<td>
													<button type="submit" name="set" value="inleners_factoren" class="btn btn-outline-success btn-sm">
														<i class="icon-checkmark5"></i>
													</button>
                                                    {if !$factor.default_factor}
														<button onclick="return confirm('Factoren verwijderen?')" type="submit" name="del" value="inleners_factoren" class="btn btn-outline-danger btn-sm">
															<i class="icon-trash"></i>
														</button>
                                                    {/if}
												</td>
											</tr>
										</form>
                                    {/foreach}
								</table>

							</div><!-- /einde tab -->

							<!-------------------------------------------------------------------------------------------------------------------------------------------------
							|| Urentypes
							-------------------------------------------------------------------------------------------------------------------------------------------------->
							<div class="tab-pane fade {if isset($smarty.get.tab) && $smarty.get.tab == 'tab-urentypes'}show active{/if}" id="tab-urentypes">

								<div class="btn-group">
									<button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#modal_add_urentype">
										<i class="icon-plus-circle2"></i>
										<span class="d-none d-inline-block ml-2">Urentype toevoegen</span>
									</button>
								</div>

								<fieldset class="mb-0 mt-3">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary mb-1">Urentypes voor inlener</legend>
								</fieldset>

								<table class="table table-striped table-xs">
									<thead>
										<tr>
											<th style="width: 180px">Type</th>
											<th style="width: 120px">Percentage</th>
											<th style="width: 350px">Afwijkende naam</th>
											<th style="width: 135px">Standaard verkooptarief</th>
											<th>Doorbelasten naar uitzender</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
                                        {if is_array($matrix)}
                                            {foreach $matrix as $urentype}
												<tr>
													<td>{$urentype.naam}</td>
													<td>{$urentype.percentage|number_format:2:',':'.'}%</td>
													<td>
														<input name="" value="{$urentype.label}" type="text" class="form-control"/>
													</td>
													<td>
														<input name="" value="{$urentype.standaard_verkooptarief|number_format:2:',':'.'}" type="text" class="form-control text-right"/>
													</td>
													<td>
														<div class="form-check form-check-inline">
															<label class="form-check-label">
																<span>
																	<input {if $urentype.doorbelasten_uitzender == 1}checked{/if} value="1" type="radio" class="form-input-styled" name="doorbelasten_uitzender-{$urentype.inlener_urentype_id}">
																</span>
																Ja
															</label>
														</div>
														<div class="form-check form-check-inline">
															<label class="form-check-label">
																<span>
																	<input {if $urentype.doorbelasten_uitzender == 0}checked{/if} value="0" type="radio" class="form-input-styled" name="doorbelasten_uitzender-{$urentype.inlener_urentype_id}">
																</span>
																Nee
															</label>
														</div>
													</td>
													<td>

													</td>
												</tr>
                                            {/foreach}
                                        {/if}
									</tbody>
								</table>


								<fieldset class="mb-0 mt-4">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary mb-0">Urentypes per werknemer</legend>
								</fieldset>

                                {foreach $matrix as $urentype}

									<fieldset class="mb-0 mt-0">
										<legend class="text-uppercase font-size-sm pl-2 mb-1 font-weight-bold" style="background-color: #EEE">
		                                {$urentype.naam} {if $urentype.label != ''}- {$urentype.label}{/if}
										</legend>
									</fieldset>

									<table class="table table-xs mt-0 mb-3">
										<thead>
											<tr>
												<th style="width: 35px">Actief</th>
												<th style="width: 135px">Werknemer ID</th>
												<th style="width: 325px">Werknemer</th>
												<th style="width: 115px">Uurloon type</th>
												<th style="width: 115px">Uurloon</th>
												<th style="width: 75px">Verkooptarief</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
                                            {if isset($urentype.werknemers) && is_array($urentype.werknemers)}
                                                {foreach $urentype.werknemers as $w}
													<tr  class="{if !$w.urentype_active} text-grey-200{/if}">
														<td>
                                                            {if $urentype.urentype_id != 1 || ($urentype.urentype_id == 1 && $urentype.label != '')}
																<div class="form-check">
																	<label class="form-check-label">
																		<input data-id="{$w.id}"  type="checkbox" class="form-input-styled-info toggle-urentype-active" {if $w.urentype_active} checked{/if}>
																	</label>
																	<i class="icon-spinner2 spinner text-primary mr-1" style="display: none; margin-left: -27px">
																</div>
                                                            {/if}
														</td>
														<td>{$w.werknemer_id}</td>
														<td>{$w.werknemer_naam}</td>
														<td>
															{if $w.uurloon_id == 1}Standaard{/if}
															{if $w.uurloon_id > 1}Uurloon {$w.uurloon_id}{/if}
														</td>
														<td>
                                                            € {$w.uurloon|number_format:2:',':'.'}
														</td>
														<td>€ {$w.verkooptarief|number_format:2:',':'.'}
														</td>
														<td></td>
													</tr>
                                                {/foreach}
                                            {/if}
										</tbody>
									</table>
                                {/foreach}

							</div>
							<script>
								{literal}
								$('.toggle-urentype-active').on('change', function() {

								    $obj = $(this);
									$formcheck = $obj.closest('.form-check');
									$formcheck.find('.spinner').show();
                                    $formcheck.find('.form-check-label').hide();

                                    $.get( 'crm/werknemers/ajax/toggleurentype?id='+$obj.data('id')+'&state=' +  $obj.prop('checked'), function( result ) {
	                                    json = JSON.parse(result);
                                        if (json.status == 'error' )
                                            failed();
                                        else
                                        {
	                                        if( $obj.prop('checked') )
                                                $formcheck.closest('tr').removeClass('text-grey-200');
	                                        else
                                                $formcheck.closest('tr').addClass('text-grey-200');
                                        }
                                    })
                                    .fail(function() {
                                        failed();
                                    }).always(function(){
                                        $formcheck.find('.spinner').hide();
                                        $formcheck.find('.form-check-label').show();
                                    });

                                    function failed()
                                    {
                                        if( $obj.prop('checked') )
                                            $obj.prop('checked', false ).closest('span').removeClass('checked');
                                        else
                                            $obj.prop('checked', true ).closest('span').addClass('checked');
                                        Swal.fire({type: 'error', title: 'Er ging wat fout', text: 'Wijzigingen zijn niet uitgevoerd!', confirmButtonClass: 'btn btn-info'});
                                    }
                                });
								{/literal}
							</script>

							<!-------------------------------------------------------------------------------------------------------------------------------------------------
							|| Vergoedingen
							-------------------------------------------------------------------------------------------------------------------------------------------------->
							<div class="tab-pane fade" id="card-tab3">

							</div>

						</div><!-- /card body-->
					</div><!-- /basic card -->


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}