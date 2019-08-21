<?php
/* Smarty version 3.1.33, created on 2019-08-07 22:15:57
  from 'C:\xampp\htdocs\app\application\views\crm\inleners\overzicht.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4b317d5c9c54_04086114',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '00af66d204156d7f2c377cb1f9238567719f9a48' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\inleners\\overzicht.tpl',
      1 => 1565100225,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d4b317d5c9c54_04086114 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14553035045d4b317d58b441_27625594', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1520377425d4b317d58f2c2_92917162', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12637863245d4b317d593151_18215087', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('datatable', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17756188185d4b317d596fd9_88859700', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_14553035045d4b317d58b441_27625594 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_14553035045d4b317d58b441_27625594',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inleners<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_1520377425d4b317d58f2c2_92917162 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_1520377425d4b317d58f2c2_92917162',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-user-tie<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_12637863245d4b317d593151_18215087 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_12637863245d4b317d593151_18215087',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inleners<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_17756188185d4b317d596fd9_88859700 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_17756188185d4b317d596fd9_88859700',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>



	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main sidebar
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="sidebar sidebar-light sidebar-main sidebar-sections sidebar-expand-lg align-self-start">

		<!-- Sidebar mobile toggler -->
		<div class="sidebar-mobile-toggler text-center">
			<a href="#" class="sidebar-mobile-main-toggle">
				<i class="icon-arrow-left8"></i>
			</a>
			<span class="font-weight-semibold">Zijmenu</span>
			<a href="#" class="sidebar-mobile-expand">
				<i class="icon-screen-full"></i>
				<i class="icon-screen-normal"></i>
			</a>
		</div>
		<!-- /sidebar mobile toggler -->

		<!-- Sidebar content -->
		<div class="sidebar-content">

			<!---------------------------------------------------------------------------------------------------------
			||Snel zoeken
			---------------------------------------------------------------------------------------------------------->
			<div class="card card-sidebar-mobile d-none d-lg-block d-xl-block">

				<!-- header -->
				<div class="card-header bg-transparent header-elements-inline">
					<span class="text-uppercase font-size-sm font-weight-semibold">Snel Zoeken</span>
				</div>

				<!-- Zoekvelden -->
				<div class="card-body">

					<div class="form-group form-group-feedback form-group-feedback-left">
						<input id="datatable-search" type="search" class="form-control" placeholder="Tabel doorzoeken...">
						<div class="form-control-feedback">
							<i class="icon-search4 text-muted"></i>
						</div>
					</div>

				</div>
			</div>


			<!---------------------------------------------------------------------------------------------------------
			||zoeken
			---------------------------------------------------------------------------------------------------------->
			<div class="card card-sidebar-mobile">

				<!-- header -->
				<div class="card-header bg-transparent header-elements-inline">
					<span class="text-uppercase font-size-sm font-weight-semibold">Uitgebreid Zoeken</span>
				</div>

				<!-- Zoekvelden -->
				<div class="card-body">

					<form action="" method="get">
						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="q1" value="<?php if (isset($_GET['q1'])) {
echo $_GET['q1'];
}?>" type="search" class="form-control" placeholder="ID of bedrijfsnaam">
							<div class="form-control-feedback">
								<i class="icon-office text-muted"></i>
							</div>
						</div>

						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="q2" value="<?php if (isset($_GET['q2'])) {
echo $_GET['q2'];
}?>" type="search" class="form-control" placeholder="Overige zoektermen">
							<div class="form-control-feedback">
								<i class="icon-search4 text-muted"></i>
							</div>
						</div>

						<div class="form-group">

							<div class="form-check">
								<label class="form-check-label">
									<input name="actief" value="1" type="checkbox" class="form-input-styled" <?php if (isset($_GET['actief']) || !isset($_GET['q1'])) {?> checked="checked"<?php }?>>
									Actieve inleners
								</label>
							</div>

							<div class="form-check">
								<label class="form-check-label text-danger">
									<input name="archief" value="1" type="checkbox" class="form-input-styled-danger" <?php if (isset($_GET['archief'])) {?> checked="checked"<?php }?> data-fouc="">
									Archief
								</label>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<button type="submit" class="btn bg-blue btn-block">
									<i class="icon-search4 font-size-base mr-2"></i>
									Zoeken
								</button>
							</div><!-- /col -->
							<div class="col-md-6">
								<a href="crm/inleners" class="btn btn-light" style="width: 100%">
									<i class="icon-cross font-size-base mr-2"></i>
									Wissen
								</a>
							</div>
						</div><!-- /row -->
					</form>
				</div>


			</div><!-- /main navigation -->

			<!---------------------------------------------------------------------------------------------------------
			||Snel zoeken
			---------------------------------------------------------------------------------------------------------->
			<div class="card card-sidebar-mobile d-none d-lg-block d-xl-block">

				<!-- header -->
				<div class="card-header bg-transparent header-elements-inline">
					<span class="text-uppercase font-size-sm font-weight-semibold">Weergave instellingen</span>
				</div>

				<!-- Zoekvelden -->
				<div class="card-body" style="height: 80px; ">

					<div id="move-length-dropdown" style="float: left; margin-left: -20px;">

					</div>

				</div>
			</div>


		</div>
		<!-- /sidebar content -->

	</div>
	<!-- /main sidebar  -->

	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- Basic card -->
			<div class="card">

				<div class="bg-light rounded-top">
					<div class="navbar navbar-light bg-light navbar-expand-lg py-lg-2 rounded-top">
						<div class="text-center d-lg-none w-100">
							<button type="button" class="navbar-toggler w-100 h-100" data-toggle="collapse" data-target="#inbox-toolbar-toggle-read">
								<i class="icon-circle-down2"></i>
							</button>
						</div>

						<div class="navbar-collapse text-center text-lg-left flex-wrap collapse" id="inbox-toolbar-toggle-read">
							<div class="mt-3 mt-lg-0 mr-lg-3">
								<div class="btn-group">
									<a type="button" class="btn btn-light" href="crm/inleners/dossier/bedrijfsgegevens">
										<i class="icon-plus-circle2"></i>
										<span class="d-none d-lg-inline-block ml-2">Nieuwe inlener</span>
									</a>
								</div>
							</div>

							<div class="navbar-text ml-lg-auto"></div>

						</div>
					</div>
				</div>

				<!-- header -->
				<!-- card  body-->
				<div class="card-body">
				<div class="media flex-column flex-md-row">
					<a href="#" class="d-none d-md-block mr-md-3 mb-3 mb-md-0">
						<span class="btn bg-teal-400 btn-icon btn-lg rounded-round">
							<span class="letter-icon">U</span>
						</span>
					</a>

					<div class="media-body">
						<h6 class="mb-0">Inleneroverzicht</h6>
						<div class="letter-icon-title font-weight-semibold"><?php echo count($_smarty_tpl->tpl_vars['inleners']->value);?>
 inleners in tabel</div>
					</div>
				</div>


				</div><!-- /card body-->


				<!-- table -->
				<table class="table datatable-basic table-striped table-hover table-xs" data-page-length="15" data-order="[[0,&quot;asc&quot; ],[2,&quot;asc&quot; ]]">
					<thead class="">
					<tr>
						<th></th>
						<th style="width: 75px;">ID</th>
						<th>Bedrijfsnaam</th>
						<th class="text-center">Actions</th>
					</tr>
					</thead>
					<?php if (isset($_smarty_tpl->tpl_vars['inleners']->value) && is_array($_smarty_tpl->tpl_vars['inleners']->value) && count($_smarty_tpl->tpl_vars['inleners']->value) > 0) {?>
						<tbody>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['inleners']->value, 'i');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value) {
?>
							<tr style="<?php if ($_smarty_tpl->tpl_vars['i']->value['complete'] == 0) {?>background-color: #EEE;<?php }
if ($_smarty_tpl->tpl_vars['i']->value['archief'] == 1) {?>color: #F44336;<?php }?>">
								<td><?php echo $_smarty_tpl->tpl_vars['i']->value['complete'];?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['i']->value['inlener_id'];?>
</td>
								<td>
									<?php if ($_smarty_tpl->tpl_vars['i']->value['complete'] == 0) {?>
										<span class="badge bg-success  mr-1">NIEUW</span>
									<?php }?>
									<a style="<?php if ($_smarty_tpl->tpl_vars['i']->value['archief'] == 1) {?>color: #F44336;<?php }?>" href="crm/inleners/dossier/overzicht/<?php echo $_smarty_tpl->tpl_vars['i']->value['inlener_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value['bedrijfsnaam'];?>
</a>
								</td>
								<td></td>
							</tr>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</tbody>
					<?php }?>
				</table>


			</div>
			<!-- /basic card -->
		</div>
		<!-- /content area -->
	</div>
	<!-- /main content -->

<?php
}
}
/* {/block "content"} */
}
