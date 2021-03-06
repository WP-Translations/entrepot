<?php
/**
 * Entrepôt hooks.
 *
 * @package Entrepôt\inc
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Aliases the admin_init hook for unit testing purpose.
 *
 * @since 1.0.0
 */
function entrepot_admin_init() {
	do_action( 'entrepot_admin_init' );
}
add_action( 'admin_init',          'entrepot_admin_init',       999 );
add_action( 'entrepot_admin_init', 'entrepot_admin_updater'         );
add_action( 'plugins_loaded',      'entrepot_setup_cache_group'     );

// Always hook these, even in multisite configs.
add_action( 'admin_menu',          'entrepot_admin_add_menu'             );
add_filter( 'plugin_action_links', 'entrepot_plugin_action_links', 10, 3 );

if ( is_multisite() ) {
	add_action( 'network_admin_menu',                'entrepot_admin_add_menu'             );
	add_filter( 'network_admin_plugin_action_links', 'entrepot_plugin_action_links', 10, 3 );
}

add_action( 'admin_head',      'entrepot_admin_head'                            );
add_action( 'admin_init',      'entrepot_admin_register_scripts'                );
add_filter( 'all_plugins',     'entrepot_all_installed_repositories_list'       );
add_filter( 'plugin_row_meta', 'entrepot_plugin_row_meta',                10, 3 );
add_action( 'in_admin_header', 'entrepot_catch_all_notices',               1    );

// Ease repositories identification
add_filter( 'extra_plugin_headers', 'entrepot_extra_header', 10, 1 );

// Manage repositories Upgrades
add_filter( 'set_site_transient_update_plugins', 'entrepot_update_repositories' );

// Plugins Install Screen > Entrepôt Tab.
add_filter( 'install_plugins_tabs',                                 'entrepot_admin_repositories_tab',            10, 1 );
add_filter( 'install_plugins_table_api_args_entrepot_repositories', 'entrepot_admin_repositories_tab_args',       10, 1 );
add_action( 'install_plugins_entrepot_repositories',                'entrepot_admin_repositories_print_templates'       );
add_action( 'install_plugins_pre_plugin-information',               'entrepot_admin_repository_information',       5    );

// Override the Plugins API for Repositories.
add_filter( 'plugins_api', 'entrepot_repositories_api', 10, 3 );

// Filters for modal content.
add_filter( 'entrepot_repository_modal_content', 'entrepot_sanitize_repository_content', 9 );
add_filter( 'entrepot_repository_modal_content', 'links_add_target'                        );

// Registers REST API routes.
add_action( 'rest_api_init', 'entrepot_rest_routes', 100 );

/**
 * Restricts the Plugins editor to only allow Plugin or custom functions file edits
 * when they use a "Allow File Edits:" Plugin Header Tag set to true.
 *
 * @since 1.2.0
 */
function entrepot_plugins_code_editor_restrictions() {
	/**
	 * Filter here returning false to disable these restrictions
	 *
	 * @since 1.2.0
	 *
	 * @param boolean $value True to restrict Plugins editor. False otherwise.
	 */
	if ( false === apply_filters( 'entrepot_plugins_code_editor_restrictions', true ) ) {
		return;
	}

	add_action( 'load-plugin-editor.php',         'entrepot_admin_plugin_editor_load'        );
	add_action( 'wp_ajax_edit-theme-plugin-file', 'entrepot_ajax_before_edit_plugin_file', 0 );
	add_action( 'admin_footer-plugin-editor.php', 'entrepot_admin_plugin_editor_footer'      );
	add_action( 'wp_ajax_edit-theme-plugin-file', 'entrepot_ajax_after_edit_plugin_file',  2 );
}
add_action( 'entrepot_admin_init', 'entrepot_plugins_code_editor_restrictions' );

// Load translations
add_action( 'plugins_loaded', 'entrepot_load_textdomain', 9 );
