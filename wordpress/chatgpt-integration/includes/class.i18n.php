<?php

class CI_I18n {

    public function load_plugin_textdomain() {
        load_plugin_textdomain(CI_PLUGIN_NAME, false, CI_PLUGIN_DIR_PATH . '/languages/');
    }

}
