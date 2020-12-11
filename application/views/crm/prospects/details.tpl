{extends file='../../layout.tpl'}
{block "title"}Prospect details{/block}
{block "header-icon"}icon-question3{/block}
{block "header-title"}Prospects{/block}

{assign "debounce" "true"}
{assign "datamask" "true"}

{block "content"}


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<div class="row">
				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| Links taken
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-3">

					<!-- Basic card -->
					<div class="card">
						<div class="card-header bg-blue font-weight-semibold header-elements-sm-inline" style="text-transform: capitalize; font-size: 16px; padding: 10px 12px">
							<div>{$prospect.bedrijfsnaam}</div>
							<div class="header-elements">
								<a href="crm/prospects" class="text-white btn-sm border-white">
									<i class="icon-undo2"></i>
								</a>
								<a href="#" class="text-white btn-sm border-white">
									<i class="icon-trash"></i>
								</a>
							</div>
						</div>
						<div class="card-body">

							<table style="width: 100%" data-link="crm/prospects/ajax/set/{$prospect.prospect_id}">
								<tr>
									<td class="pr-2 pt-1">Status</td>
									<td class="input-group-sm">
										<select name="status_id" class="form-control edit-inline-select" autocomplete="off">
											<option value="1" {if $prospect.status_id == '1'} selected{/if}>Nieuw</option>
											<option value="2" {if $prospect.status_id == '2'} selected{/if}>Opvolgen</option>
											<option value="3" {if $prospect.status_id == '3'} selected{/if}>Geen interesse</option>
											<option value="4" {if $prospect.status_id == '4'} selected{/if}>Afgerond succes</option>
										</select>
									</td>
									<td class="td-status" style="width: 25px"></td>
								</tr>

								<tr>
									<td colspan="3" style="height: 25px;"></td>
								</tr>

								<tr>
									<td class="pr-2 pt-1">Bedrijfsnaam</td>
									<td class="input-group-sm">
										<input type="text" name="bedrijfsnaam" class="form-control edit-inline" placeholder="Bedrijfsnaam...." value="{$prospect.bedrijfsnaam}" autocomplete="off">
									</td>
									<td class="td-status" style="width: 25px"></td>
								</tr>
								<tr>
									<td class="pr-2 pt-1">Omvang</td>
									<td class="input-group-sm pt-1">
										<input type="text" name="omvang" class="form-control edit-inline" placeholder="uren/uitzendkrachten...." value="{$prospect.omvang}" autocomplete="off">
									</td>
									<td class="td-status" style="width: 25px"></td>
								</tr>
								<tr>
									<td class="pr-2 pt-1">Kvk nummer</td>
									<td class="input-group-sm pt-1">
										<input type="text" name="kvknr" class="form-control edit-inline" placeholder="Kvk nummer...." value="{$prospect.kvknr}" autocomplete="off">
									</td>
									<td class="td-status pr-0"></td>
								</tr>

								<tr>
									<td colspan="3" style="height: 25px;"></td>
								</tr>

								<tr>
									<td class="pr-2 pt-1">Contactpersoon 1</td>
									<td class="input-group-sm pt-1">
										<input type="text" name="contact" class="form-control edit-inline" placeholder="Contactpersoon...." value="{$prospect.contact}" autocomplete="off">
									</td>
									<td class="td-status"></td>
								</tr>
								<tr>
									<td class="pr-2 pt-1">Telefoon 1</td>
									<td class="input-group-sm pt-1">
										<input type="text" name="telefoon" class="form-control edit-inline" placeholder="Telefoonnummer...." value="{$prospect.telefoon}" autocomplete="off">
									</td>
									<td class="td-status"></td>
								</tr>
								<tr>
									<td class="pr-2 pt-1">Email 1</td>
									<td class="input-group-sm pt-1">
										<input type="text" name="email" class="form-control edit-inline" placeholder="Emailadres...." value="{$prospect.email}" autocomplete="off">
									</td>
									<td class="td-status"></td>
								</tr>

								<tr>
									<td colspan="3" style="height: 25px;"></td>
								</tr>

								<tr>
									<td class="pr-2 pt-1">Contactpersoon 2</td>
									<td class="input-group-sm pt-1">
										<input type="text" name="contact_2" class="form-control edit-inline" placeholder="Contactpersoon...." value="{$prospect.contact_2}" autocomplete="off">
									</td>
									<td class="td-status"></td>
								</tr>
								<tr>
									<td class="pr-2 pt-1">Telefoon 2</td>
									<td class="input-group-sm pt-1">
										<input type="text" name="telefoon_2" class="form-control edit-inline" placeholder="Telefoonnummer...." value="{$prospect.telefoon_2}" autocomplete="off">
									</td>
									<td class="td-status"></td>
								</tr>
								<tr>
									<td class="pr-2 pt-1">Email 2</td>
									<td class="input-group-sm pt-1">
										<input type="text" name="email_2" class="form-control edit-inline" placeholder="Emailadres...." value="{$prospect.email_2}" autocomplete="off">
									</td>
									<td class="td-status"></td>
								</tr>

								<tr>
									<td colspan="3" style="height: 25px;"></td>
								</tr>

								<tr>
									<td class="pr-2 pt-1">Straat</td>
									<td class="input-group-sm pt-1">
										<input type="text" name="straat" class="form-control edit-inline" placeholder="Straat...." value="{$prospect.straat}" autocomplete="off">
									</td>
									<td class="td-status"></td>
								</tr>
								<tr>
									<td class="pr-2 pt-1">Huisnummer</td>
									<td class="input-group-sm pt-1">
										<input type="text" name="huisnummer" class="form-control edit-inline" placeholder="15...." value="{$prospect.huisnummer}" style="width: 55px" autocomplete="off">
									</td>
									<td class="td-status"></td>
								</tr>
								<tr>
									<td class="pr-2 pt-1">Plaats</td>
									<td class="input-group-sm pt-1">
										<input type="text" name="plaats" class="form-control edit-inline" placeholder="Plaats...." value="{$prospect.plaats}" autocomplete="off">
									</td>
									<td class="td-status"></td>
								</tr>
							</table>

						</div>
					</div>

				</div><!-- /col -->

				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| midden notities
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-6">

					<!-- Basic card -->
					<div class="card">

						<div class="card-body">

							<form method="post" action="">

								<table>
									<tr>
										<td class="pr-3">Type</td>
										<td>
											<select name="type" class="form-control" required>
												<option></option>
												<option value="notitie">Notitie</option>
												<option value="telefoon">Telefoon</option>
												<option value="email">Email</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>Notitie</td>
										<td style="width: 400px;">
											<textarea class="form-control" name="notitie" required></textarea>
										</td>
									</tr>
									<tr>
										<td></td>
										<td style="width: 300px;">
											<button type="submit" name="notitie_opslaan" class="btn btn-success btn-sm">
												<i class="icon-check2 mr-1"></i>Toevoegen
											</button>
										</td>
									</tr>
								</table>

							</form>

						</div>
					</div>

					<!-- /basic card -->

                    {if isset($notities) && is_array($notities) && count($notities) > 0}
                        {foreach $notities as $n}
							<div class="card" style="margin-top: 7px; margin-bottom: 7px">
								<div class="card-body media">
                                    {if $n.type == 'notitie'}
										<i class="icon-pencil5 text-blue-600 mr-4" style="font-size:24px"></i>
                                    {/if}
                                    {if $n.type == 'telefoon'}
										<i class="icon-phone2 text-blue-600 mr-4" style="font-size:24px"></i>
                                    {/if}
                                    {if $n.type == 'email'}
										<i class="icon-envelop3 text-blue-600 mr-4" style="font-size:24px"></i>
                                    {/if}
									<div class="media-body">
                                        {$n.notitie}
									</div>
									<div class="text-right">
                                        {$n.timestamp|date_format: '%d-%m-%Y om %R:%S'}<br/>
                                        {$n.user}
									</div>
								</div>
							</div>
                        {/foreach}
                    {/if}

				</div><!-- /col -->

				<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
				|| taken
				--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
				<div class="col-md-3">

					<!-- Basic card -->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Taken</span>
						</div>
						<div class="card-body">

							<form method="post" action="">

								<table style="width: 100%;">
									<tr>
										<td style="width: 95px">Einddatum</td>
										<td>Actie</td>
										<td></td>
									</tr>
									<tr>
										<td>
											<input name="datum" type="text" class="form-control pickadate" autocomplete="off" style="width: 95px" required>
										</td>
										<td>
											<input type="text" class="form-control" name="actie" required>
										</td>
										<td>
											<button type="submit" name="taak_opslaan" class="btn btn-success">
												<i class="icon-plus-circle2"></i>
											</button>
										</td>
									</tr>
								</table>

                                {if isset($taken) && is_array($taken) && count($taken) > 0}
									<table style="width: 100%" class="mt-3">
                                        {foreach $taken as $t}
											<tr {if $t.afgerond == 1}style="text-decoration: line-through"{/if} data-id="{$t.taak_id}">
												<td style="width: 25px; padding-top: 10px">
													<div class="form-check">
														<label class="form-check-label">
															<input name="afgerond" value="{$t.taak_id}" type="checkbox" class="form-input-styled" {if $t.afgerond == 1}checked{/if}>
														</label>
													</div>
												</td>
												<td style="padding-top: 10px">
													<div class="text-primary" style="font-size: 15px">{$t.actie}</div>
												</td>
												<td style="width:105px; padding-top: 10px">
													<span class="">
														<i class="icon-calendar2 mr-1"></i>{$t.datum|date_format: '%d-%m-%Y'}
													</span>
												</td>
												<td style="width:15px; padding-top: 8px">
													<i class="icon-trash text-muted" style="font-size: 12px; cursor: pointer" onclick="return confirm('Taak verwijderen?')"></i>
												</td>
											</tr>
                                        {/foreach}
									</table>
                                {/if}

								<script>
									{literal}
									$('[name="afgerond"]').on('change', function(){

										$obj = $(this);
										$tr = $obj.closest('tr');
										if( $obj.prop('checked') )
											$tr.css( 'text-decoration', 'line-through' );
										else
											$tr.css( 'text-decoration', 'none' );

										data.state = $obj.prop('checked');
										data.taak_id = $tr.data('id');

										xhr.url = base_url + 'crm/prospects/ajax/toggletaak/' + {/literal}{$prospect.prospect_id}{literal};
										var response = xhr.call( true );
										if( response !== false )
										{

										}
									})
									{/literal}
								</script>

							</form>
						</div>
					</div>

					<!-- /basic card -->
				</div><!-- /col -->

			</div><!-- /row -->


		</div>
		<!-- /content area -->
	</div>
	<!-- /main content -->

{/block}