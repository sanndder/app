<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{$app_name} - {block name='title'}{/block}</title>

	<base href="{$base_url}/"/>

	<!-- Global stylesheets -->
	{include file='_page/css.tpl'}
	<!-- /global stylesheets -->

	<!-- JS files -->
	{include file='_page/js.tpl'}
	<!-- /JS files -->

	<!-- js plugins -->
	{if isset($ckeditor)}
		<script src="recources/plugins/ckeditor/ckeditor.js"></script>
		<script src="recources/js/ckeditor.js"></script>
	{/if}

	<!-- file upload -->
	{if isset($uploader)}
		<link href="template/global_assets/js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
		<link href="template/global_assets/js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
		<link href="template/global_assets/js/plugins/fileinput/themes/explorer-fa/theme.css" rel="stylesheet">
		<script src="template/global_assets/js/plugins/fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
		<script src="template/global_assets/js/plugins/fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
		<script src="template/global_assets/js/plugins/fileinput/js/fileinput.min.js"></script>
		<script src="template/global_assets/js/plugins/fileinput/themes/fa/theme.js"></script>
		<script src="template/global_assets/js/plugins/fileinput/js/locales/nl.js"></script>
		<script src="recources/js/uploader.js"></script>
	{/if}

	<!-- /JS plugins -->

</head>

<body class="navbar-md-md-top">

<!-- Multiple fixed navbars wrapper -->
<div class="fixed-top">

	<!-- Main navbar -->
	{include file='_page/header.tpl'}
	<!-- /main navbar -->

	<!-- Secondary navbar -->
	{include file='_menu/werkgever.tpl'}
	<!-- /secondary navbar -->

</div>
<!-- /multiple fixed navbars wrapper -->


<!-- Page content -->
<div class="page-content">
	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">
			<div class="row">
				<div class="col-md-12">

					<div class="alert alert-danger alert-styled-left">
						U heeft onvoldoende rechten om deze pagina te bezoeken.
					</div>


				</div><!-- /col -->
			</div><!-- /row -->
		</div>
	</div>

</div>
<!-- /page content -->


</body>
</html>
