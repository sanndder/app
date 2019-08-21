<?php
/* Smarty version 3.1.33, created on 2019-08-07 22:24:13
  from 'C:\xampp\htdocs\app\application\views\instellingen\werkgever\bankrekeningen.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4b336daa5791_09437826',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b8a509ce98a420007c00bd53bf92d052929317e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\instellingen\\werkgever\\bankrekeningen.tpl',
      1 => 1564405926,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:instellingen/werkgever/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d4b336daa5791_09437826 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11483561015d4b336da7a806_78711426', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13536358265d4b336da7e691_49190703', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2197071835d4b336da82516_91137680', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18980065785d4b336da86393_79380689', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_11483561015d4b336da7a806_78711426 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_11483561015d4b336da7a806_78711426',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_13536358265d4b336da7e691_49190703 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_13536358265d4b336da7e691_49190703',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-cog<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_2197071835d4b336da82516_91137680 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_2197071835d4b336da82516_91137680',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen werkgever<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_18980065785d4b336da86393_79380689 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_18980065785d4b336da86393_79380689',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'bankrekeningen'), 0, false);
?>

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">


			<div class="row">
				<div class="col-xxl-6 col-xl-10">

					<?php if (isset($_smarty_tpl->tpl_vars['msg']->value) && !is_array($_smarty_tpl->tpl_vars['msg']->value)) {?>
						<div class="row">
							<div class="col-md-12">
								<?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

							</div><!-- /col -->
						</div><!-- /row -->
					<?php }?>

					<form method="post" action="">

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['bankrekeningen']->value, 'formdata');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['formdata']->key => $_smarty_tpl->tpl_vars['formdata']->value) {
$__foreach_formdata_0_saved = $_smarty_tpl->tpl_vars['formdata'];
?>
							<!-- Basic card -->
							<div class="card">
								<div class="card-body">

									<?php if (isset($_smarty_tpl->tpl_vars['msg']->value[$_smarty_tpl->tpl_vars['formdata']->key]) && is_array($_smarty_tpl->tpl_vars['msg']->value)) {?>
										<div class="row">
											<div class="col-md-12">
												<?php echo $_smarty_tpl->tpl_vars['msg']->value[$_smarty_tpl->tpl_vars['formdata']->key];?>

											</div><!-- /col -->
										</div><!-- /row -->
									<?php }?>

									<div class="row">
										<div class="col-md-10">

											<!-- omschrijving -->
											<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['omschrijving'])) {?>
												<?php $_smarty_tpl->_assignInScope('field', "omschrijving");?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

														:</label>
													<div class="col-xl-8 col-md-8">
														<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
[<?php echo $_smarty_tpl->tpl_vars['formdata']->key;?>
]" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
														<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
															<span class="form-text text-danger"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>


															<br/>
														<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></span><?php }?>
													</div>
												</div>
											<?php }?>


											<!-- iban -->
											<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['iban'])) {?>
												<?php $_smarty_tpl->_assignInScope('field', "iban");?>
												<div class="form-group row">
													<label class="col-lg-3 col-form-label <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>text-danger<?php }?>"><?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['label'];?>

														:</label>
													<div class="col-xl-8 col-md-8">
														<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
[<?php echo $_smarty_tpl->tpl_vars['formdata']->key;?>
]" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
														<?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
															<span class="form-text text-danger"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>


															<br/>
														<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></span><?php }?>
													</div>
												</div>
											<?php }?>

										</div><!-- /col -->
										<div class="col-md-2">
											<!-- buttons -->
											<div class="col-sm-12 text-right">
												<button name="set[<?php echo $_smarty_tpl->tpl_vars['formdata']->key;?>
]" type="submit" class="btn btn-outline-success btn-icon rounded-round" data-popup="tooltip" data-placement="top" data-original-title="Opslaan">
													<em class="icon-check mr-sm"></em>
												</button>
												<?php if ($_smarty_tpl->tpl_vars['formdata']->key != 0) {?>
												<button data-title="Bankrekening verwijderen?" data-id="<?php echo $_smarty_tpl->tpl_vars['formdata']->key;?>
" name="del[]" type="button" class="sweet-confirm btn btn-outline-danger btn-icon rounded-round ml-1" data-popup="tooltip" data-placement="top" data-original-title="Verwijderen">
													<em class="icon-cross mr-sm"></em>
												</button>
												<?php }?>
											</div>
										</div>
									</div><!-- /row -->


								</div><!-- /card body -->
							</div>
							<!-- /basic card -->
						<?php
$_smarty_tpl->tpl_vars['formdata'] = $__foreach_formdata_0_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					</form>

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
