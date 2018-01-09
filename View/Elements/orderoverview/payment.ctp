<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">PAYMENT OPTIONS</h3>
    </div>
    <?php
    $guestUserOrderType = $this->Session->read('ordersummary.order_type');
    $addOpenClass = "";
    if (!empty($guestUserOrderType)) {
        $addOpenClass = "in";
    }
    $guestUserDetail = $this->Session->check('GuestUser');
    $userId = AuthComponent::User('id');
    if (!empty($userId) || !empty($guestUserDetail)) {
        ?>
        <div id="collapseThree" class="panel-collapse collapse <?php echo $addOpenClass ?>">
            <div class="panel-body nested-ac">
                <div class="pay-wrap payment-opt clearfix paymentForm">
                    <?php
                    echo $this->element('design/aaron/payment');
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
