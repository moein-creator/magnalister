<?php 
    if ($block->getMagnalisterOrder()->get('platform') == 'amazon') {
        $oI18n = MLI18n::gi();
        $returnData = $block->getMagnalisterOrder()->get('shopAdditionalOrderField');
        $carrier = isset($returnData['carrierCode']) ? $returnData['carrierCode'] : '';
        $shipMethod = isset($returnData['shipMethod']) ? $returnData['shipMethod'] : '';
?>
    <section class="admin__page-section order-view-billing-shipping">
        <div class="admin__page-section-title">
            <span class="title"><?php echo $oI18n->sAmazon_order_detail_header ?><img style="margin-left: 5px;" src="<?php echo $block->getMagnalisterOrder()->getLogo() ?>"/></span>
        </div>
        <div class="admin__page-section-content">
            <form id="magnalisterShippingData"
                data-platform="<?php echo $block->getMagnalisterOrder()->get('platform') ?>"
                data-magnalisterorderid="<?php echo $block->getMagnalisterOrder()->get('orders_id'); ?>"
                action="<?php echo MLHttp::gi()->getBackendUrl('magnalister/backend/order', array()); ?>" 
                method="post" >
            <?php foreach (MLHttp::gi()->getNeededFormFields() as $sName => $sValue) { ?>
            <input type="hidden" id="mangalisterFormKey" name="<?php echo $sName ?>" value="<?php echo $sValue ?>" />
            <?php } ?>
            <div class="admin__page-section-item">
                <div style="display: flex;">
                    <div class="admin__page-section-item-content" style="margin-right: 15px;">
                        <label style="font-size: 1.4rem; font-weight: 600;"><?php echo $oI18n->sAmazon_order_detail_ship_method ?></label>
                        <input type="text" name="shipMethod" value="<?php echo $shipMethod ?>" id="shipMethod" class="admin__control-text">
                    </div>

                    <div class="admin__page-section-item-content">
                        <label style="font-size: 1.4rem; font-weight: 600;"><?php echo $oI18n->sAmazon_order_detail_carrier ?></label>
                        <input type="text" name="carrierCode" value="<?php echo $carrier ?>" id="carrierCode" class="admin__control-text">
                    </div>
                </div>
                <div class="admin__page-section-item-content order-history-comments-actions">
                    <div style="display: flex">
                        <button id="submitCarrier" onclick="saveData()" title="<?php echo $oI18n->get('form_action_save'); ?>" type="submit" class="action-default scalable action-save action-secondary">
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
                var orderId = document.getElementById("magnalisterShippingData").dataset.magnalisterorderid;
                var platform = document.getElementById("magnalisterShippingData").dataset.platform;
                var carrier = document.getElementById("carrierCode").value;
                var shipMethod = document.getElementById("shipMethod").value;
                var formKey = document.getElementById("mangalisterFormKey").value;

                var formData = new FormData();
                formData.append('orderId', orderId);
                formData.append('platform', platform);
                formData.append('shipMethod', shipMethod);
                formData.append('carrierCode', carrier);
                formData.append('form_key', formKey);

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

                xmlHttp.open(document.getElementById('magnalisterShippingData').method, document.getElementById('magnalisterShippingData').action);
                xmlHttp.send(formData);
            }

        </script>
        </div>
    </section>
<?php } ?>
