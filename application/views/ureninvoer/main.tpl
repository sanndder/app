{extends file='../layout.tpl'}
{block "title"}Ureninvoer{/block}
{block "header-icon"}mi-timer{/block}
{block "header-title"}Ureninvoer{/block}

{block "content"}

	<script>
		{literal}

        $( document ).ready(function() {
           //
			$( '.vi-list-inleners .vi-list-item').on('click', function(){
			    $('.vi-list-inleners li').removeClass('vi-list-item-active');
			   	$(this).addClass('vi-list-item-active');

				$('.vi-list-werknemers').hide();
				$('.vi-tabs').hide();

				id = $(this).data('id');

                $('.list-' + id).show();
			});

            $( '.vi-list-werknemers .vi-list-item').on('click', function(){
                $('.vi-list-werknemers li').removeClass('vi-list-item-active');
                $(this).addClass('vi-list-item-active');
                $('.vi-tabs').show();

            });
        });

		{/literal}
	</script>


	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-- Basic card -->
			<div class="card">
				<!-- card  body-->
				<div class="card-body p-0">


					<div class="row">
						<div class="col-md-2 p-0">

							<ul class="vi-list mt-2 vi-list-inleners">
								<li class="vi-list-header">Inleners</li>
								<li class="vi-list-item" data-id="1"><span>1001Tafelkleden.com</span></li>
								<li class="vi-list-item" data-id="2"><span>4you Personeelsdiensten</span></li>
								<li class="vi-list-item" data-id="3"><span>Aardappelgroothandel Jansen-Dongen B.V.</span></li>
							</ul>

						</div><!-- /col -->

						<div class="col-xxl-2 col-lg-3 p-0">

							<div class="ajax-wait mt-4 mb-4" style="text-align: center; display: none"><i class="icon-spinner2 spinner mr-1"></i></div>

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
								<li class="vi-list-item"><span>Wijnen, Marcel (I.L.M.) (15003) </li>
							</ul>

							<ul class="vi-list mt-2 vi-list-werknemers list-3" style="display: none">
								<li class="vi-list-header">Werknemers</li>
								<li class="vi-list-item"><span>Baggermans, Berend (B.) (14002)</span></li>
								<li class="vi-list-item"><span>Beers, Ivo (I.J.) (14005)</li>
								<li class="vi-list-item"><span>Bruijn de, Sierd (S) (14003)</li>
								<li class="vi-list-item"><span>Otten, Wierd (W.J.B.W) (15001)</li>
								<li class="vi-list-item"><span>Wijnen, Marcel (I.L.M.) (15003) </li>
								<li class="vi-list-item"><span>Zwiers, Karel (K.I.M.) (15010) </li>
							</ul>


						</div><!-- /col -->
						<div class="col-xxl-8 col-lg-7 p-0 vi-tabs" style="display: none">

							<ul class="nav nav-tabs nav-tabs-bottom ">
								<li class="nav-item"><a href="#bottom-tab1" class="nav-link active show" data-toggle="tab">Uren</a></li>
								<li class="nav-item"><a href="#bottom-tab2" class="nav-link" data-toggle="tab">Kilometers</a></li>
								<li class="nav-item"><a href="#bottom-tab3" class="nav-link" data-toggle="tab">Vergoedingen</a></li>
								<li class="nav-item"><a href="#bottom-tab4" class="nav-link" data-toggle="tab">Reserveringen</a></li>
								<li class="nav-item"><a href="#bottom-tab5" class="nav-link" data-toggle="tab">Inhoudingen</a></li>
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
	</div><!-- /main content -->


{/block}