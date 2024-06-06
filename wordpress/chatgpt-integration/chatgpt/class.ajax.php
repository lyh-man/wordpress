<?php

class CI_ChatGPT_Ajax {

    public function add_ajax() {
        $ajax_events = array(
            'ci_chatgpt_openai_generate_answer' => 'ci_chatgpt_openai_generate_answer'
        );
        foreach ($ajax_events as $ajax_event => $class) {
            add_action('wp_ajax_' . $ajax_event, array($this, $class));
            add_action('wp_ajax_nopriv_' . $ajax_event, array($this, $class));
        }
    }

    public function ci_chatgpt_openai_generate_answer() {
        global $ci_base;
        $res = check_ajax_referer( 'ci_chatgpt_security_key', 'security' );
        if ( isset( $_POST['action'] ) && 'ci_chatgpt_openai_generate_answer' !== $_POST['action'] ) {
            return;
        }
        $question = isset( $_POST['question'] ) ? sanitize_text_field( wp_unslash( $_POST['question'] ) ) : '';

        $args = array(
            'question' => $question,
        );
        $ci_base->chatgpt->ci_chatgpt_openai_generate_answer_output_json( $args );
    }
}
