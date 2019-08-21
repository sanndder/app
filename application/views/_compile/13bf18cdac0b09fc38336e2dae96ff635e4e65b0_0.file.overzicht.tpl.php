<?php
/* Smarty version 3.1.33, created on 2019-08-07 15:05:21
  from 'C:\xampp\htdocs\app\application\views\crm\uitzenders\overzicht.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4acc91837f00_84481714',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '13bf18cdac0b09fc38336e2dae96ff635e4e65b0' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\uitzenders\\overzicht.tpl',
      1 => 1565100149,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d4acc91837f00_84481714 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11175816955d4acc91814c77_78863923', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12658720065d4acc91818af2_40967253', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3979689205d4acc9181c978_02456652', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('datatable', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7710980765d4acc918207f4_96073686', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_11175816955d4acc91814c77_78863923 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_11175816955d4acc91814c77_78863923',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzenders<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_12658720065d4acc91818af2_40967253 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_12658720065d4acc91818af2_40967253',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_3979689205d4acc9181c978_02456652 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_3979689205d4acc9181c978_02456652',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzenders<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_7710980765d4acc918207f4_96073686 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_7710980765d4acc918207f4_96073686',
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
									Actieve uitzenders
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
								<a href="crm/uitzenders" class="btn btn-light" style="width: 100%">
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
									<a type="button" class="btn btn-light" href="crm/uitzenders/dossier/bedrijfsgegevens">
										<i class="icon-plus-circle2"></i>
										<span class="d-none d-lg-inline-block ml-2">Nieuwe uitzender</span>
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
						<h6 class="mb-0">Uitzenderoverzicht</h6>
						<div class="letter-icon-title font-weight-semibold"><?php echo count($_smarty_tpl->tpl_vars['uitzenders']->value);?>
 uitzenders in tabel</div>
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
					<?php if (isset($_smarty_tpl->tpl_vars['uitzenders']->value) && is_array($_smarty_tpl->tpl_vars['uitzenders']->value) && count($_smarty_tpl->tpl_vars['uitzenders']->value) > 0) {?>
						<tbody>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['uitzenders']->value, 'u');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['u']->value) {
?>
							<tr style="<?php if ($_smarty_tpl->tpl_vars['u']->value['complete'] == 0) {?>background-color: #EEE;<?php }
if ($_smarty_tpl->tpl_vars['u']->value['archief'] == 1) {?>color: #F44336;<?php }?>">
								<td><?php echo $_smarty_tpl->tpl_vars['u']->value['complete'];?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['u']->value['uitzender_id'];?>
</td>
								<td>
									<?php if ($_smarty_tpl->tpl_vars['u']->value['complete'] == 0) {?>
										<span class="badge bg-success  mr-1">NIEUW</span>
									<?php }?>
									<a style="<?php if ($_smarty_tpl->tpl_vars['u']->value['archief'] == 1) {?>color: #F44336;<?php }?>" href="crm/uitzenders/dossier/overzicht/<?php echo $_smarty_tpl->tpl_vars['u']->value['uitzender_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['u']->value['bedrijfsnaam'];?>
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
