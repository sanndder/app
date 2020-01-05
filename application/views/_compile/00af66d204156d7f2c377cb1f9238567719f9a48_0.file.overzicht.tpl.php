<?php
/* Smarty version 3.1.33, created on 2020-01-05 13:47:27
  from 'C:\xampp\htdocs\app\application\views\crm\inleners\overzicht.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e11dadf9986a0_51231701',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '00af66d204156d7f2c377cb1f9238567719f9a48' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\inleners\\overzicht.tpl',
      1 => 1578228446,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e11dadf9986a0_51231701 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13385163825e11dadf965a13_57335648', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9039922705e11dadf969897_63492043', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19807136115e11dadf96d713_89417526', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('datatable', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12009807395e11dadf971593_61479006', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_13385163825e11dadf965a13_57335648 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_13385163825e11dadf965a13_57335648',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inleners<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_9039922705e11dadf969897_63492043 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_9039922705e11dadf969897_63492043',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-user-tie<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_19807136115e11dadf96d713_89417526 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_19807136115e11dadf96d713_89417526',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inleners<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_12009807395e11dadf971593_61479006 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_12009807395e11dadf971593_61479006',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'C:\\xampp\\htdocs\\app\\application\\third_party\\smarty\\plugins\\modifier.truncate.php','function'=>'smarty_modifier_truncate',),));
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

				<!-- card  body-->
				<div class="card-body">
					<div class="media flex-column flex-md-row">
						<a href="#" class="d-none d-md-block mr-md-3 mb-3 mb-md-0">
						<span class="btn bg-teal-400 btn-icon btn-lg rounded-round">
							<span class="letter-icon">I</span>
						</span>
						</a>

						<div class="media-body">
							<h6 class="mb-0">Inleneroverzicht</h6>
							<div class="letter-icon-title font-weight-semibold"><?php echo count($_smarty_tpl->tpl_vars['inleners']->value);?>
 inleners in tabel</div>
						</div>

						<div class="justify-content-between">
							<a href="crm/inleners/kredietlimiet" class="btn bg-teal-400">
								<i class="icon-plus-circle2 icon mr-1"></i>
								<span>Kredietlimiet aanvragen</span>
							</a>
							<a href="crm/uitzenders/dossier/bedrijfsgegevens" class="btn btn-outline bg-teal-400 text-teal-400 border-teal-400">
								<i class="icon-pencil7 icon mr-1"></i>
								<span>Inlener invoeren</span>
							</a>
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
							<th>Uitzender</th>
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
											<?php if (isset($_smarty_tpl->tpl_vars['i']->value['krediet'])) {?>
                                                												<span class="badge bg-primary  mr-1">KREDIET</span>
											<?php } else { ?>
                                                		                                        <span class="badge bg-success  mr-1">NIEUW</span>
                                            <?php }?>
                                        <?php }?>

                                                                                <?php if (!isset($_smarty_tpl->tpl_vars['i']->value['krediet'])) {?>
                                            	                                        <a style="<?php if ($_smarty_tpl->tpl_vars['i']->value['archief'] == 1) {?>color: #F44336;<?php }?>" href="crm/inleners/dossier/overzicht/<?php echo $_smarty_tpl->tpl_vars['i']->value['inlener_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value['bedrijfsnaam'];?>
</a>
                                        <?php } else { ?>
                                                                                        <?php if ($_smarty_tpl->tpl_vars['i']->value['inlener_id'] === NULL) {?>
                                                	                                            <a style="<?php if ($_smarty_tpl->tpl_vars['i']->value['archief'] == 1) {?>color: #F44336;<?php }?>" href="crm/inleners/dossier/kredietoverzicht/k<?php echo $_smarty_tpl->tpl_vars['i']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value['bedrijfsnaam'];?>
</a>
                                            <?php } else { ?>
                                                	                                            <a style="<?php if ($_smarty_tpl->tpl_vars['i']->value['archief'] == 1) {?>color: #F44336;<?php }?>" href="crm/inleners/dossier/kredietoverzicht/<?php echo $_smarty_tpl->tpl_vars['i']->value['inlener_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value['bedrijfsnaam'];?>
</a>
                                            <?php }?>
                                        <?php }?>

									</td>
									<td>
										<a href="crm/uitzenders/dossier/overzicht/<?php echo $_smarty_tpl->tpl_vars['i']->value['uitzender_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value['uitzender'];?>
</a>
									</td>
									<td>
										<?php if ($_smarty_tpl->tpl_vars['ENV']->value == 'development') {?>
											<a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
//crm/inleners?del=<?php echo $_smarty_tpl->tpl_vars['i']->value['inlener_id'];?>
"><i class="icon-trash font-size-sm"></i></a>
                                        <?php }?>
									</td>
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
	<div class="sidebar sidebar-light sidebar-main d-none d-xxl-block sidebar-sections sidebar-expand-lg align-self-start">

		<!-- Sidebar content -->
		<div class="sidebar-content">

			<!-- Latest updates -->
			<div class="card">
				<div class="card-header bg-transparent header-elements-inline">
					<span class="text-uppercase font-size-sm font-weight-semibold">Laatst bezocht</span>
				</div>

				<div class="card-body">
					<ul class="media-list">
						<li class="media">
							<div class="media-body">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['last_visits']->value, 'visit');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['visit']->value) {
?>
									<a href="crm/inleners/dossier/overzicht/<?php echo $_smarty_tpl->tpl_vars['visit']->value['inlener_id'];?>
">
										<div class="float-left" style="width: 45px;"><?php echo $_smarty_tpl->tpl_vars['visit']->value['inlener_id'];?>
</div>
										<div class="mb-1"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['visit']->value['bedrijfsnaam'],28,'...',true);?>
</div>
									</a>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</div>
						</li>

					</ul>
				</div>
			</div>
			<!-- /latest updates -->

		</div>
		<!-- /sidebar content -->

	</div>
<?php
}
}
/* {/block "content"} */
}
