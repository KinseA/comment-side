<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Facebook' => array(
            'client_id'     => '760160750726833',
            'client_secret' => 'b090ea21cc06d444a41e00b3e835a503',
            'scope'         => array(),
        ),

        'Twitter' => array(
		    'client_id'     => 'l2gGzT5oLdj4eGbNGfEEMpCmS',
		    'client_secret' => 'ogJV36BIAWN1jdjjxXLTjMuDMtrrmhJFj050KChJiJAQ6cr3OJ',
		),

		'Google' => array(
		    'client_id'     => '892037698680-1gaq9d97f1b0gai1q1inrkpb7ntaj0rk.apps.googleusercontent.com',
		    'client_secret' => 'xPT9yBB6fgAoD6a-DhjgUJGk',
		    'scope' => array('userinfo_email', 'userinfo_profile','https://www.googleapis.com/auth/youtube.readonly'),
		),  		

	)

);