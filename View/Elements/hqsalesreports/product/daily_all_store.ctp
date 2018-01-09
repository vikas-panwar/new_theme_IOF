<?php
if (!empty($graphData['Store']) && !empty($stores)) {
    $alltamntt = $alltorders = $totalProducts = $allTotalNames = array();
    
    /**************************** For All Data in one Graph ****************************/
    
    if($graphPageNumber == 0)
    {
        foreach ($stores as $store)
        {
            if (isset($graphDataAll))
            {
                $datee = $tamntt = $torders = array();
                $summarytotalAmount=0;
                $summarytotalOrders=0;
                $totalorders=array();
                $step = '+1 day';
                $output_format = 'Y-m-d';
                $datee = array();
                $text = '';
                $current = strtotime($startDate);
                $last = strtotime($endDate);    
                while( $current <= $last ) {    
                    $datee[] = "'".date($output_format, $current)."'";
                    $totalProducts[date($output_format, $current)]=0;    
                    $current = strtotime($step, $current);

                }
                $amnt=0;
                $order=0;
                $totalItem=0;
                $itemNames = array();

                foreach ($graphDataAll['Store'][$store['Store']['id']] as $result) {
                    if(!empty($result)){
                        foreach($result as $key => $data){
                            $result1[$key]=$data;
                            unset($data);
                        }
                        if(!empty($result1)){
                            foreach($result1 as $product){
                                $forCurrent = '';
                                if(isset($product['order_date']) && isset($product['number'])){
                                    $totalProducts[$product['order_date']] += $product['number'];
                                    $totalItem = $totalItem + $product['number'];
                                    
                                    //$itemNames["'" . $product['order_date'] . "'"][] = array('name' => $product['itemname'], 'count' => $product['number']);
                                }
                            }
                        }
                    }
                }
            }
            
            $itemdate = implode(',',$datee);
            $itemcount = implode(',',$totalProducts);

            $allTotalDates[] = $itemdate;
            $allTotalItems[] = $itemcount;
            //$allTotalNames[] = $itemNames;
        }

        
        $newItems = '';
        foreach ($allTotalItems as $allItems){
            $newItems .=  $allItems . ',';
        }
        $newItems = trim($newItems,',');


        $totalItem = array_sum(explode(',',$newItems));
        $itemName = array();
        $aData = array();
        if (!empty($allTotalItems)) {
            foreach ($allTotalItems as $akey => $amt) {
                $amt = explode(',',$amt);
                foreach ($amt as $key => $amtvalue) {
                    if (!empty($aData[$key])) {
                        $aData[$key] = $aData[$key] + $amtvalue;
                    } else {
                        $aData[$key] = $amtvalue;
                    }
                    //echo $key;echo '<br/>';
                    /*if(isset($itemName[$key]))
                    {
                        $itemName[$key] = $itemNames[$key];   
                    }*/
                }
            }
        }
        /*//pr($allTotalNames);
        $newItemName = array();
        if(isset($allTotalNames))
        {
            foreach($allTotalNames as $allTotalNameK => $allTotalNameV)
            {
                foreach($allTotalNameV as $allTotalNameVK => $allTotalNameVV)
                {
                    foreach($allTotalNameVV as $allTotalNameVVK => $allTotalNameVVV)
                    {
                        if(isset($newItemName[$allTotalNameVVV['name']]))
                        {
                           $newItemName[$allTotalNameVK][$allTotalNameVVV['name']] += $allTotalNameVVV['count'];
                        } else {
                            $newItemName[$allTotalNameVK][$allTotalNameVVV['name']] = $allTotalNameVVV['count'];
                        }
                    }
                }
            }
        }
        $newItemName2 = array();
        foreach ($datee as $date)
        {
            if(array_key_exists($date, $newItemName))
            {
                $newItemName2[$date] = $newItemName[$date];
            }
            else {
                $newItemName2[$date] = array();
             }
        }*/
        
        $newaData = '';
        if (!empty($aData)) {
            foreach ($aData as $a){
                $newaData .=  $a . ',';
            }
            $newaData = trim($newaData,',');
        }
        
        if (!empty($allTotalItems) && !empty($datee)) 
        {
            //Total result
            $text = '<style="font-size:12px;">Daily Report for</style> <br/><style="font-size:14px;">HQ</style><br/><style="font-size:12px;">' . $startDate . ' to ' . $endDate . '</style>';
            $subTitle = '<style="font-size:14px;font-weight:bold;">Total '.$totalItem.' Item </style>';
            ?>
            <div class="col-lg-12">
                <div id="cTotal"></div>
            </div>
            <script>
                $(function () {
                $('#cTotal').highcharts({
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: '<?php echo  $text;?>'
                        },
                        subtitle: {
                            text: '<?php echo $subTitle;?>'
                        },
                        xAxis: {
                            categories: [<?php echo $itemdate;?>],
                            crosshair: true
                        },
                       yAxis: {
                            min: 0,
                            title: {
                                text: 'Item Count',
                                align: 'middle'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        },
                        tooltip: {
                            valueSuffix: '',
                            /*useHTML: true,
                            formatter: function() {
                                    var html = '';
                                    var currentX = this.x;
                                    html += currentX + '<br/>';
                                <?php
                                /*foreach ($newItemName2 as $newItemName2VK => $newItemName2V){
                                    ?>
                                    var currentDate = <?php echo $newItemName2VK;?>;
                                    if(currentX == currentDate)
                                    {
                                        <?php
                                        foreach ($newItemName2V as $newItemName2VK => $newItemName2VV){
                                        ?>
                                        html += '<?php echo $newItemName2VK . ': <b>' . $newItemName2VV;?></b><br/>';
                                        <?php
                                        }
                                        ?>
                                    }                   
                                    <?php
                                }*/
                                ?>
                                return html;
                            }*/
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: true
                                },
                                enableMouseTracking: true
                            },
                            series: {
                                pointWidth: 50
                            }
                        },
                        exporting: { enabled: false },
                        
                        series: [{
                            name: 'Item',
                            data: [<?php echo $newaData; ?>],
                            color: '#f79d54'

                        }]
                    });
            });
            </script>
            <?php
        }
    }
    
    ?>    
    <div class="clear"></div>
    <?php
    
    /**************************** For Pagination Graph ****************************/
    foreach($pageMerchant[$graphPageNumber] as $keyStore => $valueStore)
    {
        $datee = $tamntt = $torders = array();
        $summarytotalAmount=0;
        $summarytotalOrders=0;
        $totalorders=array();
        $step = '+1 day';
        $output_format = 'Y-m-d';
        $datee = array();
        $text = '';
        $current = strtotime($startDate);
        $last = strtotime($endDate);    
        while( $current <= $last ) {    
            $datee[] = "'".date($output_format, $current)."'";
            $totalProducts[date($output_format, $current)]=0;    
            $current = strtotime($step, $current);
                    
        }
        $amnt=0;
        $order=0;
        $totalItem=0;
        $itemNames = array();
        foreach ($graphData['Store'][$keyStore] as $result) {
            if(!empty($result)){
                foreach($result as $key => $data){
                    $result1[$key]=$data;
                    unset($data); 
                }
                if(!empty($result1)){
                    foreach($result1 as $product){
                        $totalProducts[$product['order_date']] += $product['number'];
                        $totalItem = $totalItem + $product['number'];
                        //$itemNames["'" . $product['order_date'] . "'"][] = array('name' => $product['itemname'], 'count' => $product['number']);
                    }
                }
            }
        }
        
        /*$newItemName = array();
        foreach ($datee as $date)
        {
            if(array_key_exists($date, $itemNames))
            {
                $newItemName[$date] = $itemNames[$date];
            }
            else {
                $newItemName[$date] = array();
             }
        }*/
        $subTitle = '<style="font-size:14px;font-weight:bold;">Total '.$totalItem.' Item </style>';
        $itemdate = implode(',',$datee);
        $itemcount = implode(',',$totalProducts);
        
        $allTotalDates[] = $itemdate;
        $allTotalItems[] = $itemcount;
        
        $text = '<style="font-size:12px;">Daily Report for</style> <br/><style="font-size:14px;">' . addslashes($valueStore) . '</style><br/><style="font-size:12px;">' . $startDate . ' to ' . $endDate . '</style>';
        
        ?>
        <div class="col-lg-4">
            <div id="<?php echo "container" . $keyStore; ?>"></div>
        </div>

        <script>

        $(function () {
            $('#container<?php echo $keyStore; ?>').highcharts({
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: '<?php echo  $text;?>'
                    },
                    subtitle: {
                        text: '<?php echo $subTitle;?>'
                    },
                    xAxis: {
                        categories: [<?php echo $itemdate;?>],
                        title: {
                            text: null
                        },
                        crosshair: true
                    },
                   yAxis: {
                        min: 0,
                        title: {
                            text: 'Item Count',
                            align: 'middle'
                        },
                        labels: {
                            overflow: 'justify'
                        }
                    },
                    tooltip: {
                        valueSuffix: '',
                        /*useHTML: true,
                        formatter: function() {
                                var html = '';
                                var currentX = this.x;
                                html += currentX + '<br/>'
                                //console.log(this.x + '' + this.y)
                            <?php
                            foreach ($newItemName as $newItemNameK => $newItemNameV){
                                ?>
                                var currentDate = <?php echo $newItemNameK;?>;
                                if(currentX == currentDate)
                                {
                                    <?php
                                        //pr($newItemNameV);
                                    foreach ($newItemNameV as $newItemNameVK => $newItemNameVV){
                                    ?>
                                    html += '<?php echo $newItemNameVV['name'] . ': <b>' . $newItemNameVV['count'];?></b><br/>';
                                    <?php
                                    }
                                    ?>
                                }                   
                                <?php
                            }
                            ?>
                            return html;
                        }*/
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: true
                        },
                        series: {
                            pointWidth: 50
                        }
                    },
                    exporting: { enabled: false },
                    series: [{
                        name: 'Item',
                        data: [<?php echo $itemcount; ?>],
                        color: '#f79d54'

                    }]
                });
        });
        </script>
        <?php

    }
}
?>
<script>
$('.date-select').datepicker({
    dateFormat: 'yy-mm-dd',
});
</script>
<?php
if($allPagesCount > 1){
    ?>
    <?php echo $this->Html->css('pagination'); ?>
        <div class="clear clear-clearfix"></div>
    <div class="paginator paging_full_numbers graph-paginator" id="example_paginate" style="padding-top:10px">
    <?php
    for ($i=0, $j=1; $i < $allPagesCount, $j <= $allPagesCount; $i++, $j++)
    {
        if($i == $graphPageNumber)
        {
            ?>
            <span class="current"><?php echo $j;?></span>
            <?php
        } else {
            ?>
            <span><a href="/hqsalesreports/index/page:<?php echo $i;?>"><?php echo $j;?></a></span>
            <?php
        }
    }
    ?>
    </div>
    <style>
    #example_paginate span:nth-last-child(2) {padding: 2px 8px;}
    .clear{clear: both;}
    </style>
    <script>
        $(document).ready(function(){
            $(".graph-paginator a").click(function(e){
                e.preventDefault();
                var page = $.urlParam(this.href,'/');
                var page = $.urlParam(page,':');

                fetchGraphPaginationData(page);
                return false;
            });
        });


        $.urlParam = function(url,delimeter, c = 1){
            var param = '';
            if(url.length > 0)
            {
                param = url.split(delimeter);
                if(param.length > 0){
                    return param[param.length-c];
                }
            }
        }
    </script>
    <?php
}
?>
<div id="pagination_data_request">
    <?php echo $this->element('hqsalesreports/product/paginationall'); ?>
</div>