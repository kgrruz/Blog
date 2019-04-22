<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_blog extends Migration
{
	/**
	 * @var string The name of the database table
	 */
	private $table_name = 'blog_posts';
	private $table_categ = 'blog_categories';
	private $table_blog_categ = 'blog_categs';
	private $table_comments = 'blog_comments';

	/**
	 * @var array The table's fields
	 */
	private $fields = array(
		'id_post' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true,
		),
        'title_post' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => false,
        ),
          'slug_post' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => false,
        ),
            'preview_image' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'default'    => 'post_preview.jpg',
            'null'       => true,
        ),
        'body_post' => array(
            'type'       => 'TEXT',
            'null'       => false,
        ),
        'enable_comments' => array(
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => '0',
        ),
        'enable_attach' => array(
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => '0',
        ),
        'roles_access' => array(
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'    => false,
        ),
        'deleted' => array(
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => '0',
        ),
        'deleted_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => true,
        ),
        'created_on' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),
        'created_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => false,
        ),
        'modified_on' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),
        'modified_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => true,
        ),

	);

	private $fields_categories = array(
		'id_category' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true,
		),
        'name_category' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => false,
        ),
          'slug_category' => array(
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => false,
        ),
          'desc_category' => array(
            'type'       => 'TEXT',
            'null'       => true,
        ),
        'parent_category' => array(
            'type'       => 'INT',
            'constraint'       => 11,
            'null'       => false,
        ),
        'lft' => array(
					'type'       => 'INT',
					'constraint'       => 11,
					'null'       => false
        ),
        'rgt' => array(
					'type'       => 'INT',
					'constraint'       => 11,
            'null'       => false,
        ),
        'deleted' => array(
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => '0',
        ),
        'deleted_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => true,
        ),
        'created_on' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),
        'created_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => false,
        ),
        'modified_on' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),
        'modified_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => true,
        )

	);
	private $fields_blog_categs = array(
		'id_join_category' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true
		),
        'category_id' => array(
					'type'       => 'INT',
					'constraint' => 11,
            'null'       => false
        ),
        'blog_post_id' => array(
					'type'       => 'INT',
					'constraint' => 11,
            'null'       => false
        ),
        'deleted' => array(
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => '0',
        ),
        'deleted_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => true,
        ),
        'created_on' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),
        'created_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => false,
        ),
        'modified_on' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),
        'modified_by' => array(
            'type'       => 'BIGINT',
            'constraint' => 20,
            'null'       => true,
        )

	);
	private $fields_blog_comments = array(
		'id' => array(
			'type'       => 'INT',
			'constraint' => 11,
			'auto_increment' => true
		),
        'post_id' => array(
					'type'       => 'INT',
					'constraint' => 11,
            'null'       => false
        ),
        'parent' => array(
					'type'       => 'INT',
					'constraint' => 11,
            'null'       => false
        ),
				'content' => array(
					'type'       => 'TEXT',
						'null'       => true
				),
        'file_url' => array(
					'type'       => 'VARCHAR',
					'constraint' => 255,
            'null'       => true
        ),
        'file_mime_type' => array(
					'type'       => 'VARCHAR',
					'constraint' => 20,
            'null'       => true
        ),
        'file_size' => array(
					'type'       => 'INT',
					'constraint' => 10,
            'null'       => true
        ),
        'approved' => array(
					'type'       => 'TINYINT',
					'constraint' => 1,
            'default'    => '0'
        ),
        'created' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),
        'creator' => array(
					'type'       => 'INT',
					'constraint' => 11,
						'null'       => false
        ),
        'created_by_admin' => array(
            'type'       => "set('false', 'true')",
            'null'       => false
        ),
        'modified' => array(
            'type'       => 'datetime',
            'default'    => '0000-00-00 00:00:00',
        ),
        'modified_by' => array(
					'type'       => 'INT',
					'constraint' => 11,
						'null'       => true
        ),
        'upvote_count' => array(
            'type'       => 'INT',
            'constraint' => 11,
            'null'       => true,
        ),
        'user_has_upvoted' => array(
            'type'       => "set('false', 'true')",
            'null'       => false
        ),
				'deleted' => array(
						'type'       => 'TINYINT',
						'constraint' => 1,
						'default'    => '0',
        ),
				'deleted_by' => array(
					'type'       => 'INT',
					'constraint' => 11,
					'null'       => true
				)
	);

	/**
	 * Install this version
	 *
	 * @return void
	 */
	public function up(){

		if ( ! $this->db->table_exists($this->table_name)){
		$this->dbforge->add_field($this->fields);
		$this->dbforge->add_key('id_post', true);
		$this->dbforge->create_table($this->table_name);
	}

		if ( ! $this->db->table_exists($this->table_categ)){
		$this->dbforge->add_field($this->fields_categories);
		$this->dbforge->add_key('id_category', true);
		$this->dbforge->create_table($this->table_categ);

		$this->lang->load('blog/category');

		$first_categ = array(
			'name_category'=>$this->lang->line('category_first_categ_name'),
			'slug_category'=>$this->lang->line('category_first_categ_slug'),
			'parent_category'=>0,
			'lft'=>1,
			'rgt'=>2,
			'desc_category'=>$this->lang->line('category_first_categ_desc'),
			'created_on'=>date('Y-m-d H:i:s'),
			'created_by'=>1
		);

		$this->db->insert("blog_categories",$first_categ);

	}
		if ( ! $this->db->table_exists($this->table_blog_categ)){
		$this->dbforge->add_field($this->fields_blog_categs);
		$this->dbforge->add_key('id_join_category', true);
		$this->dbforge->create_table($this->table_blog_categ);
	}
		if ( ! $this->db->table_exists($this->table_comments)){
		$this->dbforge->add_field($this->fields_blog_comments);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table($this->table_comments);
	}


	$version = Modules::config("blog");

	$data_st = array(
		'name' => 'blog.module_update',
		'module' => 'blog',
		'value' => serialize(array("timestamp"=>time(),"version"=>$version['version'],"update"=>0))
	);

	$this->db->insert("settings",$data_st);

	$settings_data = array(
		array(
		'name' => 'blog.post_visibility',
		'module' => 'blog',
		'value' => 1
	),
		array(
		'name' => 'blog.email_new_comment',
		'module' => 'blog',
		'value' => 0
	),
		array(
		'name' => 'blog.email_new_comment_mod',
		'module' => 'blog',
		'value' => 1
	),
		array(
		'name' => 'blog.must_aprove_comment',
		'module' => 'blog',
		'value' => 0
	),
		array(
		'name' => 'blog.block_post_after',
		'module' => 'blog',
		'value' => '1 month'
	),
		array(
		'name' => 'blog.comment_flood',
		'module' => 'blog',
		'value' => '1 minute'
	)
	);

	$this->db->insert_batch("settings",$settings_data);

	$email_preferences = array(
		'preference_name'=>"blog_new_post",
		'preference_desc'=>"blog_new_post_desc",
		'module'=>"blog",
	);

	$this->db->insert("email_preferences",$email_preferences);


	$data_widget = array(
		'name_widget'=>'News',
		'description_widget'=>'Display list of lastest posts',
		'order_view'=>0,
		'panel'=>'available',
		'path'=>serialize(array("class"=>"Blog_events","function"=>"_show_widget_news")),
		'module'=>'blog'
	);

	$this->db->insert("widgets",$data_widget);

	}

	/**
	 * Uninstall this version
	 *
	 * @return void
	 */
	public function down()
	{
		$this->dbforge->drop_table($this->table_name);
	}
}
