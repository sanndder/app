{extends file='../layout.tpl'}
{block "title"}Ureninvoer{/block}
{block "header-icon"}mi-timer{/block}
{block "header-title"}Ureninvoer{/block}

{block "content"}
	<script src="recources/js/verloning_invoer/main.js?{$time}"></script>
	<script src="recources/js/verloning_invoer/templates.js?{$time}"></script>
	<script>
        {literal}

        $( document ).ready( function() {
            //
            $( document ).on( 'click', '.vi-list-inleners .vi-list-item', function() {
                $( '.vi-list-inleners li' ).removeClass( 'vi-list-item-active' );
                $( this ).addClass( 'vi-list-item-active' );

                $( '.vi-list-werknemers' ).hide();
                $( '.vi-tabs' ).hide();

                id = $( this ).data( 'id' );

                $( '.list-' + id ).show();
            } );

            $( '.vi-list-werknemers .vi-list-item' ).on( 'click', function() {
                $( '.vi-list-werknemers li' ).removeClass( 'vi-list-item-active' );
                $( this ).addClass( 'vi-list-item-active' );
                $( '.vi-tabs' ).show();

            } );
        } );

        {/literal}
	</script>
	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="navbar navbar-expand-lg navbar-light navbar-component rounded">
				<div class="text-center d-lg-none w-100">
					<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse"
					        data-target="#navbar-filter">
						<i class="icon-unfold mr-2"></i>
						Tijdvakinstellingen
					</button>
				</div>

				<div class="navbar-collapse collapse" id="navbar-filter">
					<span class="navbar-text font-weight-semibold mr-3">
						Tijdvak:
					</span>

					<ul class="navbar-nav flex-wrap">
						<li class="nav-item dropdown">
							<a href="javascript:void(0)" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
								Week
							</a>

							<div class="dropdown-menu">
								<a href="javascript:void(0)" class="dropdown-item">Maand</a>
								<a href="javascript:void(0)" class="dropdown-item">4 weken</a>
							</div>
						</li>

						<li class="nav-item dropdown">
							<a href="javascript:void(0)" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown"
							   aria-expanded="false">
								31
							</a>

							<div class="dropdown-menu">
								<a href="javascript:void(0)" class="dropdown-item">30</a>
								<a href="javascript:void(0)" class="dropdown-item">29</a>
								<a href="javascript:void(0)" class="dropdown-item">28</a>
								<a href="javascript:void(0)" class="dropdown-item">27</a>
							</div>
						</li>

					</ul>


				</div>
			</div>

			<!-- Basic card -->
			<div class="card">
				<!-- card  body-->
				<div class="card-body p-0">


					<div class="row">
						<div class="col-md-2 p-0 append-button">

                            {*
														<ul class="vi-list mt-2 vi-list-inleners">
															<li class="vi-list-header">Inleners</li>
														</ul>*}


							<ul class="vi-list mt-2 vi-list-inleners">

								<li class="vi-list-item" data-id="1"><span>1001Tafelkleden.com</span></li>
								<li class="vi-list-item" data-id="2"><span>4you Personeelsdiensten</span></li>
								<li class="vi-list-item" data-id="3">
									<span>Aardappelgroothandel Jansen-Dongen B.V.</span></li>
								<li class="vi-list-item" data-id="4"><span>Inhoudingen</span></li>
							</ul>


						</div><!-- /col -->

						<div class="col-xxl-2 col-lg-3 p-0">

							<div class="ajax-wait mt-4 mb-4" style="text-align: center; display: none"><i
										class="icon-spinner2 spinner mr-1"></i></div>

							<ul class="vi-list mt-2 vi-list-werknemers list-1" style="display: none">
								<li class="vi-list-header">Werknemers</li>
								<li class="vi-list-item"><span>Baggermans, Berend (B.) (14002)</span></li>
								<li class="vi-list-item"><span>Bruijn de, Sierd (S) (14003)</li>
								<li class="vi-list-item"><span>Otten, Wierd (W.J.B.W) (15001)</li>
							</ul>

							<ul class="vi-list mt-2 vi-list-werknemers list-2" style="display: none">
								<li class="vi-list-header">Werknemers</li>
								<li class="vi-list-item"><span>Baggermans, Berend (B.) (14002)</span></li>
								<li class="vi-list-item"><span>Beers, Ivo (I.J.) (14005)</li>
								<li class="vi-list-item"><span>Otten, Wierd (W.J.B.W) (15001)</li>
								<li class="vi-list-item"><span>Wijnen, Marcel (I.L.M.) (15003)</li>
							</ul>

							<ul class="vi-list mt-2 vi-list-werknemers list-3" style="display: none">
								<li class="vi-list-header">Werknemers</li>
								<li class="vi-list-item"><span>Baggermans, Berend (B.) (14002)</span></li>
								<li class="vi-list-item"><span>Beers, Ivo (I.J.) (14005)</li>
								<li class="vi-list-item"><span>Bruijn de, Sierd (S) (14003)</li>
								<li class="vi-list-item"><span>Otten, Wierd (W.J.B.W) (15001)</li>
								<li class="vi-list-item"><span>Wijnen, Marcel (I.L.M.) (15003)</li>
								<li class="vi-list-item"><span>Zwiers, Karel (K.I.M.) (15010)</li>
							</ul>


						</div><!-- /col -->
						<div class="col-xxl-8 col-lg-7 p-0 vi-tabs" style="display: none">

							<ul class="nav nav-tabs nav-tabs-bottom ">
								<li class="nav-item"><a href="#bottom-tab1" class="nav-link active show"
								                        data-toggle="tab">Uren</a></li>
								<li class="nav-item"><a href="#bottom-tab2" class="nav-link" data-toggle="tab">Kilometers</a>
								</li>
								<li class="nav-item"><a href="#bottom-tab3" class="nav-link" data-toggle="tab">Vergoedingen</a>
								</li>
								<li class="nav-item"><a href="#bottom-tab4" class="nav-link" data-toggle="tab">Reserveringen</a>
								</li>
								<li class="nav-item"><a href="#bottom-tab5" class="nav-link" data-toggle="tab">Inhoudingen</a>
								</li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane fade active show" id="bottom-tab1">

								</div>

								<div class="tab-pane fade" id="bottom-tab2">

								</div>

								<div class="tab-pane fade" id="bottom-tab3">

								</div>

								<div class="tab-pane fade" id="bottom-tab4">

								</div>

								<div class="tab-pane fade" id="bottom-tab5">

								</div>
							</div>

						</div><!-- /col -->
					</div><!-- /row -->

				</div><!-- /card body-->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


{/block}