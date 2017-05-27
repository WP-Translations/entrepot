<?php
/**
 * Galerie hooks.
 *
 * @package Galerie\inc
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', 'galerie_admin_register_scripts' );
add_filter( 'install_plugins_tabs', 'galerie_admin_repositories_tab', 10, 1 );
add_filter( 'install_plugins_table_api_args_galerie_repositories', 'galerie_admin_repositories_tab_args', 10, 1 );
add_filter( 'plugins_api', 'galerie_repositories_api', 10, 3 );
add_action( 'install_plugins_galerie_repositories', 'galerie_admin_repositories_print_templates' );
