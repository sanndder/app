<?php
/* Smarty version 3.1.33, created on 2019-08-07 22:24:14
  from 'C:\xampp\htdocs\app\application\views\users\overzicht.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4b336e67f234_28961255',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4a07db14191bed4f053bd5cb41114dae11a873cc' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\users\\overzicht.tpl',
      1 => 1558642180,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:instellingen/werkgever/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d4b336e67f234_28961255 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20870724985d4b336e66b9a8_14041836', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2752555235d4b336e66f832_59156230', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14449378125d4b336e6736b2_25511473', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5554581965d4b336e677536_84875657', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../layout.tpl');
}
/* {block "title"} */
class Block_20870724985d4b336e66b9a8_14041836 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_20870724985d4b336e66b9a8_14041836',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_2752555235d4b336e66f832_59156230 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_2752555235d4b336e66f832_59156230',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-cog<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_14449378125d4b336e6736b2_25511473 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_14449378125d4b336e6736b2_25511473',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen <?php echo $_smarty_tpl->tpl_vars['usertype']->value;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_5554581965d4b336e677536_84875657 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_5554581965d4b336e677536_84875657',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php if ($_smarty_tpl->tpl_vars['usertype']->value == 'werkgever') {
$_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'users'), 0, false);
}?>

	<!-- Main content -->
	<div class="content-wrapper">
		<!-- Content area -->
		<div class="content">

			<div class="row">
				<div class="col-xl-12">

					<!-- Basic card -->
					<div class="card">
						<div class="card-body">



						</div><!-- /card body -->
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
