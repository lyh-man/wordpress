<?php

class CI_Base {

    protected $loader;
    protected $name;
    protected $version;
    public $chatgpt;

    public function __construct() {
        if (defined('CI_PLUGIN_VERSION')) {
            $this->version = CI_PLUGIN_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        if (defined('CI_PLUGIN_NAME')) {
            $this->name = CI_PLUGIN_NAME;
        } else {
            $this->name = 'chatgpt-integration';
        }

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_chatgpt_hooks();
    }

    private function load_dependencies() {
        require_once CI_PLUGIN_DIR_PATH . 'includes/class.loader.php';
        $this->loader = new CI_Loader();
    }

    private function set_locale() {
        require_once CI_PLUGIN_DIR_PATH . 'includes/class.i18n.php';
        $plugin_i18n = new CI_I18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    private function define_admin_hooks() {
        require_once CI_PLUGIN_DIR_PATH . 'admin/class.admin.php';
        $plugin_admin = new CI_Admin($this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_menu');
    }

    public function define_chatgpt_hooks() {
        require_once CI_PLUGIN_DIR_PATH . 'chatgpt/class.main.php';
        $this->chatgpt = new CI_ChatGPT_Main($this->get_version());
        $this->loader->add_action('wp_enqueue_scripts', $this->chatgpt, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $this->chatgpt, 'enqueue_scripts');

        require_once CI_PLUGIN_DIR_PATH . 'chatgpt/class.ajax.php';
        $chatgpt_ajax = new CI_ChatGPT_Ajax();
        $this->loader->add_action('init', $chatgpt_ajax, 'add_ajax');

        if (is_admin()) {
            require_once CI_PLUGIN_DIR_PATH . 'chatgpt/admin/class.admin.php';
            $chatgpt_admin = new CI_ChatGPT_Admin();
            $this->loader->add_action('admin_menu', $chatgpt_admin, 'add_submenu_page');
            $this->loader->add_action('admin_init', $chatgpt_admin, 'register_setting');
        }

        require_once CI_PLUGIN_DIR_PATH . 'chatgpt/class.shortcodes.php';
        $chatgpt_shortcode = new CI_ChatGPT_Shortcodes();
        $this->loader->add_action('init', $chatgpt_shortcode, 'add_shortcode');
    }

    public function run() {
        $this->loader->run();
    }

    public function get_name() {
        return $this->name;
    }

    public function get_loader() {
        return $this->loader;
    }

    public function get_version() {
        return $this->version;
    }

    public function get_chatgpt() {
        return $this->chatgpt;
    }

}
