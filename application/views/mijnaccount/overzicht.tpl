<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>App - Mijn account</title>

	<base href="{$base_url}/"/>

	<!-- Global stylesheets -->
	{include file='_page/css.tpl'}
	<!-- /global stylesheets -->

	<!-- JS files -->
	{include file='_page/js.tpl'}
	<!-- /JS files -->

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
				<i class="icon-office mr-2"></i>
				<span class="font-weight-semibold">Uitzenders</span>
			</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none py-0 mb-3 mb-md-0">
			<div class="breadcrumb">
				<a href="full/index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="#" class="breadcrumb-item">Link</a>
				<span class="breadcrumb-item active">Current</span>
			</div>
		</div>
	</div>
</div>
<!-- /page header -->


<!-- Page content -->
<div class="page-content pt-0">

	<!-- Main content -->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<div class="col-md-6">

				<!-- Vertical form -->
				<div class="card">
					<div class="card-header header-elements-inline">
						<h5 class="card-title">Vertical form</h5>
						<div class="header-elements">
							<div class="list-icons">
								<a class="list-icons-item" data-action="collapse"></a>
								<a class="list-icons-item" data-action="reload"></a>
								<a class="list-icons-item" data-action="remove"></a>
							</div>
						</div>
					</div>

					<div class="card-body">
						<form action="#">
							<div class="form-group">
								<label>Text input</label>
								<input type="text" class="form-control">
							</div>

							<div class="form-group">
								<label>Select</label>
								<select name="select" class="form-control">
									<option value="opt1">Basic select</option>
									<option value="opt2">Option 2</option>
									<option value="opt3">Option 3</option>
									<option value="opt4">Option 4</option>
									<option value="opt5">Option 5</option>
									<option value="opt6">Option 6</option>
									<option value="opt7">Option 7</option>
									<option value="opt8">Option 8</option>
								</select>
							</div>

							<div class="form-group">
								<label>Textarea</label>
								<textarea rows="4" cols="4" class="form-control" placeholder="Default textarea"></textarea>
							</div>

							<div class="text-right">
								<button type="submit" class="btn btn-primary">Submit form
									<i class="icon-paperplane ml-2"></i></button>
							</div>
						</form>
					</div>
					<!-- /vertical form -->

				</div>
			</div>
			<!-- /form layouts -->

		</div>
		<!-- /content area -->

	</div>
	<!-- /main content -->

</div>
<!-- /page content -->


</body>
</html>
