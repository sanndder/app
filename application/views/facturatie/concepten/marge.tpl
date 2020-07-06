{extends file='../../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}mi-euro-symbol {/block}
{block "header-title"}Concept - Marge{/block}
{assign "select2" "true"}

{block "content"}

	<!---------------------------------------------------------------------------------------------------------
	|| Zijmenu
	---------------------------------------------------------------------------------------------------------->
	<div class="sidebar sidebar-light sidebar-main sidebar-sections sidebar-expand-lg align-self-start" style="width: 30em">

		<!-- Sidebar mobile toggler -->
		<div class="sidebar-mobile-toggler text-center">
			<a href="#" class="sidebar-mobile-main-toggle">
				<i class="icon-arrow-left8"></i>
			</a>
			<span class="font-weight-semibold">Zijmenu</span>
			<a href="#" class="sidebar-mobile-expand">
				<i class="icon-screen-full"></i>
				<i class="icon-screen-normal"></i>
			</a>
		</div>
		<!-- /sidebar mobile toggler -->

		<!-- Sidebar content -->
		<div class="sidebar-content">

			<div class="card card-sidebar-mobile d-none d-lg-block d-xl-block">

				<!-------------------------------------------------- Knoppen -------------------------------------------------------------->
				<div class="card-body">
					<div class="row">
						<div class="col">

							<form method="post" action="">

								<div class="form-group form-group-feedback form-group-feedback-left">

									<select name="uitzender_id" class="form-control select-search">
										<option value="">Selecteer een uitzender</option>
                                        {if $uitzenders !== NULL}
                                            {foreach $uitzenders as $u}
												<option {if isset($factuur.uitzender_id) && $factuur.uitzender_id == $u@key}selected{/if} value="{$u@key}">{$u@key} - {$u}</option>
                                            {/foreach}
                                        {/if}
									</select>

								</div>

                                {if isset($factuur.uitzender_id)}
									<div class="form-group">
										<table>
											<tbody>
												<tr>
													<td>
														<ul class="nav nav-sidebar p-0" data-nav-type="accordion">
															<li class="nav-item-header font-weight-bolder pl-2 pr-2 pb-1 pt-0">
																<div class="text-uppercase font-size-xs line-height-xs">Tijdvak</div>
															</li>
														</ul>
													</td>
													<td>
														<ul class="nav nav-sidebar p-0" data-nav-type="accordion">
															<li class="nav-item-header font-weight-bolder pl-2 pr-2 pb-1 pt-0">
																<div class="text-uppercase font-size-xs line-height-xs">Jaar</div>
															</li>
														</ul>
													</td>
													<td>
														<ul class="nav nav-sidebar p-0" data-nav-type="accordion">
															<li class="nav-item-header font-weight-bolder pl-2 pr-2 pb-1 pt-0">
																<div class="text-uppercase font-size-xs line-height-xs vi-tijdvak-titel">Week</div>
															</li>
														</ul>
													</td>
												</tr>
												<tr>
													<td class="pr-3">
														<select name="tijdvak" class="form-control">
															<option value=""></option>
															<option {if isset($factuur.tijdvak) && $factuur.tijdvak == 'w'} selected{/if} value="w">Week</option>
															<option {if isset($factuur.tijdvak) && $factuur.tijdvak == '4w'} selected{/if} value="4w">4-weken</option>
															<option {if isset($factuur.tijdvak) && $factuur.tijdvak == 'm'} selected{/if} value="m">Maand</option>
														</select>
													</td>
													<td>
														<input type="text" class="form-control text-right pr-2" name="jaar" style="width:75px;" value="{if isset($factuur.jaar)}{$factuur.jaar}{else}2020{/if}">
													</td>
													<td>
														<input type="text" class="form-control text-right" name="periode" style="width:75px;" value="{if isset($factuur.periode)}{$factuur.periode}{/if}">
													</td>
												</tr>
											</tbody>
										</table>
									</div>
                                {/if}

								<button type="submit" name="opslaan" class="btn btn-sm btn-success">
									<i class="icon-checkmark3 mr-1"></i>Opslaan
								</button>

							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<div class="row">
				<!--<div class="col-xxl-6 offset-xxl-3 col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12">-->
				<div class="col-xxl-6 col-xl-8 col-lg-10 col-md-12">

					<div>
                        {if isset($msg)}{$msg}{/if}
					</div>

					<!-- Basic card -->
					<div class="card">

						<div class="card-body" style="height: 800px">

							<form method="post" action="">


								<button type="submit" name="opslaan" class="btn btn-sm btn-success mb-3">
									<i class="icon-checkmark3 mr-1"></i>Factuurregels opslaan
								</button>


								<table style="width: 100%">
									<thead>
										<tr>
											<th style="width:70%">Omschrijving</th>
											<th>Bedrag</th>
											<th style="width: 10%"></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input type="text" name="regels[0][omschrijving]" class="form-control" />
											</td>
											<td>
												<input type="text" name="regels[0][bedrag]" class="form-control text-right" />
											</td>
											<td>

											</td>
										</tr>
										{foreach $regels as $r}
											<tr>
												<td>
													<input type="text" name="regels[{$r@key}][omschrijving]" class="form-control" value="{$r.omschrijving}" />
												</td>
												<td>
													<input type="text" name="regels[{$r@key}][bedrag]" class="form-control text-right" value="{$r.subtotaal_verkoop}" />
												</td>
												<td>
													<a href="facturatie/concepten/marge/{$factuur.factuur_id}?delregel={$r@key}" class="btn btn-outline-danger">
														<i class="icon-trash"></i>
													</a>
												</td>
											</tr>
										{/foreach}
									</tbody>
									<tfoot>
										<tr>
											<td class="text-right pt-2 pr-1 font-weight-bold">Totaal excl BTW</td>
											<td class="text-right pt-2 pr-1 font-weight-bold">
												€ {$factuur.bedrag_excl|number_format:2:',':'.'}
											</td>
										</tr>
										<tr>
											<td class="text-right pr-1 font-weight-bold">BTW</td>
											<td class="pr-1 font-weight-bold text-right">
                                               € {$factuur.bedrag_btw|number_format:2:',':'.'}
											</td>
										</tr>
										<tr>
											<td class="text-right pr-1 font-weight-bold">Totaal</td>
											<td class="pr-1 font-weight-bold text-right">
												€ {$factuur.bedrag_incl|number_format:2:',':'.'}
											</td>
										</tr>
										<tr>
											<td colspan="3" class="pt-3">
												<i>Een negatief bedrag betekend dat de uitzender geld ontvangt, een positief bedrag betekend marge terugbetalen.</i>
											</td>
										</tr>
									</tfoot>
								</table>

							</form>


						</div><!-- /basic card body-->
					</div><!-- /basic card -->

				</div><!-- /col -->
			</div><!-- /row -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->

{/block}