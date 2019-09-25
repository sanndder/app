<?php
/* Smarty version 3.1.33, created on 2019-09-24 18:44:34
  from 'C:\xampp\htdocs\app\application\views\instellingen\werkgever\minimumloon.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d8a47f242ec90_19310403',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4d5e1a087a20718e7cb56d81f01af6a9a5cfdc92' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\instellingen\\werkgever\\minimumloon.tpl',
      1 => 1569343473,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:instellingen/werkgever/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d8a47f242ec90_19310403 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9879846495d8a47f240ba09_42343984', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16612663705d8a47f240f890_75821835', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10924806355d8a47f2413714_03996562', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('uploader', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8328326545d8a47f2417590_92085189', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_9879846495d8a47f240ba09_42343984 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_9879846495d8a47f240ba09_42343984',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_16612663705d8a47f240f890_75821835 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_16612663705d8a47f240f890_75821835',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-cog<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_10924806355d8a47f2413714_03996562 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_10924806355d8a47f2413714_03996562',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen werkgever<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_8328326545d8a47f2417590_92085189 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_8328326545d8a47f2417590_92085189',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


    <?php $_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'minimumloon'), 0, false);
?>

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<!-------------------------------------------------------------------------------------------------------------------------------------------------
			|| Bedrijfsgegevens
			-------------------------------------------------------------------------------------------------------------------------------------------------->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">Minimumloon aanpassen</h5>
				</div>

				<div class="card-body">
					<form method="post" action="">

                        <?php if (isset($_smarty_tpl->tpl_vars['msg']->value)) {?>
							<div class="row">
								<div class="col-md-12">
                                    <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

								</div><!-- /col -->
							</div>
							<!-- /row -->
                        <?php }?>

                        <?php if (count($_smarty_tpl->tpl_vars['formdata']->value) > 0) {?>
							<table class="table">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value, 'row');
$_smarty_tpl->tpl_vars['row']->index = -1;
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->index++;
$__foreach_row_0_saved = $_smarty_tpl->tpl_vars['row'];
?>
									<tr>
										<td style="width: 200px;"><?php echo $_smarty_tpl->tpl_vars['row']->value['label'];?>
</td>
										<td>
											<div class="input-group" style="width: 200px">
                                                <?php if ($_smarty_tpl->tpl_vars['row']->index > 0) {?>
													<span class="input-group-prepend">
												<span class="input-group-text">€</span>
											</span>
                                                <?php }?>
												<input name="<?php echo $_smarty_tpl->tpl_vars['row']->key;?>
" type="text" class="form-control text-right" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['value'];?>
">
											</div>
										</td>
										<td>
											<?php if (isset($_smarty_tpl->tpl_vars['row']->value['error'])) {?>
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['row']->value['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
?>
													<span class="text-danger"><?php echo $_smarty_tpl->tpl_vars['e']->value;?>
</span><br />
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											<?php }?>
										</td>
									</tr>
                                <?php
$_smarty_tpl->tpl_vars['row'] = $__foreach_row_0_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</table>
                        <?php }?>


						<div class="row">
							<div class="col-lg-12">
								<button type="submit" name="set" class="btn btn-success">
									<i class="icon-checkmark2 mr-1"></i>Opslaan
								</button>
							</div><!-- /col -->
						</div><!-- /row -->


					</form>
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
