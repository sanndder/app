<?php
/* Smarty version 3.1.33, created on 2019-08-15 16:44:44
  from 'C:\xampp\htdocs\app\application\views\test\vue.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d556fdc3ee6b1_01320214',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4283cd20ea23ae061315d32516e8a91e90bece67' => 
    array (
      0 => 'C:\\xampp\\htdocs\\app\\application\\views\\test\\vue.tpl',
      1 => 1565880271,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d556fdc3ee6b1_01320214 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12059771675d556fdc3decb2_63868086', "title");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_16352102795d556fdc3e2b35_11185884', "header-icon");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6360596555d556fdc3e69b9_50225962', "header-title");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4026842855d556fdc3ea832_01877156', "content");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, '../layout.tpl');
}
/* {block "title"} */
class Block_12059771675d556fdc3decb2_63868086 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'title' => 
  array (
    0 => 'Block_12059771675d556fdc3decb2_63868086',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender<?php
}
}
/* {/block "title"} */
/* {block "header-icon"} */
class Block_16352102795d556fdc3e2b35_11185884 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-icon' => 
  array (
    0 => 'Block_16352102795d556fdc3e2b35_11185884',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
icon-office<?php
}
}
/* {/block "header-icon"} */
/* {block "header-title"} */
class Block_6360596555d556fdc3e69b9_50225962 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header-title' => 
  array (
    0 => 'Block_6360596555d556fdc3e69b9_50225962',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
Uitzender - <?php
}
}
/* {/block "header-title"} */
/* {block "content"} */
class Block_4026842855d556fdc3ea832_01877156 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_4026842855d556fdc3ea832_01877156',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
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
					<div class="card" id="vue-app">

						<!-- header -->
						<div class="card-header bg-transparent header-elements-inline">
							<span class="text-uppercase font-size-sm font-weight-semibold">Facturen</span>
							<div class="header-elements">

							</div>
						</div>
						
						<div class="bg-light" >

							<ul class="nav nav-tabs nav-tabs-bottom mb-0">
								<li class="nav-item">
									<a href="#card-toolbar-tab1" @click.prevent="" class="nav-link">
										2019
									</a>
								</li>
								<li class="nav-item">
									<a href="#card-toolbar-tab2" @click.prevent="" class="nav-link">
										2018
									</a>
								</li>
								<li class="nav-item">
									<a href="#card-toolbar-tab2" @click.prevent="" class="nav-link">
										2017
									</a>
								</li>
							</ul>
						</div>

						<div class="card-body tab-content">
							<div class="tab-pane fade show active" id="card-toolbar-tab1">
								<datepicker></datepicker>
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
