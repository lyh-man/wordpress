<?php

class CI_ChatGPT_Shortcodes {

    public function add_shortcode() {
        add_shortcode('ci_chatgpt', array($this, 'ci_chatgpt_ui'));
        $this->ci_chatgpt_ui();
    }

    public function ci_chatgpt_ui() {
        ob_start();
        include CI_PLUGIN_DIR_PATH . 'chatgpt/assets/pages/ui.php';
        return ob_get_clean();
    }

}

