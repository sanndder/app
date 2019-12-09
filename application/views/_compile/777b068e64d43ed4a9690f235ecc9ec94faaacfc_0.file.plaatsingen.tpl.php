<?php
/* Smarty version 3.1.33, created on 2019-12-05 10:27:53
  from 'C:\xampp\htdocs\app\application\views\crm\werknemers\dossier\plaatsingen.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5de8cd99a08d97_59947668',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '777b068e64d43ed4a9690f235ecc9ec94faaacfc' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\werknemers\\dossier\\plaatsingen.tpl',
      1 => 1574341031,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/werknemers/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5de8cd99a08d97_59947668 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_7590748865de8cd999e9997_64804753', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_205580985de8cd999ed818_79780428', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15060898755de8cd999f1695_91689372', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('select2', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13741226795de8cd999f9391_98186362', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_7590748865de8cd999e9997_64804753 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_7590748865de8cd999e9997_64804753',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Werknemer<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_205580985de8cd999ed818_79780428 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_205580985de8cd999ed818_79780428',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_15060898755de8cd999f1695_91689372 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_15060898755de8cd999f1695_91689372',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Werknemer - <?php echo $_smarty_tpl->tpl_vars['werknemer']->value->naam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_13741226795de8cd999f9391_98186362 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_13741226795de8cd999f9391_98186362',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/werknemers/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'plaatsingen'), 0, false);
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

							<!--------------------------------------------- Uitzender ------------------------------------------------->
							<form method="post" action="">
								<fieldset class="">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Uitzender</legend>
								</fieldset>

								<table>
									<tr>
										<td class="pr-2" style="width: 500px">
											<select required name="uitzender_id" class="form-control select-search">
												<option value="">Selecteer een uitzender</option>
                                                <?php if ($_smarty_tpl->tpl_vars['uitzenders']->value !== NULL) {?>
                                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['uitzenders']->value, 'u');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['u']->key => $_smarty_tpl->tpl_vars['u']->value) {
$__foreach_u_0_saved = $_smarty_tpl->tpl_vars['u'];
?>
														<option <?php if ($_smarty_tpl->tpl_vars['werknemer_uitzender']->value['uitzender_id'] == $_smarty_tpl->tpl_vars['u']->key) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['u']->key;?>
"><?php echo $_smarty_tpl->tpl_vars['u']->key;?>
 - <?php echo $_smarty_tpl->tpl_vars['u']->value;?>
</option>
                                                    <?php
$_smarty_tpl->tpl_vars['u'] = $__foreach_u_0_saved;
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                <?php }?>
											</select>
										</td>
										<td>
											<button type="submit" name="set" value="set_uitzender" class="btn btn-outline-success btn-sm">
												<i class="icon-check mr-1"></i>Wijzigen
											</button>
										</td>
									</tr>
								</table>

							</form>


							<!--------------------------------------------- Plaatsing ------------------------------------------------->
							<form method="post" action="">
								<fieldset class="mt-4">
									<legend class="text-uppercase font-size-sm font-weight-bold text-primary">Plaatsingen</legend>
								</fieldset>



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
