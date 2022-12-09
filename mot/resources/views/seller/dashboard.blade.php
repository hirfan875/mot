@extends('seller.layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="animated fadeIn">
        <div class="row sparkboxes">
            <div class="col-sm-12">
                <x-alert class="alert-success" :status="session('success')" />
                <x-alert class="alert-danger" :status="session('error')" />
                @if($store[0]->getApprovalValidationMessage())
                <div class="alert alert-info">
                    <h5><strong>{{__('Alert !')}}</strong> {!!$store[0]->getApprovalValidationMessage('<br />')!!}.</h5>
                </div>
                @endif
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-success box box1">
                    <div class="card-body pb-0 mb-4 details">
                        <h3>{{ $total_products }}</h3>
                        <h4>{{ __('Products') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-success box box1">
                    <div class="card-body pb-0 mb-4 details">
                        <h3>{{ $total_active_products }}</h3>
                        <h4>{{ __('Active Products') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-info box box2">
                    <div class="card-body pb-0 mb-4 details">
                        <h3>{{ $category }}</h3>
                        <h4>{{ __('Total Category') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-primary box box3">
                    <div class="card-body pb-0 mb-4 details">
                        <h3>{{ $total_orders }}</h3>
                        <h4>{{ __('Total Orders') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-primary box box4">
                    <div class="card-body pb-0 mb-4 details">
                        <h3>{{ $total_orders_Delivered }}</h3>
                        <h4>{{ __('Total Orders Delivered') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-primary box box5">
                    <div class="card-body pb-0 mb-4 details">
                        <h3>{{ $total_orders_return }}</h3>
                        <h4>{{ __('Total Orders Return') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-primary box box6">
                    <div class="card-body pb-0 mb-4 details">
                        <h3>{{ $total_orders_cancelled }}</h3>
                        <h4>{{ __('Total Orders Cancelled') }}</h4>
                    </div>
                </div>
            </div>
            
        </div>
        <!--<a href="#" id="downloadPdf">Download Report Page as PDF</a>-->
        <div>
            <form  action="{{ route('seller.dashboard') }}" method="get" class="mb-3">
                <div class="table-responsive">
                    <table>
                        <tr>
                            <td width="10%">Filter by</td>
                            <td width="20%">
                                <div class="container">
                                    Start Date: <input name="startDate" id="startDate" value="{{$startDate}}" width="200" autocomplete="false" />
                                      <!--End Date: <input id="endDate" width="276" />-->
                                </div>
                            </td>
                            <td width="20%">
                                <div class="container">
                                      <!--Start Date: <input id="startDate" width="276" />-->
                                    End Date: <input name="endDate" id="endDate" value="{{$endDate}}" width="200" autocomplete="false" />
                                </div>
                            </td>
                            <td width="20%" class="pr-3">
                                Group By:
                                <select name="groupby" id="groupby" class="custom-select ml-2 mr-2">
                                    <option value="">--Calendar--</option>
                                    <option value="Yearly" @if( $groupby == 'Yearly' ) selected @endif >Yearly</option>
                                    <option value="Monthly" @if( $groupby == 'Monthly' ) selected @endif >Monthly</option>
                                    <option value="Daily" @if( $groupby == 'Daily' ) selected @endif >Daily</option>
                                </select>
                            </td>
                            <td width="30%">
                                <button id="filter" type="submit" class="btn btn-success ml-2" style="margin-top:20px;">Filter</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <div id="reportPage">
            <div class="row ">

                <div class="col-sm-6 col-lg-6">
                    <div class="card text-white bg-danger">
                        <div class="card-body pb-0 mb-6">
                            <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6">
                    <div class="card text-white bg-danger">
                        <div class="card-body pb-0 mb-6">
                            <canvas id="myChart1" style="width:100%;max-width:600px"></canvas>
                        </div>
                    </div>
                </div>
<!--                <div class="col-sm-6 col-lg-6">
                    <div class="card text-white bg-danger">
                        <div class="card-body pb-0 mb-6">
                            <canvas id="myChart2" style="width:100%;max-width:600px"></canvas>
                        </div>
                    </div>
                </div>-->
                <div class="col-sm-6 col-lg-6">
                    <div class="card text-white bg-danger">

                            <div class="card-body pb-0 mb-6">
                                <canvas id="myCharts" style="width:100%;max-width:600px"></canvas>
                            </div>

                    </div>
                </div>
<!--                <div class="col-sm-6 col-lg-6">
                    <div class="card text-white bg-danger">
                        <div class="card-body pb-0 mb-6">
                            <canvas id="myChart2s" style="width:100%;max-width:600px"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6">
                    <div class="card text-white bg-danger">
                        <div class="card-body pb-0 mb-6">
                            <canvas id="myChart3s" style="width:100%;max-width:600px"></canvas>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</div>
@php
        $countTotal = array();
        $pass = array();
        $date = array();
    @endphp
    @foreach($sales as $key => $row)
        @php
            $pass[] = '#'.substr(str_shuffle("0123456789abcdef"), 0, 6);
            $countTotal[] = $row->countTotal;
            $date[] = $row->date;
        @endphp
    @endforeach

    @php
        $countTotal1 = array();
        $pass1 = array();
        $date1 = array();
    @endphp
    @foreach($storeSales as $key => $row)
        @php
            $pass1[] = '#'.substr(str_shuffle("0123456789abcdef"), 0, 6);
            $countTotal1[] = $row->countTotal;
            $date1[] = $row->seller->name;
        @endphp
    @endforeach

    @php
        $countTotal2 = array();
        $pass2 = array();
        $date2 = array();
    @endphp
    @foreach($products as $key => $row)
        @php
            $pass2[] = '#'.substr(str_shuffle("0123456789abcdef"), 0, 6);
            $countTotal2[] = $row['countTotal'];
            $date2[] = substr($row['product']['title'], 0, 35);
        @endphp
    @endforeach

@endsection

@push('header')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.debug.js"></script>

<style>
    body { color: #777; font-family: Montserrat, Arial, sans-serif; }
    .body-bg { background: #F3F4FA !important; }
    h1, h2, h3, strong { font-weight: 600; font-size: 35px; }
    h1, h2, h3, h4, h5, h6, strong { font-weight: 600; }
    body { min-height: 100vh; }
    .content-area { max-width: 1280px; margin: 0 auto; }
    .box { background-color: #2B2D3E; padding: 25px 20px; }
    .shadow { box-shadow: 0px 1px 15px 1px rgba(69, 65, 78, 0.08); }
    .sparkboxes .box { padding-top: 10px; padding-bottom: 10px; text-shadow: 0 1px 1px 1px #666; box-shadow: 0px 1px 15px 1px rgba(69, 65, 78, 0.08); border-radius: 5px; }
    .sparkboxes .box .details { color: #fff; transform: scale(0.7) translate(-22px, 20px);    }
    .sparkboxes strong { z-index: 3; top: -8px; color: #fff; }
    .sparkboxes .box1 { background-image: linear-gradient( 135deg, #ABDCFF 10%, #0396FF 100%); }
    .sparkboxes .box2 { background-image: linear-gradient( 135deg, #CAFADF 10%, #ABDCFF 100%); }
    .sparkboxes .box3 { background-image: linear-gradient( 135deg, #FFD3A5 10%, #FD6585 100%); }
    .sparkboxes .box4 { background-image: linear-gradient( 135deg, #EE9AE5 10%, #5961F9 100%); }
    .sparkboxes .box5 { background-image: linear-gradient( 135deg, #BD9AE5 10%, #ABDCFF 100%); }
    .sparkboxes .box6 { background-image: linear-gradient( 135deg, #6D9AE5 10%, #CAFADF 100%); }
</style>
@endpush


@push('footer')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /><!-- comment -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script>
var chartColors = {
    red: 'rgb(255, 99, 132)',
    orange: 'rgb(255, 159, 64)',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(75, 192, 192)',
    blue: 'rgb(54, 162, 235)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(231,233,237)'
};

var randomScalingFactor = function () {
    return (Math.random() > 0.5 ? 1.0 : 1.0) * Math.round(Math.random() * 100);
};

var data = {
    labels: {!! json_encode($date2, JSON_HEX_TAG) !!},
    datasets: [{
            label: 'Orders By Products',
            backgroundColor: {!! json_encode($pass2, JSON_HEX_TAG) !!},
            data: {!! json_encode($countTotal2, JSON_HEX_TAG) !!}
        }]
};

var myBar = new Chart(document.getElementById("myCharts"), {
    type: 'horizontalBar',
    data: data,
    options: {
        responsive: true,
        title: {
            display: true,
            text: "Orders By Products"
        },
        tooltips: {
            mode: 'index',
            intersect: false
        },
        legend: {
            display: false,
        },
        scales: {
            xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
        }
    }
});

//var myBar2 = new Chart(document.getElementById("myChart2s"), {
//    type: 'horizontalBar',
//    data: data,
//    options: {
//        responsive: true,
//        title: {
//            display: true,
//            text: "Chart.js - Changing X Axis Step Size"
//        },
//        tooltips: {
//            mode: 'index',
//            intersect: false
//        },
//        legend: {
//            display: false,
//        },
//        scales: {
//            xAxes: [{
//                    ticks: {
//                        beginAtZero: true,
//                        stepSize: 2
//                    }
//                }]
//        }
//    }
//});

//var myBar3 = new Chart(document.getElementById("myChart3s"), {
//    type: 'horizontalBar',
//    data: data,
//    options: {
//        responsive: true,
//        maintainAspectRatio: false,
//        title: {
//            display: true,
//            text: "Chart.js - Setting maintainAspectRatio = false and Setting Parent Width/Height"
//        },
//        tooltips: {
//            mode: 'index',
//            intersect: false
//        },
//        legend: {
//            display: false,
//        },
//        scales: {
//            xAxes: [{
//                    ticks: {
//                        beginAtZero: true
//                    }
//                }]
//        }
//    }
//});

$('#downloadPdf').click(function (event) {
    // get size of report page
    var reportPageHeight = $('#reportPage').innerHeight();
    var reportPageWidth = $('#reportPage').innerWidth();

    // create a new canvas object that we will populate with all other canvas objects
    var pdfCanvas = $('<canvas />').attr({
        id: "canvaspdf",
        width: reportPageWidth,
        height: reportPageHeight
    });

    // keep track canvas position
    var pdfctx = $(pdfCanvas)[0].getContext('2d');
    var pdfctxX = 0;
    var pdfctxY = 0;
    var buffer = 100;

    // for each chart.js chart
    $("canvas").each(function (index) {
        // get the chart height/width
        var canvasHeight = $(this).innerHeight();
        var canvasWidth = $(this).innerWidth();

        // draw the chart into the new canvas
        pdfctx.drawImage($(this)[0], pdfctxX, pdfctxY, canvasWidth, canvasHeight);
        pdfctxX += canvasWidth + buffer;

        // our report page is in a grid pattern so replicate that in the new canvas
        if (index % 2 === 1) {
            pdfctxX = 0;
            pdfctxY += canvasHeight + buffer;
        }
    });

    // create new pdf and add our new canvas as an image
    var pdf = new jsPDF('l', 'pt', [reportPageWidth, reportPageHeight]);
    pdf.addImage($(pdfCanvas)[0], 'PNG', 0, 0);

    // download the pdf
    pdf.save('filename.pdf');
});


var barColors1 = {!! json_encode($pass, JSON_HEX_TAG) !!};
var xValues1 = {!! json_encode($date, JSON_HEX_TAG) !!};
var yValues1 = {!! json_encode($countTotal, JSON_HEX_TAG) !!};

var barColors = {!! json_encode($pass1, JSON_HEX_TAG) !!};
var xValues = {!! json_encode($date1, JSON_HEX_TAG) !!};
var yValues = {!! json_encode($countTotal1, JSON_HEX_TAG) !!};

var barColors2 = ["#b91d12", "#00ab43", "#2b5712", "#e8c334", "#1e7112"];
var xValues2 = ["Italy", "France", "Spain", "USA", "Argentina"];
var yValues2 = [55, 49, 44, 24, 15];

new Chart("myChart", {
    type: "doughnut",
    data: {
        labels: xValues,
        datasets: [{ backgroundColor: barColors,  data: yValues }]
    },
    options: {
        title: {
            display: true,
            text: "Sale By Stores"
        },
        responsive: true,
        legend: {
            position: 'bottom',
            labels: {
                boxWidth: 10,
                padding: 2
            }
        }
    }
});

new Chart("myChart1", {
    type: "doughnut",
    data: {
        labels: xValues1,
        datasets: [{ backgroundColor: barColors1, data: yValues1 }]
    },
    options: {
        title: {
            display: true,
            text: "Best Sales"
        },
        responsive: true,
        legend: {
            position: 'bottom',
            labels: {
                boxWidth: 10,
                padding: 2
            }
        }
    },
    fill: {
        type: 'gradient',
    }
});

//new Chart("myChart2", {
//    type: "doughnut",
//    data: {
//        labels: xValues2,
//        datasets: [{ backgroundColor: barColors2, data: yValues2 }]
//    },
//    options: {
//        title: {
//            display: true,
//            text: "Orders"
//        }
//    }
//});
</script>
<script>
    var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    $('#startDate').datepicker({
        uiLibrary: 'bootstrap4',
        iconsLibrary: 'fontawesome',
        format: 'yyyy-mm-dd',
        maxDate: function () {
            return $('#endDate').val();
        },
        onSelect: function (selected) {
            $("#endDate").datepicker("option", "minDate", selected)
        }
    });
    $('#endDate').datepicker({
        uiLibrary: 'bootstrap4',
        iconsLibrary: 'fontawesome',
        format: 'yyyy-mm-dd',
        minDate: function () {
            return $('#startDate').val();
        },
        onSelect: function (selected) {
            $("#startDate").datepicker("option", "maxDate", selected)
        }
    });

    $(document).ready(function ()
    {
        $("#filter").click(function (event) {
            var date_ini = getDate($('#startDate').val());
            var date_end = getDate($('#endDate').val());

        });
    });

    function getDate(input)
    {
        from = input.split("-");
        return new Date(from[2], from[1] - 1, from[0]);
    }
</script>
@endpush
