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

/*******************
Datamap
*******************/
(function (e) {
    "use strict"

    const map = new Datamap({
        scope: "world", 
        element: document.getElementById("world-datamap"), 
        responsive: !0, 
        geographyConfig: {
            popupOnHover: !1, 
            highlightOnHover: !1, 
            borderColor: "transparent", 
            borderRadius: 0, 
            highlightBorderWidth: 3, 
            highlightFillColor: "rgba(0,123,255,0.5)", 
            highlightBorderColor: "rgba(255,255,255,0.1)", 
            borderWidth: 1
        }
        , bubblesConfig: {
            popupTemplate: function (e, i) {
                return '<div class="datamap-sales-hover-tooltip">' + i.country + '<span class="m-l-5"></span> ' + i.sold + "</div>"
            }, 
            borderWidth: 0, 
            highlightBorderWidth: 0, 
            highlightFillColor: "rgb(255, 255, 255)", 
            highlightBorderColor: "rgb(255, 255, 255)", 
            fillOpacity: .75
        }
        , fills: {
            Visited: "#f5f5f5", 
            neato: "rgba(0,123,255,1)", 
            white: "rgb(255, 255, 255)", 
            defaultFill: "#EBEFF2",
            primary: "#727cf5",
            secondary: "#6c757d",
            success: "#0acf97",
            info: "#39afd1",
            warning: "#ffbc00",
            danger: "#fa5c7c",
        }
    });
    
    map.bubbles([{
        centered: "USA", fillKey: "secondary", radius: 5, sold: "$500", country: "United States"
    }
    , {
        centered: "SAU", fillKey: "success", radius: 5, sold: "$900", country: "Saudia Arabia"
    }
    , {
        centered: "RUS", fillKey: "warning", radius: 5, sold: "$250", country: "Russia"
    }
    , {
        centered: "CAN", fillKey: "primary", radius: 5, sold: "$999", country: "Canada"
    }
    , {
        centered: "AUS", fillKey: "danger", radius: 5, sold: "$700", country: "Australia"
    }
    , {
        centered: "BGD", fillKey: "info", radius: 5, sold: "$1500", country: "Bangladesh"
    }]),

    window.addEventListener("resize", function (e) {
        map.resize();
    });

    


/*******************
Donut chart
*******************/
    //doughut chart
    const user_rating_graph = document.getElementById("user_rating_graph").getContext('2d');
    // user_rating_graph.height = 100;
    new Chart(user_rating_graph, {
        type: 'doughnut',
        data: {
            defaultFontFamily: 'Roboto',
            datasets: [{
                data: [220, 420, 260, 460],
                borderWidth: 0, 
                backgroundColor: [
                    `rgba(${PRIMARY}, 1)`,
                    `rgba(${INFO}, 1)`,
                    `rgba(${SUCCESS}, 1)`,
                    `rgba(${DARK}, 1)`
                ],
                hoverBackgroundColor: [
                    `rgba(${PRIMARY}, 1)`,
                    `rgba(${INFO}, 1)`,
                    `rgba(${SUCCESS}, 1)`,
                    `rgba(${DARK}, 1)`
                ]

            }],
            labels: [
                "Food",
                "Service",
                "Waiting Time",
                "Others"
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, 
            legend: {
                display: false
            }
        }
    });




/*******************
Line chart
*******************/

    const visitor_graph = document.getElementById("visitor_graph").getContext('2d');
    // visitor_graph.height = 100;

    new Chart(visitor_graph, {
        type: 'line',
        data: {
            defaultFontFamily: 'Roboto',
            labels: ["10:00", "11:00", "12:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00"],
            datasets: [
                {
                    label: "My First dataset",
                    data: [25, 20, 60, 41, 66, 45, 80, 76, 89, 65, 55],
                    borderColor: `rgba(${PRIMARY}, 1)`,
                    borderWidth: "2",
                    backgroundColor: 'transparent',  
                    pointBackgroundColor: `rgba(${PRIMARY}, 1)`
                },
                {
                    label: "My First dataset",
                    data: [30, 25, 50, 45, 75, 50, 90, 80, 95, 75, 55],
                    borderColor: `rgba(${SUCCESS}, 1)`,
                    borderWidth: "2",
                    backgroundColor: 'transparent',  
                    pointBackgroundColor: `rgba(${SUCCESS}, 1)`
                }
            ],
        },
        options: {
            legend: false, 
            maintainAspectRatio: false, 
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true, 
                        max: 100, 
                        min: 0, 
                        stepSize: 20, 
                        padding: 0, 
                        display: true
                    }, 
                    gridLines: {
                        display: false, 
                        drawBorder: false
                    }
                }],
                xAxes: [{
                    ticks: {
                        padding: 10
                    }, 
                    gridLines: {
                        display: true, 
                        drawBorder: false
                    }
                }]
            }
        }
    });



/*******************
Bar chart
*******************/

    const earnings_bar_chart = document.getElementById("earnings_bar_chart").getContext('2d');
    
    // earnings_bar_chart.height = 100;

    let barChartData = {
        defaultFontFamily: 'Poppins',
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Expense',
            backgroundColor: `rgba(${PRIMARY}, .75)`,
            hoverBackgroundColor: `rgba(${PRIMARY}, .75)`, 
            data: [
                '20',
                '14',
                '18',
                '25',
                '27',
                '22',
                '12', 
                '24', 
                '20', 
                '14', 
                '18', 
                '16'
            ]
        }, {
            label: 'Earning',
            backgroundColor: '#F1F3F7',
            hoverBackgroundColor: '#F1F3F7', 
            data: [
                '12',
                '18',
                '14',
                '7',
                '5',
                '10',
                '20', 
                '8', 
                '12', 
                '18', 
                '14', 
                '16'
            ]
        }]

    };

    new Chart(earnings_bar_chart, {
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
            maintainAspectRatio: false, 
            scales: {
                xAxes: [{
                    stacked: true,
                    barPercentage: .4, 
                    // ticks: {
                    //     max: 
                    // }, 
                    gridLines: {
                        display: false, 
                        drawBorder: false
                    }
                }],
                yAxes: [{
                    stacked: true, 
                    gridLines: {
                        display: false, 
                        drawBorder: false
                    }
                }]
            }
        }
    });




/*******************
Carousel
*******************/

    $('#top_menu_carousel').owlCarousel({
        margin: 25, 
        autoplay: false, 
        rewind: true, 
        responsive: {
            1500: {
                items: 4
            }, 
            1200: {
                items: 3
            }, 
            992: {
                items: 3
            }, 
            768: {
                items: 2
            }, 
            575: {
                items: 2
            }, 
            0: {
                items: 1
            }
        }
    });




/*******************
TO DO LIST 
*******************/



    //todo list
    $(".tdl-new").on('keypress', function(e) {

        var code = (e.keyCode ? e.keyCode : e.which);

        if (code == 13) {

            var v = $(this).val();

            var s = v.replace(/ +?/g, '');

            if (s == "") {

                return false;

            } else {

                $(".tdl-content ul").append("<li><label><input type='checkbox'><i></i><span>" + v + "</span><a href='#' class='ti-trash'></a></label></li>");

                $(this).val("");

            }

        }

    });





    $(".tdl-content a").on("click", function() {

        var _li = $(this).parent().parent("li");

        _li.addClass("remove").stop().delay(100).slideUp("fast", function() {

            _li.remove();

        });

        return false;

    });



    // for dynamically created a tags

    $(".tdl-content").on('click', "a", function() {

        var _li = $(this).parent().parent("li");

        _li.addClass("remove").stop().delay(100).slideUp("fast", function() {

            _li.remove();

        });

        return false;

    });






/*******************
Chart widgets
*******************/



    new Chartist.Line("#home_chart_widget_1", {
        labels: ["1", "2", "3", "4", "5", "6", "7", "8"],
        series: [
            [4, 5, 3.5, 5, 4, 5.5, 3.8, 4.6]
        ]
    }, {
        low: 0,
        showArea: 1,
        showPoint: !0,
        showLine: !0,
        fullWidth: !0,
        lineSmooth: !1,
        chartPadding: {
            top: 4,
            right: 0,
            bottom: 0,
            left: 0
        },
        axisX: {
            showLabel: !1,
            showGrid: !1,
            offset: 0
        },
        axisY: {
            showLabel: !1,
            showGrid: !1,
            offset: 0
        }
    });


    new Chartist.Line("#home_chart_widget_2", {
        labels: ["1", "2", "3", "4", "5", "6", "7", "8"],
        series: [
            [4, 5, 3.5, 5, 4, 5.5, 3.8, 4.6]
        ]
    }, {
        low: 0,
        showArea: 1,
        showPoint: !0,
        showLine: !0,
        fullWidth: !0,
        lineSmooth: !1,
        chartPadding: {
            top: 4,
            right: 0,
            bottom: 0,
            left: 0
        },
        axisX: {
            showLabel: !1,
            showGrid: !1,
            offset: 0
        },
        axisY: {
            showLabel: !1,
            showGrid: !1,
            offset: 0
        }
    });


    new Chartist.Line("#home_chart_widget_3", {
        labels: ["1", "2", "3", "4", "5", "6", "7", "8"],
        series: [
            [4, 5, 3.5, 5, 4, 5.5, 3.8, 4.6]
        ]
    }, {
        low: 0,
        showArea: 1,
        showPoint: !0,
        showLine: !0,
        fullWidth: !0,
        lineSmooth: !1,
        chartPadding: {
            top: 4,
            right: 0,
            bottom: 0,
            left: 0
        },
        axisX: {
            showLabel: !1,
            showGrid: !1,
            offset: 0
        },
        axisY: {
            showLabel: !1,
            showGrid: !1,
            offset: 0
        }
    });


    new Chartist.Line("#home_chart_widget_4", {
        labels: ["1", "2", "3", "4", "5", "6", "7", "8"],
        series: [
            [4, 5, 3.5, 5, 4, 5.5, 3.8, 4.6]
        ]
    }, {
        low: 0,
        showArea: 1,
        showPoint: !0,
        showLine: !0,
        fullWidth: !0,
        lineSmooth: !1,
        chartPadding: {
            top: 4,
            right: 0,
            bottom: 0,
            left: 0
        },
        axisX: {
            showLabel: !1,
            showGrid: !1,
            offset: 0
        },
        axisY: {
            showLabel: !1,
            showGrid: !1,
            offset: 0
        }
    });





/*******************
Misceleneous
*******************/


    $('#todo_list').slimscroll({
        position: "right",
        size: "2px",
        height: "413px",
        color: "transparent"
    });

    $('#ticket').slimscroll({
        position: "right",
        size: "2px",
        height: "495px",
        color: "transparent"
    });

    //step
    const li = $('.multi-steps').find('li');
    li.on('click', function() {
        li.removeClass('is-active');
        $(this).addClass('is-active');
    });

})(jQuery)