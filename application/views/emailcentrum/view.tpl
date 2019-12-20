{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}icon-envelope{/block}
{block "header-title"}Emailcentrum{/block}

{block "content"}

    {include file='emailcentrum/_sidebar.tpl'}

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="flex-fill">

				<div class="row">
					<div class="col-xxl-6 col-xl-8 col-lg-12">

						<!-- Single mail -->
						<div class="card">

							<!-- Action toolbar -->
							<div class="navbar navbar-light bg-light navbar-expand-lg border-bottom-0 py-lg-2 rounded-top">
								<div class="text-center d-lg-none w-100">
									<button type="button" class="navbar-toggler w-100 h-100" data-toggle="collapse" data-target="#inbox-toolbar-toggle-read">
										<i class="icon-circle-down2"></i>
									</button>
								</div>

								<div class="navbar-collapse text-center text-lg-left flex-wrap collapse" id="inbox-toolbar-toggle-read">
									<div class="mt-3 mt-lg-0 mr-lg-3">
										<div class="btn-group">
											<button type="button" class="btn btn-light">
												<i class="icon-pencil"></i>
												<span class="d-none d-lg-inline-block ml-2">Bewerken</span>
											</button>
											<button type="button" class="btn btn-light">
												<i class="icon-envelope"></i>
												<span class="d-none d-lg-inline-block ml-2">Nu verzenden</span>
											</button>
											<button type="button" class="btn btn-light">
												<i class="icon-bin"></i>
												<span class="d-none d-lg-inline-block ml-2">Verwijderen</span>
											</button>
										</div>
									</div>

									<div class="navbar-text ml-lg-auto text-right">
										12-12-2019 om 12:49
										<br/>
										<span class="text-muted">Johan van Abeelen</span>
									</div>

								</div>
							</div>
							<!-- /action toolbar -->


							<!-- Mail details -->
							<div class="card-body">
								<div class="media flex-column flex-md-row">
									<a href="#" class="d-none d-md-block mr-md-3 mb-3 mb-md-0">
										<span class="btn bg-teal-400 btn-icon btn-lg rounded-round">
											<span class="letter-icon">U</span>
										</span>
									</a>

									<div class="media-body">
										<h6 class="mb-0">Aanmeldlink</h6>

										<table>
											<tr>
												<td class="text-black-50 pr-2">verzonden:</td>
												<td>12-12-2019 om 12:51 door Roel</td>
											</tr>
											<tr>
												<td class="text-black-50 pr-2">aan:</td>
												<td>info@4you-pd.nl</td>
											</tr>
											<tr>
												<td class="text-black-50 pr-2">cc:</td>
												<td>administratie@4you-pd.nl</td>
											</tr>
										</table>
									</div>

								</div>
							</div>
							<!-- /mail details -->

							<!-- Attachments -->
							<div class="card-body border-top">

								<ul class="list-inline mb-0">
									<li class="list-inline-item">
										<div class="card bg-light py-1 px-2 mb-0">
											<div class="media my-1">
												<div class="mr-3 align-self-center">
													<i class="icon-file-pdf icon-2x text-danger-400 top-0"></i></div>
												<div class="media-body">
													<div class="font-weight-semibold">new_december_offers.pdf</div>

													<ul class="list-inline list-inline-condensed mb-0">
														<li class="list-inline-item text-muted">174 KB</li>
														<li class="list-inline-item">
															<a href="#">View</a>
														</li>
														<li class="list-inline-item">
															<a href="#">Download</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</li>
									<li class="list-inline-item">
										<div class="card bg-light py-1 px-2 mb-0">
											<div class="media my-1">
												<div class="mr-3 align-self-center">
													<i class="icon-file-pdf icon-2x text-danger-400 top-0"></i></div>
												<div class="media-body">
													<div class="font-weight-semibold">assignment_letter.pdf</div>

													<ul class="list-inline list-inline-condensed mb-0">
														<li class="list-inline-item text-muted">736 KB</li>
														<li class="list-inline-item">
															<a href="#">View</a>
														</li>
														<li class="list-inline-item">
															<a href="#">Download</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</li>
								</ul>
							</div>
							<!-- /attachments -->

							<!-- Mail container -->
							<div class="card-body">
								<div class="overflow-auto mw-100">
									<h4>Welkom bij Abering Uitzend B.V.</h4>

									In deze email vind u uw aanmeldlink voor onze online applicatie
									<b>Devis Online</b>. Nadat u uw gegevens heeft ingevuld zullen wij binnen één werkdag uw gegevens controleren
									en uw account activeren. Daarna kunt u volledig gebruik maken van alle mogelijkheden van
									<b>Devis Online</b>.
									<br/><br/>
									<a href="https://www.devisonline.nl/aanmelden/uitzender?wid=3">https://www.devisonline.nl/aanmelden/uitzender?wid=3</a>
									<br/><br/>Wij hopen op een fijne samenwerking!<br/><br/>Abering Uitzend B.V.'

								</div>
							</div>
							<!-- /mail container -->


						</div>
						<!-- /single mail -->

					</div>
				</div>

			</div>


		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}