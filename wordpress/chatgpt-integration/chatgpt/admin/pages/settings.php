<?php

if(!defined('ABSPATH')){
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die(__( 'Sorry, you are not allowed to manage options for this site.'));
}

$ci_chatgpt_setting = get_option('ci_chatgpt_setting');
$ci_chatgpt_api_key = $ci_chatgpt_setting['api_key'] ?? '';
$ci_chatgpt_api_token = $ci_chatgpt_setting['api_token'] ?? '';

?>

<div>
    <h1><?php echo esc_html_e('ChatGPT Settings', 'wp-ci'); ?></h1>
    <div>
        <dl>
            <dt><?php esc_html_e( 'Make sure API key is added / Add API key if not added already.', 'wp-ci' ); ?></dt>
            <dt><?php esc_html_e( 'Copy this shortcode and paste it in the page you want to display the chat : ', 'wp-ci' ); ?><code>[ci_chatgpt]</code></dt>
        </dl>
    </div>

    <form method="post" action="options.php" >
        <?php settings_fields('ci_chatgpt_settings'); ?>
        <?php do_settings_sections('wp-ci-chatgpt-settings'); ?>

        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="ci_chatgpt_setting[api_key]"><?php esc_html_e("API Key", "wp-ci"); ?></label>
                </th>
                <td>
                    <input name="ci_chatgpt_setting[api_key]" type="text" id="ci_chatgpt_setting[api_key]" value="<?php echo esc_attr($ci_chatgpt_api_key); ?>" class="regular-text ci-input">
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="ci_chatgpt_setting[api_token]"><?php esc_html_e("API Token", "wp-ci"); ?></label>
                </th>
                <td>
                    <input name="ci_chatgpt_setting[api_token]" type="text" id="ci_chatgpt_setting[api_token]" value="<?php echo esc_attr($ci_chatgpt_api_token); ?>" class="regular-text ci-input">
                </td>
            </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type='submit' name='submit' id='submit' class="button button-primary" value='<?php esc_html_e("Save Changes", "wp-ci"); ?>'>
        </p>
    </form>
</div>