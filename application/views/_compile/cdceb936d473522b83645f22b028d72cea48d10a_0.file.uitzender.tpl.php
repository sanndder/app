<?php
/* Smarty version 3.1.33, created on 2020-01-05 21:04:39
  from 'C:\xampp\htdocs\app\application\views\facturenoverzicht\uitzender.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5e124157bc88b7_49279081',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cdceb936d473522b83645f22b028d72cea48d10a' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\facturenoverzicht\\uitzender.tpl',
      1 => 1578254237,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5e124157bc88b7_49279081 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14908694415e124157bb8eb9_17619439', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16482558085e124157bbcd39_04922183', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13543083805e124157bc0bb0_36981895', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('ckeditor', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9187360225e124157bc4a33_88349198', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../layout.tpl');
}
/* {block "title"} */
class Block_14908694415e124157bb8eb9_17619439 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_14908694415e124157bb8eb9_17619439',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Dashboard<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_16482558085e124157bbcd39_04922183 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_16482558085e124157bbcd39_04922183',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
mi-euro-symbol<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_13543083805e124157bc0bb0_36981895 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_13543083805e124157bc0bb0_36981895',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Facturen & Marge<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_9187360225e124157bc4a33_88349198 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_9187360225e124157bc4a33_88349198',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>



	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="card">


				<div class="card-header header-elements-inline">
					<h5 class="card-title">Recente facturen</h5>
				</div>

				<div class="table-responsive">
					<div class="p-4 font-italic">Geen facturen gevonden</div>
				</div>


			</div>

		</div><!-- /content area -->
	</div>
	<!-- /main content -->


<?php
}
}
/* {/block "content"} */
}
