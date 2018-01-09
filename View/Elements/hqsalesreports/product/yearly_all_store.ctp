<?php
if (!empty($graphData['Store']) && !empty($stores)) {
    $alltamntt = $alltorders = $totalProducts = array();
    
    /**************************** For All Data in one Graph ****************************/
    
    if($graphPageNumber == 0)
    {
        foreach ($stores as $store)
        {
            if (isset($graphDataAll))
            {
                $totalItem=0;
                $difference = $yearTo-$yearFrom;
                $datee = $tamntt = $torders = array();
                for($i=$yearFrom;$i<=$yearTo;$i++){
                   $list[$i]['Year'] = "'".$i."'";
                   $list[$i]['number'] = 0;  
                }

                foreach ($graphDataAll['Store'][$store['Store']['id']] as $result) {
                    foreach($result as $key => $data){
                        $result1[$key]=$data;
                        unset($data); 
                    }
                    if(!empty($result1)){
                        foreach($result1 as $amount){
                           $list[date('Y',strtotime($amount['order_date']))]['Year'] = "'".date('Y',strtotime($amount['order_date']))."'";
                           if(empty($list[date('Y',strtotime($amount['order_date']))]['number'])){
                               $list[date('Y',strtotime($amount['order_date']))]['number'] = $amount['number'];
                               $totalItem = $totalItem + $amount['number'];

                           } else {
                               $list[date('Y',strtotime($amount['order_date']))]['number'] += $amount['number'];
                               $totalItem = $totalItem + $amount['number'];

                           }
                       }
                    }
                }
                foreach($list as $lst){
                    $datee[] = $lst['Year']; 
                    $tamntt[] = $lst['number']; 
                }

                $amntdate = implode(',',$datee);
                $tamnt = implode(',',$tamntt);
                $text = 'Yearly Report for <br/>'. $store['Store']['store_name'] . '<br/>'.$yearFrom.'-'. $yearTo;
                
            }
            
            $amntdate = implode(',',$datee);
            $tamnt = implode(',',$tamntt);
            $allTotalDates[] = $amntdate;
            $allTotalItems[] = $tamnt;
        }

        
        $newItems = '';
        foreach ($allTotalItems as $allItems){
            $newItems .=  $allItems . ',';
        }
        $newItems = trim($newItems,',');

        $totalItem = array_sum(explode(',',$newItems));

        $amntdate = implode(',',$datee);

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
                }
            }
        }

        $newaData = '';
        if (!empty($aData)) {
            foreach ($aData as $a){
                $newaData .=  $a . ',';
            }
            $newaData = trim($newaData,',');
        }
        
        if (!empty($allTotalItems) && !empty($datee)) //Total result
        {
            //Total result
            $text = '<style="font-size:12px;">Yearly Report For</style> <br/><style="font-size:14px;">HQ</style><br/><style="font-size:12px;">' . $yearFrom . ' - ' . $yearTo;
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
                            categories: [<?php echo $amntdate;?>],
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
                            valueSuffix: ''
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
        $totalItem=0;
        $difference = $yearTo-$yearFrom;
        $datee = $tamntt = $torders = array();
        for($i=$yearFrom;$i<=$yearTo;$i++){
           $list[$i]['Year'] = "'".$i."'";
           $list[$i]['number'] = 0;  
        }

        foreach ($graphData['Store'][$keyStore] as $result) {
            foreach($result as $key => $data){
                $result1[$key]=$data;
                unset($data); 
            }
            if(!empty($result1)){
                foreach($result1 as $amount){
                   $list[date('Y',strtotime($amount['order_date']))]['Year'] = "'".date('Y',strtotime($amount['order_date']))."'";
                   if(empty($list[date('Y',strtotime($amount['order_date']))]['number'])){
                       $list[date('Y',strtotime($amount['order_date']))]['number'] = $amount['number'];
                       $totalItem = $totalItem + $amount['number'];

                   } else {
                       $list[date('Y',strtotime($amount['order_date']))]['number'] += $amount['number'];
                       $totalItem = $totalItem + $amount['number'];

                   }
               }
            }
        }
        foreach($list as $lst){
            $datee[] = $lst['Year']; 
            $tamntt[] = $lst['number']; 
        }
        
        $amntdate = implode(',',$datee);
        $tamnt = implode(',',$tamntt);
        $text = '<style="font-size:12px;">Yearly Report For</style> <br/><style="font-size:14px;">' . addslashes($valueStore) . '</style><br/><style="font-size:12px;">' . $yearFrom . ' - ' . $yearTo;
        $subTitle = '<style="font-size:14px;font-weight:bold;">Total '.$totalItem.' Item </style>';
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
                        categories: [<?php echo $amntdate;?>],
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
                        valueSuffix: ''
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
                        data: [<?php echo $tamnt; ?>],
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
