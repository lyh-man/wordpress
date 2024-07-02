<?php

class CI_ChatGPT_Main {

    private $version;

    public function __construct($version) {
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style('dashicons', get_template_directory_uri() . '/path/to/dashicons.min.css', array(), null);
        wp_enqueue_style('ci_chatgpt_style', CI_PLUGIN_DIR_URL . 'chatgpt/assets/css/chatgpt.css', array(), $this->version, 'all');

        wp_enqueue_style('ci_interprets_dreams_style', CI_PLUGIN_DIR_URL . 'chatgpt/assets/css/interprets-dreams.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script('ci_chatgpt_script', CI_PLUGIN_DIR_URL . 'chatgpt/assets/js/chatgpt.js', array( 'jquery' ), $this->version, false);
        $ajaxData = array(
            'ajaxurl'               => admin_url('admin-ajax.php'),
            'ci_chatgpt_ajax_nonce' => wp_create_nonce('ci_chatgpt_security_key'),
            'action'                => 'ci_chatgpt_openai_generate_answer'
        );
        wp_localize_script('ci_chatgpt_script', 'ci_chatgpt', $ajaxData);

        wp_enqueue_script('ci_interprets_dreams_script', CI_PLUGIN_DIR_URL . 'chatgpt/assets/js/interprets-dreams.js', array( 'jquery' ), $this->version, false);
        wp_localize_script('ci_interprets_dreams_script', 'ci_chatgpt', $ajaxData);
    }

    public function ci_chatgpt_openai_generate_answer_output_json( $args ) {
        $question = $args['question'] ?? '';
        $ci_chatgpt_setting = get_option('ci_chatgpt_setting');
        $ci_chatgpt_api_key = $ci_chatgpt_setting['api_key'] ?? '';
        $ci_chatgpt_api_token = $ci_chatgpt_setting['api_token'] ?? '';

        if ( empty( $ci_chatgpt_api_key ) ) {
            echo wp_json_encode(array(
                'status'  => 'bad',
                'msg' => esc_html__( 'Empty API key, please store API key in settings first.', 'wp_ci' ),
            ));
            exit();
        }

        if ( empty( $ci_chatgpt_api_token ) ) {
            echo wp_json_encode(array(
                'status'  => 'bad',
                'msg' => esc_html__( 'Empty API Token, please store API Token in settings first.', 'wp_ci' ),
            ));
            exit();
        }

        $url = 'https://qianfan.baidubce.com/v2/app/conversation';
        $data = array(
            'app_id' => $ci_chatgpt_api_key
        );

        $message  = '';
        $response = $this->ci_chatgpt_run_rest_api('POST', $url, $data, $ci_chatgpt_api_key, $ci_chatgpt_api_token);
        if ($this->status_404($response)) {
            exit;
        }

        if ( isset( $response['status'] ) && 200 === $response['status'] ) {
            $conversation_id = isset( $response['body']->conversation_id ) ? $response['body']->conversation_id : array();
            if ( empty( $conversation_id ) ) {
                $message .= esc_html__( 'Not conversation_id generated.', 'wp_ci' );
                $this->bad_result($message);
                exit;
            }
        }

        $url = 'https://qianfan.baidubce.com/v2/app/conversation/runs';
        $data = array(
            'app_id' => $ci_chatgpt_api_key,
            'query' => $question,
            'stream' => false,
            'conversation_id' => $conversation_id
        );

        $response = $this->ci_chatgpt_run_rest_api('POST', $url, $data, $ci_chatgpt_api_key, $ci_chatgpt_api_token);
        if ($this->status_404($response)) {
            exit;
        }

        if ( isset( $response['status'] ) && 200 === $response['status'] ) {
            $answer = isset( $response['body']->answer ) ? $response['body']->answer : array();
            if ( empty( $answer ) ) {
                $message .= esc_html__( 'Not answer generated.', 'wp_ci' );
                $this->bad_result($message);
                exit;
            } else {
                $content = $answer;
                $this->good_result($content);
                exit;
            }
        }
    }

    public function bad_result($message) {
        echo wp_json_encode(
            array(
                'status'  => 'bad',
                'msg'     => empty( $message ) ? esc_html__( 'Something went wrong while generating answer. Please try again.', 'wp_ci' ) : $message,
                'content' => empty( $message ) ? esc_html__( 'Something went wrong while generating answer. Please try again.', 'wp_ci' ) : $message
            )
        );
    }

    public function good_result($content) {
        echo wp_json_encode(
            array(
                'status'  => 'good',
                'msg'     => esc_html__( 'Content generated successfully.', 'wp_ci' ),
                'content' => $content,
            )
        );
    }

    public function status_404($response) {
        $message = '';
        if (is_wp_error( $response )) {
            $message .= $response->get_error_messages() !== null ? $response->get_error_messages()[0] : esc_html__( 'Other Error.', 'wp_ci' );
            $this->bad_result($message);
            return true;
        }
        if ( isset( $response['status'] ) && 404 === $response['status'] ) {
            $message .= isset( $response['body']->error->message ) ? $response['body']->error->message : esc_html__( 'Provided url not found.', 'wp_ci' );
            $this->bad_result($message);
            return true;
        }
        return false;
    }

    public function ci_chatgpt_run_rest_api($method, $url, $body , $ci_chatgpt_api_key, $ci_chatgpt_api_token) {

        $timeout = 45;
        $args = array(
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $ci_chatgpt_api_token,
            ),
            'method'  => $method,
            'timeout' => $timeout,
        );
        if ( ! empty( $body ) ) {
            $args['body'] = wp_json_encode( $body );
        }
        $response = 'POST' === $method ? wp_remote_post( $url, $args ) : wp_remote_request( $url, $args );

        $json     = wp_remote_retrieve_body( $response );
        $details  = json_decode( $json );
        if ( ! is_wp_error( $response ) && isset( $response['response'] ) ) {
            $response = array(
                'status'  => $response['response']['code'],
                'message' => $response['response']['message'],
                'code'    => $response['response']['code'],
                'body'    => json_decode( $response['body'] ),
            );
            return $response;
        }
        return $response;
    }
}
