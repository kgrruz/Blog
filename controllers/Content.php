<?php defined('BASEPATH') || exit('No direct script access allowed');

class Content extends Admin_Controller{
    /**
     * Basic constructor. Calls the Admin_Controller's constructor, then sets
     * the toolbar title displayed on the admin/content/blog page.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        Template::set('toolbar_title', 'Manage Your Blog');
    }

    /**
     * The default page for this context.
     *
     * @return void
     */
    public function index()
    {
        Template::render();
    }
}
