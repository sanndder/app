<?php
/* Smarty version 3.1.33, created on 2019-08-07 22:15:59
  from 'C:\xampp\htdocs\app\application\views\crm\inleners\dossier\overzicht.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4b317f0a5bc8_09560570',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7b841c81136d9aca3a70de48192882537482ec51' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\inleners\\dossier\\overzicht.tpl',
      1 => 1565100513,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/inleners/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d4b317f0a5bc8_09560570 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9229542655d4b317f092340_08900013', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1971159045d4b317f0961c7_84706824', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3717251355d4b317f09a049_21543580', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8737839535d4b317f09dec8_16137921', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_9229542655d4b317f092340_08900013 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_9229542655d4b317f092340_08900013',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inlener<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_1971159045d4b317f0961c7_84706824 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_1971159045d4b317f0961c7_84706824',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_3717251355d4b317f09a049_21543580 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_3717251355d4b317f09a049_21543580',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Inlener - <?php echo $_smarty_tpl->tpl_vars['inlener']->value->bedrijfsnaam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_8737839535d4b317f09dec8_16137921 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_8737839535d4b317f09dec8_16137921',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/inleners/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'overzicht'), 0, false);
?>


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">

			<!-- Basic card -->
			<div class="card">

				<!-- header -->
				<div class="card-header bg-transparent header-elements-inline">
					<span class="text-uppercase font-size-sm font-weight-semibold"><?php echo $_smarty_tpl->tpl_vars['inlener']->value->inlener_id;?>
 - <?php echo $_smarty_tpl->tpl_vars['inlener']->value->bedrijfsnaam;?>
</span>
					<div class="header-elements">

					</div>
				</div>

				<!-- card  body-->
				<div class="card-body">



				</div><!-- /card body-->


			</div><!-- /basic card -->

		</div><!-- /content area -->
	</div>	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
