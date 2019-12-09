<?php
/* Smarty version 3.1.33, created on 2019-12-04 15:56:01
  from 'C:\xampp\htdocs\app\application\views\instellingen\werkgever\vergoedingen.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5de7c901d2fc07_43955217',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fbffeeda9b688ae5807b261f282aa42c9f4ba8f9' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\instellingen\\werkgever\\vergoedingen.tpl',
      1 => 1575471360,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:instellingen/werkgever/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5de7c901d2fc07_43955217 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3922420215de7c901d0c986_45850242', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14863773205de7c901d10808_45069658', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13845094305de7c901d14688_66846087', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_21421526635de7c901d18504_74358400', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_3922420215de7c901d0c986_45850242 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_3922420215de7c901d0c986_45850242',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_14863773205de7c901d10808_45069658 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_14863773205de7c901d10808_45069658',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-cog<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_13845094305de7c901d14688_66846087 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_13845094305de7c901d14688_66846087',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen werkgever<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_21421526635de7c901d18504_74358400 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_21421526635de7c901d18504_74358400',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


    <?php $_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'vergoedingen'), 0, false);
?>

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Toevoegen
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Vergoeding toevoegen</h5>
				</div>

				<div class="card-body">
					<form method="post" action="">

                        <?php if (isset($_smarty_tpl->tpl_vars['errors']->value)) {?>
							<div class="row">
								<div class="col-md-12">
									<div class="alert alert-warning alert-styled-left alert-arrow-left alert-dismissible" role="alert">
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['errors']->value, 'arr');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['arr']->value) {
?>
                                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr']->value, 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
?>
                                                <?php echo $_smarty_tpl->tpl_vars['e']->value;?>

                                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</div><!-- /col -->
								</div><!-- /col -->
							</div>
							<!-- /row -->
                        <?php }?>


						<table>
							<tr>
								<th>Vergoeding</th>
								<th>Belast/Onbelast</th>
								<th></th>
							</tr>
							<tr>
								<td style="width: 400px;" class="pr-2">
									<input type="text" class="form-control" name="naam" required>
								</td>
								<td style="width:150px;" class="pr-2">
									<select name="belast" class="form-control" required>
										<option value=""></option>
										<option value="1">Belast</option>
										<option value="0">Onbelast</option>
									</select>
								</td>
								<td>
									<button type="submit" name="set" class="btn btn-success">
										<i class="icon-add mr-1"></i>Toevoegen
									</button>
								</td>
							</tr>
						</table>

					</form>
				</div><!-- /card body -->
			</div><!-- /basic card -->

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| msg
			-------------------------------------------------------------------------------------------------------------------------------------------------->
            <?php if (isset($_smarty_tpl->tpl_vars['msg']->value)) {?>
				<div class="row">
					<div class="col-md-12">
                        <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

					</div><!-- /col -->
				</div>
				<!-- /row -->
            <?php }?>

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Toevoegen
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Vergoedingen overzicht</h5>
				</div>

				<div class="card-body">

                    <?php if (!empty($_smarty_tpl->tpl_vars['vergoedingen']->value)) {?>
						<form method="post" action="">
							<table class="table table-striped" style="width: 600px;">
								<thead>
									<tr>
										<th></th>
										<th>Vergoeding</th>
										<th>Belast/Onbelast</th>
									</tr>
								</thead>
								<tbody>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['vergoedingen']->value, 'v');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['v']->value) {
?>
										<tr>
											<th style="width: 20px;">
												<button type="button" class="sweet-confirm p-0 btn" data-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['vergoeding_id'];?>
" data-popup="tooltip" data-placement="top" data-title="Vergoeding verwijderen">
													<i class="icon-trash text-danger"></i>
												</button>
											</th>
											<td><?php echo $_smarty_tpl->tpl_vars['v']->value['naam'];?>
</td>
											<td>
                                                <?php if ($_smarty_tpl->tpl_vars['v']->value['belast'] == 1) {?>
													Belast
                                                <?php } else { ?>
													Onbelast
                                                <?php }?>
											</td>
										</tr>
                                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</tbody>
							</table>
						</form>
                    <?php }?>

				</div><!-- /card body -->
			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
