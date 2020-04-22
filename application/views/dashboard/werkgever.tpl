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

				<!--------------------------------------------------------------------------- aantallen ------------------------------------------------->
				<div class="card">
					<div class="card-body">
                        {* uitzenders *}
						<div class="d-flex align-items-center mb-3 mb-sm-0">
							<a href="crm/uitzenders">
								<div class="rounded-circle bg-teal-400">
									<i class="icon-office icon-xl text-white p-2"></i>
								</div>
							</a>
							<a href="crm/uitzenders" class="text-default">
								<div class="ml-3">
									<h5 class="font-weight-semibold mb-0">{$count_uitzenders}</h5>
									<span class="text-muted text-uppercase">Uitzenders</span>
								</div>
							</a>
						</div>

                        {* inleners *}
						<div class="d-flex align-items-center mb-3 mb-sm-0 mt-3">
							<a href="crm/inleners" class="text-default">
								<div class="rounded-circle bg-warning-400">
									<i class="icon-user-tie icon-xl text-white p-2"></i>
								</div>
							</a>
							<a href="crm/inleners" class="text-default">
								<div class="ml-3">
									<h5 class="font-weight-semibold mb-0">{$count_inleners}</h5>
									<span class="text-muted text-uppercase">Inleners</span>
								</div>
							</a>
						</div>

                        {* werknemers of zzp'ers *}
						{if $werkgever_type == 'uitzenden'}
						<div class="d-flex align-items-center mb-3 mb-sm-0 mt-3">
							<a href="crm/werknemers" class="text-default">
								<div class="rounded-circle bg-blue">
									<i class="icon-user icon-xl text-white p-2"></i>
								</div>
							</a>
							<a href="crm/werknemers" class="text-default">
								<div class="ml-3">
									<h5 class="font-weight-semibold mb-0">{$count_werknemers}</h5>
									<span class="text-muted text-uppercase">Werknemers</span>
								</div>
							</a>
						</div>
						{/if}

                        {if $werkgever_type == 'bemiddeling'}
							<div class="d-flex align-items-center mb-3 mb-sm-0 mt-3">
								<a href="crm/zzp" class="text-default">
									<div class="rounded-circle bg-blue">
										<i class="icon-user icon-xl text-white p-2"></i>
									</div>
								</a>
								<a href="crm/zzp" class="text-default">
									<div class="ml-3">
										<h5 class="font-weight-semibold mb-0">{$count_zzp}</h5>
										<span class="text-muted text-uppercase">ZZp'ers</span>
									</div>
								</a>
							</div>
                        {/if}

					</div>
				</div>

				<!--------------------------------------------------------------------------- aantallen ------------------------------------------------->
				<div class="card">
					<div class="card-body">

						{if $werkgever_type == 'uitzenden'}
							<div class="d-flex align-items-center mb-3 mb-sm-0 mt-1">
								<a href="crm/werknemers/documenten" class="text-default">
									<div class="rounded-circle bg-blue">
										<i class="icon-files-empty icon-xl text-white p-2"></i>
									</div>
								</a>
								<a href="crm/werknemers/documenten" class="text-default">
									<div class="ml-3">
										<h5 class="font-weight-semibold mb-0">Documenten werknemers</h5>
										<span class="text-muted text-uppercase">
											{if $documenten_werknemers_flags == 0}
												geen aandachtspunten
											{else}
												{$documenten_werknemers_flags} documenten wachten op actie
											{/if}

										</span>
									</div>
								</a>
							</div>
						{/if}

					</div>
				</div>

			</div>


			<!--------------------------------------------------------------------------- left ------------------------------------------------->
			<div class="col-md-6">

				<!-- Basic card -->
				<div class="card">

					<ul class="nav nav-tabs nav-tabs-highlight nav-justified mb-0">
						<li class="nav-item">
							<a href="#tab1" class="nav-link active border-left-0 pt-3 pb-3" data-toggle="tab">Nieuwe aanmeldingen</a>
						</li>
						<li class="nav-item">
							<a href="#tab2" class="nav-link  border-right-0 pt-3 pb-3" data-toggle="tab">Overzicht status</a>
						</li>
					</ul>

					<div class="tab-content">
                        {* tab nieuw *}
						<div class="tab-pane fade show active" id="tab1">

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
															<a href="{$base_url}/crm/uitzenders/dossier/overzicht/{$u.uitzender_id}"
															   class="text-default font-weight-semibold">{$u.bedrijfsnaam}</a>
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
															<a href="{$base_url}/crm/inleners/dossier/overzicht/{$i.inlener_id}"
															   class="text-default font-weight-semibold">{$i.bedrijfsnaam}</a>
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
															<i class="mi-euro-symbol text-warning-400"
															   style="font-size: 24px;"></i>
														</div>
														<div>
															<a href="{$base_url}/crm/inleners/dossier/kredietoverzicht/k{$k.id}"
															   class="text-default font-weight-semibold">{$k.bedrijfsnaam}</a>
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
                                    {* nieuwe werknemers *}
                                    {if count($werknemers) > 0}
										<tr class="table-active">
											<td style="max-width:200px ">Nieuwe werknemers</td>
											<td colspan="3" class="text-right">
												<a href="{$base_url}/crm/werknemers/">
													<i class="icon-list-unordered"></i> alle werknemers
												</a>
											</td>
										</tr>
                                        {foreach $werknemers as $w}
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<i class="icon-user icon-lg text-blue"></i>
														</div>
														<div>
															<a href="{$base_url}/crm/werknemers/dossier/overzicht/{$w.werknemer_id}"
															   class="text-default font-weight-semibold">{$w.naam}</a>
															<div class="text-muted font-size-sm">
                                                                {$w.timestamp|date_format: '%d-%m-%Y om %R'}
															</div>
														</div>
													</div>
												</td>
												<td>
													<div class="font-weight-bolder">{$w.uitzender}</div>
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

                        {* tab nieuw *}
						<div class="tab-pane fade" id="tab2">
							<div class="table-responsive">
								<table class="table text-nowrap">
									<tbody>
                                    {* status inleners *}
                                    {if count($inlener_acties) > 0}
										<tr class="table-active">
											<td style="max-width:200px ">Status inleners</td>
											<td colspan="3" class="text-right">
												<a href="{$base_url}/crm/inleners/">
													<i class="icon-list-unordered"></i> alle inleners
												</a>
											</td>
										</tr>
                                        {foreach $inlener_acties as $i}
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="mr-3">
															<i class="icon-user-tie icon-lg text-warning-400"></i>
														</div>
														<div>
															<a href="{$base_url}/crm/inleners/dossier/overzicht/{$i.inlener_id}"
															   class="text-default font-weight-semibold">{$i.bedrijfsnaam}</a>
														</div>
													</div>
												</td>
												<td>
													<ul class="media-list">

														<li class="media">
															<div class="mr-2">
                                                                {if $i.complete != 0}
																	<i class="icon-checkbox-checked2 text-green-700"></i>
                                                                {else}
																	<i class="icon-checkbox-unchecked2 text-grey-300"></i>
                                                                {/if}
															</div>
															<div class="media-body {if $i.complete == 0} text-grey-300{/if}">
																Uitzender heeft alle gegevens ingevuld
															</div>
														</li>

														<li class="media mt-2">
															<div class="mr-2">
                                                                {if $i.user != NULL}
																	<i class="icon-checkbox-checked2 text-green-700"></i>
                                                                {else}
																	<i class="icon-checkbox-unchecked2 text-grey-300"></i>
                                                                {/if}
															</div>
															<div class="media-body {if $i.user == NULL} text-grey-300{/if}">
																User aangemaakt en verzonden naar inlener
															</div>
														</li>

														<li class="media mt-2">
															<div class="mr-2">
                                                                {if $i.user_password != NULL}
																	<i class="icon-checkbox-checked2 text-green-700"></i>
                                                                {else}
																	<i class="icon-checkbox-unchecked2 text-grey-300"></i>
                                                                {/if}
															</div>
															<div class="media-body {if $i.user_password == NULL} text-grey-300{/if}">
																Inlener heeft een wachtwoord aangemaakt
															</div>
														</li>

														<li class="media mt-2">
															<div class="mr-2">
                                                                {if $i.av_id != NULL}
																	<i class="icon-checkbox-checked2 text-green-700"></i>
                                                                {else}
																	<i class="icon-checkbox-unchecked2 text-grey-300"></i>
                                                                {/if}
															</div>
															<div class="media-body {if $i.av_id == NULL} text-grey-300{/if}">
																Algemene voowaarden zijn getekend
															</div>
														</li>

														<li class="media mt-2">
															<div class="mr-2">
                                                                {if $i.overeenkomst_opdracht != NULL}
																	<i class="icon-checkbox-checked2 text-green-700"></i>
                                                                {else}
																	<i class="icon-checkbox-unchecked2 text-grey-300"></i>
                                                                {/if}
															</div>
															<div class="media-body {if $i.overeenkomst_opdracht == NULL} text-grey-300{/if}">
																Overeenkomst van opdracht is getekend
															</div>
														</li>
													</ul>
												</td>
												<td class="text-center">

												</td>
											</tr>
                                        {/foreach}
                                    {/if}
									</tbody>
								</table>

							</div>
						</div>

					</div>
				</div>


			</div><!-- /col -->
			<!--------------------------------------------------------------------------- /left ------------------------------------------------->

			<!--------------------------------------------------------------------------- documenten ------------------------------------------------->
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
										<a href="{$base_url}/documenten/pdf/av/download" class="list-icons-item"
										   target="_blank">
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
											<a href="{$base_url}/recources/docs/g-rekening.pdf"
											   class="list-icons-item"
											   target="_blank">
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
											<a href="{$base_url}/recources/docs/kvkuitzenden.pdf"
											   class="list-icons-item" target="_blank">
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
											<a href="{$base_url}/recources/docs/nbbu.pdf" class="list-icons-item"
											   target="_blank">
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
											<a href="{$base_url}/recources/docs/nen.pdf" class="list-icons-item"
											   target="_blank">
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
											<a href="{$base_url}/recources/docs/betalinguitzenden.pdf"
											   target="_blank">
												verklaring betalingsgedrag
											</a>
										</div>
									</div>

									<div class="ml-3">
										<div class="list-icons">
											<a href="{$base_url}/recources/docs/betalinguitzenden.pdf"
											   class="list-icons-item" target="_blank">
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
											<a href="{$base_url}/recources/docs/kvkbemiddeling.pdf"
											   class="list-icons-item" target="_blank">
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
											<a href="{$base_url}/recources/docs/betalingbemiddeling.pdf"
											   target="_blank">
												verklaring betalingsgedrag
											</a>
										</div>
									</div>

									<div class="ml-3">
										<div class="list-icons">
											<a href="{$base_url}/recources/docs/betalingbemiddeling.pdf"
											   class="list-icons-item" target="_blank">
												<i class="icon-download"></i></a>
										</div>
									</div>
								</li>
                            {/if}
						</ul>
					</div>
				</div>


			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}