<?php

class mf_faq
{
	var $post_type = 'mf_faq';
	var $meta_prefix;

	function __construct()
	{
		$this->meta_prefix = $this->post_type.'_';
	}

	function block_render_callback($attributes)
	{
		global $wpdb;

		$out = "<div".parse_block_attributes(array('class' => "widget faq", 'attributes' => $attributes)).">";

			$result= $wpdb->get_results($wpdb->prepare("SELECT ID, post_title, post_content FROM ".$wpdb->posts." WHERE post_type = %s AND post_status = %s ORDER BY menu_order ASC", $this->post_type, 'publish'));

			foreach($result as $r)
			{
				$post_meta = get_post_meta($r->ID, $this->meta_prefix.'open_on_load', true);

				$out .= get_toggler_container(array('type' => 'start', 'text' => $r->post_title, 'is_open' => ($post_meta == 'yes')))
					.apply_filters('the_content', $r->post_content)
				.get_toggler_container(array('type' => 'end'));
			}

		$out .= "</div>";

		return $out;
	}

	function enqueue_block_editor_assets()
	{
		$plugin_include_url = plugin_dir_url(__FILE__);
		$plugin_version = get_plugin_version(__FILE__);

		wp_register_script('script_faq_block_wp', $plugin_include_url."block/script_wp.js", array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-block-editor'), $plugin_version, true);

		wp_localize_script('script_faq_block_wp', 'script_faq_block_wp', array(
			'block_title' => __("FAQ", 'lang_faq'),
			'block_description' => __("Display FAQ", 'lang_faq'),
		));
	}

	function init()
	{
		load_plugin_textdomain('lang_faq', false, str_replace("/include", "", dirname(plugin_basename(__FILE__)))."/lang/");

		register_post_type($this->post_type, array(
			'labels' => array(
				'name' => __("FAQ", 'lang_faq'),
				'singular_name' => __("FAQ", 'lang_faq'),
				'menu_name' => __("FAQ", 'lang_faq')
			),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => false,
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-format-status',
			'supports' => array('title', 'editor'),
			'hierarchical' => true,
			'has_archive' => false,
		));

		register_block_type('mf/faq', array(
			'editor_script' => 'script_faq_block_wp',
			'editor_style' => 'style_base_block_wp',
			'render_callback' => array($this, 'block_render_callback'),
		));
	}

	function column_header($columns)
	{
		global $post_type;

		unset($columns['date']);

		switch($post_type)
		{
			case $this->post_type:
				$columns['open_on_load'] = __("Open on Load", 'lang_faq');
			break;
		}

		return $columns;
	}

	function column_cell($column, $post_id)
	{
		global $wpdb, $post;

		switch($post->post_type)
		{
			case $this->post_type:
				switch($column)
				{
					case 'open_on_load':
						$post_meta = get_post_meta($post_id, $this->meta_prefix.$column, true);

						echo "<i class='fa ".($post_meta == 'yes' ? "fa-check green" : "fa-times red")." fa-lg'></i>";
					break;
				}
			break;
		}
	}

	function rwmb_meta_boxes($meta_boxes)
	{
		$meta_boxes[] = array(
			'id' => $this->meta_prefix.'faq',
			'title' => __("Settings", 'lang_faq'),
			'post_types' => $this->post_type,
			'context' => 'side',
			//'priority' => 'high',
			'fields' => array(
				array(
					'name' => __("Open on Load", 'lang_faq'),
					'id' => $this->meta_prefix.'open_on_load',
					'type' => 'select',
					'options' => get_yes_no_for_select(),
					'multiple' => false,
					'std' => 'no',
				),
			)
		);

		return $meta_boxes;
	}
}