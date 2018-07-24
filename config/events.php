<?php defined('BASEPATH') || exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

$config['insert_post_blog'][] = array(
        'module'     => 'blog',
        'filepath'   => 'controllers',
        'filename'   => 'Events_category.php',
        'class'  => 'Events_category',
        'method'     => '_add_to_categ'
    );

    $config['show_email_prefs'][] = array(
            'module'     => 'blog',
            'filepath'   => 'controllers',
            'filename'   => 'Blog.php',
            'class'  => 'Blog',
            'method'     => 'emails_prefs'
        );

    $config['get_notifications_user'][] = array(
            'module'     => 'blog',
            'filepath'   => 'controllers',
            'filename'   => 'Blog.php',
            'class'  => 'Blog',
            'method'     => '_get_user_notif'
        );
