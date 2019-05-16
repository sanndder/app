<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>App- Template</title>

	<base href="{$base_url}/" />

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="template/global_assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="template/global_assets/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
	<link href="template/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="template/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
	<link href="template/assets/css/layout.css" rel="stylesheet" type="text/css">
	<link href="template/assets/css/components.min.css" rel="stylesheet" type="text/css">
	<link href="template/assets/css/colors.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Custom stylesheets -->
	<link href="recources/css/custom.css" rel="stylesheet" type="text/css">
	<!-- /Custom stylesheets -->

	<!-- file upload -->
	<link href="template/global_assets/js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
	<link href="template/global_assets/js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
	<link href="template/global_assets/js/plugins/fileinput/themes/explorer-fa/theme.css" rel="stylesheet">

	<!-- Core JS files -->
	<script src="template/global_assets/js/main/jquery.min.js"></script>
	<script src="template/global_assets/js/main/bootstrap.bundle.min.js"></script>
	<script src="template/global_assets/js/plugins/loaders/blockui.min.js"></script>
	<script src="template/global_assets/js/plugins/ui/slinky.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="template/assets/js/app.js"></script>
	<!-- /theme JS files -->

	<!-- Load plugin -->
	<script src="template/global_assets/js/plugins/fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
	<script src="template/global_assets/js/plugins/fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>

	<script src="template/global_assets/js/plugins/fileinput/js/fileinput.min.js"></script>
	<script src="template/global_assets/js/plugins/fileinput/themes/explorer-fa/theme.js"></script>
	<script src="template/global_assets/js/plugins/fileinput/js/locales/nl.js"></script>

	<script>
		{literal}
        // Basic setup
        $(document).ready(function(){
			$('#fileupload').fileinput({
				theme: "explorer-fa",
				language: 'nl',
                overwriteInitial: false,
                initialPreviewShowDelete: true,
                uploadUrl: 'upload',
                dropZoneEnabled: false,
                uploadAsync: true,
                msgUploadError: ''
			});
        });
		{/literal}
	</script>

</head>

<body class="navbar-md-md-top">

<!-- Multiple fixed navbars wrapper -->
<div class="fixed-top">

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark">
		<div class="navbar-brand wmin-0 mr-5">
			<img src="template/global_assets/images/logo_light.png" alt="">
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">

			<ul class="navbar-nav ml-auto">

				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
						<i class="icon-user mr-3 icon-2x"></i>
						<span>Victoria</span>
					</a>

					<div class="dropdown-menu dropdown-menu-right">
						<a href="mijnaccount/index" class="dropdown-item"><i class="icon-cog5"></i> Mijn account</a>
						<a href="#" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
					</div>
				</li>

			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Secondary navbar -->
	<div class="navbar navbar-expand-md navbar-light">
		<div class="text-center d-md-none w-100">
			<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-navigation">
				<i class="icon-unfold mr-2"></i>
				Navigation
			</button>
		</div>

		<div class="navbar-collapse collapse" id="navbar-navigation">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="index" class="navbar-nav-link">
						<i class="icon-home4 mr-2"></i>
						Dashboard
					</a>
				</li>

				<li class="nav-item dropdown">
					<a href="#" class="navbar-nav-link dropdown-toggle active" data-toggle="dropdown">
						<i class="icon-users mr-2"></i>
						CRM
					</a>

					<div class="dropdown-menu">
						<a href="#" class="dropdown-item">
							<i class="icon-office"></i>Uitzenders
						</a>
						<a href="#" class="dropdown-item">
							<i class="icon-user-tie"></i>Inleners
						</a>
						<a href="#" class="dropdown-item">
							<i class="icon-user"></i>Werknemers
						</a>
					</div>
				</li>
			</ul>

		</div>
	</div>
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
							<input name="file" type="file" id="fileupload" class="file-input" multiple="multiple">
						</form>
					</div>
				</div>
			</div>




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
