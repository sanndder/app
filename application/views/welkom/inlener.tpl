{extends file='../layout.tpl'}
{block "title"}Welkom{/block}
{block "header-icon"}{/block}
{block "header-title"}{/block}
{assign "hide_menu" "true"}
{assign "select2" "true"}

{block "content"}

    {include file='_modals/signdocument.tpl'}

	<!-- Main content -->
	<div class="content-wrapper" style="margin-top: -40px;">
		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-xl-6 offset-xl-3">

					<!--------------------------------------------------------------------------- Welkom ------------------------------------------------->
					<div class="card">

						<div class="card-header header-elements-inline">
							<h4 class="card-title">Welkom bij Devis Online</h4>
						</div>

						<div class="card-body">
							<strong class="font-weight-bold">{$werkgever.bedrijfsnaam}</strong>
							maakt gebruik van Devis Online, dé applicatie om al uw personeelszaken te regelen. Devis Online bespaart u veel kostbare tijd door u
							administratieve taken uit handen te nemen. De applicatie werkt in alle moderne browsers en zolang u met het internet verbonden bent kunt u Devis Online overal gebruiken.
							<br/><br/>

							Voordat u aan de slag kunt hebben wij nog een paar zaken van u nodig. U dient onze
							<strong class="font-weight-bold">algemene voorwaarden</strong> te accepteren en de <strong class="font-weight-bold">overeenkomst van opdracht</strong> te ondertekenen.

						</div>
					</div>

					<!--------------------------------------------------------------------------- Algemene voorwaarden ------------------------------------------------->
					<div class="card">

						<div class="card-body">

							<div class="media">
                                {if !$accepted_av}<span style="font-size: 26px" class="mr-3 number number-av"> 1.</span>{/if}
								<i class="far fa-check-circle check-av fa-2x mr-3 mt-1" {if !$accepted_av}style="display: none;"{/if}></i>

                                {if !$accepted_av}
								<button type="button" onclick="acceptAV()" name="set" class="btn btn-sm btn-success btn-av mt-1 mr-3" style="width: 180px">
									<i class="icon-check mr-1"></i>Akkoord voorwaarden
								</button>
                                {/if}

								<div class="media-body mt-2">
									Ik heb de <a href="javascript:void(0)" data-target="#modal_av" data-toggle="modal">algemene voorwaarden</a> gelezen en ik ga akkoord met de voorwaarden.
								</div>

							</div>

						</div>

					</div>

					<!--------------------------------------------------------------------------- Inlenersbeloning ------------------------------------------------->
					<div class="card">

						<div class="card-body">

							<div class="media">
								<span style="font-size: 26px" class="mr-3 number number-av"> 2. </span>

								<div class="media-body mt-1">
									<h4 class="card-title">Inlenersbeloning</h4>

									<table style="margin-top: 25px">
										<tr>
											<td class="pr-4 pt-1">CAO</td>
											<td style="width: 400px">
												<select name="cao_id" class="form-control select-search">
													<option value="">Selecteer een CAO</option>
                                                    {foreach $caos as $cao}
														<option {if isset($cao_id_selected) && $cao_id_selected == $cao.id} selected{/if} value="{$cao.id}">{$cao.name} (start: {$cao.duration_start|date_format: '%d-%m-%Y'})</option>
                                                    {/foreach}
												</select>
											</td>
										</tr>
										<tr>
											<td class="pr-4 pt-1">Branche</td>
											<td class="pt-2">
												<input name="branche" value="" type="text" class="form-control" />
											</td>
										</tr>
										<tr>
											<td class="pr-4 pt-1">Uren werkweek</td>
											<td class="pt-2">
												<input name="uren_werkweek" value="" type="text" class="form-control" style="width: 70px" />
											</td>
										</tr>
										<tr>
											<td class="pr-4 pt-1">Maakt u gebruik van loonschalen</td>
											<td class="pt-2">
												<select class="form-control">
													<option>Niet van toepassing</option>
													<option>Ja</option>
													<option>Nee</option>
												</select>
											</td>
										</tr>
										<tr>
											<td class="pr-4 pt-1">Is er er sprake van ADV/ATV dagen?</td>
											<td class="pt-2">
												<select class="form-control">
													<option></option>
													<option>Ja</option>
													<option>Nee</option>
												</select>
											</td>
										</tr>
										<tr>
											<td class="pr-4 pt-1">Aantal ADV/ATV dagen</td>
											<td class="pt-2">
												<input name="atv_dagen" value="" type="text" class="form-control" style="width: 70px" />
											</td>
										</tr>
									</table>

								</div>

							</div>

						</div>

					</div>

					<!--------------------------------------------------------------------------- Te tekenen overeenkomsten ------------------------------------------------->
                    {if isset($document_details)}
                        {foreach $document_details as $d}
							<div class="card">

								<div class="card-body">

									<div class="media step-{$d@iteration +2}">
                                        {if !$d.signed}
											<span style="font-size: 26px" class="mr-3 number"> {$d@iteration +2}.</span>
                                        {/if}
										<i class="far fa-check-circle  fa-2x mr-3 mt-1" {if !$d.signed}style="display: none;"{/if}></i>

                                        {if !$d.signed}
											<button type="button" onclick="modalSignDocumentWelkom( {$d.document_id}, '{$d@iteration +2}' )" class="btn btn-sm btn-success mt-1 mr-3" style="width: 180px">
												<i class="icon-pencil5 mr-2"></i>overeenkomst tekenen
											</button>
                                        {/if}

										<div class="media-body mt-2">
											<strong class="font-weight-bold">{$d.template_name|ucfirst}</strong> ondertekenen
										</div>

									</div>

								</div>
							</div>
                        {/foreach}
                    {/if}

					<a href="dashboard/inlener" class="btn btn-success btn-labeled btn-labeled-left btn-lg btn-start" style="display: none"><b><i class="icon-file-check"></i></b>We hebben alles wat we nodig hebben. Aan de slag!</a>

				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


	<!--------------------------------------------------------------------------- Algemene voorwaarden ------------------------------------------------->
	<div id="modal_av" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header pl-4 pr-4">
					<h5 class="modal-title">Algemene voorwaarden {$werkgever.bedrijfsnaam} <span class="var-action"></span></h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body pl-4 pr-4">
					{$av}
				</div>
				<div class="modal-footer pl-4 pr-4">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">
						<i class="icon-cross mr-1"></i> Sluiten
					</button>
				</div>
			</div>
		</div>
	</div>

{/block}