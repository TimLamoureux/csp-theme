<?php

class Conditional_Enqueuer {
	const TYPE_PAGE_TEMPLATE = 1;

	private $page_templates = [];

	function __construct() {
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue') );
	}

	function enqueue() {
		do_action('before_conditional_enqueue');
		foreach($this->page_templates as $key => $page_template) {
			if ( is_page_template( $key ) && !empty($page_template['scripts']) ) {
				wp_enqueue_script(
					$page_template['scripts']['handle'],
					$page_template['scripts']['script'],
					$page_template['scripts']['dep'],
					$page_template['scripts']['ver'],
					$page_template['scripts']['in_footer']
				);
			}
		}
		do_action('after_conditional_enqueue');
	}

	function add_script(
		$handle = null,
		$script = null,
		$dep = null,
		$ver = null,
		$in_footer = true,
		$type = self::TYPE_PAGE_TEMPLATE,
		$arg = null
	) {

		// TODO: Condition callbacks, example if already loaded by other module

		if (self::TYPE_PAGE_TEMPLATE == $type) {
			if ( null == $handle /*|| null == $script*/ || null == arg )
				return false;

			foreach ($arg as $page) {
				$this->page_templates[$page]['scripts'] = [
					'handle' => $handle,
					'script' => $script,
					'dep' => $dep,
					'ver' => $ver,
					'in_footer' => $in_footer
				];
			}
			return true;
		}
		return false;
	}
}





