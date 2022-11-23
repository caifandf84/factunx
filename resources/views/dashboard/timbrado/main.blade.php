@extends('layouts.app')

@section('content')
<style  >
#caja_mediana {
    width: 100%;  
    border: 1px solid #222935;
}
</style>
<script src="https://www.conuxi.com/factunx_frameworks/Highcharts-5.0.14/code/highcharts.js"></script>
<script src="https://www.conuxi.com/factunx_frameworks/Highcharts-5.0.14/code/highcharts-more.js"></script>
<script src="https://www.conuxi.com/factunx_frameworks/Highcharts-5.0.14/code/modules/exporting.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-3">
                                <img src="https://www.conuxi.com/factunx_frameworks/images/factu_nx_dashboard.png" style="height: 80px;width: 250px;" alt="SAT" >
                            </div>
                            <div class="col-lg-9">
                                <div class="col-lg-12">
                                    <p class="lead">
                                        <strong>Selecciona Mes y Año para visualizar</strong>
                                    </p>
                                </div>
                                <div class="col-lg-12">
                                    
                                    <div class="row">
                                        {{ Form::open(array('url' => '/reporte/timbrado','method' => 'get')) }}
                                        <div class="col-sm-4">
                                          <div class="input-group">
                                              <span class="input-group-addon ">Año:</span>
                                              {!! Form::select('anio',$anios,$anio, ['class' => 'form-control']) !!}
                                          </div>  
                                        </div>
                                        <div class="col-sm-4">
                                          <div class="input-group">
                                              <span class="input-group-addon">Mes:</span>
                                              {!! Form::select('mes',$meses,$mes, ['class' => 'form-control']) !!}
                                          </div>  
                                        </div>
                                        <div class="col-sm-4">
                                          {!! Form::submit('Ver',['class' => 'btn btn-info']) !!}
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- Cuerpo del Dashboard-->
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div id="container_anio" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
                            </div>
                            <div class="col-sm-6">
                                <div id="container_timb_mes" style="min-width: 310px; max-width: 400px; height: 300px; margin: 0 auto"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div id="container_cancelado_mes" style="min-width: 310px; max-width: 400px; height: 300px; margin: 0 auto"></div>
                            </div>
                            <div class="col-sm-6">
                                <div id="container_cancelado_anio" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
Highcharts.chart('container_anio', {
    chart: {
        type: 'column',
        plotBackgroundColor: null,
        plotBackgroundImage: null,
        plotBorderWidth: 1,
        plotShadow: true
    },
    title: {
        text: 'Timbrado ante el SAT en {{$anio}} mediante FACTUNX'
    },
    subtitle: {
        text: 'ver: <a href="{{ url('/documentos/ver/lista/emitidos') }}">Documentos</a>'
    },
    xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Timbres (unidades)'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: 'Existen : <b>{point.y:.1f} unidades</b>'
    },
    series: [{
        name: 'Año',
        data: [
            ['Enero', {{$tTimbradoAnio[0]}}],
            ['Febrero', {{$tTimbradoAnio[1]}}],
            ['Marzo', {{$tTimbradoAnio[2]}}],
            ['Abril', {{$tTimbradoAnio[3]}}],
            ['Mayo', {{$tTimbradoAnio[4]}}],
            ['Junio', {{$tTimbradoAnio[5]}}],
            ['Julio', {{$tTimbradoAnio[6]}}],
            ['Agosto', {{$tTimbradoAnio[7]}}],
            ['Septiembre', {{$tTimbradoAnio[8]}}],
            ['Octubre', {{$tTimbradoAnio[9]}}],
            ['Noviembre', {{$tTimbradoAnio[10]}}],
            ['Diciembre', {{$tTimbradoAnio[11]}}]
        ],
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});

Highcharts.chart('container_cancelado_anio', {
    chart: {
        type: 'column',
        plotBackgroundColor: null,
        plotBackgroundImage: null,
        plotBorderWidth: 1,
        plotShadow: true
    },
    title: {
        text: 'Cancelados ante el SAT en {{$anio}} mediante FACTUNX'
    },
    subtitle: {
        text: 'ver: <a href="{{ url('/documentos/ver/lista/emitidos') }}">Documentos Cancelados</a>'
    },
    xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Timbres Cancelados (unidades)'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: 'Existen : <b>{point.y:.1f} unidades</b>'
    },
    series: [{
        name: 'Año',
        data: [
            ['Enero', {{$tCanceladoAnio[0]}}],
            ['Febrero', {{$tCanceladoAnio[1]}}],
            ['Marzo', {{$tCanceladoAnio[2]}}],
            ['Abril', {{$tCanceladoAnio[3]}}],
            ['Mayo', {{$tCanceladoAnio[4]}}],
            ['Junio', {{$tCanceladoAnio[5]}}],
            ['Julio', {{$tCanceladoAnio[6]}}],
            ['Agosto', {{$tCanceladoAnio[7]}}],
            ['Septiembre', {{$tCanceladoAnio[8]}}],
            ['Octubre', {{$tCanceladoAnio[9]}}],
            ['Noviembre', {{$tCanceladoAnio[10]}}],
            ['Diciembre', {{$tCanceladoAnio[11]}}]
        ],
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});
/*Datos mensuales*/
Highcharts.chart('container_timb_mes', {

    chart: {
        type: 'gauge',
        plotBackgroundColor: '#F2EB58',
        plotBackgroundImage: null,
        plotBorderWidth: 1,
        plotShadow: true
    },

    title: {
        text: 'Timbres utilizados en {{$mesTxt}}'
    },

    pane: {
        startAngle: -150,
        endAngle: 150,
        background: [{
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#FFF'],
                    [1, '#333']
                ]
            },
            borderWidth: 0,
            outerRadius: '109%'
        }, {
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#333'],
                    [1, '#FFF']
                ]
            },
            borderWidth: 1,
            outerRadius: '107%'
        }, {
            // default background
        }, {
            backgroundColor: '#DDD',
            borderWidth: 0,
            outerRadius: '105%',
            innerRadius: '103%'
        }]
    },

    // the value axis
    yAxis: {
        min: 0,
        max: 300,

        minorTickInterval: 'auto',
        minorTickWidth: 1,
        minorTickLength: 10,
        minorTickPosition: 'inside',
        minorTickColor: '#666',

        tickPixelInterval: 30,
        tickWidth: 2,
        tickPosition: 'inside',
        tickLength: 10,
        tickColor: '#666',
        labels: {
            step: 2,
            rotation: 'auto'
        },
        title: {
            text: 'Timbres'
        },
        plotBands: [{
            from: 0,
            to: 150,
            color: '#55BF3B' // green
        }, {
            from: 150,
            to: 200,
            color: '#DDDF0D' // yellow
        }, {
            from: 200,
            to: 300,
            color: '#DF5353' // red
        }]
    },

    series: [{
        name: 'Timbres del mes de {{$mesTxt}}',
        data: [{{$timbradoMes}}],
        tooltip: {
            valueSuffix: ' Unidades'
        }
    }]

});

Highcharts.chart('container_cancelado_mes', {

    chart: {
        type: 'gauge',
        plotBackgroundColor: '#F2EB58',
        plotBackgroundImage: null,
        plotBorderWidth: 1,
        plotShadow: true
    },

    title: {
        text: 'Timbres Cancelados en {{$mesTxt}}'
    },

    pane: {
        startAngle: -150,
        endAngle: 150,
        background: [{
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#FFF'],
                    [1, '#333']
                ]
            },
            borderWidth: 0,
            outerRadius: '109%'
        }, {
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#333'],
                    [1, '#FFF']
                ]
            },
            borderWidth: 1,
            outerRadius: '107%'
        }, {
            // default background
        }, {
            backgroundColor: '#DDD',
            borderWidth: 0,
            outerRadius: '105%',
            innerRadius: '103%'
        }]
    },

    // the value axis
    yAxis: {
        min: 0,
        max: 300,

        minorTickInterval: 'auto',
        minorTickWidth: 1,
        minorTickLength: 10,
        minorTickPosition: 'inside',
        minorTickColor: '#666',

        tickPixelInterval: 30,
        tickWidth: 2,
        tickPosition: 'inside',
        tickLength: 10,
        tickColor: '#666',
        labels: {
            step: 2,
            rotation: 'auto'
        },
        title: {
            text: 'Timbres'
        },
        plotBands: [{
            from: 0,
            to: 150,
            color: '#55BF3B' // green
        }, {
            from: 150,
            to: 200,
            color: '#DDDF0D' // yellow
        }, {
            from: 200,
            to: 300,
            color: '#DF5353' // red
        }]
    },

    series: [{
        name: 'Cancelaciones de timbres del mes de {{$mesTxt}}',
        data: [{{$canceladoMes}}],
        tooltip: {
            valueSuffix: ' Unidades'
        }
    }]

});

</script>
@endsection