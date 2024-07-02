<?php

class CI_ChatGPT_Shortcodes {

    public function add_shortcode() {
        add_shortcode('ci_chatgpt', array($this, 'ci_chatgpt_ui'));
        add_shortcode('ci_chatgpt_interprets_dreams', array($this, 'ci_chatgpt_interprets_dreams'));
        $this->ci_chatgpt_ui();
        $this->ci_chatgpt_interprets_dreams();
    }

    public function ci_chatgpt_ui() {
        ob_start();
        include CI_PLUGIN_DIR_PATH . 'chatgpt/assets/pages/ui.php';
        return ob_get_clean();
    }

    public function ci_chatgpt_interprets_dreams() {
        ob_start();
        include CI_PLUGIN_DIR_PATH . 'chatgpt/assets/pages/interprets-dreams.php';
        return ob_get_clean();
    }

}

