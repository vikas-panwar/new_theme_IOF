<style>
    .btn.active { background-color: gray;color: #ffff;}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-9">
            <h3>Reports</h3>
            <?php echo $this->Session->flash(); ?>   
        </div>
        <div class="col-lg-3">
            <?php
            $merchantList = $this->Common->getHQStores($merchantId);
            if (!empty($merchantList)) {
                $allOption = array('All' => 'All Store');
                $merchantList = array_replace($allOption, $merchantList);
            }
            echo $this->Form->input('Merchant.store_id', array('options' => $merchantList, 'class' => 'form-control', 'label' => false, 'div' => false));
            ?>
        </div>
    </div>
    <div class="col-lg-12">&nbsp</div>
    <div class="col-lg-12">
        <div class="col-lg-9">
            <ul class="nav nav-pills report-type">
                <li class="active" data-report-id="1"><?php echo $this->Html->link('Product', array('controller' => 'hqsalesreports', 'action' => 'productReport')); ?></li>
                <li data-report-id="2"><?php echo $this->Html->link('Order', array('controller' => 'hqsalesreports', 'action' => 'productReport')); ?></li>
                <li data-report-id="3"><?php echo $this->Html->link('Customer', array('controller' => 'hqsalesreports', 'action' => 'productReport')); ?></li>
            </ul>
        </div>
        <div class="col-lg-3">
            <?php
            $options = array('1' => 'Today', '2' => 'Yesterday', '3' => 'This week(Sun-Today)', '4' => 'This week(Mon-Today)', '5' => 'Last 7 days', '6' => 'Last week(Sun-Sat)', '7' => 'Last week(Mon-Sun)', '8' => 'Last business week(Mon-Fri)', '9' => 'Last 14 days', '10' => 'This month', '11' => 'Last 30 days', '12' => 'Last month', '13' => 'All time');
            echo $this->Form->input('Merchant.option', array('options' => $options, 'class' => 'form-control', 'label' => false, 'div' => false));
            ?>
        </div>
    </div>
    <div class="col-lg-12">&nbsp</div>
    <div class="col-lg-12">
        <div class="col-lg-3">
            <div class="btn-group order-type">
                <button class="btn active" data-order-id="1">Both</button>
                <button class="btn" data-order-id="2">Pick Up</button>
                <button class="btn" data-order-id="3">Delivery</button>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="btn-group graph-type">
                <button class="btn active" data-graph-id="1">Day</button>
                <!--button class="btn" data-type-id="2">Week</button-->
                <button class="btn" data-graph-id="3">Month</button>
                <button class="btn" data-graph-id="4">Year</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        fetchGraphData();
        $(".order-type > .btn , .graph-type > .btn").click(function () {
            $(this).addClass("active").siblings().removeClass("active");
            fetchGraphData();
        });
        $("#MerchantStoreId,#MerchantOption").change(function () {
            fetchGraphData();
        });
    });
    function fetchGraphData() {
        var reportType = $(".nav-pills > li.active").data('report-id');
        var orderType = $(".order-type > .btn.active").data('order-id');
        var graphType = $(".graph-type > .btn.active").data('graph-id');
        var storeId = $('#MerchantStoreId').val();
        var merchantOption = $('#MerchantOption').val();

        alert(reportType + '-----' + graphType + '-----------' + orderType + '-----------------' + storeId + '----------' + merchantOption);
//        var startDate = $('#startdate').val();
//        var endDate = $('#enddate').val();
//        var year = $('#year').val();
//        var fromYear = $('#from_year').val();
//        var toYear = $('#to_year').val();
        $.ajax({
            type: 'post',
            url: '/hqsalesreports/getReportData',
            data: {reportType: reportType, orderType: orderType, graphType: graphType, storeId: storeId, merchantOption: merchantOption},
            success: function (result) {
                $("#showchart").html(result);
            }
        });
    }

</script>