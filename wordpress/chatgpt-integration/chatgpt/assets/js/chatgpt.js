jQuery(document).ready(function ($) {
    $('body').on('keypress', '#ciInstructInputField', function (e) {
        if (e.keyCode === 13) {
            var $parent = $(this).closest('#ciInstructPage');
            processChatGPT($parent);
        }
    });

    $('body').on('click', '#ciInstruct', function () {
        var $parent = $(this).closest('#ciInstructPage');
        processChatGPT($parent);
    });

    function processChatGPT($parent) {
        var $input = $parent.find('#ciInstructInputField');
        var value = $input.val();
        if ('' === value) {
            return;
        }
        $input.val('');
        var ajaxdata = {};
        ajaxdata.question = value;
        ajaxdata.security = ci_chatgpt.ci_chatgpt_ajax_nonce;
        ajaxdata.action = ci_chatgpt.action;
        $.ajax({
            beforeSend: function () {
                $input.prop('disabled', true );
                generateAnswer(value, $parent, 'sender');
            },
            type: 'POST',
            dataType: 'json',
            url: ci_chatgpt.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                console.log(data);
                if (data.hasOwnProperty('status') && 'good' === data.status) {
                    generateAnswer(data.content, $parent, 'answer');
                } else {
                    generateAnswer(data.msg, $parent, 'answer');
                }
            },
            complete: function () {
                $input.prop('disabled', false );
            }
        });
    }

    function generateAnswer(value, $parent, type) {
        var $messageHolder = $parent.find('#chatgpt-response');
        var output = '<div class="momo-chat-row">';
        output += '<div class="momo-chat-left"><strong>' + type + '</strong></div>';
        output += '<div class="momo-chat-right">' + value + '</div>';
        output += '</div>';
        $messageHolder.append(output);
        scrollToBottom($messageHolder);
    }

    function scrollToBottom($messageBlock) {
        var shouldScroll = $messageBlock.scrollTop + $messageBlock.clientHeight === $messageBlock.scrollHeight;
        if (!shouldScroll) {
            $messageBlock.scrollTop($messageBlock.prop('scrollHeight'));
        }
    }
});