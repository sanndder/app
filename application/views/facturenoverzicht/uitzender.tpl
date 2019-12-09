{extends file='../layout.tpl'}
{block "title"}Dashboard{/block}
{block "header-icon"}mi-euro-symbol{/block}
{block "header-title"}Facturen & Marge{/block}
{assign "ckeditor" "true"}

{block "content"}


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="card">


				<div class="card-header header-elements-inline">
					<h5 class="card-title">Recente facturen</h5>
				</div>

				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th style="width: 25px;">Jaar</th>
								<th style="width: 25px;">Periode</th>
								<th style="width: 25px;">Nr.</th>
								<th style="width: 205px;">Factuur</th>
								<th></th>
								<th style="width: 205px;">Kosten</th>
								<th></th>
								<th style="width: 205px;">Marge</th>
								<th></th>
								<th style="width: 25px"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>2019</td>
								<td>30</td>
								<td>45669</td>
								<td>factuur_2019_30.pdf</td>
								<td>€ 250,59</td>
								<td>kosten_2019_30.pdf</td>
								<td>€ 150,59</td>
								<td>marge_2019_30.pdf</td>
								<td>€ 100,00</td>
								<td>
									<ul class="list-inline mb-0 mt-2 mt-sm-0">
										<li class="list-inline-item dropdown">
											<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

											<div class="dropdown-menu dropdown-menu-right">
												<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
												<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
												<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
											</div>
										</li>
									</ul>
								</td>
							</tr>
							<tr>
								<td>2019</td>
								<td>29</td>
								<td>45659</td>
								<td>factuur_2019_29.pdf</td>
								<td>€ 250,59</td>
								<td>kosten_2019_29.pdf</td>
								<td>€ 150,59</td>
								<td>marge_2019_29.pdf</td>
								<td>€ 100,00</td>
								<td>
									<ul class="list-inline mb-0 mt-2 mt-sm-0">
										<li class="list-inline-item dropdown">
											<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

											<div class="dropdown-menu dropdown-menu-right">
												<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
												<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
												<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
											</div>
										</li>
									</ul>
								</td>
							</tr>
							<tr>
								<td>2019</td>
								<td>28</td>
								<td>45449</td>
								<td>factuur_2019_28.pdf</td>
								<td>€ 250,59</td>
								<td>kosten_2019_28.pdf</td>
								<td>€ 150,59</td>
								<td>marge_2019_28.pdf</td>
								<td>€ 100,00</td>
								<td>
									<ul class="list-inline mb-0 mt-2 mt-sm-0">
										<li class="list-inline-item dropdown">
											<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

											<div class="dropdown-menu dropdown-menu-right">
												<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
												<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
												<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
											</div>
										</li>
									</ul>
								</td>
							</tr>
							<tr>
								<td>2019</td>
								<td>27</td>
								<td>45379</td>
								<td>factuur_2019_27.pdf</td>
								<td>€ 250,59</td>
								<td>kosten_2019_27.pdf</td>
								<td>€ 150,59</td>
								<td>marge_2019_27.pdf</td>
								<td>€ 100,00</td>
								<td>
									<ul class="list-inline mb-0 mt-2 mt-sm-0">
										<li class="list-inline-item dropdown">
											<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

											<div class="dropdown-menu dropdown-menu-right">
												<a href="javascript:void()" class="dropdown-item"><i class="icon-file-eye"></i> Details</a>
												<a href="javascript:void()" class="dropdown-item"><i class="icon-file-download"></i> Download</a>
												<a href="javascript:void()" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
											</div>
										</li>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
				</div>


			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}