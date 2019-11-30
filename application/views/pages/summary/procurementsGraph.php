<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div>
    <div style="width:22%;float:left;">
        <fieldset>
            <legend style="text-align:center">TAMPILKAN DATA BERDASAR :</legend>
            <div>
                <form id="summaryForm">
                    <?php input('select_only', '', 'catCode', 'catCode', 'select2', '', ' style="width:100%" ', 'firstOption', array('MIP' => 'INSTRUMENT (MIP)', 'MIS' => 'SET (MIS)', 'MIC' => 'CONTAINER/BOX (MIC)', '' => 'SEMUA KATEGORI ASET')) ?>
                    
                    <hr>

                    <div style="width:100%;text-align:center;">
                        <button class="btnMode btn btn-info" onClick="refreshChart('year')">Tahunan</button>
                        <button class="btnMode btn btn-dark" onClick="refreshChart('month')">Bulanan</button>
                    </div>
                    
                    <br>

                    <?php input('select_only', '', 'firstMonth', 'firstMonth', 'select2', '', ' style="width:100%;" ', 'firstOption', explode(',', env('MONTH_LIST')), intToMonth('01')); ?>
                    <input type="number" class="form-control" name="yearProcurement_first" placeholder="Tahun Awal" min="1945" style="margin-bottom:10px;margin-top:5px;" value="<?=currentTime('Y')?>">
                    <div style="text-align:center;margin-bottom:10px;font-weight:bold;">s/d</div>
                    <?php input('select_only', '', 'lastMonth', 'lastMonth', 'select2', '', ' style="width:100%;" ', 'firstOption', explode(',', env('MONTH_LIST')), intToMonth(currentTime('m'))); ?>
                    <input type="number" class="form-control" name="yearProcurement_last" placeholder="Tahun Akhir" min="1945" style="margin-bottom:10px;margin-top:5px;" value="<?=currentTime('Y')?>">

                    <button type="submit" class="btn btn-primary" style="width:100%;">APPLY</button>
                </form>
            </div>
        </fieldset>
    </div>

    <br>
    <div style="float:right;width:73%;">
        <span style="float:right;">
            <button class="btn btn-primary" id="plain">Vertical</button>
            <button class="btn btn-primary" id="inverted">Horizontal</button>
            <button class="btn btn-primary" id="polar">Polar</button>
        </span>
        <br><br><br>

        <div id="container"></div>
        <table class="table table-stripped" style="text-align:center;vertical-align:middle;border:solid 1px #fff !important;">
            <tr>    
                <th width="100" rowspan="2" style="vertical-align:middle !important;">NO</th>
                <th rowspan="2" style="vertical-align:middle !important;">TAHUN</th>
                <th colspan="3">TOTAL</th>
            </tr>
            <tr>
                <th style="background:#6ba4ff !important;">INSTRUMENT</th>
                <th style="background:#6ba4ff !important;">SET</th>
                <th style="background:#6ba4ff !important;">CONTAINER/BOX</th>
            </tr>
            <tr>
                <td>1</td>
                <td>2017</td>
                <td>12085</td>
                <td>102</td>
                <td>102</td>
            </tr>
            <tr>
                <td>2</td>
                <td>2018</td>
                <td>50120</td>
                <td>502</td>
                <td>502</td>
            </tr>
            <tr>
                <td>1</td>
                <td>2019</td>
                <td>9830</td>
                <td>354</td>
                <td>354</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    
    var chartMode = 'year';

    var chart = Highcharts.chart('container', {
        title: {
            text: 'Grafik Perolehan Aset'
        },
        yAxis: [{
            className: 'highcharts-color-0',
            title: {
                text: 'Jumlah Aset'
            }
        }, {
            className: 'highcharts-color-1',
            opposite: true,
            title: {
                text: 'Parameter'
            }
        }],
        xAxis: {
            categories: []
        },
        plotOptions: {
            column: {
                borderRadius: 5
            }
        },
        series: [{
            type: 'column',
            colorByPoint: true,
            data: [],
            showInLegend: false
        }],
        credits: {
            enabled: false
        }
    });

    $('#plain').click(function () {
        chart.update({
            chart: {
                inverted: false,
                polar: false
            }
        });
    });

    $('#inverted').click(function () {
        chart.update({
            chart: {
                inverted: true,
                polar: false
            }
        });
    });

    $('#polar').click(function () {
        chart.update({
            chart: {
                inverted: false,
                polar: true
            }
        });
    });

    refreshChart('year');

    function refreshChart(mode) {
        chartMode = mode;

        $.ajax({
            'url' : '<?=site_url('summary/procurementsData?mode=')?>'+mode,
            'type' : 'GET',
        }).then(res => {
            res = JSON.parse(res);     

            chart.update({
                xAxis: {
                    categories: res.categories
                },
                series: [{
                    name: 'Jumlah Aset',
                    type: 'column',
                    colorByPoint: true,
                    data: res.data,
                    showInLegend: false,
                }]
            })
        });
    }

    $(document).ready(function() {
        $("#catCode").select2({minimumResultsForSearch: -1});

        $('#summaryForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '<?=site_url('summary/procurementsData?mode=')?>' + chartMode,
                method: 'POST',
                data: $(this).serialize(),
            }).then(res => {
                console.log(res);
            });
        });

        $('.btnMode').click(function() {
            $('.btnMode').removeClass('btn-info');
            $('.btnMode').addClass('btn-dark');

            $(this).removeClass('btn-dark');
            $(this).addClass('btn-info');
        });
    });

</script>
