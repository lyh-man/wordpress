<?php

class CI_ChatGPT_Admin {

    public function register_setting() {
        register_setting('ci_chatgpt_settings', 'ci_chatgpt_setting');
    }

    public function add_submenu_page() {
        add_submenu_page(
            'wp-ci-menu',
            'ChatGPT',
            'ChatGPT',
            'manage_options',
            'wp-ci-chatgpt-settings',
            array($this, 'add_admin_settings_page')
        );
    }

    public function add_admin_settings_page() {
        require_once CI_PLUGIN_DIR_PATH . 'chatgpt/admin/pages/settings.php';
    }
}

