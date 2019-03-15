<?php defined('BASEPATH') || exit('No direct script access allowed');

$config['module_config'] = array(
	'description'	=> 'Manage your blog posts',
	'category'    => 'Media',
	'name'		    => 'Blog',
	'home' 				=> 'blog',
	'label'       => array('english'=>'Blog','portuguese_br'=>'Blog','spanish_am'=>'Blog'),
	'route'       => 'blog',
	'visible_module' => true,
	'context_customer' => false,
	'version'		=> '1.2.0',
	'author'		=> 'admin',
);

$config['install_check'] = array(
'php_version'=>array('5.6','>='),
'gestor_version'=>array('0.4.0-dev','>='),
'php_ext'=>array('mbstring'),
'modules'=>array());
