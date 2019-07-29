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

	<!-- ckeditor plugin -->
	{if isset($ckeditor)}
		<script src="recources/plugins/ckeditor/ckeditor.js"></script>
		<script src="recources/js/ckeditor.js"></script>
	{/if}

	<!-- file upload -->
	{if isset($uploader)}
		<link href="template/global_assets/js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
		<link href="template/global_assets/js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
		<link href="template/global_assets/js/plugins/fileinput/themes/explorer-fa/theme.css" rel="stylesheet">

		<script src="template/global_assets/js/plugins/fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
		<script src="template/global_assets/js/plugins/fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>

		<script src="template/global_assets/js/plugins/fileinput/js/fileinput.min.js"></script>
		<script src="template/global_assets/js/plugins/fileinput/themes/fa/theme.js"></script>
		<script src="template/global_assets/js/plugins/fileinput/js/locales/nl.js"></script>
		<script src="recources/js/uploader.js"></script>
	{/if}

	<!-- datatable -->
	{if isset($datatable)}
		<script src="template/global_assets/js/plugins/tables/datatables/datatables.min.js" type="text/javascript"></script>
		<script src="template/global_assets/js/plugins/forms/selects/select2.min.js" type="text/javascript"></script>
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


<!-- Page header -->
<div class="page-header">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4>
				<i class="{block name='header-icon'}{/block} mr-2"></i>
				<span class="font-weight-semibold">{block name='header-title'}{/block}</span>
			</h4>
		</div>

		{*
		<div class="header-elements d-none py-0 mb-3 mb-md-0">
			<div class="breadcrumb">
				<a href="full/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="#" class="breadcrumb-item">Link</a>
				<span class="breadcrumb-item active">Current</span>
			</div>
		</div>
		*}
	</div>
</div>
<!-- /page header -->


<!-- Page content -->
<div class="page-content pt-0">


	<!-- Content area -->
	{block name='content'}{/block}
	<!-- /content area -->

</div>
<!-- /page content -->


</body>
</html>