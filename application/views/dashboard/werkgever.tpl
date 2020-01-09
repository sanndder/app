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

				<div class="col-md-3">

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
										<div class="font-weight-semibold">
											<a href="{$base_url}/documenten/pdf/av" target="_blank">
												algemenevoorwaarden.pdf
											</a>
										</div>
										<ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
											<li class="list-inline-item">01-01-2020</li>
											<li class="list-inline-item">0.01Mb</a></li>
										</ul>
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
										<div class="mr-3 align-self-center">
											<i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/grekening.pdf" target="_blank">
													bankverklaring grekening.pdf
												</a>
											</div>
											<ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
												<li class="list-inline-item">09-01-2020</li>
												<li class="list-inline-item">0.15Mb</a></li>
											</ul>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/grekening.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-3 align-self-center">
											<i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/kvkuitzenden.pdf" target="_blank">
													Uittreksel KvK.pdf
												</a>
											</div>
											<ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
												<li class="list-inline-item">09-01-2020</li>
												<li class="list-inline-item">0.15Mb</a></li>
											</ul>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/kvkuitzenden.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
	                                <li class="media">
		                                <div class="mr-3 align-self-center">
			                                <i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
		                                </div>

		                                <div class="media-body">
			                                <div class="font-weight-semibold">
				                                <a href="{$base_url}/recources/docs/nbbu.pdf" target="_blank">
					                                NBBU lidmaatschap.pdf
				                                </a>
			                                </div>
			                                <ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
				                                <li class="list-inline-item">09-01-2020</li>
				                                <li class="list-inline-item">0.15Mb</a></li>
			                                </ul>
		                                </div>

		                                <div class="ml-3">
			                                <div class="list-icons">
				                                <a href="{$base_url}/recources/docs/nbbu.pdf" class="list-icons-item" target="_blank">
					                                <i class="icon-download"></i></a>
			                                </div>
		                                </div>
	                                </li>
	                                <li class="media">
		                                <div class="mr-3 align-self-center">
			                                <i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
		                                </div>

		                                <div class="media-body">
			                                <div class="font-weight-semibold">
				                                <a href="{$base_url}/recources/docs/nen.pdf" target="_blank">
					                                NEN certtificaat.pdf
				                                </a>
			                                </div>
			                                <ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
				                                <li class="list-inline-item">09-01-2020</li>
				                                <li class="list-inline-item">0.15Mb</a></li>
			                                </ul>
		                                </div>

		                                <div class="ml-3">
			                                <div class="list-icons">
				                                <a href="{$base_url}/recources/docs/nen.pdf" class="list-icons-item" target="_blank">
					                                <i class="icon-download"></i></a>
			                                </div>
		                                </div>
	                                </li>
									<li class="media">
										<div class="mr-3 align-self-center">
											<i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/betalinguitzenden.pdf" target="_blank">
													verklaring betalingsgedrag.pdf
												</a>
											</div>
											<ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
												<li class="list-inline-item">09-01-2020</li>
												<li class="list-inline-item">0.15Mb</a></li>
											</ul>
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
										<div class="mr-3 align-self-center">
											<i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/kvkbemiddeling.pdf" target="_blank">
													Uittreksel KvK.pdf
												</a>
											</div>
											<ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
												<li class="list-inline-item">09-01-2020</li>
												<li class="list-inline-item">0.15Mb</a></li>
											</ul>
										</div>

										<div class="ml-3">
											<div class="list-icons">
												<a href="{$base_url}/recources/docs/kvkbemiddeling.pdf" class="list-icons-item" target="_blank">
													<i class="icon-download"></i></a>
											</div>
										</div>
									</li>
									<li class="media">
										<div class="mr-3 align-self-center">
											<i class="icon-file-pdf icon-2x text-warning-300 top-0"></i>
										</div>

										<div class="media-body">
											<div class="font-weight-semibold">
												<a href="{$base_url}/recources/docs/betalingbemiddeling.pdf" target="_blank">
													verklaring betalingsgedrag.pdf
												</a>
											</div>
											<ul class="list-inline list-inline-dotted list-inline-condensed font-size-sm text-muted">
												<li class="list-inline-item">09-01-2020</li>
												<li class="list-inline-item">0.15Mb</a></li>
											</ul>
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

				</div>


				<!--------------------------------------------------------------------------- left ------------------------------------------------->
				<div class="col-md-6">

					<!-- Basic card -->
					<div class="card">

                        {* bovenste rij is aantalen crm *}
						<div class="card-body d-sm-flex align-items-sm-center justify-content-sm-between flex-sm-wrap">

                            {* uitzenders *}
							<div class="d-flex align-items-center mb-3 mb-sm-0">
								<div class="rounded-circle bg-teal-400">
									<i class="icon-office icon-xl text-white p-2"></i>
								</div>
								<div class="ml-3">
									<h5 class="font-weight-semibold mb-0">{$count_uitzenders}</h5>
									<span class="text-muted text-uppercase">Uitzenders</span>
								</div>
							</div>

                            {* inleners *}
							<div class="d-flex align-items-center mb-3 mb-sm-0">
								<div class="rounded-circle bg-warning-400">
									<i class="icon-user-tie icon-xl text-white p-2"></i>
								</div>
								<div class="ml-3">
									<h5 class="font-weight-semibold mb-0">{$count_inleners}</h5>
									<span class="text-muted text-uppercase">Inleners</span>
								</div>
							</div>

                            {* werknemers of zzp'ers *}
							<div class="d-flex align-items-center mb-3 mb-sm-0">
								<div class="rounded-circle bg-blue">
									<i class="icon-user icon-xl text-white p-2"></i>
								</div>
								<div class="ml-3">
									<h5 class="font-weight-semibold mb-0">{$count_werknemers}</h5>
									<span class="text-muted text-uppercase">Werknemers</span>
								</div>
							</div>

						</div>

						<div class="table-responsive">
							<table class="table text-nowrap">
								<tbody>

                                    {* nieuwe uitzenders *}
                                    {if count($uitzenders) > 0  }
										<tr class="table-active">
											<td style="max-width:200px ">Nieuwe uitzenders</td>
											<td colspan="3" class="text-right">
												<a href="{$base_url}/crm/uitzenders/">
													<i class="icon-list-unordered"></i> alle uitzenders
												</a>
											</td>
										</tr>
                                        {foreach $uitzenders as $u}
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<i class="icon-office icon-lg text-teal-400"></i>
														</div>
														<div>
															<a href="{$base_url}/crm/uitzenders/dossier/overzicht/{$u.uitzender_id}" class="text-default font-weight-semibold">{$u.bedrijfsnaam}</a>
															<div class="text-muted font-size-sm">
                                                                {$u.timestamp|date_format: '%d-%m-%Y om %R'}
															</div>
														</div>
													</div>
												</td>
												<td></td>
												<td></td>
												<td class="text-center">

												</td>
											</tr>
                                        {/foreach}
                                    {/if}
                                    {* nieuwe inleners *}
                                    {if count($inleners) > 0}
										<tr class="table-active">
											<td style="max-width:200px ">Nieuwe inleners</td>
											<td colspan="3" class="text-right">
												<a href="{$base_url}/crm/inleners/">
													<i class="icon-list-unordered"></i> alle inleners
												</a>
											</td>
										</tr>
                                        {foreach $inleners as $i}
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<i class="icon-user-tie icon-lg text-warning-400"></i>
														</div>
														<div>
															<a href="{$base_url}/crm/inleners/dossier/overzicht/{$i.inlener_id}" class="text-default font-weight-semibold">{$i.bedrijfsnaam}</a>
															<div class="text-muted font-size-sm">
                                                                {$i.timestamp|date_format: '%d-%m-%Y om %R'}
															</div>
														</div>
													</div>
												</td>
												<td>
													<div class="font-weight-bolder">{$i.uitzender}</div>
													<div class="text-muted">uitzender</div>
												</td>
												<td></td>
												<td class="text-center">

												</td>
											</tr>
                                        {/foreach}
                                    {/if}
                                    {* nieuwe kredietaanrvagen *}
                                    {if count($kredietaanvragen) > 0}
										<tr class="table-active">
											<td style="max-width:200px ">Kredietaanrvagen</td>
											<td colspan="3" class="text-right">
												<a href="{$base_url}/crm/inleners/">
													<i class="icon-list-unordered"></i> alle kredietaanvragen
												</a>
											</td>
										</tr>
                                        {foreach $kredietaanvragen as $k}
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<i class="mi-euro-symbol text-warning-400" style="font-size: 24px;"></i>
														</div>
														<div>
															<a href="{$base_url}/crm/inleners/dossier/kredietoverzicht/k{$k.id}" class="text-default font-weight-semibold">{$k.bedrijfsnaam}</a>
															<div class="text-muted font-size-sm">
                                                                {$k.timestamp|date_format: '%d-%m-%Y om %R'}
															</div>
														</div>
													</div>
												</td>
												<td>
													<div class="font-weight-bolder">{$k.uitzender}</div>
													<div class="text-muted">uitzender</div>
												</td>
												<td></td>
												<td class="text-center">

												</td>
											</tr>
                                        {/foreach}
                                    {/if}

								</tbody>
							</table>
						</div>
					</div>


				</div><!-- /col -->
				<!--------------------------------------------------------------------------- /left ------------------------------------------------->
			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}