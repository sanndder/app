{extends file='../../layout.tpl'}
{block "title"}Snelstart{/block}
{block "header-icon"}icon-books{/block}
{block "header-title"}Overzicht - Snelstart{/block}
{assign "datamask" "true"}

{block "content"}

	<!---------------------------------------------------------------------------------------------------------
	|| Main content
	---------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

            {if isset($msg)}{$msg}{/if}

			<!---------------------------------------------------------------------------------------------------------
			|| Zijmenu
			---------------------------------------------------------------------------------------------------------->
			<div class="row">

				<div class="col-md-9">

					<div class="row">

						<!----------------------------------- boekingen export ------------------------------------------>
						<div class="col-md-4">
							<div class="card ">
								<div class="media h-100">
									<div class="bg-info p-2">
										<i class="icon-file-spreadsheet icon-2x"></i>
									</div>
									<div class="media-body pl-3 font-size-lg" style="padding-top: 9px">
										<form method="post" action="" target="_blank">
											Boekingen t/m
											<input name="datum" value="{$yesterday|date_format: '%d-%m-%Y'}" type="text" class="form-control" style="display: inline-block; width:105px;"/>
											<button type="submit" name="go" class="btn btn-sm btn-success">
												<i class="icon-check"></i>
											</button>
										</form>
									</div>
								</div>
							</div><!-- /basic card -->
						</div>

						<!----------------------------------- inleners lijst ------------------------------------------>
						<div class="col-md-4">
							<!-- Basic card -->
							<div class="card ">
								<div class="media h-100">
									<a target="_blank" href="{$base_url}/overzichten/snelstart/lijst/debiteuren">
									<div class="bg-info p-2">
										<i class="icon-file-download icon-2x"></i>
									</div>
									</a>
									<div class="media-body pl-3 font-size-lg" style="padding-top: 17px">
										<a target="_blank" href="{$base_url}/overzichten/snelstart/lijst/debiteuren">
											Actuele lijst debiteuren (inleners)
										</a>
									</div>
								</div>
							</div><!-- /basic card -->
						</div><!-- /col -->

						<!----------------------------------- uitzenders lijst ------------------------------------------>
						<div class="col-md-4">
							<!-- Basic card -->
							<div class="card ">
								<div class="media h-100">
									<a target="_blank" href="{$base_url}/overzichten/snelstart/lijst/crediteuren">
										<div class="bg-info p-2">
											<i class="icon-file-download icon-2x"></i>
										</div>
									</a>
									<div class="media-body pl-3 font-size-lg" style="padding-top: 17px">
										<a target="_blank" href="{$base_url}/overzichten/snelstart/lijst/crediteuren">
											Actuele lijst crediteuren (uitzenders)
										</a>
									</div>
								</div>
							</div><!-- /basic card -->
						</div><!-- /col -->


					</div><!-- /row -->

                    {if $werkgever_type == 'bemiddeling'}
					<div class="row">

						<!----------------------------------- boekingen export ------------------------------------------>
						<div class="col-md-4">
							<div class="card ">
								<div class="media h-100">
									<div class="bg-info p-2">
										<i class="icon-file-spreadsheet icon-2x"></i>
									</div>
									<div class="media-body pl-3 font-size-lg" style="padding-top: 9px">
										<form method="post" action="" target="_blank">
											Boekingen ZZP t/m
											<input name="datum" value="{$yesterday|date_format: '%d-%m-%Y'}" type="text" class="form-control" style="display: inline-block; width:105px;"/>
											<button type="submit" name="go_zzp" class="btn btn-sm btn-success">
												<i class="icon-check"></i>
											</button>
										</form>
									</div>
								</div>
							</div><!-- /basic card -->
						</div>
					</div>
					{/if}

					      <!-- Basic card -->
					<div class="card">


					</div><!-- /basic card -->

				</div><!-- /col -->

				<div class="col-md-3">

					<!-------------------------------------------------- Details -------------------------------------------------------------->
					<div class="card">

						<!-- header -->
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Instellingen<span class="factuur-nr"></span> </span>
						</div>

						<!-- Zoekvelden -->
						<div class="card-body">

							<form method="post" action="">

								<table>
									<tr>
										<td class="pr-3">Verkoop</td>
										<td>
											<input type="text" class="form-control" name="verkoop" value="{if isset($settings.verkoop.rekening)}{$settings.verkoop.rekening}{/if}">
										</td>
									</tr>
									<tr>
										<td class="pr-3">Omzet BTW hoog</td>
										<td>
											<input type="text" class="form-control" name="omzet_btw_hoog" value="{if isset($settings.omzet_btw_hoog.rekening)}{$settings.omzet_btw_hoog.rekening}{/if}">
										</td>
									</tr>
									<tr>
										<td class="pr-3">BTW af te dragen hoog</td>
										<td>
											<input type="text" class="form-control" name="btw_afdragen_hoog" value="{if isset($settings.btw_afdragen_hoog.rekening)}{$settings.btw_afdragen_hoog.rekening}{/if}">
										</td>
									</tr>
									<tr>
										<td class="pr-3">Omzet BTW verlegd</td>
										<td>
											<input type="text" class="form-control" name="omzet_btw_verlegd" value="{if isset($settings.omzet_btw_verlegd.rekening)}{$settings.omzet_btw_verlegd.rekening}{/if}">
										</td>
									</tr>

									<tr>
										<td colspan="2" style="height: 15px"></td>
									</tr>

									<tr>
										<td class="pr-3">Inkoop</td>
										<td>
											<input type="text" class="form-control" name="inkoop" value="{if isset($settings.inkoop.rekening)}{$settings.inkoop.rekening}{/if}">
										</td>
									</tr>
									<tr>
										<td class="pr-3">Marge uitzenders</td>
										<td>
											<input type="text" class="form-control" name="marge_uitzenders" value="{if isset($settings.marge_uitzenders.rekening)}{$settings.marge_uitzenders.rekening}{/if}">
										</td>
									</tr>
									<tr>
										<td class="pr-3">BTW te vorderen hoog</td>
										<td>
											<input type="text" class="form-control" name="btw_vorderen_hoog" value="{if isset($settings.btw_vorderen_hoog.rekening)}{$settings.btw_vorderen_hoog.rekening}{/if}">
										</td>
									</tr>

									<tr>
										<td colspan="2" class="pt-2">
											<button type="submit" name="set_settings" class="btn btn-sm btn-success">
												<i class="icon-check pr-1"></i>Opslaan
											</button>
										</td>
									</tr>
								</table>

							</form>


						</div>
					</div>

				</div><!-- /col -->


			</div><!-- /row -->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->
{/block}