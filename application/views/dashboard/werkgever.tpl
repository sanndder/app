{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-home2{/block}
{block "header-title"}Dashboard{/block}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<!--------------------------------------------------------------------------- left ------------------------------------------------->
			<div class="row">
				<div class="col-md-9">

					<!-- Basic card -->
					<div class="card">
						<div class="card-body">

							<fieldset class="mb-0 mt-0">
								<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Omzet en marge</legend>
							</fieldset>

							<div class="row">
								<div class="col-md-12">
									<img src="recources/img/bar.png" style="width: 100%">
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
									<img src="recources/img/uren.png" style="width: 100%">
								</div>
							</div>
						</div><!-- /card body -->
					</div><!-- /basic card -->

				</div><!-- /col -->
			    <!--------------------------------------------------------------------------- /left ------------------------------------------------->


			    <!--------------------------------------------------------------------------- right ------------------------------------------------->
				<div class="col-md-3">

					<!----------------- Documenten --------------------->
					<div class="card">
						<div class="card-header bg-transparent header-elements-inline">
							<span class="card-title font-weight-semibold">Documenten Abering</span>
							<div class="header-elements">
								<div class="list-icons">
									<a class="list-icons-item" data-action="collapse"></a>
								</div>
							</div>
						</div>

						<div class="card-body">
							<ul class="media-list">

								<li class="media">
									<div class="mr-3 align-self-center">
										<i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
									</div>

									<div class="media-body">
										<div class="font-weight-semibold">uittreksel_kvk_abering.pdf</div>
										<ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
											<li class="list-inline-item">15-10-2019</li>
											<li class="list-inline-item">0.3Mb</a></li>
										</ul>
									</div>

									<div class="ml-3">
										<div class="list-icons">
											<a href="javascript:void(0)" class="list-icons-item">
												<i class="icon-download"></i></a>
										</div>
									</div>
								</li>

								<li class="media">
									<div class="mr-3 align-self-center">
										<i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
									</div>

									<div class="media-body">
										<div class="font-weight-semibold">verklaring_betaalgedrag.pdf</div>
										<ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
											<li class="list-inline-item">27-11-2019</li>
											<li class="list-inline-item">0.15Mb</a></li>
										</ul>
									</div>

									<div class="ml-3">
										<div class="list-icons">
											<a href="javascript:void(0)" class="list-icons-item">
												<i class="icon-download"></i></a>
										</div>
									</div>
								</li>

								<li class="media">
									<div class="mr-3 align-self-center">
										<i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
									</div>

									<div class="media-body">
										<div class="font-weight-semibold">nen_certificaat.pdf</div>
										<ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
											<li class="list-inline-item">12-11-2019</li>
											<li class="list-inline-item">0.27Mb</a></li>
										</ul>
									</div>

									<div class="ml-3">
										<div class="list-icons">
											<a href="javascript:void(0)" class="list-icons-item">
												<i class="icon-download"></i></a>
										</div>
									</div>
								</li>

								<li class="media">
									<div class="mr-3 align-self-center">
										<i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
									</div>

									<div class="media-body">
										<div class="font-weight-semibold">overeenkomst_g_rekening.pdf</div>
										<ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
											<li class="list-inline-item">01-12-2019</li>
											<li class="list-inline-item">0.13Mb</a></li>
										</ul>
									</div>

									<div class="ml-3">
										<div class="list-icons">
											<a href="javascript:void(0)" class="list-icons-item">
												<i class="icon-download"></i></a>
										</div>
									</div>
								</li>

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
						<div class="card-body border-top-teal">
							<div class="list-feed">
								<div class="list-feed-item">
									<div class="text-muted">Dec 3, 17:47</div>
									Werknemer
									<a href="javascript:void(0)">B. Groothuis</a>
									aangemeld
								</div>

								<div class="list-feed-item">
									<div class="text-muted">Dec 2, 10:25</div>
									Factuur
									<a href="javascript:void(0)">#1256</a>
									gegenereerd door
									<a href="javascript:void(0)">Arnold Asbestverwijdering B.V.</a>
								</div>

								<div class="list-feed-item">
									<div class="text-muted">Dec 2, 09:37</div>
									Werknemer
									<a href="javascript:void(0)">W.H. Nijenhuis</a>
									ziekgemeld
								</div>

								<div class="list-feed-item">
									<div class="text-muted">Nov 30, 15:28</div>
									Factuur
									<a href="javascript:void(0)">#1201</a>
									gegenereerd door
									<a href="javascript:void(0)">CleanServices B.V.</a>
								</div>

								<div class="list-feed-item">
									<div class="text-muted">Nov 29, 11:32</div>
									Inlener
									<a href="javascript:void(0)">CleanServices B.V.</a>
									goedgekeurd
								</div>

								<div class="list-feed-item">
									<div class="text-muted">Nov 29, 08:17</div>
									Arbeidscontract
									<a href="javascript:void(0)">L. Boom</a>
									ondertekend
								</div>
							</div>
						</div>
					</div>


				</div><!-- /col -->
			</div><!-- /row -->
			<!--------------------------------------------------------------------------- /right ------------------------------------------------->


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}