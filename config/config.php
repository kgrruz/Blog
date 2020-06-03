<?php defined('BASEPATH') || exit('No direct script access allowed');

$config['module_config'] = array(
	'description'	=> 'Manage your blog posts',
	'category'    => 'Media',
	'name'		    => 'Blog',
	'home' 				=> 'blog',
	'fa_icon' 				=> 'fas fa-newspaper',
	'label'       => array('en_US'=>'Blog','pt_BR'=>'Blog','es'=>'Blog','ru'=>'блог'),
	'label_public'       => array('en_US'=>'Blog','pt_BR'=>'Novidades','es'=>'Blog','ru'=>'блог'),
	'route'       => 'blog',
	'visible_module' => true,
	'context_customer' => false,
	'version'		=> '1.3.0',
	'author'		=> 'admin',
);

$config['install_check'] = array(
'php_version'=>array('5.6','>='),
'gestor_version'=>array('0.9.3-dev','>='),
'php_ext'=>array('mbstring'),
'modules'=>array());
