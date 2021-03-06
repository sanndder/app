{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-home2{/block}
{block "header-title"}Dashboard{/block}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">



			<div class="row">
				<!--------------------------------------------------------------------------- left ------------------------------------------------->
				<div class="col-md-3">

					<!----------------- Documenten --------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Documenten FlexxOffice</span>
							<div class="header-elements">
								<div class="list-icons">
									<a class="list-icons-item" data-action="collapse"></a>
								</div>
							</div>
						</div>

						<div class="card-body">

							<ul class="media-list">

								<li class="media">
									<div class="mr-2 align-self-center">
										<img src="recources/img/icons/pdf.svg" style="height: 25px">
									</div>

									<div class="media-body">
										<div class="font-weight-semibold">
											<a href="{$base_url}/documenten/pdf/av" target="_blank">
												Algemene voorwaarden
											</a>
										</div>
									</div>

									<div class="ml-3">
										<div class="list-icons">
											<a href="{$base_url}/documenten/pdf/av/download" class="list-icons-item" target="_blank">
												<i class="icon-download"></i></a>
										</div>
									</div>
								</li>

                                {if $werkgever_type == 'uitzenden'}
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/g-rekening.pdf" target="_blank">
													verklaring g-rekening
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/g-rekening.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/kvkuitzenden.pdf" target="_blank">
													Uittreksel KvK
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/kvkuitzenden.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/nbbu.pdf" target="_blank">
													NBBU lidmaatschap
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/nbbu.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/nen.pdf" target="_blank">
													NEN certtificaat
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/nen.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/betalinguitzenden.pdf" target="_blank">
													verklaring betalingsgedrag
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/betalinguitzenden.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
                                {/if}
                                {if $werkgever_type == 'bemiddeling'}
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/kvkbemiddeling.pdf" target="_blank">
													Uittreksel KvK
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/kvkbemiddeling.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-2 align-self-center">
											<img src="recources/img/icons/pdf.svg" style="height: 25px">
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/betalingbemiddeling.pdf" target="_blank">
													verklaring betalingsgedrag
												</a>
											</div>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/betalingbemiddeling.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
                                {/if}
							</ul>
						</div>
					</div>

					<!----------------- Log  --------------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Laatste gebeurtenissen</span>
							<div class="header-elements">
								<div class="list-icons">
									<a class="list-icons-item" data-action="collapse"></a>
								</div>
							</div>
						</div>
						<div class="card-body">

						</div>
					</div>


				</div>
			    <!--------------------------------------------------------------------------- /left ------------------------------------------------->


			    <!--------------------------------------------------------------------------- right ------------------------------------------------->
				<div class="col-md-9">

					<!-- Basic card -->
					<div class="card">
						<div class="card-body">

							<fieldset class="mb-0 mt-0">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Omzet en marge</legend>
							</fieldset>

							<div class="row">
								<div class="col-md-12">
									<i>Geen data beschikbaar</i>
								</div>
							</div>
						</div><!-- /card body -->
					</div><!-- /basic card -->


					<!-- Basic card -->
					<div class="card">
						<div class="card-body">

							<fieldset class="mb-0 mt-0">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Gewerkte uren</legend>
							</fieldset>

							<div class="row">
								<div class="col-md-12">
									<i>Geen data beschikbaar</i>
								</div>
							</div>
						</div><!-- /card body -->
					</div><!-- /basic card -->

				</div>
				<!-- /col -->
			</div><!-- /row -->
			<!--------------------------------------------------------------------------- /right ------------------------------------------------->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}