<?php
/* Smarty version 3.1.33, created on 2019-08-07 22:24:33
  from 'C:\xampp\htdocs\app\application\views\crm\uitzenders\dossier\facturen.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d4b3381cc1219_41422227',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1732f45889c24f638662579e6770197f556e0288' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\crm\\uitzenders\\dossier\\facturen.tpl',
      1 => 1564752125,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:crm/uitzenders/dossier/_sidebar.tpl' => 1,
  ),
),false)) {
function content_5d4b3381cc1219_41422227 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_5591633475d4b3381cad991_20351343', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_20275894075d4b3381cb1813_56857941', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_17467810175d4b3381cb5693_30071416', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19391044825d4b3381cb9511_52817878', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../../../layout.tpl');
}
/* {block "title"} */
class Block_5591633475d4b3381cad991_20351343 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_5591633475d4b3381cad991_20351343',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_20275894075d4b3381cb1813_56857941 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_20275894075d4b3381cb1813_56857941',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_17467810175d4b3381cb5693_30071416 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_17467810175d4b3381cb5693_30071416',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender - <?php echo $_smarty_tpl->tpl_vars['uitzender']->value->bedrijfsnaam;
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_19391044825d4b3381cb9511_52817878 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_19391044825d4b3381cb9511_52817878',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php $_smarty_tpl->_subTemplateRender('file:crm/uitzenders/dossier/_sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('active'=>'facturen'), 0, false);
?>


	<!-------------------------------------------------------------------------------------------------------------------------------------------------------------------
	|| Main content
	--------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	<div class="content-wrapper">

		<!-- Content area -->
		<div class="content">


			<div class="row">
				<div class="col-xl-12">

					<!-- Default tabs -->
					<div class="card">

						<!-- header -->
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Facturen</span>
							<div class="header-elements">

							</div>
						</div>

						<div class="bg-light">
							<ul class="nav nav-tabs nav-tabs-bottom mb-0">
								<li class="nav-item">
									<a href="#card-toolbar-tab1" class="nav-link active show" data-toggle="tab">
										2019
									</a>
								</li>
								<li class="nav-item">
									<a href="#card-toolbar-tab2" class="nav-link" data-toggle="tab">
										2018
									</a>
								</li>
								<li class="nav-item">
									<a href="#card-toolbar-tab2" class="nav-link" data-toggle="tab">
										2017
									</a>
								</li>
							</ul>
						</div>

						<div class="card-body tab-content">
							<div class="tab-pane fade show active" id="card-toolbar-tab1">
								This is the first card tab content
							</div>

							<div class="tab-pane fade" id="card-toolbar-tab2">
								This is the second card tab content
							</div>

							<div class="tab-pane fade" id="card-tab3">
								This is the third card tab content
							</div>

							<div class="tab-pane fade" id="card-tab4">
								This is the fourth card tab content
							</div>
						</div>
					</div>
					<!-- /default tabs -->

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