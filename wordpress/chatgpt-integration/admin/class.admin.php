<?php

class CI_Admin {

    private $version;

    public function __construct($version) {
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style('ci_admin_style', CI_PLUGIN_DIR_URL . 'admin/css/admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script('ci_admin_script', CI_PLUGIN_DIR_URL . 'admin/js/admin.js', array('jquery'), $this->version, false);
    }

    public function add_admin_menu() {
        add_menu_page(
            'ChatgptIntegration',
            'ChatgptIntegration',
            'manage_options',
            'wp-ci-menu',
            array($this, 'ci_menu_page')
        );
    }
    public function ci_menu_page() {
        require_once CI_PLUGIN_DIR_PATH . 'admin/pages/index.php';
    }

}
