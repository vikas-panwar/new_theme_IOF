<style>
    .btn.active { background-color: gray;color: #ffff;}
</style>
<?php echo $this->element('chart/chart_script'); ?>
<?php
$dataRequest = $this->Session->read('superReportRequest');
//pr($dataRequest);
$storeId            = (isset($dataRequest['storeId']) ? $dataRequest['storeId'] : 'All');
$type               = (isset($dataRequest['type']) ? $dataRequest['type'] : 1);
$orderType          = (isset($dataRequest['orderType']) ? $dataRequest['orderType'] : 1);
$customerType       = (isset($dataRequest['customerType']) ? $dataRequest['customerType'] : 1);
$reportType         = (isset($dataRequest['reportType']) ? $dataRequest['reportType'] : 1);
$merchantOption     = (isset($dataRequest['merchantOption']) ? $dataRequest['merchantOption'] : 0);
$startDate          = (isset($dataRequest['startDate']) ? $dataRequest['startDate'] : null);
$endDate            = (isset($dataRequest['endDate']) ? $dataRequest['endDate'] : null);
$date_start_from    = (isset($dataRequest['date_start_from']) ? $dataRequest['date_start_from'] : null);
$date_end_from      = (isset($dataRequest['date_end_from']) ? $dataRequest['date_end_from'] : null);
$defaultMonth       = (isset($dataRequest['month']) ? $dataRequest['month'] : date('m'));
$defaultYear        = (isset($dataRequest['year']) ? $dataRequest['year'] : date('Y'));
$fromYear           = (isset($dataRequest['fromYear']) ? $dataRequest['fromYear'] : date('Y'));
$toYear             = (isset($dataRequest['toYear']) ? $dataRequest['toYear'] : date('Y'));
$coupon_code        = (isset($dataRequest['coupon_code']) ? $dataRequest['coupon_code'] : null);
$promo_id           = (isset($dataRequest['promo_id']) ? $dataRequest['promo_id'] : null);
$extended_offer_id  = (isset($dataRequest['extended_offer_id']) ? $dataRequest['extended_offer_id'] : null);
if(!empty($dataRequest)){
    $startDate          = date('Y-m-d', strtotime($startDate));
    $endDate            = date("Y-m-d", strtotime($endDate));
    $start_date_from    = date('Y-m-d', strtotime($date_start_from));
    $end_date_from      = date('Y-m-d', strtotime($date_end_from));
} else {
    $startDate          = date('Y-m-d', strtotime('-6 day'));
    $endDate            = date("Y-m-d");
    $start_date_from    = date('Y-m-d', strtotime("first day of this month"));
    $end_date_from      = date('Y-m-d', strtotime("today"));
}

if($reportType == 4)
{
    $coupon_div         = '';
    $promo_div          = 'hidden';
    $extended_offer_div = 'hidden';
} else if($reportType == 5) {
    $coupon_div         = 'hidden';
    $promo_div          = '';
    $extended_offer_div = 'hidden';
} else if($reportType == 6) {
    $coupon_div         = 'hidden';
    $promo_div          = 'hidden';
    $extended_offer_div = '';
} else {
    $coupon_div         = 'hidden';
    $promo_div          = 'hidden';
    $extended_offer_div = 'hidden';
}


if(isset($storeId))
{
    if($storeId == 'All')
    {
        $couponList = array('' => 'Select Coupon');
        foreach ($storesList as $storeData){
            $getCouponList    = $this->Common->couponList($storeData['Store']['id']);
            if(!empty($getCouponList)){
                foreach ($getCouponList as $key => $data){
                    $couponList[$data['Order']['coupon']] = $data['Order']['coupon'];
                }
            }
        }
        
        $promoList = array('' => 'Select Offer');
        foreach ($storesList as $storeData){
            $getPromoList      = $this->Common->promoList($storeData['Store']['id']);
            if(!empty($getPromoList)){
                foreach ($getPromoList as $key => $data){
                    $promoList[$data['Offer']['id']] = $data['Offer']['description'];
                }
            }
        }
        
        $extendedList = array('' => 'Select Offer');
        foreach ($storesList as $storeData){
            $getExtendedList      = $this->Common->extendedOfferList($storeData['Store']['id']);
            if(!empty($getExtendedList)){
                foreach ($getExtendedList as $key => $data){
                    $extendedList[$data['Item']['id']] = $data['Item']['name'];
                }
            }
        }
    }
    else
    {
        $getCouponList     = $this->Common->couponList($storeId);
        $getPromoList      = $this->Common->promoList($storeId);
        $getExtendedList   = $this->Common->extendedOfferList($storeId);
        
        if(!empty($getCouponList))
        {
            $couponList = array('' => 'Select Coupon');
            foreach ($getCouponList as $kCouponList => $vCouponList){
                $couponList[$vCouponList['Order']['coupon']] = $vCouponList['Order']['coupon'];
            }
        }

        if(!empty($getPromoList))
        {
            $promoList = array('' => 'Select Offer');
            foreach ($getPromoList as $kPromoList => $vPromoList){
                $promoList[$vPromoList['Offer']['id']] = $vPromoList['Offer']['description'];
            }
        }

        if(!empty($getExtendedList))
        {
            $extendedList = array('' => 'Select Extended Offer');
            foreach ($getExtendedList as $kExtendedList => $vExtendedList){
                $extendedList[$vExtendedList['Item']['id']] = $vExtendedList['Item']['name'];
            }
        }
    }
} else {
    $couponList     = array('' => 'Select Coupon');
    $promoList      = array('' => 'Select Offer');
    $extendedList   = array('' => 'Select Extended Offer');
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-9">
            <h3>Sales Reports</h3> 
        </div>
        <div class="col-lg-3">
            <?php
            $merchantList = $this->Common->getStoreList();
//            $storeId = '';
//            if ($this->Session->read('selectedStoreId')) {
//                $storeId = $this->Session->read('selectedStoreId');
//            }
            if (!empty($merchantList)) {
                $allOption = array('All' => 'All Store');
                $merchantList = array_replace($allOption, $merchantList);
            }
            echo $this->Form->input('Merchant.store_id', array('options' => $merchantList, 'class' => 'form-control', 'label' => false, 'div' => false, 'default' => $storeId));
            ?>
        </div>
    </div>
</div>
<div class="col-lg-12">&nbsp;</div>
<div class="col-lg-12" id="error-message"><?php echo $this->Session->flash(); ?></div>
<script type="text/javascript">
    $(function(){
       setTimeout(function(){
           $("#error-message").hide();
       }, 5000);
    });
</script>
<div class="col-lg-12"><hr></div>
<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-7 for-single-report">
            <ul class="nav nav-pills report-type">
                <li class="<?php echo ($reportType == 1 ? 'active' : '');?>" data-report-id="1"><?php echo $this->Html->link('Order Type', 'javascript:void(0)', array()); ?></li>
                <li class="<?php echo ($reportType == 2 ? 'active' : '');?>" data-report-id="2"><?php echo $this->Html->link('Product', 'javascript:void(0)', array()); ?></li>
                <li class="<?php echo ($reportType == 3 ? 'active' : '');?>" data-report-id="3"><?php echo $this->Html->link('Customer', 'javascript:void(0)', array()); ?></li>
                <li class="<?php echo ($reportType == 4 ? 'active' : '');?>" data-report-id="4"><?php echo $this->Html->link('Coupon', 'javascript:void(0)', array()); ?></li>
                <li class="<?php echo ($reportType == 5 ? 'active' : '');?>" data-report-id="5"><?php echo $this->Html->link('Promo', 'javascript:void(0)', array()); ?></li>
                <li class="<?php echo ($reportType == 6 ? 'active' : '');?>" data-report-id="6"><?php echo $this->Html->link('Extended Offers', 'javascript:void(0)', array()); ?></li>
                <li class="<?php echo ($reportType == 7 ? 'active' : '');?>" data-report-id="7"><?php echo $this->Html->link('Dine In', 'javascript:void(0)', array()); ?></li>
            </ul>
        </div>
        <div class="col-lg-5" style="padding-left:0;">
            <div class="col-lg-8">
                <div class="btn-group type">
                    <button class="btn <?php echo ((($type == 1) && ($merchantOption < 1)) ? 'active' : '');?>" data-type-id="1">Day</button>
                    <button class="btn <?php echo ((($type == 2) && ($merchantOption < 1)) ? 'active' : '');?>" data-type-id="2">Week</button>
                    <button class="btn <?php echo ((($type == 3) && ($merchantOption < 1)) ? 'active' : '');?>" data-type-id="3">Month</button>
                    <button class="btn <?php echo ((($type == 4) && ($merchantOption < 1)) ? 'active' : '');?>" data-type-id="4">Year</button>
                </div>
            </div>
            <div class="col-lg-4  for-single-report" style="padding: 0;">
                <?php
                $options = array('0' => 'Custom', '1' => 'Today', '2' => 'Yesterday', '3' => 'This week(Sun-Today)', '4' => 'This week(Mon-Today)', '5' => 'Last 7 days', '6' => 'Last week(Sun-Sat)', '7' => 'Last week(Mon-Sun)', '8' => 'Last business week(Mon-Fri)', '9' => 'Last 14 days', '10' => 'This month', '11' => 'Last 30 days', '12' => 'Last month', '13' => 'All time');
                echo $this->Form->input('Merchant.option', array('options' => $options, 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $merchantOption));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12">&nbsp</div>
    <div class="col-lg-12">
<!--        <div class="col-lg-3  hidden for-single-report">  
            <?php echo $this->Form->input('Item.id', array('type' => 'select', 'class' => 'form-control valid', 'label' => false, 'div' => false, 'autocomplete' => 'off', 'options' => $categoryList, 'empty' => 'Item name')); ?>
        </div>-->
        <div class="col-lg-3">
            <div class="btn-group order-type <?php echo (($reportType == 3 || $reportType == 7) ? 'hidden' : '');?>">
                <button class="btn <?php echo ((($orderType == 1)) ? 'active' : '');?>" data-type-id="1">Both</button>
                <button class="btn <?php echo ((($orderType == 2)) ? 'active' : '');?>" data-type-id="2">Pick Up</button>
                <button class="btn <?php echo ((($orderType == 3)) ? 'active' : '');?>" data-type-id="3">Delivery</button>
            </div>
            
            
            <div class="btn-group customer-type <?php echo (($reportType != 3 || $reportType == 7) ? 'hidden' : '');?>">
                <button class="btn <?php echo ((($customerType == 1)) ? 'active' : '');?>" data-type-id="1">Both</button>
                <button class="btn <?php echo ((($customerType == 5)) ? 'active' : '');?>" data-type-id="5">Merchant</button>
                <button class="btn <?php echo ((($customerType == 4)) ? 'active' : '');?>" data-type-id="4">Store</button>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="col-lg-6 daily-div" style="<?php echo ((($type == 1) && ($merchantOption < 1)) ? 'display:block' : 'display:none');?>">
                <div class="col-lg-6">
                    <?php echo $this->Form->input('startdate', array('label' => false, 'div' => false, 'class' => 'form-control date-select', 'placeholder' => 'Start Date', 'readonly' => true, 'value' => $startDate)); ?>
                </div>
                <div class="col-lg-6">
                    <?php echo $this->Form->input('enddate', array('label' => false, 'div' => false, 'class' => 'form-control date-select', 'placeholder' => 'End Date', 'readonly' => true, 'value' => $endDate)); ?>
                </div>
            </div>
            <div class="col-lg-6 weekly-div" style="<?php echo ((($type == 2) && ($merchantOption < 1)) ? 'display:block' : 'display:none');?>">
                <div class="col-lg-6">
                    <?php echo $this->Form->input('date_start_from', array('label' => false, 'div' => false, 'class' => 'form-control week-picker', 'placeholder' => 'Start Week', 'readonly' => true, 'value' => $start_date_from)); ?>
                </div>
                <div class="col-lg-6">
                    <?php echo $this->Form->input('date_end_from', array('label' => false, 'div' => false, 'class' => 'form-control week-picker', 'placeholder' => 'End Week', 'readonly' => true, 'value' => $end_date_from)); ?>
                </div>
            </div>
            <div class="col-lg-6 monthly-div" style="<?php echo ((($type == 3) && ($merchantOption < 1)) ? 'display:block' : 'display:none');?>">
                <?php
                $month = array('1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
                for ($y = 2010; $y <= date('Y'); $y++) {
                    $yr[$y] = $y;
                }
                for ($m = 1; $m <= 12; $m++) {
                    $mth[$m] = $month[$m];
                }
                ?>
                <div class="col-lg-6">
                    <?php echo $this->Form->input('month', array('type' => 'select', 'class' => 'form-control', 'label' => false, 'div' => false, 'options' => $mth, 'default' => $defaultMonth)); ?>	
                </div>
                <div class="col-lg-6">
                    <?php echo $this->Form->input('year', array('type' => 'select', 'class' => 'form-control', 'label' => false, 'div' => false, 'options' => $yr, 'placeholder' => 'End Select Year', 'default' => $defaultYear)); ?>		
                </div>
            </div>
            <div class="col-lg-6 yearly-div" style="<?php echo ((($type == 4) && ($merchantOption < 1)) ? 'display:block' : 'display:none');?>">
                <?php
                for ($y = 2010; $y <= date('Y'); $y++) {
                    $yr[$y] = $y;
                }
                ?>
                <div class="col-lg-6">
                    <?php echo $this->Form->input('from_year', array('type' => 'select', 'class' => 'form-control', 'label' => false, 'div' => false, 'options' => $yr, 'default' => $fromYear)); ?>	
                </div>
                <div class="col-lg-6">
                    <?php echo $this->Form->input('to_year', array('type' => 'select', 'class' => 'form-control', 'label' => false, 'div' => false, 'options' => $yr, 'default' => $toYear)); ?>		
                </div>
            </div>
            
            <div class="col-lg-6 coupon-div <?php echo $coupon_div;?>">
                <?php echo $this->Form->input('coupon_code', array('type' => 'select', 'class' => 'form-control', 'label' => false, 'div' => false, 'options' => (isset($couponList) ? $couponList : null), 'default' => $coupon_code)); ?>            
            </div>
            <div class="col-lg-6 promo-div <?php echo $promo_div;?>">
                <?php echo $this->Form->input('promo_id', array('type' => 'select', 'class' => 'form-control', 'label' => false, 'div' => false, 'options' => (isset($promoList) ? $promoList : null), 'default' => $promo_id)); ?>            
            </div>
            <div class="col-lg-6 extended-offer-div <?php echo $extended_offer_div;?>">
                <?php echo $this->Form->input('extended_offer_id', array('type' => 'select', 'class' => 'form-control', 'label' => false, 'div' => false, 'options' => (isset($extendedList) ? $extendedList : null), 'default' => $extended_offer_id)); ?>            
            </div>
            
        </div>
        <!--        <div class="col-lg-2">
        <?php echo $this->Form->input('Segment.id', array('type' => 'select', 'class' => 'form-control valid', 'label' => false, 'div' => false, 'autocomplete' => 'off', 'options' => @$typeList, 'empty' => 'Order Type')); ?>		
                </div>-->
        <div class="col-lg-2">
            <?php echo $this->Html->link('Download Excel', array('controller' => 'superNewReports', 'action' => 'reportDownload'), array('class' => 'btn btn-default')); ?>
        </div>
    </div>
    <div class="col-lg-12">&nbsp</div>
    <div class="col-lg-12">
        
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id="showchart"></div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var now = new Date();
        $('#startdate').datepicker({
            dateFormat: 'yy-mm-dd',
            maxDate: 0,
            onSelect: function (selectedDate) {
                var dt = new Date(selectedDate);
                dt.setDate(dt.getDate() + 1);
                $("#enddate").datepicker("option", "minDate", dt);
                fetchGraphData();
            }

        });
        $('#enddate').datepicker({
            dateFormat: 'yy-mm-dd',
            maxDate: 0,
            onSelect: function (selectedDate) {
                var dt = new Date(selectedDate);
                dt.setDate(dt.getDate() - 1);
                $("#startdate").datepicker("option", "maxDate", dt);
                fetchGraphData();
            }

        });
        $(".type > .btn").click(function () {
            $(this).addClass("active").siblings().removeClass("active");
            changeDateDiv($(this).data('type-id'));
            $("#MerchantOption").val(0);
            $("#MerchantOption option").removeAttr('selected');
            fetchGraphData();
        });
        //changeDateDiv($(".type > .btn.active").data('type-id'));
        
        $("#SegmentId,#month,#year,#from_year,#to_year").change(function () {
            fetchGraphData();
        });
        $(".order-type > .btn").click(function () {
            $(this).addClass("active").siblings().removeClass("active");
            fetchGraphData();
        });
        
        $(".customer-type > .btn").click(function () {
            $(this).addClass("active").siblings().removeClass("active");
            fetchGraphData();
        });
        
        $("#MerchantStoreId").on('change', function(){
            fetchGraphData();
        });
        
        $("#MerchantOption").change(function () {
            $(".type > .btn").removeClass("active");
            changeDateDiv();
            fetchGraphData();
        });
    });
    function changeDateDiv(type) {
        if (type == 1) {
            $('.daily-div').css('display', 'block');
            $('.weekly-div').css('display', 'none');
            $('.monthly-div').css('display', 'none');
            $('.yearly-div').css('display', 'none');
        } else if (type == 2) {
            $('.weekly-div').css('display', 'block');
            $('.daily-div').css('display', 'none');
            $('.monthly-div').css('display', 'none');
            $('.yearly-div').css('display', 'none');
        } else if (type == 3) {
            $('.monthly-div').css('display', 'block');
            $('.daily-div').css('display', 'none');
            $('.weekly-div').css('display', 'none');
            $('.yearly-div').css('display', 'none');
        } else if (type == 4) {
            $('.yearly-div').css('display', 'block');
            $('.daily-div').css('display', 'none');
            $('.weekly-div').css('display', 'none');
            $('.monthly-div').css('display', 'none');
        } else{
            $('.yearly-div').css('display', 'none');
            $('.daily-div').css('display', 'none');
            $('.weekly-div').css('display', 'none');
            $('.monthly-div').css('display', 'none');
        }
    }
    function fetchGraphData(condition = null) {
        $('#loading').removeClass('hidden');
        //console.log();
        var storeId             = $('#MerchantStoreId').val();
        var reportType          = $(".nav-pills > li.active").data('report-id');
        var pageNo              = $("#pageNo").val();
        var type                = $(".type > .btn.active").data('type-id');
        var orderType           = $(".order-type > .btn.active").data('type-id');
        var customerType        = $(".customer-type > .btn.active").data('type-id');
        var startDate           = $('#startdate').val();
        var endDate             = $('#enddate').val();
        var year                = $('#year').val();
        var month               = $('#month').val();
        var fromYear            = $('#from_year').val();
        var toYear              = $('#to_year').val();
        var merchantOption      = $('#MerchantOption').val();
        var itemId              = $('#ItemId').val();
        var date_start_from     = $('#date_start_from').val();
        var date_end_from       = $('#date_end_from').val();
        var coupon_code         = $('#coupon_code').val();
        var promo_id            = $('#promo_id').val();
        var extended_offer_id   = $('#extended_offer_id').val();
        //console.log(condition + '=====' + type + '=====' + merchantOption)
        if(condition == 'first' && typeof type ===  "undefined" && merchantOption == 0)
        {
            $(".type > .btn:first").addClass('active');
            $(".daily-div").css('display','block');
            
        }
        
        $.ajax({
            type: 'post',
            url: '/superNewReports/fetchReport',
            data: {reportType: reportType, type: type, orderType: orderType, customerType: customerType, startDate: startDate, endDate: endDate, storeId: storeId, month: month, year: year, fromYear: fromYear, toYear: toYear, pageNo: pageNo, merchantOption: merchantOption, itemId: itemId, date_start_from: date_start_from, date_end_from: date_end_from, coupon_code: coupon_code, promo_id: promo_id, extended_offer_id: extended_offer_id},
            success: function (result) {
                $("#showchart").html(result);
                $('#loading').addClass('hidden');
            }
        })
        .fail(function(xhr, err) {
            var responseTitle= $(xhr.responseText).filter('title').get(0);
            console.log($(responseTitle).text() + "\n" + xhr + "\n" + err);
            $('#loading').addClass('hidden');
        });
    }
    
    function fetchPaginationData(page = null, sort = null, sort_direction = null) {
        $('#loading').removeClass('hidden');
        var storeId             = $('#MerchantStoreId').val();
        var reportType          = $(".nav-pills > li.active").data('report-id');
        var orderType           = $(".order-type > .btn.active").data('type-id');
        var customerType        = $(".customer-type > .btn.active").data('type-id');
        var type                = $(".type > .btn.active").data('type-id');
        var merchantOption      = $('#MerchantOption').val();
        var itemId              = $('#ItemId').val();
        var startDate           = $('#startdate').val();
        var endDate             = $('#enddate').val();
        var month               = $('#month').val();
        var year                = $('#year').val();
        var fromYear            = $('#from_year').val();
        var toYear              = $('#to_year').val();
        var date_start_from     = $('#date_start_from').val();
        var date_end_from       = $('#date_end_from').val();
        var coupon_code         = $('#coupon_code').val();
        var promo_id            = $('#promo_id').val();
        var extended_offer_id   = $('#extended_offer_id').val();
        
        $.ajax({
            type: 'post',
            url: '/superNewReports/getPaginationData',
            data: {reportType: reportType, orderType: orderType, type: type, storeId: storeId, customerType: customerType, merchantOption: merchantOption, itemId: itemId, startDate: startDate, endDate: endDate, month: month, year: year, fromYear: fromYear, toYear: toYear, date_start_from: date_start_from, date_end_from: date_end_from, coupon_code: coupon_code, promo_id: promo_id, extended_offer_id: extended_offer_id, page: page, sort: sort, sort_direction: sort_direction},
            success: function (result) {
                $("#pagination_data_request").html(result);
                $('#loading').addClass('hidden');

            }
        })
        .fail(function(xhr, err) {
            var responseTitle= $(xhr.responseText).filter('title').get(0);
            console.log($(responseTitle).text() + "\n" + xhr + "\n" + err);
            $('#loading').addClass('hidden');
        });
    }
    
    
    
    /*Function For Merchant Orders*/
    function fetchPaginationAllData(page = null, sort = null, sort_direction = null) 
    {
        $('#loading').removeClass('hidden');
        var storeId             = $('#MerchantStoreId').val();
        var reportType          = $(".nav-pills > li.active").data('report-id');
        var orderType           = $(".order-type > .btn.active").data('type-id');
        var customerType        = $(".customer-type > .btn.active").data('type-id');
        var type                = $(".type > .btn.active").data('type-id');
        var merchantOption      = $('#MerchantOption').val();
        var itemId              = $('#ItemId').val();
        var startDate           = $('#startdate').val();
        var endDate             = $('#enddate').val();
        var month               = $('#month').val();
        var year                = $('#year').val();
        var fromYear            = $('#from_year').val();
        var toYear              = $('#to_year').val();
        var date_start_from     = $('#date_start_from').val();
        var date_end_from       = $('#date_end_from').val();
        var coupon_code         = $('#coupon_code').val();
        var promo_id            = $('#promo_id').val();
        var extended_offer_id   = $('#extended_offer_id').val();
        
        $.ajax({
            type: 'post',
            url: '/superNewReports/getMerchantPaginationData',
            data: {reportType: reportType, orderType: orderType, customerType: customerType, type: type, storeId: storeId, merchantOption: merchantOption, itemId: itemId, startDate: startDate, endDate: endDate, month: month, year: year, fromYear: fromYear, toYear: toYear, date_start_from: date_start_from, date_end_from: date_end_from, coupon_code: coupon_code, promo_id: promo_id, extended_offer_id: extended_offer_id, page: page, sort: sort, sort_direction: sort_direction},
            success: function (result) {
                $("#pagination_data_request").html(result);
                $('#loading').addClass('hidden');

            }
        })
        .fail(function(xhr, err) {
            var responseTitle= $(xhr.responseText).filter('title').get(0);
            console.log($(responseTitle).text() + "\n" + xhr + "\n" + err);
            $('#loading').addClass('hidden');
        });
    }
    
    function fetchGraphPaginationData(graph_page_number = null) {
        $('#loading').removeClass('hidden');
        var storeId             = $('#MerchantStoreId').val();
        var reportType          = $(".nav-pills > li.active").data('report-id');
        var orderType           = $(".order-type > .btn.active").data('type-id');
        var customerType        = $(".customer-type > .btn.active").data('type-id');
        var type                = $(".type > .btn.active").data('type-id');
        var merchantOption      = $('#MerchantOption').val();
        var itemId              = $('#ItemId').val();
        var startDate           = $('#startdate').val();
        var endDate             = $('#enddate').val();
        var month               = $('#month').val();
        var year                = $('#year').val();
        var fromYear            = $('#from_year').val();
        var toYear              = $('#to_year').val();
        var date_start_from     = $('#date_start_from').val();
        var date_end_from       = $('#date_end_from').val();
        
        $.ajax({
            type: 'post',
            url: '/superNewReports/getGraphPaginationData',
            data: {reportType: reportType, orderType: orderType, customerType: customerType, type: type, storeId: storeId, merchantOption: merchantOption, itemId: itemId, startDate: startDate, endDate: endDate, month: month, year: year, fromYear: fromYear, toYear: toYear, date_start_from: date_start_from, date_end_from: date_end_from, graph_page_number: graph_page_number},
            success: function (result) {
                $("#showchart").html(result);
                $('#loading').addClass('hidden');
            }
        })
        .fail(function(xhr, err) {
            var responseTitle= $(xhr.responseText).filter('title').get(0);
            console.log($(responseTitle).text() + "\n" + xhr + "\n" + err);
            $('#loading').addClass('hidden');
        });
    }
    
    $("#date_start_from").datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true,
        showWeek: true,
        beforeShowDay: enableSUNDAYS,
        onSelect: function (selectedDate) {
            var dateText = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate"));
            $('#date_start_from').text(dateText);
            
            var dt = new Date(selectedDate);
            dt.setDate(dt.getDate() + 6);
            $("#date_end_from").datepicker("option", "minDate", dt);
            fetchGraphData();
        }
    });

    $("#date_end_from").datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true,
        showWeek: true,
        beforeShowDay: enableSUNDAYS,
        onSelect: function (selectedDate) {
            var dateText = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate"));
            $('#date_start_from').text(dateText);
            
            var dt = new Date(selectedDate);
            dt.setDate(dt.getDate() - 6);
            $("#date_start_from").datepicker("option", "maxDate", dt);
            fetchGraphData();
        }
    });
    function enableSUNDAYS(date) {
        var day = date.getDay();
        return [(day == 0), ''];
    }
    
    $(".nav-pills > li").click(function(){
        $(this).addClass("active").siblings().removeClass("active");
        if($(this).data("report-id") == 7)
        {
            $(".customer-type").addClass('hidden');
            $(".order-type").addClass('hidden');
        }
        else if($(this).data("report-id") == 3)
        {
            $(".customer-type").removeClass('hidden');
            $(".order-type").addClass('hidden');
        } else {
            $(".order-type").removeClass('hidden');
            $(".customer-type").addClass('hidden');
        }
        
        if($(this).data("report-id") == 4)
        {
            var storeId = $('#MerchantStoreId').val();
            $.ajax({
                type        : 'post',
                dataType    : 'json',
                url         : '/superNewReports/getOrderCouponList',
                data        : {
                    storeId: storeId
                },
                success     : function (result) {
                    if(result)
                    {
                        var option = '<option value="">Select Coupon</option>';
                        $.each(result, function(key,value){
                            option += '<option value="' + value.Order.coupon + '">' + value.Order.coupon + '</option>';
                        });
                        $(".coupon-div #coupon_code").html(option);
                    }
                }
            });
            $(".coupon-div").removeClass('hidden');
            $(".extended-offer-div").addClass('hidden');
            $(".promo-div").addClass('hidden');
        }
        else if($(this).data("report-id") == 5)
        {
            var storeId = $('#MerchantStoreId').val();
            $.ajax({
                type        : 'post',
                dataType    : 'json',
                url         : '/superNewReports/getOrderPromoList',
                data        : {
                    storeId: storeId
                },
                success     : function (result) {
                    if(result)
                    {
                        var option = '<option value="">Select Offer</option>';
                        $.each(result, function(key,value){
                            option += '<option value="' + value.Offer.id + '">' + value.Offer.description + '</option>';
                        });
                        $(".promo-div #promo_id").html(option);
                    }
                }
            });
            $(".promo-div").removeClass('hidden');
            $(".extended-offer-div").addClass('hidden');
            $(".coupon-div").addClass('hidden');
        }
        else if($(this).data("report-id") == 6)
        {
            var storeId = $('#MerchantStoreId').val();
            $.ajax({
                type        : 'post',
                dataType    : 'json',
                url         : '/superNewReports/getOrderExtendedOfferList',
                data        : {
                    storeId: storeId
                },
                success     : function (result) {
                    if(result)
                    {
                        var option = '<option value="">Select Extended Offer</option>';
                        $.each(result, function(key,value){
                            option += '<option value="' + value.Item.id + '">' + value.Item.name + '</option>';
                        });
                        $(".extended-offer-div #extended_offer_id").html(option);
                    }
                }
            });
            $(".extended-offer-div").removeClass('hidden');
            $(".coupon-div").addClass('hidden');
            $(".promo-div").addClass('hidden');
        } else {
            $(".coupon-div").addClass('hidden');
            $(".promo-div").addClass('hidden');
            $(".extended-offer-div").addClass('hidden');
        }
        fetchGraphData();
    });
    
    $(".coupon-div #coupon_code").on('change', function(){
        fetchGraphData();
    });
    
    $(".promo-div #promo_id").on('change', function(){
        fetchGraphData();
    });
    
    $(".extended-offer-div #extended_offer_id").on('change', function(){
        fetchGraphData();
    });
    
    setTimeout(function(){
        fetchGraphData('first');
    },200)
</script>

<style>
    .btn-group.type button{
        background: gray;
        color: #ffffff;
        font-weight: 600;
    }
    .btn-group.order-type button, .btn-group.customer-type button{
        background: #ffc800;
        color: #ffffff;
        font-weight: 600;
    }
    
    .nav-pills.report-type > li
    {
        background: #93badc;
    }
    .nav-pills.report-type > li a
    {
        color: #ffffff;
        font-weight: 600;
        padding: 6px 7px;
    }
    .nav-pills.report-type > li.active > a, .nav-pills.report-type > li.active > a:hover, .nav-pills.report-type > li.active > a:focus{
        background: none;
    }
    .nav > li > a:hover, .nav > li > a:focus{
        background: none;
    }
    .nav-pills.report-type > li.active{
        background: #337ab7;
    }
    .btn:focus, .btn:active:focus, .btn.active:focus, .btn.focus, .btn:active.focus, .btn.active.focus{
        outline: none;
        outline: none;
        outline-offset: 0px;
    }
    #pagination_data_request{
        clear: both;
        padding-top: 20px;
    }
</style>