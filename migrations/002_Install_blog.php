<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migration_Install_blog extends Migration
{
	/**
	 * @var string The name of the database table
	 */
	private $table_name = 'blog_posts';

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

	/**
	 * Install this version
	 *
	 * @return void
	 */
	public function up()
	{
		$this->dbforge->add_field($this->fields);
		$this->dbforge->add_key('id_post', true);
		$this->dbforge->create_table($this->table_name);
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