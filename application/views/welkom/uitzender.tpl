{extends file='../layout.tpl'}
{block "title"}Welkom{/block}
{block "header-icon"}{/block}
{block "header-title"}{/block}
{assign "hide_menu" "true"}

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
							<strong class="font-weight-bold">algemene voorwaarden</strong> te accepteren en de <strong class="font-weight-bold">samenwerkingsovereenkomst</strong> te ondertekenen. Daarnaast dient u
							in het kader van de AVG dient ook onze <strong class="font-weight-bold">verwerkinsovereenkomst</strong> te tekenen.

						</div>
					</div>

					<!--------------------------------------------------------------------------- Algemene voorwaarden ------------------------------------------------->
					<div class="card">

						<div class="card-body">

							<div class="media">
                                {if !$accepted_av}<span style="font-size: 26px" class="mr-3 number-av"> 1.</span>{/if}
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

					<!--------------------------------------------------------------------------- Samenwerkingsovereenkomst ------------------------------------------------->
					<div class="card">

						<div class="card-body">

							<div class="media">
								<span style="font-size: 26px" class="mr-3"> 2.</span>
								<i class="far fa-check-circle check-samenwerkingsovereenkomst fa-2x mr-3 mt-1" {if !$samenwerkingsovereenkomst.signed}style="display: none;"{/if}></i>

								<button type="button" onclick="modalSignDocument( {$samenwerkingsovereenkomst.document_id} )" class="btn btn-sm btn-success mt-1 mr-3" style="width: 180px">
									<i class="icon-pencil5 mr-2"></i>overeenkomst tekenen
								</button>
								<div class="media-body mt-2">
									<strong class="font-weight-bold">Samenwerkingsovereenkomst</strong> ondertekenen
								</div>

							</div>

						</div>
					</div>

					<!--------------------------------------------------------------------------- Verwerkinsovereenkomst  ------------------------------------------------->
					<div class="card">

						<div class="card-body">

							<div class="media">
								<span style="font-size: 26px" class="mr-3"> 3.</span>
								<i class="far fa-check-circle check-verwerkinsovereenkomst fa-2x mr-3 mt-1" {if !$samenwerkingsovereenkomst.signed}style="display: none;"{/if}></i>

								<button type="button" class="btn btn-sm btn-success mt-1 mr-3" style="width: 180px">
									<i class="icon-pencil5 mr-2"></i>overeenkomst tekenen
								</button>
								<div class="media-body mt-2">
									Omdat wij werken met persoonsgegevens dient u onze <strong class="font-weight-bold">verwerkingsovereenkomst</strong> te tekenen.
								</div>

							</div>

						</div>
					</div>


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