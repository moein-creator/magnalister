<?php 
    if ($block->getMagnalisterOrder()->get('platform') == 'otto') {
        $oI18n = MLI18n::gi();
        $returnData = $block->getMagnalisterOrder()->get('shopAdditionalOrderField');
        $returnCarrier = isset($returnData['returnCarrier']) ? $returnData['returnCarrier'] : '';
        $returnTrackingNumber = isset($returnData['returnTrackingNumber']) ? $returnData['returnTrackingNumber'] : ''; ?>
    <section class="admin__page-section order-view-billing-shipping">
        <div class="admin__page-section-title">
            <span class="title"><?php echo $oI18n->sOtto_order_detail_header ?> <img src="<?php echo $block->getMagnalisterOrder()->getLogo() ?>"/></span>
        </div>
        <div class="admin__page-section-content">
            <form id="magnalister_return_tracking_data"
                    data-platform="<?php echo $block->getMagnalisterOrder()->get('platform') ?>"
                    data-magnalisterorderid="<?php echo $block->getMagnalisterOrder()->get('orders_id'); ?>"
                    action="<?php echo MLHttp::gi()->getBackendUrl('magnalister/backend/order', array()); ?>" 
                    method="post">
            <?php foreach (MLHttp::gi()->getNeededFormFields() as $sName => $sValue) { ?>
            <input type="hidden" id="mangalisterFormKey" name="<?php echo $sName ?>" value="<?php echo $sValue ?>" />
            <?php } ?>
            <div class="admin__page-section-item">
                <div style="display: flex;">
                    <div class="admin__page-section-item-content" style="margin-right: 15px;">
                        <label style="font-size: 1.4rem; font-weight: 600;"><?php echo $oI18n->sOtto_order_detail_return_carrier ?></label>
                        <input type="text" name="returnCarrier" value="<?php echo $returnCarrier ?>" id="returnCarrier" class="admin__control-text">
                    </div>

                    <div class="admin__page-section-item-content">
                        <label style="font-size: 1.4rem; font-weight: 600;"><?php echo $oI18n->sOtto_order_detail_return_tracking_key ?></label>
                        <input type="text" name="returnTrackingNumber" value="<?php echo $returnTrackingNumber ?>" id="returnTrackingNumber" class="admin__control-text">
                    </div>
                </div>
                <div class="admin__page-section-item-content order-history-comments-actions">
                    <div style="display: flex">
                        <button id="submitCarrier" onClick="saveData()" title="<?php echo $oI18n->get('form_action_save'); ?>" type="submit" class="action-default scalable action-save action-secondary">
                            <span><?php echo $oI18n->get('form_action_save'); ?></span>
                        </button>
                        <p style="margin-top: 7px; margin-left: 15px; color: #308538" id="magna-message-success"><?php echo $oI18n->sAmazon_order_detail_form_save_success ?></p>
                        <p style="margin-top: 7px; margin-left: 15px; color: #e6410a" id="magna-message-error"><?php echo $oI18n->sAmazon_order_detail_form_save_failed ?></p>
                    </div>
                </div>  
            </div>
        </form>
        <script type="text/javascript">
            document.getElementById("submitCarrier").addEventListener("click", function (event) {
                event.preventDefault()
            });

            var succsesMsg = document.getElementById("magna-message-success");
            var errorMsg = document.getElementById("magna-message-error");
            succsesMsg.style.display = "none";
            errorMsg.style.display = "none";

            function saveData() {
                var orderId = document.getElementById("magnalister_return_tracking_data").dataset.magnalisterorderid;
                var platform = document.getElementById("magnalister_return_tracking_data").dataset.platform;
                var carrier = document.getElementById("returnCarrier").value;
                var returnKey = document.getElementById("returnTrackingNumber").value;
                var formKey = document.getElementById("mangalisterFormKey").value;

                var xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function() {
                    if(xmlHttp.readyState === 4 && xmlHttp.status === 200) {
                        errorMsg.style.display = "none";
                        succsesMsg.style.display = "block";
                        setTimeout(function() {
                            succsesMsg.style.display = "none";
                        }, 3000);
                    } else if (xmlHttp.readyState === 4 && xmlHttp.status !== 200) {
                        succsesMsg.style.display = "none";
                        errorMsg.style.display = "block";
                        setTimeout(function() {
                            errorMsg.style.display = "none";
                        }, 3000);
                    }
                }

                var formData = new FormData();
                formData.append('orderId', orderId);
                formData.append('platform', platform);
                formData.append('returnCarrier', carrier);
                formData.append('returnTrackingNumber', returnKey);
                formData.append('form_key', formKey);

                xmlHttp.open(document.getElementById('magnalister_return_tracking_data').method, document.getElementById('magnalister_return_tracking_data').action);
                xmlHttp.send(formData);
            }
        </script>
        </div>
    </section>
<?php } ?>
