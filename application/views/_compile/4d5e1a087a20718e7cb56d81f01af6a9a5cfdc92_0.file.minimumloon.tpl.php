<?php
/* Smarty version 3.1.33, created on 2019-08-20 13:02:43
  from 'C:\xampp\htdocs\app\application\views\instellingen\werkgever\minimumloon.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5bd35321eb69_36420215',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4d5e1a087a20718e7cb56d81f01af6a9a5cfdc92' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\instellingen\\werkgever\\minimumloon.tpl',
      1 => 1565793538,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:instellingen/werkgever/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d5bd35321eb69_36420215 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14505169475d5bd3531efd55_41791801', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2460341245d5bd3531f3bd4_41222429', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4445972745d5bd3531f7a59_17530039', "header-title");
?>

<?php $_smarty_tpl->_assignInScope('uploader', "true");?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_13240868285d5bd3531fb8e9_59892674', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../layout.tpl');
}
/* {block "title"} */
class Block_14505169475d5bd3531efd55_41791801 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_14505169475d5bd3531efd55_41791801',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_2460341245d5bd3531f3bd4_41222429 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_2460341245d5bd3531f3bd4_41222429',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-cog<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_4445972745d5bd3531f7a59_17530039 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_4445972745d5bd3531f7a59_17530039',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Instellingen werkgever<?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_13240868285d5bd3531fb8e9_59892674 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_13240868285d5bd3531fb8e9_59892674',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


    <?php $_smarty_tpl->_subTemplateRender('file:instellingen/werkgever/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'bedrijfsgegevens'), 0, false);
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


						<div class="row">
							<div class="col-lg-12">
								<button type="submit" name="set" class="btn btn-success"><i	class="icon-checkmark2 mr-1"></i>Opslaan
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
