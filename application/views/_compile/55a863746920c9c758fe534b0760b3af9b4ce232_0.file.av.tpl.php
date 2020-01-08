<?php
/* Smarty version 3.1.33, created on 2020-01-07 15:43:09
  from 'C:\xampp\htdocs\app\application\views\instellingen\werkgever\av.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e1498fd577df0_16101839',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '55a863746920c9c758fe534b0760b3af9b4ce232' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\instellingen\\werkgever\\av.tpl',
      1 => 1575899290,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:instellingen/werkgever/_sidebar.tpl' => 1,
    'file:instellingen/werkgever/_topbar.tpl' => 1,
  ),
),false)) {
function content_5e1498fd577df0_16101839 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9805667615e1498fd550cf9_74525991', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11915423215e1498fd554b76_03005641', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6581462665e1498fd5589f9_61769472', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('ckeditor', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5801253745e1498fd55c874_62515752', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_9805667615e1498fd550cf9_74525991 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_9805667615e1498fd550cf9_74525991',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_11915423215e1498fd554b76_03005641 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_11915423215e1498fd554b76_03005641',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-cog<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_6581462665e1498fd5589f9_61769472 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_6581462665e1498fd5589f9_61769472',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen werkgever<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_5801253745e1498fd55c874_62515752 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_5801253745e1498fd55c874_62515752',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

	<?php echo '<script'; ?>
>
        


        
	<?php echo '</script'; ?>
>
    <?php $_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'av'), 0, false);
?>

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<?php if (isset($_smarty_tpl->tpl_vars['msg']->value)) {
echo $_smarty_tpl->tpl_vars['msg']->value;
}?>

            <?php $_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_topbar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

			<!-- Basic card -->
			<div class="card">
				<div class="card-body">

					<form method="post" action="">

						<div class="row mb-2">
							<div class="col-lg-12">

								<button type="submit" name="set" value="save" class="btn btn-success">
									<i class="icon-check mr-1"></i>Wijzigingen opslaan
								</button>

								<button type="submit" name="set" value="activate" class="btn btn-primary">
									<i class="icon-file-check2 mr-1"></i>Publiceren
								</button>

							</div>
						</div>


						<div class="row">
							<div class="col-lg-6">

								<textarea name="editor" id="editor"><?php echo $_smarty_tpl->tpl_vars['av']->value;?>
</textarea>

							</div><!-- /col -->
							<div class="col-lg-2">

								

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
