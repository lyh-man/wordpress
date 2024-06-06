<?php
?>

<div class="ciPageWrapper" id="ciInstructPage">
    <div class="ciPageHead">
        <div class="ciPageHeading">
            <h1><?php _e("Chat", "wp-ci"); ?></h1>
        </div>
    </div>

    <div class="ciInstructMainContainer">
        <div class="ciInstructDisplayWrapper">
            <div class="ciInstructDisplay" id="chatgpt-response">
                <!-- Populated with JS -->
            </div>
        </div>

        <div class="ciInstructBottomRow">
            <div class="ciInstructInput">
                <textarea class="ci-input" id="ciInstructInputField" placeholder="What do you want me to do?" value=""></textarea>
                <a href="javascript:void(0);" class="ciInstructButtonIcon" id="ciInstruct"><span class="dashicons dashicons-arrow-right-alt2"></span></a>
            </div>
        </div>
    </div>
</div>