<?php
/* Smarty version 3.1.33, created on 2019-08-07 22:23:49
  from 'C:\xampp\htdocs\app\application\views\crm\inleners\dossier\notities.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4b3355ccebc7_27397959',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6278aa10939d9517f4db93ab0501294bc50c4525' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\inleners\\dossier\\notities.tpl',
      1 => 1565100513,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/inleners/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d4b3355ccebc7_27397959 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3084113225d4b3355caf7c0_85922533', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9399518835d4b3355cb3640_04608264', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14145656545d4b3355cb74c6_05842734', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14138414825d4b3355cbf1c9_66568928', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_3084113225d4b3355caf7c0_85922533 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_3084113225d4b3355caf7c0_85922533',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inlener<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_9399518835d4b3355cb3640_04608264 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_9399518835d4b3355cb3640_04608264',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_14145656545d4b3355cb74c6_05842734 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_14145656545d4b3355cb74c6_05842734',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inlener - <?php echo $_smarty_tpl->tpl_vars['inlener']->value->bedrijfsnaam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_14138414825d4b3355cbf1c9_66568928 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_14138414825d4b3355cbf1c9_66568928',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/inleners/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'notities'), 0, false);
?>


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- msg -->
			<?php if (isset($_smarty_tpl->tpl_vars['msg']->value)) {?>
				<div class="row">
					<div class="col-xl-11">
						<?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

					</div><!-- /col -->
				</div>
				<!-- /row -->
			<?php }?>

			<div class="row">
				<div class="col-md-10">

					<!-- Basic card -->
					<div class="card mb-2">

						<div class="bg-light rounded-top">
							<div class="navbar navbar-light bg-light navbar-expand-sm py-header rounded-top">

								<div class="navbar-collapse text-center text-lg-left flex-wrap collapse show" id="inbox-toolbar-toggle-read">
									<div class="mt-3 mt-lg-0 mr-lg-3">
										<div class="btn-group">
											<button type="button" class="btn btn-light btn-sm" data-id="0" onclick="modalContact(this, 'inlener', <?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
)">
												<i class="icon-plus-circle2"></i>
												<span class="d-none d-inline-block ml-2">Notitie toevoegen</span>
											</button>
										</div>
									</div>

									<div class="navbar-text ml-lg-auto"></div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xl-11">

					<div class="card border-left-3 border-left-blue-400 rounded-left-0 mb-2">
						<div class="card-body">
							<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
								<div>
									<p class="mb-0">Inlener geeft aan dat hij NIET gebeld wil worden, alleen emailen.	</p>
								</div>
							</div>
						</div>

						<div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">
							<div>
								<span>Door </span><span class="font-weight-semibold">Sander Meijering </span><span>op</span><span class="font-weight-semibold"> 28 Augustus</span>
							</div>

							<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">

								<ul class="list-inline mb-0 mt-2 mt-sm-0">
									<li class="list-inline-item dropdown">
										<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

										<div class="dropdown-menu dropdown-menu-right">
											<a href="#" class="dropdown-item"><i class="icon-pencil7"></i> Wijzigen</a>
											<a href="#" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
										</div>
									</li>
								</ul>

							</div>


						</div>
					</div>


				</div><!-- /col -->
			</div><!-- /row -->

			<div class="row">
				<div class="col-xl-11">

					<div class="card border-left-3 border-left-blue-400 rounded-left-0">
						<div class="card-body">
							<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
								<div>
									<p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at lorem magna. Nam vulputate semper ligula, in hendrerit urna. Morbi ultrices tellus sed accumsan congue. Nam maximus mattis nisl et luctus. Phasellus pharetra justo in bibendum semper. Proin vel lacus accumsan, mattis velit nec, aliquet velit. Aliquam ut efficitur justo. Curabitur quis leo dui.</p>
								</div>
							</div>
						</div>

						<div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">
							<div>
								<span>Door </span><span class="font-weight-semibold">Sander Meijering </span><span>op</span><span class="font-weight-semibold"> 28 Augustus</span>
							</div>

							<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">

								<ul class="list-inline mb-0 mt-2 mt-sm-0">
									<li class="list-inline-item dropdown">
										<a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

										<div class="dropdown-menu dropdown-menu-right">
											<a href="#" class="dropdown-item"><i class="icon-pencil7"></i> Wijzigen</a>
											<a href="#" class="dropdown-item"><i class="icon-cross2"></i> Verwijderen </a>
										</div>
									</li>
								</ul>

							</div>


						</div>
					</div>


				</div><!-- /col -->
			</div><!-- /row -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
