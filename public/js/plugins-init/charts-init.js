/**
 * **************************************************
 * ******* Name: drora
 * ******* Description: Bootstrap 4 Admin Dashboard
 * ******* Version: 1.0.0
 * ******* Released on 2019-02-08 15:41:24
 * ******* Support Email : quixlab.com@gmail.com
 * ******* Support Skype : sporsho9
 * ******* Author: Quixlab
 * ******* URL: https://quixlab.com
 * ******* Themeforest Profile : https://themeforest.net/user/quixlab
 * ******* License: ISC
 * ***************************************************
 */

/*************
ChartJS 
***************/

//basic bar chart
(function($) {
    "use strict"

    const barChart_1 = document.getElementById("barChart_1").getContext('2d');
    
    barChart_1.height = 100;

    new Chart(barChart_1, {
        type: 'bar',
        data: {
            defaultFontFamily: 'Poppins',
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
            datasets: [
                {
                    label: "My First dataset",
                    data: [65, 59, 80, 81, 56, 55, 40],
                    borderColor: '#c00a27',
                    borderWidth: "0",
                    backgroundColor: '#c00a27'
                }
            ]
        },
        options: {
            legend: false, 
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    // Change here
                    barPercentage: 0.5
                }]
            }
        }
    });



//gradient bar chart
    const barChart_2 = document.getElementById("barChart_2").getContext('2d');
    //generate gradient
    const barChart_2gradientStroke = barChart_2.createLinearGradient(0, 0, 0, 250);
    barChart_2gradientStroke.addColorStop(0, "#fa5c7c");
    barChart_2gradientStroke.addColorStop(1, "#727cf5");

    barChart_2.height = 100;

    new Chart(barChart_2, {
        type: 'bar',
        data: {
            defaultFontFamily: 'Poppins',
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
            datasets: [
                {
                    label: "My First dataset",
                    data: [65, 59, 80, 81, 56, 55, 40],
                    borderColor: barChart_2gradientStroke,
                    borderWidth: "0",
                    backgroundColor: barChart_2gradientStroke, 
                    hoverBackgroundColor: barChart_2gradientStroke
                }
            ]
        },
        options: {
            legend: false, 
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    // Change here
                    barPercentage: 0.5
                }]
            }
        }
    });



//stalked bar chart
    const barChart_3 = document.getElementById("barChart_3").getContext('2d');
    //generate gradient
    const barChart_3gradientStroke = barChart_3.createLinearGradient(50, 100, 50, 50);
    barChart_3gradientStroke.addColorStop(0, "#fa5c7c");
    barChart_3gradientStroke.addColorStop(1, "#727cf5");

    const barChart_3gradientStroke2 = barChart_3.createLinearGradient(50, 100, 50, 50);
    barChart_3gradientStroke2.addColorStop(0, "rgb(192, 10, 39)");
    barChart_3gradientStroke2.addColorStop(1, "rgb(206, 29, 118)");

    const barChart_3gradientStroke3 = barChart_3.createLinearGradient(50, 100, 50, 50);
    barChart_3gradientStroke3.addColorStop(0, "#6c757d");
    barChart_3gradientStroke3.addColorStop(1, "#0787ea");
    
    barChart_3.height = 100;

    let barChartData = {
        defaultFontFamily: 'Poppins',
        labels: ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Red',
            backgroundColor: barChart_3gradientStroke,
            hoverBackgroundColor: barChart_3gradientStroke, 
            data: [
                '12',
                '12',
                '12',
                '12',
                '12',
                '12',
                '12'
            ]
        }, {
            label: 'Green',
            backgroundColor: barChart_3gradientStroke2,
            hoverBackgroundColor: barChart_3gradientStroke2, 
            data: [
                '12',
                '12',
                '12',
                '12',
                '12',
                '12',
                '12'
            ]
        }, {
            label: 'Blue',
            backgroundColor: barChart_3gradientStroke3,
            hoverBackgroundColor: barChart_3gradientStroke3, 
            data: [
                '12',
                '12',
                '12',
                '12',
                '12',
                '12',
                '12'
            ]
        }]

    };

    new Chart(barChart_3, {
        type: 'bar',
        data: barChartData,
        options: {
            legend: {
                display: false
            }, 
            title: {
                display: false
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            responsive: true,
            scales: {
                xAxes: [{
                    stacked: true,
                }],
                yAxes: [{
                    stacked: true
                }]
            }
        }
    });




let draw = Chart.controllers.line.__super__.draw; //draw shadow

//basic line chart
    const lineChart_1 = document.getElementById("lineChart_1").getContext('2d');

    Chart.controllers.line = Chart.controllers.line.extend({
        draw: function () {
            draw.apply(this, arguments);
            let nk = this.chart.chart.ctx;
            let _stroke = nk.stroke;
            nk.stroke = function () {
                nk.save();
                nk.shadowColor = 'rgba(255, 0, 0, .2)';
                nk.shadowBlur = 10;
                nk.shadowOffsetX = 0;
                nk.shadowOffsetY = 10;
                _stroke.apply(this, arguments)
                nk.restore();
            }
        }
    });
    
    lineChart_1.height = 100;

    new Chart(lineChart_1, {
        type: 'line',
        data: {
            defaultFontFamily: 'Poppins',
            labels: ["Jan", "Febr", "Mar", "Apr", "May", "Jun", "Jul"],
            datasets: [
                {
                    label: "My First dataset",
                    data: [25, 20, 60, 41, 66, 45, 80],
                    borderColor: 'rgba(192, 10, 39, 1)',
                    borderWidth: "2",
                    backgroundColor: 'transparent',  
                    pointBackgroundColor: 'rgba(192, 10, 39, 1)'
                }
            ]
        },
        options: {
            legend: false, 
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true, 
                        max: 100, 
                        min: 0, 
                        stepSize: 20, 
                        padding: 10
                    }
                }],
                xAxes: [{
                    ticks: {
                        padding: 5
                    }
                }]
            }
        }
    });
    


//gradient line chart
    const lineChart_2 = document.getElementById("lineChart_2").getContext('2d');
    //generate gradient
    const lineChart_2gradientStroke = lineChart_2.createLinearGradient(500, 0, 100, 0);
    lineChart_2gradientStroke.addColorStop(0, "#fa5c7c");
    lineChart_2gradientStroke.addColorStop(1, "#727cf5");

    Chart.controllers.line = Chart.controllers.line.extend({
        draw: function () {
            draw.apply(this, arguments);
            let nk = this.chart.chart.ctx;
            let _stroke = nk.stroke;
            nk.stroke = function () {
                nk.save();
                nk.shadowColor = 'rgba(250,92,124, .2)';
                nk.shadowBlur = 10;
                nk.shadowOffsetX = 0;
                nk.shadowOffsetY = 10;
                _stroke.apply(this, arguments)
                nk.restore();
            }
        }
    });
        
    lineChart_2.height = 100;

    new Chart(lineChart_2, {
        type: 'line',
        data: {
            defaultFontFamily: 'Poppins',
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
            datasets: [
                {
                    label: "My First dataset",
                    data: [25, 20, 60, 41, 66, 45, 80],
                    borderColor: lineChart_2gradientStroke,
                    borderWidth: "2",
                    backgroundColor: 'transparent', 
                    pointBackgroundColor: '#727cf5'
                }
            ]
        },
        options: {
            legend: false, 
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true, 
                        max: 100, 
                        min: 0, 
                        stepSize: 20, 
                        padding: 10
                    }
                }],
                xAxes: [{ 
                    ticks: {
                        padding: 5
                    }
                }]
            }
        }
    });


//dual line chart
    const lineChart_3 = document.getElementById("lineChart_3").getContext('2d');
    //generate gradient
    const lineChart_3gradientStroke1 = lineChart_3.createLinearGradient(500, 0, 100, 0);
    lineChart_3gradientStroke1.addColorStop(0, "#fa5c7c");
    lineChart_3gradientStroke1.addColorStop(1, "#727cf5");

    const lineChart_3gradientStroke2 = lineChart_3.createLinearGradient(500, 0, 100, 0);
    lineChart_3gradientStroke2.addColorStop(0, "rgb(192, 10, 39)");
    lineChart_3gradientStroke2.addColorStop(1, "#ce1d76");

    Chart.controllers.line = Chart.controllers.line.extend({
        draw: function () {
            draw.apply(this, arguments);
            let nk = this.chart.chart.ctx;
            let _stroke = nk.stroke;
            nk.stroke = function () {
                nk.save();
                nk.shadowColor = 'rgba(0, 0, 0, 0)';
                nk.shadowBlur = 10;
                nk.shadowOffsetX = 0;
                nk.shadowOffsetY = 10;
                _stroke.apply(this, arguments)
                nk.restore();
            }
        }
    });
        
    lineChart_3.height = 100;

    new Chart(lineChart_3, {
        type: 'line',
        data: {
            defaultFontFamily: 'Poppins',
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
            datasets: [
                {
                    label: "My First dataset",
                    data: [25, 20, 60, 41, 66, 45, 80],
                    borderColor: lineChart_3gradientStroke1,
                    borderWidth: "2",
                    backgroundColor: 'transparent', 
                    pointBackgroundColor: '#727cf5'
                }, {
                    label: "My First dataset",
                    data: [5, 20, 15, 41, 35, 65, 80],
                    borderColor: lineChart_3gradientStroke2,
                    borderWidth: "2",
                    backgroundColor: 'transparent', 
                    pointBackgroundColor: 'rgb(192, 10, 39)'
                }
            ]
        },
        options: {
            legend: false, 
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true, 
                        max: 100, 
                        min: 0, 
                        stepSize: 20, 
                        padding: 10
                    }
                }],
                xAxes: [{ 
                    ticks: {
                        padding: 5
                    }
                }]
            }
        }
    });
    


//basic area chart

    const areaChart_1 = document.getElementById("areaChart_1").getContext('2d');
    
    areaChart_1.height = 100;

    new Chart(areaChart_1, {
        type: 'line',
        data: {
            defaultFontFamily: 'Poppins',
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
            datasets: [
                {
                    label: "My First dataset",
                    data: [25, 20, 60, 41, 66, 45, 80],
                    borderColor: 'rgba(0, 0, 1128, .3)',
                    borderWidth: "1",
                    backgroundColor: 'rgba(0, 171, 197, .5)', 
                    pointBackgroundColor: 'rgba(0, 0, 1128, .3)'
                }
            ]
        },
        options: {
            legend: false, 
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true, 
                        max: 100, 
                        min: 0, 
                        stepSize: 20, 
                        padding: 10
                    }
                }],
                xAxes: [{ 
                    ticks: {
                        padding: 5
                    }
                }]
            }
        }
    });


//gradient area chart

    const areaChart_2 = document.getElementById("areaChart_2").getContext('2d');
    //generate gradient
    const areaChart_2gradientStroke = areaChart_2.createLinearGradient(500, 0, 100, 0);
    areaChart_2gradientStroke.addColorStop(0, "#fa5c7c");
    areaChart_2gradientStroke.addColorStop(1, "#727cf5");
    
    areaChart_2.height = 100;

    new Chart(areaChart_2, {
        type: 'line',
        data: {
            defaultFontFamily: 'Poppins',
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
            datasets: [
                {
                    label: "My First dataset",
                    data: [25, 20, 60, 41, 66, 45, 80],
                    borderColor: areaChart_2gradientStroke,
                    borderWidth: "1",
                    backgroundColor: areaChart_2gradientStroke
                }
            ]
        },
        options: {
            legend: false, 
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true, 
                        max: 100, 
                        min: 0, 
                        stepSize: 20, 
                        padding: 10
                    }
                }],
                xAxes: [{ 
                    ticks: {
                        padding: 5
                    }
                }]
            }
        }
    });
    

//gradient area chart

    const areaChart_3 = document.getElementById("areaChart_3").getContext('2d');
    
    areaChart_3.height = 100;

    new Chart(areaChart_3, {
        type: 'line',
        data: {
            defaultFontFamily: 'Poppins',
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
            datasets: [
                {
                    label: "My First dataset",
                    data: [25, 20, 60, 41, 66, 45, 80],
                    borderColor: 'rgb(0, 171, 197)',
                    borderWidth: "1",
                    backgroundColor: 'rgba(0, 171, 197, .5)'
                }, 
                {
                    label: "My First dataset",
                    data: [5, 25, 20, 41, 36, 75, 70],
                    borderColor: 'rgb(250,92,124)',
                    borderWidth: "1",
                    backgroundColor: 'rgba(250,92,124, .5)'
                }
            ]
        },
        options: {
            legend: false, 
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true, 
                        max: 100, 
                        min: 0, 
                        stepSize: 20, 
                        padding: 10
                    }
                }],
                xAxes: [{ 
                    ticks: {
                        padding: 5
                    }
                }]
            }
        }
    });
    


    
    //radar chart
    const radar_chart = document.getElementById("radar_chart").getContext('2d');

    const radar_chartgradientStroke1 = radar_chart.createLinearGradient(500, 0, 100, 0);
    radar_chartgradientStroke1.addColorStop(0, "rgba(54, 185, 216, .5)");
    radar_chartgradientStroke1.addColorStop(1, "rgba(75, 255, 162, .5)");

    const radar_chartgradientStroke2 = radar_chart.createLinearGradient(500, 0, 100, 0);
    radar_chartgradientStroke2.addColorStop(0, "rgba(68, 0, 235, .5");
    radar_chartgradientStroke2.addColorStop(1, "rgba(68, 236, 245, .5");

    // radar_chart.height = 100;
    new Chart(radar_chart, {
        type: 'radar',
        data: {
            defaultFontFamily: 'Poppins',
            labels: [["Eating", "Dinner"], ["Drinking", "Water"], "Sleeping", ["Designing", "Graphics"], "Coding", "Cycling", "Running"],
            datasets: [
                {
                    label: "My First dataset",
                    data: [65, 59, 66, 45, 56, 55, 40],
                    borderColor: '#727cf5',
                    borderWidth: "1",
                    backgroundColor: radar_chartgradientStroke2
                },
                {
                    label: "My Second dataset",
                    data: [28, 12, 40, 19, 63, 27, 87],
                    borderColor: '#727cf5',
                    borderWidth: "1",
                    backgroundColor: radar_chartgradientStroke1
                }
            ]
        },
        options: {
            legend: false,
            maintainAspectRatio: false, 
            scale: {
                ticks: {
                    beginAtZero: true
                }
            }
        }
    });
    


//pie chart

    //pie chart
    const pie_chart = document.getElementById("pie_chart").getContext('2d');
    // pie_chart.height = 100;
    new Chart(pie_chart, {
        type: 'pie',
        data: {
            defaultFontFamily: 'Poppins',
            datasets: [{
                data: [45, 25, 20, 10],
                borderWidth: 0, 
                backgroundColor: [
                    "rgba(0, 171, 197, .9)",
                    "rgba(0, 171, 197, .7)",
                    "rgba(0, 171, 197, .5)",
                    "rgba(0,0,0,0.07)"
                ],
                hoverBackgroundColor: [
                    "rgba(0, 171, 197, .9)",
                    "rgba(0, 171, 197, .7)",
                    "rgba(0, 171, 197, .5)",
                    "rgba(0,0,0,0.07)"
                ]

            }],
            labels: [
                "one",
                "two",
                "three", 
                "four"
            ]
        },
        options: {
            responsive: true, 
            legend: false, 
            maintainAspectRatio: false
        }
    });
    
    
    
    //doughut chart
    const doughnut_chart = document.getElementById("doughnut_chart").getContext('2d');
    // doughnut_chart.height = 100;
    new Chart(doughnut_chart, {
        type: 'doughnut',
        data: {
            defaultFontFamily: 'Poppins',
            datasets: [{
                data: [45, 25, 20, 10],
                borderWidth: 0, 
                backgroundColor: [
                    "rgba(250,92,124, .9)",
                    "rgba(250,92,124, .7)",
                    "rgba(250,92,124, .5)",
                    "rgba(250,92,124, .4)"
                ],
                hoverBackgroundColor: [
                    "rgba(250,92,124, .5)",
                    "rgba(250,92,124, .4)",
                    "rgba(250,92,124, .3)",
                    "rgba(250,92,124, .2)"
                ]

            }],
            // labels: [
            //     "green",
            //     "green",
            //     "green",
            //     "green"
            // ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    
    
    
    //polar chart
    const polar_chart = document.getElementById("polar_chart").getContext('2d');
    // polar_chart.height = 100;
    new Chart(polar_chart, {
        type: 'polarArea',
        data: {
            defaultFontFamily: 'Poppins',
            datasets: [{
                data: [15, 18, 9, 6, 19],
                borderWidth: 0, 
                backgroundColor: [
                    "rgba(0, 171, 197, .8)",
                    "rgba(250,92,124, .8)",
                    "rgba(192, 10, 39, .8)",
                    "rgba(206, 29, 118, .8)",
                    "rgba(7, 135, 234, .8)"
                ]

            }]
        },
        options: {
            responsive: true, 
            maintainAspectRatio: false
        }
    });

    



/*************
Morris Charts
************** */


//donught chart


    Morris.Donut({
        element: 'morris_donught',
        data: [{
            label: "\xa0 \xa0 Download Sales \xa0 \xa0",
            value: 12,

        }, {
            label: "\xa0 \xa0 In-Store Sales \xa0 \xa0",
            value: 30
        }, {
            label: "\xa0 \xa0 Mail-Order Sales \xa0 \xa0",
            value: 20
        }],
        resize: true,
        colors: ['#6c757d', 'rgb(192, 10, 39)', '#4400eb']
    });
    

//donught chart
    Morris.Donut({
        element: 'morris_donught_2',
        data: [{
            label: "\xa0 \xa0 Download Sales \xa0 \xa0",
            value: 12,

        }, {
            label: "\xa0 \xa0 In-Store Sales \xa0 \xa0",
            value: 30
        }, {
            label: "\xa0 \xa0 Mail-Order Sales \xa0 \xa0",
            value: 20
        }],
        resize: true,
        colors: ['#6c757d', 'rgb(192, 10, 39)', '#4400eb']
    });
    

//line chart
    let line = new Morris.Line({
        element: 'morris_line',
        resize: true,
        data: [{
                y: '2011 Q1',
                item1: 2666
            },
            {
                y: '2011 Q2',
                item1: 2778
            },
            {
                y: '2011 Q3',
                item1: 4912
            },
            {
                y: '2011 Q4',
                item1: 3767
            },
            {
                y: '2012 Q1',
                item1: 6810
            },
            {
                y: '2012 Q2',
                item1: 5670
            },
            {
                y: '2012 Q3',
                item1: 4820
            },
            {
                y: '2012 Q4',
                item1: 15073
            },
            {
                y: '2013 Q1',
                item1: 10687
            },
            {
                y: '2013 Q2',
                item1: 8432
            }
        ],
        xkey: 'y',
        ykeys: ['item1'],
        labels: ['Item 1'],
        gridLineColor: 'transparent',
        lineColors: ['rgb(192, 10, 39)'], //here
        lineWidth: 1,
        hideHover: 'auto',
        pointSize: 0,
        axes: false
    });
    



//line chart
    Morris.Area({
        element: 'line_chart_2',
        data: [{
                period: '2001',
                smartphone: 0,
                windows: 0,
                mac: 0
            }, {
                period: '2002',
                smartphone: 90,
                windows: 60,
                mac: 25
            }, {
                period: '2003',
                smartphone: 40,
                windows: 80,
                mac: 35
            }, {
                period: '2004',
                smartphone: 30,
                windows: 47,
                mac: 17
            }, {
                period: '2005',
                smartphone: 150,
                windows: 40,
                mac: 120
            }, {
                period: '2006',
                smartphone: 25,
                windows: 80,
                mac: 40
            }, {
                period: '2007',
                smartphone: 10,
                windows: 10,
                mac: 10
            }


        ],
        xkey: 'period',
        ykeys: ['smartphone', 'windows', 'mac'],
        labels: ['Phone', 'Windows', 'Mac'],
        pointSize: 3,
        fillOpacity: 0,
        pointStrokeColors: ['#DCDCDC', '#34C73B', '#0000FF'],
        behaveLikeLine: true,
        gridLineColor: 'transparent',
        lineWidth: 3,
        hideHover: 'auto',
        lineColors: ['rgb(192, 10, 39)', 'rgb(0, 171, 197)', '#6c757d'],
        resize: true

    });



//bar chart
    Morris.Bar({
        element: 'morris_bar',
        data: [{
            y: '2006',
            a: 100,
            b: 90,
            c: 60
        }, {
            y: '2007',
            a: 75,
            b: 65,
            c: 40
        }, {
            y: '2008',
            a: 50,
            b: 40,
            c: 30
        }, {
            y: '2009',
            a: 75,
            b: 65,
            c: 40
        }, {
            y: '2010',
            a: 50,
            b: 40,
            c: 30
        }, {
            y: '2011',
            a: 75,
            b: 65,
            c: 40
        }, {
            y: '2012',
            a: 100,
            b: 90,
            c: 40
        }],
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
        labels: ['A', 'B', 'C'],
        barColors: ['#f25521', '#f9c70a', '#727cf5'],
        hideHover: 'auto',
        gridLineColor: 'transparent',
        resize: true,
        barSizeRatio: 0.25,
    });


//bar chart

    Morris.Bar({
        element: 'morris_bar_stalked',
        data: [{
            y: 'S',
            a: 66, 
            b: 34
        }, {
            y: 'M',
            a: 75, 
            b: 25
        }, {
            y: 'T',
            a: 50, 
            b: 50
        }, {
            y: 'W',
            a: 75, 
            b: 25
        }, {
            y: 'T',
            a: 50, 
            b: 50
        }, {
            y: 'F',
            a: 16, 
            b: 84
        }, {
            y: 'S',
            a: 70, 
            b: 30
        }, {
            y: 'S',
            a: 30, 
            b: 70
        }, {
            y: 'M',
            a: 40, 
            b: 60
        }, {
            y: 'T',
            a: 29, 
            b: 71
        }, {
            y: 'W',
            a: 44, 
            b: 56
        }, {
            y: 'T',
            a: 30, 
            b: 70
        }, {
            y: 'F',
            a: 60, 
            b: 40
        }, {
            y: 'G',
            a: 40, 
            b: 60
        }, {
            y: 'S',
            a: 46, 
            b: 54
        }],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['A', 'B'],
        barColors: ['#727cf5', "#F1F3F7"],
        hideHover: 'auto',
        gridLineColor: 'transparent',
        resize: true,
        barSizeRatio: 0.25,
        stacked: true, 
        behaveLikeLine: true, 
        // barRadius: [6, 6, 0, 0]
    });
    


//area chart

    Morris.Area({
        element: 'morris_area',
        data: [{
                period: '2001',
                smartphone: 0,
                windows: 0,
                mac: 0
            }, {
                period: '2002',
                smartphone: 90,
                windows: 60,
                mac: 25
            }, {
                period: '2003',
                smartphone: 40,
                windows: 80,
                mac: 35
            }, {
                period: '2004',
                smartphone: 30,
                windows: 47,
                mac: 17
            }, {
                period: '2005',
                smartphone: 150,
                windows: 40,
                mac: 120
            }, {
                period: '2006',
                smartphone: 25,
                windows: 80,
                mac: 40
            }, {
                period: '2007',
                smartphone: 10,
                windows: 10,
                mac: 10
            }


        ],
        lineColors: ['#6c757d', 'rgb(0, 171, 197)', 'rgb(250,92,124)'],
        xkey: 'period',
        ykeys: ['smartphone', 'windows', 'mac'],
        labels: ['Phone', 'Windows', 'Mac'],
        pointSize: 0,
        lineWidth: 0,
        resize: true,
        fillOpacity: 0.8,
        behaveLikeLine: true,
        gridLineColor: 'transparent',
        hideHover: 'auto'

    });


//area chart
    Morris.Area({
        element: 'morris_area_2',
        data: [{
                period: '2010',
                SiteA: 0,
                SiteB: 0,

            }, {
                period: '2011',
                SiteA: 130,
                SiteB: 100,

            }, {
                period: '2012',
                SiteA: 80,
                SiteB: 60,

            }, {
                period: '2013',
                SiteA: 70,
                SiteB: 200,

            }, {
                period: '2014',
                SiteA: 180,
                SiteB: 150,

            }, {
                period: '2015',
                SiteA: 105,
                SiteB: 90,

            },
            {
                period: '2016',
                SiteA: 250,
                SiteB: 150,

            }
        ],
        xkey: 'period',
        ykeys: ['SiteA', 'SiteB'],
        labels: ['Site A', 'Site B'],
        pointSize: 0,
        fillOpacity: 0.6,
        pointStrokeColors: ['#b4becb', '#00A2FF'], //here
        behaveLikeLine: true,
        gridLineColor: 'transparent',
        lineWidth: 0,
        smooth: false,
        hideHover: 'auto',
        lineColors: ['rgb(0, 171, 197)', 'rgb(250,92,124)'],
        resize: true

    });
    



//bar chart stalked

    Morris.Bar.prototype.fillForSeries = function(i) {
        var color;
        return "0-#f00-#f00:20-#f00";
    };

    Morris.Bar({
        element: 'morris_bar_2',
        data: [
          { y: '2006', a: 100, b: 90, c: 80 },
          { y: '2007', a: 75,  b: 65, c: 75 },
          { y: '2007', a: 75,  b: 65, c: 75 },
          { y: '2007', a: 75,  b: 65, c: 75 },
          { y: '2008', a: 50,  b: 40, c: 45 },
          { y: '2009', a: 75,  b: 65, c: 85 },
          { y: '2009', a: 79,  b: 35, c: 45 },
          { y: '2009', a: 60,  b: 20, c: 60 },
          { y: '2009', a: 66,  b: 30, c: 50 },
          { y: '2009', a: 46,  b: 60, c: 90 },
          { y: '2009', a: 35,  b: 80, c: 60 },
        ],
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
        labels: ['Series A', 'Series B', 'Series C'],
        barColors: ['rgb(250,92,124)', 'rgb(0, 171, 197)', '#6c757d'], 
        stacked: true,
        gridTextSize: 11,
        hideHover: 'auto',
        resize: true
    });
    


//


/******************
Chartist
******************/

//basic line

    
var chart = new Chartist.Line('#smil-animations', {
    labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
    series: [
      [12, 9, 7, 8, 5, 4, 6, 2, 3, 3, 4, 6],
      [4,  5, 3, 7, 3, 5, 5, 3, 4, 4, 5, 5],
      [5,  3, 4, 5, 6, 3, 3, 4, 5, 6, 3, 4],
      [3,  4, 5, 6, 7, 6, 4, 5, 6, 7, 6, 3]
    ]
  }, {
    low: 0,
    plugins: [
      Chartist.plugins.tooltip()
    ]
  });
  
  // Let's put a sequence number aside so we can use it in the event callbacks
  var seq = 0,
    delays = 80,
    durations = 500;
  
  // Once the chart is fully created we reset the sequence
  chart.on('created', function() {
    seq = 0;
  });
  
  // On each drawn element by Chartist we use the Chartist.Svg API to trigger SMIL animations
  chart.on('draw', function(data) {
    seq++;
  
    if(data.type === 'line') {
      // If the drawn element is a line we do a simple opacity fade in. This could also be achieved using CSS3 animations.
      data.element.animate({
        opacity: {
          // The delay when we like to start the animation
          begin: seq * delays + 1000,
          // Duration of the animation
          dur: durations,
          // The value where the animation should start
          from: 0,
          // The value where it should end
          to: 1
        }
      });
    } else if(data.type === 'label' && data.axis === 'x') {
      data.element.animate({
        y: {
          begin: seq * delays,
          dur: durations,
          from: data.y + 100,
          to: data.y,
          // We can specify an easing function from Chartist.Svg.Easing
          easing: 'easeOutQuart'
        }
      });
    } else if(data.type === 'label' && data.axis === 'y') {
      data.element.animate({
        x: {
          begin: seq * delays,
          dur: durations,
          from: data.x - 100,
          to: data.x,
          easing: 'easeOutQuart'
        }
      });
    } else if(data.type === 'point') {
      data.element.animate({
        x1: {
          begin: seq * delays,
          dur: durations,
          from: data.x - 10,
          to: data.x,
          easing: 'easeOutQuart'
        },
        x2: {
          begin: seq * delays,
          dur: durations,
          from: data.x - 10,
          to: data.x,
          easing: 'easeOutQuart'
        },
        opacity: {
          begin: seq * delays,
          dur: durations,
          from: 0,
          to: 1,
          easing: 'easeOutQuart'
        }
      });
    } else if(data.type === 'grid') {
      // Using data.axis we get x or y which we can use to construct our animation definition objects
      var pos1Animation = {
        begin: seq * delays,
        dur: durations,
        from: data[data.axis.units.pos + '1'] - 30,
        to: data[data.axis.units.pos + '1'],
        easing: 'easeOutQuart'
      };
  
      var pos2Animation = {
        begin: seq * delays,
        dur: durations,
        from: data[data.axis.units.pos + '2'] - 100,
        to: data[data.axis.units.pos + '2'],
        easing: 'easeOutQuart'
      };
  
      var animations = {};
      animations[data.axis.units.pos + '1'] = pos1Animation;
      animations[data.axis.units.pos + '2'] = pos2Animation;
      animations['opacity'] = {
        begin: seq * delays,
        dur: durations,
        from: 0,
        to: 1,
        easing: 'easeOutQuart'
      };
  
      data.element.animate(animations);
    }
  });
  
  // For the sake of the example we update the chart every time it's created with a delay of 10 seconds
  chart.on('created', function() {
    if(window.__exampleAnimateTimeout) {
      clearTimeout(window.__exampleAnimateTimeout);
      window.__exampleAnimateTimeout = null;
    }
    window.__exampleAnimateTimeout = setTimeout(chart.update.bind(chart), 12000);
  });
  
  
  
  //Simple line chart
  new Chartist.Line('#simple-line-chart', {
    labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
    series: [
      [12, 9, 7, 8, 5],
      [2, 1, 3.5, 7, 3],
      [1, 3, 4, 5, 6]
    ]
  }, {
    fullWidth: true,
    chartPadding: {
      right: 40
    },
    plugins: [
      Chartist.plugins.tooltip()
    ]
  });
  
  
  
  
  //Line Scatter Diagram
  var times = function(n) {
    return Array.apply(null, new Array(n));
  };
  
  var data = times(52).map(Math.random).reduce(function(data, rnd, index) {
    data.labels.push(index + 1);
    data.series.forEach(function(series) {
      series.push(Math.random() * 100)
    });
  
    return data;
  }, {
    labels: [],
    series: times(4).map(function() { return new Array() })
  });
  
  var options = {
    showLine: false,
    axisX: {
      labelInterpolationFnc: function(value, index) {
        return index % 13 === 0 ? 'W' + value : null;
      }
    }
  };
  
  var responsiveOptions = [
    ['screen and (min-width: 640px)', {
      axisX: {
        labelInterpolationFnc: function(value, index) {
          return index % 4 === 0 ? 'W' + value : null;
        }
      }
    }]
  ];
  
  new Chartist.Line('#scatter-diagram', data, options, responsiveOptions);
  
  
  
  
  
  //Line chart with tooltips
  
  new Chartist.Line('#line-chart-tooltips', {
    labels: ['1', '2', '3', '4', '5', '6'],
    series: [
      {
        name: 'Fibonacci sequence',
        data: [1, 2, 3, 5, 8, 13]
      },
      {
        name: 'Golden section',
        data: [1, 1.618, 2.618, 4.236, 6.854, 11.09]
      }
    ]
  },
      {
    plugins: [
      Chartist.plugins.tooltip()
    ]
  }
  );
  
  var $chart = $('#line-chart-tooltips');
  
  var $toolTip = $chart
    .append('<div class="tooltip"></div>')
    .find('.tooltip')
    .hide();
  
  $chart.on('mouseenter', '.ct-point', function() {
    var $point = $(this),
      value = $point.attr('ct:value'),
      seriesName = $point.parent().attr('ct:series-name');
    $toolTip.html(seriesName + '<br>' + value).show();
  });
  
  $chart.on('mouseleave', '.ct-point', function() {
    $toolTip.hide();
  });
  
  $chart.on('mousemove', function(event) {
    $toolTip.css({
      left: (event.offsetX || event.originalEvent.layerX) - $toolTip.width() / 2 - 10,
      top: (event.offsetY || event.originalEvent.layerY) - $toolTip.height() - 40
    });
  });
  
  
  
  
  //Line chart with area
  
  new Chartist.Line('#chart-with-area', {
    labels: [1, 2, 3, 4, 5, 6, 7, 8],
    series: [
      [5, 9, 7, 8, 5, 3, 5, 4]
    ]
  }, {
    low: 0,
    showArea: true,
    plugins: [
      Chartist.plugins.tooltip()
    ]
  });
  
  
  //Bi-polar Line chart with area only
  
  new Chartist.Line('#bi-polar-line', {
    labels: [1, 2, 3, 4, 5, 6, 7, 8],
    series: [
      [1, 2, 3, 1, -2, 0, 1, 0],
      [-2, -1, -2, -1, -2.5, -1, -2, -1],
      [0, 0, 0, 1, 2, 2.5, 2, 1],
      [2.5, 2, 1, 0.5, 1, 0.5, -1, -2.5]
    ]
  }, {
    high: 3,
    low: -3,
    showArea: true,
    showLine: false,
    showPoint: false,
    fullWidth: true,
    axisX: {
      showLabel: false,
      showGrid: false
    },
    plugins: [
      Chartist.plugins.tooltip()
    ]
  });
  
  
  
  
  
  //SVG Path animation
  
  var chart = new Chartist.Line('#svg-animation', {
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    series: [
      [1, 5, 2, 5, 4, 3],
      [2, 3, 4, 8, 1, 2],
      [5, 4, 3, 2, 1, 0.5]
    ]
  }, {
    low: 0,
    showArea: true,
    showPoint: false,
    fullWidth: true
  });
  
  chart.on('draw', function(data) {
    if(data.type === 'line' || data.type === 'area') {
      data.element.animate({
        d: {
          begin: 2000 * data.index,
          dur: 2000,
          from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
          to: data.path.clone().stringify(),
          easing: Chartist.Svg.Easing.easeOutQuint
        }
      });
    }
  });
  
  
  
  
  
  //Line Interpolation / Smoothing
  
  var chart = new Chartist.Line('#line-smoothing', {
    labels: [1, 2, 3, 4, 5],
    series: [
      [1, 5, 10, 0, 1],
      [10, 15, 0, 1, 2]
    ]
  }, {
    // Remove this configuration to see that chart rendered with cardinal spline interpolation
    // Sometimes, on large jumps in data values, it's better to use simple smoothing.
    lineSmooth: Chartist.Interpolation.simple({
      divisor: 2
    }),
    fullWidth: true,
    chartPadding: {
      right: 20
    },
    low: 0,
    plugins: [
      Chartist.plugins.tooltip()
    ]
  });
  
  
  
  
  
  //Bi-polar bar chart
  
  var data = {
    labels: ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8', 'W9', 'W10'],
    series: [
      [1, 2, 4, 8, 6, -2, -1, -4, -6, -2]
    ]
  };
  
  var options = {
    high: 10,
    low: -10,
    axisX: {
      labelInterpolationFnc: function(value, index) {
        return index % 2 === 0 ? value : null;
      }
    },
    plugins: [
      Chartist.plugins.tooltip()
    ]
  };
  
  new Chartist.Bar('#bi-polar-bar', data, options);
  
  
  
  
  //Overlapping bars on mobile
  
  var data = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    series: [
      [5, 4, 3, 7, 5, 10, 3, 4, 8, 10, 6, 8],
      [3, 2, 9, 5, 4, 6, 4, 6, 7, 8, 7, 4]
    ]
  };
  
  var options = {
    seriesBarDistance: 10
  };
  
  var responsiveOptions = [
    ['screen and (max-width: 640px)', {
      seriesBarDistance: 5,
      axisX: {
        labelInterpolationFnc: function (value) {
          return value[0];
        }
      }
    }]
  ];
  
  new Chartist.Bar('#overlapping-bars', data, options, responsiveOptions);
  
  
  
  
  
  //Multi-line labels
  
  new Chartist.Bar('#multi-line-chart', {
    labels: ['First quarter of the year', 'Second quarter of the year', 'Third quarter of the year', 'Fourth quarter of the year'],
    series: [
      [60000, 40000, 80000, 70000],
      [40000, 30000, 70000, 65000],
      [8000, 3000, 10000, 6000]
    ]
  }, {
    seriesBarDistance: 10,
    axisX: {
      offset: 60
    },
    axisY: {
      offset: 80,
      labelInterpolationFnc: function(value) {
        return value + ' CHF'
      },
      scaleMinSpace: 15
    },
    plugins: [
      Chartist.plugins.tooltip()
    ]
  });
  
  
  
  
  //Stacked bar chart
  
  new Chartist.Bar('#stacked-bar-chart', {
    labels: ['Q1', 'Q2', 'Q3', 'Q4'],
    series: [
      [800000, 1200000, 1400000, 1300000],
      [200000, 400000, 500000, 300000],
      [160000, 290000, 410000, 600000]
    ]
  }, {
    stackBars: true,
    axisY: {
      labelInterpolationFnc: function(value) {
        return (value / 1000) + 'k';
      }
    },
    plugins: [
      Chartist.plugins.tooltip()
    ]
  }).on('draw', function(data) {
    if(data.type === 'bar') {
      data.element.attr({
        style: 'stroke-width: 30px'
      });
    }
  });
  
  
  
  
  
  
  //Horizontal bar chart
  
  new Chartist.Bar('#horizontal-bar-chart', {
    labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
    series: [
      [5, 4, 3, 7, 5, 10, 3],
      [3, 2, 9, 5, 4, 6, 4]
    ]
  }, {
    seriesBarDistance: 10,
    reverseData: true,
    horizontalBars: true,
    axisY: {
      offset: 70
    },
    plugins: [
      Chartist.plugins.tooltip()
    ]
  });
  
  
  
  
  
  // Extreme responsive configuration
  
  new Chartist.Bar('#extreme-chart', {
    labels: ['Quarter 1', 'Quarter 2', 'Quarter 3', 'Quarter 4'],
    series: [
      [5, 4, 3, 7],
      [3, 2, 9, 5],
      [1, 5, 8, 4],
      [2, 3, 4, 6],
      [4, 1, 2, 1]
    ]
  }, {
    // Default mobile configuration
    stackBars: true,
    axisX: {
      labelInterpolationFnc: function(value) {
        return value.split(/\s+/).map(function(word) {
          return word[0];
        }).join('');
      }
    },
    axisY: {
      offset: 20
    },
    plugins: [
      Chartist.plugins.tooltip()
    ]
  }, [
    // Options override for media > 400px
    ['screen and (min-width: 400px)', {
      reverseData: true,
      horizontalBars: true,
      axisX: {
        labelInterpolationFnc: Chartist.noop
      },
      axisY: {
        offset: 60
      }
    }],
    // Options override for media > 800px
    ['screen and (min-width: 800px)', {
      stackBars: false,
      seriesBarDistance: 10
    }],
    // Options override for media > 1000px
    ['screen and (min-width: 1000px)', {
      reverseData: false,
      horizontalBars: false,
      seriesBarDistance: 15
    }]
  ]);
  
  
  
  
  //Distributed series
  
//   new Chartist.Bar('#distributed-series', {
//     labels: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
//     series: [20, 60, 120, 200, 180, 20, 10]
//   }, {
//     distributeSeries: true,
//     plugins: [
//       Chartist.plugins.tooltip()
//     ]
//   });
  
  
  
  //Label placement
  
  new Chartist.Bar('#label-placement-chart', {
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    series: [
      [5, 4, 3, 7, 5, 10, 3],
      [3, 2, 9, 5, 4, 6, 4]
    ]
  }, {
    axisX: {
      // On the x-axis start means top and end means bottom
      position: 'start'
    },
    axisY: {
      // On the y-axis start means left and end means right
      position: 'end'
    },
    plugins: [
      Chartist.plugins.tooltip()
    ]
  });
  
  
  
  
  //Animating a Donut with Svg.animate
  
  var chart = new Chartist.Pie('#animating-donut', {
    series: [10, 20, 50, 20, 5, 50, 15],
    labels: [1, 2, 3, 4, 5, 6, 7]
  }, {
    donut: true,
    showLabel: false,
    plugins: [
      Chartist.plugins.tooltip()
    ]
  });
  
  chart.on('draw', function(data) {
    if(data.type === 'slice') {
      // Get the total path length in order to use for dash array animation
      var pathLength = data.element._node.getTotalLength();
  
      // Set a dasharray that matches the path length as prerequisite to animate dashoffset
      data.element.attr({
        'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
      });
  
      // Create animation definition while also assigning an ID to the animation for later sync usage
      var animationDefinition = {
        'stroke-dashoffset': {
          id: 'anim' + data.index,
          dur: 1000,
          from: -pathLength + 'px',
          to:  '0px',
          easing: Chartist.Svg.Easing.easeOutQuint,
          // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
          fill: 'freeze'
        }
      };
  
      // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
      if(data.index !== 0) {
        animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
      }
  
      // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us
      data.element.attr({
        'stroke-dashoffset': -pathLength + 'px'
      });
  
      // We can't use guided mode as the animations need to rely on setting begin manually
      // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
      data.element.animate(animationDefinition, false);
    }
  });
  
  // For the sake of the example we update the chart every time it's created with a delay of 8 seconds
  chart.on('created', function() {
    if(window.__anim21278907124) {
      clearTimeout(window.__anim21278907124);
      window.__anim21278907124 = null;
    }
    window.__anim21278907124 = setTimeout(chart.update.bind(chart), 10000);
  });
  
  
  
  
  //Simple pie chart
  
  var data1 = {
    series: [5, 3, 4]
  };
  
  var sum = function(a, b) { return a + b };
  
  new Chartist.Pie('#simple-pie', data1, {
    labelInterpolationFnc: function(value) {
      return Math.round(value / data1.series.reduce(sum) * 100) + '%';
    }
  });
  
  
  
  
  //Pie chart with custom labels
  
  var data = {
    labels: ['Bananas', 'Apples', 'Grapes'],
    series: [20, 15, 40]
  };
  
  var options = {
    labelInterpolationFnc: function(value) {
      return value[0]
    }
  };
  
  var responsiveOptions = [
    ['screen and (min-width: 640px)', {
      chartPadding: 30,
      labelOffset: 100,
      labelDirection: 'explode',
      labelInterpolationFnc: function(value) {
        return value;
      }
    }],
    ['screen and (min-width: 1024px)', {
      labelOffset: 80,
      chartPadding: 20
    }]
  ];
  
  new Chartist.Pie('#pie-chart', data, options, responsiveOptions);
  
  
  
  //Gauge chart
  
  new Chartist.Pie('#gauge-chart', {
    series: [20, 10, 30, 40]
  }, {
    donut: true,
    donutWidth: 60,
    startAngle: 270,
    total: 200,
    showLabel: false,
    plugins: [
      Chartist.plugins.tooltip()
    ]
  });
  
  
  
  
  
  // Different configuration for different series
  
  var chart = new Chartist.Line('#different-series', {
    labels: ['1', '2', '3', '4', '5', '6', '7', '8'],
    // Naming the series with the series object array notation
    series: [{
      name: 'series-1',
      data: [5, 2, -4, 2, 0, -2, 5, -3]
    }, {
      name: 'series-2',
      data: [4, 3, 5, 3, 1, 3, 6, 4]
    }, {
      name: 'series-3',
      data: [2, 4, 3, 1, 4, 5, 3, 2]
    }]
  }, {
    fullWidth: true,
    // Within the series options you can use the series names
    // to specify configuration that will only be used for the
    // specific series.
    series: {
      'series-1': {
        lineSmooth: Chartist.Interpolation.step()
      },
      'series-2': {
        lineSmooth: Chartist.Interpolation.simple(),
        showArea: true
      },
      'series-3': {
        showPoint: false
      }
    },
    plugins: [
      Chartist.plugins.tooltip()
    ]
  }, [
    // You can even use responsive configuration overrides to
    // customize your series configuration even further!
    ['screen and (max-width: 320px)', {
      series: {
        'series-1': {
          lineSmooth: Chartist.Interpolation.none()
        },
        'series-2': {
          lineSmooth: Chartist.Interpolation.none(),
          showArea: false
        },
        'series-3': {
          lineSmooth: Chartist.Interpolation.none(),
          showPoint: true
        }
      }
    }]
  ]);
  
  
  
  
  //SVG Animations chart
  
  var chart = new Chartist.Line('#svg-dot-animation', {
    labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
    series: [
      [12, 4, 2, 8, 5, 4, 6, 2, 3, 3, 4, 6],
      [4, 8, 9, 3, 7, 2, 10, 5, 8, 1, 7, 10]
    ]
  }, {
    low: 0,
    showLine: false,
    axisX: {
      showLabel: false,
      offset: 0
    },
    axisY: {
      showLabel: false,
      offset: 0
    },
    plugins: [
      Chartist.plugins.tooltip()
    ]
  });
  
  // Let's put a sequence number aside so we can use it in the event callbacks
  var seq = 0;
  
  // Once the chart is fully created we reset the sequence
  chart.on('created', function() {
    seq = 0;
  });
  
  // On each drawn element by Chartist we use the Chartist.Svg API to trigger SMIL animations
  chart.on('draw', function(data) {
    if(data.type === 'point') {
      // If the drawn element is a line we do a simple opacity fade in. This could also be achieved using CSS3 animations.
      data.element.animate({
        opacity: {
          // The delay when we like to start the animation
          begin: seq++ * 80,
          // Duration of the animation
          dur: 500,
          // The value where the animation should start
          from: 0,
          // The value where it should end
          to: 1
        },
        x1: {
          begin: seq++ * 80,
          dur: 500,
          from: data.x - 100,
          to: data.x,
          // You can specify an easing function name or use easing functions from Chartist.Svg.Easing directly
          easing: Chartist.Svg.Easing.easeOutQuart
        }
      });
    }
  });
  
  // For the sake of the example we update the chart every time it's created with a delay of 8 seconds
  chart.on('created', function() {
    if(window.__anim0987432598723) {
      clearTimeout(window.__anim0987432598723);
      window.__anim0987432598723 = null;
    }
    window.__anim0987432598723 = setTimeout(chart.update.bind(chart), 8000);
  });
  
  



/****************
Flot chart
*****************/

	$.plot("#flotBar1", [{
		data: [[0, 3], [2, 8], [4, 5], [6, 13], [8, 5], [10, 7], [12, 4], [14, 6]]
	}], {
		series: {
			bars: {
				show: true,
				lineWidth: 0,
				fillColor: '#727cf5'
			}
		},
		grid: {
			borderWidth: 1,
			borderColor: 'transparent'
		},
		yaxis: {
			tickColor: 'transparent',
			font: {
				color: '#fff',
				size: 10
			}
		},
		xaxis: {
			tickColor: 'transparent',
			font: {
				color: '#fff',
				size: 10
			}
		}
	});

	$.plot("#flotBar2", [{
		data: [[0, 3], [2, 8], [4, 5], [6, 13], [8, 5], [10, 7], [12, 8], [14, 10]],
		bars: {
			show: true,
			lineWidth: 0,
			fillColor: '#f25521'
		}
	}, {
		data: [[1, 5], [3, 7], [5, 10], [7, 7], [9, 9], [11, 5], [13, 4], [15, 6]],
		bars: {
			show: true,
			lineWidth: 0,
			fillColor: '#f9c70a'
		}
	}], {
			grid: {
				borderWidth: 1,
				borderColor: 'transparent'
			},
			yaxis: {
				tickColor: 'transparent',
				font: {
					color: '#fff',
					size: 10
				}
			},
			xaxis: {
				tickColor: 'transparent',
				font: {
					color: '#fff',
					size: 10
				}
			}
		});

	var newCust = [[0, 2], [1, 3], [2, 6], [3, 5], [4, 7], [5, 8], [6, 10]];
	var retCust = [[0, 1], [1, 2], [2, 5], [3, 3], [4, 5], [5, 6], [6, 9]];

	var plot = $.plot($('#flotLine1'), [
		{
			data: newCust,
			label: 'New Customer',
			color: '#f25521'
		},
		{
			data: retCust,
			label: 'Returning Customer',
			color: '#f9c70a'
		}],
		{
			series: {
				lines: {
					show: true,
					lineWidth: 1
				},
				shadowSize: 0
			},
			points: {
				show: false,
			},
			legend: {
				noColumns: 1,
				position: 'nw'
			},
			grid: {
				hoverable: true,
				clickable: true,
				borderColor: '#ddd',
				borderWidth: 0,
				labelMargin: 5,
				backgroundColor: 'transparent'
			},
			yaxis: {
				min: 0,
				max: 15,
				color: 'transparent',
				font: {
					size: 10,
					color: '#999'
				}
			},
			xaxis: {
				color: 'transparent',
				font: {
					size: 10,
					color: '#999'
				}
			}
		});

	var plot = $.plot($('#flotLine2'), [
		{
			data: newCust,
			label: 'New Customer',
			color: '#727cf5'
		},
		{
			data: retCust,
			label: 'Returning Customer',
			color: '#fd712c'
		}],
		{
			series: {
				lines: {
					show: false
				},
				splines: {
					show: true,
					tension: 0.4,
					lineWidth: 1,
					//fill: 0.4
				},
				shadowSize: 0
			},
			points: {
				show: false,
			},
			legend: {
				noColumns: 1,
				position: 'nw'
			},
			grid: {
				hoverable: true,
				clickable: true,
				borderColor: '#ddd',
				borderWidth: 0,
				labelMargin: 5,
				backgroundColor: 'transparent'
			},
			yaxis: {
				min: 0,
				max: 15,
				color: 'transparent',
				font: {
					size: 10,
					color: '#fff'
				}
			},
			xaxis: {
				color: 'transparent',
				font: {
					size: 10,
					color: '#fff'
				}
			}
		});

	var newCust2 = [[0, 10], [1, 7], [2, 8], [3, 9], [4, 6], [5, 5], [6, 7]];
	var retCust2 = [[0, 8], [1, 5], [2, 6], [3, 8], [4, 4], [5, 3], [6, 6]];

	var plot = $.plot($('#flotLine3'), [
		{
			data: newCust2,
			label: 'New Customer',
			color: '#727cf5'
		},
		{
			data: retCust2,
			label: 'Returning Customer',
			color: '#fd712c'
		}],
		{
			series: {
				lines: {
					show: true,
					lineWidth: 1
				},
				shadowSize: 0
			},
			points: {
				show: true,
			},
			legend: {
				noColumns: 1,
				position: 'nw'
			},
			grid: {
				hoverable: true,
				clickable: true,
				borderColor: '#ddd',
				borderWidth: 0,
				labelMargin: 5,
				backgroundColor: 'transparent'
			},
			yaxis: {
				min: 0,
				max: 15,
				color: 'transparent',
				font: {
					size: 10,
					color: '#fff'
				}
			},
			xaxis: {
				color: 'transparent',
				font: {
					size: 10,
					color: '#fff'
				}
			}
		});


	var plot = $.plot($('#flotArea1'), [
		{
			data: newCust,
			label: 'New Customer',
			color: '#4400eb'
		},
		{
			data: retCust,
			label: 'Returning Customer',
			color: '#727cf5'
		}],
		{
			series: {
				lines: {
					show: true,
					lineWidth: 0,
					fill: 0.8
				},
				shadowSize: 0
			},
			points: {
				show: false,
			},
			legend: {
				noColumns: 1,
				position: 'nw'
			},
			grid: {
				hoverable: true,
				clickable: true,
				borderColor: '#ddd',
				borderWidth: 0,
				labelMargin: 5,
				backgroundColor: 'transparent'
			},
			yaxis: {
				min: 0,
				max: 15,
				color: 'transparent',
				font: {
					size: 10,
					color: '#fff'
				}
			},
			xaxis: {
				color: 'transparent',
				font: {
					size: 10,
					color: '#fff'
				}
			}
		});

	var plot = $.plot($('#flotArea2'), [
		{
			data: newCust,
			label: 'New Customer',
			color: '#36b9d8'
		},
		{
			data: retCust,
			label: 'Returning Customer',
			color: '#727cf5'
		}],
		{
			series: {
				lines: {
					show: false
				},
				splines: {
					show: true,
					tension: 0.4,
					lineWidth: 0,
					fill: 0.8
				},
				shadowSize: 0
			},
			points: {
				show: false,
			},
			legend: {
				noColumns: 1,
				position: 'nw'
			},
			grid: {
				hoverable: true,
				clickable: true,
				borderColor: '#ddd',
				borderWidth: 0,
				labelMargin: 5,
				backgroundColor: 'transparent'
			},
			yaxis: {
				min: 0,
				max: 15,
				color: 'transparent',
				font: {
					size: 10,
					color: '#fff'
				}
			},
			xaxis: {
				color: 'transparent',
				font: {
					size: 10,
					color: '#fff'
				}
			}
		});

	var previousPoint = null;

	$('#flotLine3, #flotLine4').bind('plothover', function (event, pos, item) {
		$('#x').text(pos.x.toFixed(2));
		$('#y').text(pos.y.toFixed(2));

		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;

				$('#tooltip').remove();
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);

				showTooltip(item.pageX, item.pageY, item.series.label + ' of ' + x + ' = ' + y);
			}
		} else {

			$('#tooltip').remove();
			previousPoint = null;
		}
	});

	$('#flotLine3, #flotLine4').bind('plotclick', function (event, pos, item) {
		if (item) {
			plot.highlight(item.series, item.datapoint);
		}
	});

	function showTooltip(x, y, contents) {
		$('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css({
			position: 'absolute',
			display: 'none',
			top: y + 5,
			left: x + 5
		}).appendTo('body').fadeIn(200);
	}


	/*********** REAL TIME UPDATES **************/

	var data = [], totalPoints = 50;

	function getRandomData() {
		if (data.length > 0)
			data = data.slice(1);
		while (data.length < totalPoints) {
			var prev = data.length > 0 ? data[data.length - 1] : 50,
				y = prev + Math.random() * 10 - 5;
			if (y < 0) {
				y = 0;
			} else if (y > 100) {
				y = 100;
			}
			data.push(y);
		}
		var res = [];
		for (var i = 0; i < data.length; ++i) {
			res.push([i, data[i]])
		}
		return res;
	}


	// Set up the control widget
	var updateInterval = 1000;

	var plot4 = $.plot('#flotRealtime1', [getRandomData()], {
		colors: ['#727cf5'],
		series: {
			lines: {
				show: true,
				lineWidth: 1
			},
			shadowSize: 0	// Drawing is faster without shadows
		},
		grid: {
			borderColor: 'transparent',
			borderWidth: 1,
			labelMargin: 5
		},
		xaxis: {
			color: 'transparent',
			font: {
				size: 10,
				color: '#fff'
			}
		},
		yaxis: {
			min: 0,
			max: 100,
			color: 'transparent',
			font: {
				size: 10,
				color: '#fff'
			}
		}
	});

	var plot5 = $.plot('#flotRealtime2', [getRandomData()], {
		colors: ['#727cf5'],
		series: {
			lines: {
				show: true,
				lineWidth: 0,
				fill: 0.9
			},
			shadowSize: 0	// Drawing is faster without shadows
		},
		grid: {
			borderColor: 'transparent',
			borderWidth: 1,
			labelMargin: 5
		},
		xaxis: {
			color: 'transparent',
			font: {
				size: 10,
				color: '#fff'
			}
		},
		yaxis: {
			min: 0,
			max: 100,
			color: 'transparent',
			font: {
				size: 10,
				color: '#fff'
			}
		}
	});

	function update_plot4() {
		plot4.setData([getRandomData()]);
		plot4.draw();
		setTimeout(update_plot4, updateInterval);
	}

	function update_plot5() {
		plot5.setData([getRandomData()]);
		plot5.draw();
		setTimeout(update_plot5, updateInterval);
	}

	update_plot4();
	update_plot5();






/****************
Piety chart
*****************/

    $(".bar-line").peity("bar", {
        width: "100",
        height: "100"
    });

    
    $("span.pie").peity("pie", {
        fill: ['rgb(250,92,124)', 'rgba(250,92,124, .3)'], 
        width: "100",
        height: "100"
    });    
    
    
    $("span.donut").peity("donut", {
        width: "100",
        height: "100"
    });
    
    
    
    $(".peity-line").peity("line", {
        fill: ["rgba(250,92,124, .5)"], 
        stroke: 'rgb(250,92,124)', 
        width: "100%",
        height: "100"
    });
    
    
    
    $(".bar").peity("bar", {
        fill: ["rgb(250,92,124)", "rgb(117, 180, 50)", "rgb(7, 135, 234)"],  
        width: "100%",
        height: "100"
    });
    
    
    $(".bar-colours-1").peity("bar", {
        fill: ["#fa5c7c", "rgb(250,92,124)", "#6c757d"],
        width: "100",
        height: "100"
    });
    
    
    
    $(".bar-colours-2").peity("bar", {
        fill: function(t, e, i) {
            return "rgb(0, " + parseInt(e / i.length * 255) + ", 197)"
        },
        width: "100",
        height: "100"
    });
    
    
    
    $(".bar-colours-3").peity("bar", {
        fill: function(t, e, i) {
            return "rgb(54, " + parseInt(e / i.length * 255) + ", 216)"
        },
        width: "100",
        height: "100"
    });
    
    
    
    $(".pie-colours-1").peity("pie", {
        fill: ["cyan", "magenta", "yellow", "black"],
        width: "100",
        height: "100"
    });
    
    
    
    $(".pie-colours-2").peity("pie", {
        fill: ["#36b9d8", "#28D6C3", "#4400eb", "#727cf5", "#727cf5"],
        width: "100",
        height: "100"
    });
    
    
    
    $(".data-attr").peity("donut");



    var t = $(".updating-chart").peity("line", {
        fill: ['rgba(0, 171, 197, .5)'],
        stroke: 'rgb(0, 171, 197)', 
        width: "100%",
        height: 100
    });
    setInterval(function() {
        var e = Math.round(10 * Math.random()),
            i = t.text().split(",");
        i.shift(), i.push(e), t.text(i.join(",")).change()
    }, 1e3)

    



/****************
Sparkline chart
*****************/

     // Line Chart
     $("#sparklinedash").sparkline([10, 15, 26, 27, 28, 31, 34, 40, 41, 44, 49, 64, 68, 69, 72], {
        type: "bar",
        height: "50",
        barWidth: "4",
        resize: !0,
        barSpacing: "5",
        barColor: "rgb(0, 171, 197)"
    });


    $("#sparkline8").sparkline([79, 72, 29, 6, 52, 32, 73, 40, 14, 75, 77, 39, 9, 15, 10], {
        type: "line",
        width: "100%",
        height: "50",
        lineColor: "rgb(0, 171, 197)",
        fillColor: "rgba(0, 171, 197, .5)",
        minSpotColor: "rgb(0, 171, 197)",
        maxSpotColor: "rgb(0, 171, 197)",
        highlightLineColor: "rgb(0, 171, 197)",
        highlightSpotColor: "rgb(0, 171, 197)"
    });

    $("#sparkline9").sparkline([27, 31, 35, 28, 45, 52, 24, 4, 50, 11, 54, 49, 72, 59, 75], {
        type: "line",
        width: "100%",
        height: "50",
        lineColor: "rgb(192, 10, 39)",
        fillColor: "rgba(192, 10, 39, .5)",
        minSpotColor: "#fd712c",
        maxSpotColor: "#fd712c",
        highlightLineColor: "rgb(192, 10, 39)",
        highlightSpotColor: "#fd712c"
    });


    // Bar Chart


    $("#spark-bar").sparkline([33, 22, 68, 54, 8, 30, 74, 7, 36, 5, 41, 19, 43, 29, 38], {
        type: "bar",
        height: "200",
        barWidth: 6,
        barSpacing: 7,
        barColor: "rgb(7, 135, 234)"
    });

    $('#StackedBarChart').sparkline([
        [1, 4, 2],
        [2, 3, 2],
        [3, 2, 2],
        [4, 1, 2]
    ], {
            type: "bar",
            height: "200",
            barWidth: 10,
            barSpacing: 7, 
            stackedBarColor: ['#36b9d8', '#727cf5', 'rgba(68, 0, 235, .8)']
        });

    $("#tristate").sparkline([1, 1, 0, 1, -1, -1, 1, -1, 0, 0, 1, 1], {
        type: 'tristate',
        height: "200",
        barWidth: 10,
        barSpacing: 7, 
        colorMap: ['#36b9d8', '#727cf5', 'rgba(68, 0, 235, .8)'], 
        negBarColor: 'rgba(245, 60, 121, .8)'
    });

    // Composite

    $("#composite-bar").sparkline([73, 53, 50, 67, 3, 56, 19, 59, 37, 32, 40, 26, 71, 19, 4, 53, 55, 31, 37, 67, 10, 21], {
        type: "bar",
        height: "200",
        barWidth: "10",
        resize: !0,
        // barSpacing: "7",
        barColor: "rgb(0, 171, 197)", 
        width: '100%'
    });

    $("#sparkline-composite-chart").sparkline([5, 6, 7, 2, 0, 3, 6, 8, 1, 2, 2, 0, 3, 6], {
        type: 'line',
        width: '100%',
        height: '200', 
        barColor: 'rgb(0, 171, 197)', 
        colorMap: ['rgb(0, 171, 197)', '#f0ae00']
    });

    $("#sparkline-composite-chart").sparkline([5, 6, 7, 2, 0, 3, 6, 8, 1, 2, 2, 0, 3, 6], {
        type: 'bar',
        height: '150px',
        width: '100%',
        barWidth: 10,
        barSpacing: 5,
        barColor: '#34C73B',
        negBarColor: '#34C73B',
        composite: true
    });


    //Pie
    $("#sparkline11").sparkline([24, 61, 51], {
        type: "pie",
        height: "200",
        resize: !0,
        sliceColors: ["rgba(192, 10, 39, .5)", "rgba(250,92,124, .5)", "rgba(0, 171, 197, .5)"]
    });

    // Bullet
    $("#bullet-chart").sparkline([10, 12, 12, 9, 7], {
        type: 'bullet',
        height: '100',
        width: '100%',
    });

    //Boxplot
    $("#boxplot").sparkline([4,27,34,52,54,59,61,68,78,82,85,87,91,93,100], {
        type: 'box'
    });


    



/****************
Easy pie chart
*****************/

    const EPChart = Array.prototype.slice.call($('.easy-pie-chart'));

    EPChart.forEach(chart => {
        $(chart).easyPieChart({
            barColor: function(parcent) {
                return parcent > 75 ? 'rgb(0, 171, 197)' : parcent > 50 ? 'rgb(117, 180, 50)' : parcent > 25 ? 'rgb(7, 135, 234)' : 'rgb(192, 10, 39)';
            }, 
            lineWidth: 2
        });
    })

    // $('.pie-chart-1').easyPieChart({
    //     barColor:'rgb(192, 10, 39)', 
    //     lineWidth: 2
    // });

    // $('.pie-chart-2').easyPieChart({
    //     barColor:'#6c757d', 
    //     lineWidth: 2
    // });

    // $('.pie-chart-3').easyPieChart({
    //     barColor:'rgb(0, 171, 197)', 
    //     lineWidth: 2
    // });

    // $('.pie-chart-4').easyPieChart({
    //     barColor:'rgb(192, 10, 39)', 
    //     lineWidth: 2
    // });

    // $('.pie-chart-5').easyPieChart({
    //     barColor:'#6c757d', 
    //     lineWidth: 2
    // });

    // $('.pie-chart-6').easyPieChart({
    //     barColor:'rgb(0, 171, 197)', 
    //     lineWidth: 2
    // });


})(jQuery);