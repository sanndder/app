<?php
/* Smarty version 3.1.33, created on 2020-01-08 14:32:33
  from 'C:\xampp\htdocs\app\application\views\crm\inleners\dossier\emailadressen.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e15d9f1c6b8b4_31672500',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0264628f37012099ed6a5d643eb58f40359626b8' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\inleners\\dossier\\emailadressen.tpl',
      1 => 1578252882,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/inleners/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5e15d9f1c6b8b4_31672500 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9865175435e15d9f1c40939_64888200', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19402386625e15d9f1c447b4_29733819', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19527481005e15d9f1c48632_78723980', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16388785875e15d9f1c4c4b4_96735006', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_9865175435e15d9f1c40939_64888200 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_9865175435e15d9f1c40939_64888200',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inlener<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_19402386625e15d9f1c447b4_29733819 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_19402386625e15d9f1c447b4_29733819',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_19527481005e15d9f1c48632_78723980 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_19527481005e15d9f1c48632_78723980',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inlener - <?php echo $_smarty_tpl->tpl_vars['inlener']->value->bedrijfsnaam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_16388785875e15d9f1c4c4b4_96735006 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_16388785875e15d9f1c4c4b4_96735006',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


    <?php $_smarty_tpl->_subTemplateRender('file:crm/inleners/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'emailadressen'), 0, false);
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
					<div class="col-xl-10">
                        <?php echo $_smarty_tpl->tpl_vars['msg']->value;?>

					</div><!-- /col -->
				</div>
				<!-- /row -->
            <?php }?>

			<div class="row">
				<div class="col-xl-10">

					<!-- Basic card -->
					<div class="card">

						<!-- card  body-->
						<div class="card-body">

							<form method="post" action="">


                                                                <?php $_smarty_tpl->_assignInScope('label_lg', "3");?>
                                <?php $_smarty_tpl->_assignInScope('div_xl', "4");?>
                                <?php $_smarty_tpl->_assignInScope('div_md', "6");?>


								<fieldset class="mb-2">
									<legend class="mb-2 text-uppercase font-size-sm font-weight-bold">Standaard emailadres</legend>
									<div class="mb-3">Het standaard emailadres is verplicht.</div>

									<!-- standaard -->
                                    <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['standaard'])) {?>
                                        <?php $_smarty_tpl->_assignInScope('field', "standaard");?>
										<div class="form-group row">
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
                                                <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
													<span class="form-text text-danger">
                                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br/><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
													</span>
                                                <?php }?>
											</div>
										</div>
                                    <?php }?>

								</fieldset>

								<fieldset class="mb-2">
									<legend class="mb-2 text-uppercase font-size-sm font-weight-bold">Emailadres facturatie</legend>
									<div class="mb-3">U kunt een appart emailadres opgeven voor uw facturen. Indien u geen emailadres opgeeft wordt het standaard emailadres gebruikt.</div>

									<!-- standaard -->
                                    <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['facturatie'])) {?>
                                        <?php $_smarty_tpl->_assignInScope('field', "facturatie");?>
										<div class="form-group row">
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
                                                <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
													<span class="form-text text-danger">
                                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br/><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
													</span>
                                                <?php }?>
											</div>
										</div>
                                    <?php }?>

								</fieldset>

								<fieldset class="mb-2">
									<legend class="mb-2 text-uppercase font-size-sm font-weight-bold">Emailadres administratie</legend>
									<div class="mb-3">U kunt een appart emailadres opgeven voor uw contracten en overeenkomsten. Indien u geen emailadres opgeeft wordt het standaard emailadres gebruikt.</div>

									<!-- standaard -->
                                    <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value['administratie'])) {?>
                                        <?php $_smarty_tpl->_assignInScope('field', "administratie");?>
										<div class="form-group row">
											<div class="col-xl-<?php echo $_smarty_tpl->tpl_vars['div_xl']->value;?>
 col-md-<?php echo $_smarty_tpl->tpl_vars['div_md']->value;?>
">
												<input value="<?php echo $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
" type="text" class="form-control <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>border-danger<?php }?>" placeholder="" autocomplete="off">
                                                <?php if (isset($_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'])) {?>
													<span class="form-text text-danger">
                                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['formdata']->value[$_smarty_tpl->tpl_vars['field']->value]['error'], 'e');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
echo $_smarty_tpl->tpl_vars['e']->value;?>
<br/><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
													</span>
                                                <?php }?>
											</div>
										</div>
                                    <?php }?>

								</fieldset>


								<!-- opslaan -->
								<div class="row">
									<div class="col-lg-12 mb-3">
										<button type="submit" name="set" class="btn btn-success btn-sm">
											<i class="icon-checkmark2 mr-1"></i>Wijzigingen opslaan
										</button>
									</div><!-- /col -->
								</div><!-- /row -->

							</form>

						</div><!-- /card body-->
					</div><!-- /basic card -->

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
