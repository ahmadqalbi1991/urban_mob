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

(function($) {
    "use strict"

    //step
    const li = $('.multi-steps').find('li');
    li.on('click', function() {
        // console.log()
        $(this).parent().children().removeClass('is-active');
        $(this).addClass('is-active');
    });

    //easy pie chart
    const EPChart = Array.prototype.slice.call($('.easy-pie-chart'));
    EPChart.forEach(chart => {
        $(chart).easyPieChart({
            barColor: function(parcent) {
                return parcent == 100 ? '#0ACF97' : parcent > 75 ? '#727cf5' : parcent > 50 ? '#ffbc00' : parcent > 33 ? '#fa5c7c' : '#1b1d1f';
            }, 
            lineWidth: 5
        });
    });


})(jQuery);


















// (function ($) {
//     "use strict";

//     const ctx = document.getElementById("statistics").getContext('2d');
//     const gradientStroke = ctx.createLinearGradient(50, 100, 50, 50);
//     gradientStroke.addColorStop(0, "#36b9d8");
//     gradientStroke.addColorStop(1, "#4bffa2");

//     const gradientStroke2 = ctx.createLinearGradient(50, 100, 50, 50);
//     gradientStroke2.addColorStop(0, "#4400eb");
//     gradientStroke2.addColorStop(1, "#44ecf5");

//     const gradientStroke3 = ctx.createLinearGradient(50, 100, 50, 50);
//     gradientStroke3.addColorStop(0, "#f53c79");
//     gradientStroke3.addColorStop(1, "#f0ae00");

//     var barChartData = {
//         labels: ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun'],
//         datasets: [{
//             label: 'Red',
//             backgroundColor: gradientStroke,
//             hoverBackgroundColor: gradientStroke, 
//             data: [
//                 '12',
//                 '12',
//                 '12',
//                 '12',
//                 '12',
//                 '12',
//                 '12'
//             ]
//         }, {
//             label: 'Green',
//             backgroundColor: gradientStroke2,
//             hoverBackgroundColor: gradientStroke2, 
//             data: [
//                 '12',
//                 '12',
//                 '12',
//                 '12',
//                 '12',
//                 '12',
//                 '12'
//             ]
//         }, {
//             label: 'Blue',
//             backgroundColor: gradientStroke3,
//             hoverBackgroundColor: gradientStroke3, 
//             data: [
//                 '12',
//                 '12',
//                 '12',
//                 '12',
//                 '12',
//                 '12',
//                 '12'
//             ]
//         }]

//     };
    
//     new Chart(ctx, {
//         type: 'bar',
//         data: barChartData,
//         options: {
//             legend: {
//                 display: false
//             }, 
//             title: {
//                 display: false
//             },
//             tooltips: {
//                 mode: 'index',
//                 intersect: false
//             },
//             responsive: true,
//             scales: {
//                 xAxes: [{
//                     stacked: true,
//                     gridLines: {
//                         display: false
//                     }
//                 }],
//                 yAxes: [{
//                     stacked: true
//                 }]
//             }
//         }
//     });

// })(jQuery);



// (function ($) {
//     "use strict";

//     const ctx = document.getElementById("monthly_view_chart").getContext('2d');
//     const gradientStroke1 = ctx.createLinearGradient(50, 100, 50, 50);
//     gradientStroke1.addColorStop(0, "#f25521");
//     gradientStroke1.addColorStop(1, "#f9c70a");

//     const gradientStroke2 = ctx.createLinearGradient(50, 100, 50, 50);
//     gradientStroke2.addColorStop(0, "#4400eb");
//     gradientStroke2.addColorStop(1, "#44ecf5");

//     const gradientStroke3 = ctx.createLinearGradient(50, 100, 50, 50);
//     gradientStroke3.addColorStop(0, "#f53c79");
//     gradientStroke3.addColorStop(1, "#f0ae00");

//     const gradientStroke4 = ctx.createLinearGradient(50, 100, 50, 50);
//     gradientStroke4.addColorStop(0, "#36b9d8");
//     gradientStroke4.addColorStop(1, "#4bffa2");
    
//     // ctx.height = 100;

//     new Chart(ctx, {
//         type: 'bar',
//         data: {
//             labels: ["First", "Second", "Third"],
//             datasets: [
//                 {
//                     label: "My First dataset",
//                     data: [25, 39, 40],
//                     borderColor: gradientStroke1,
//                     borderWidth: "0",
//                     backgroundColor: gradientStroke1, 
//                     hoverBackgroundColor: gradientStroke1
//                 },
//                 {
//                     label: "My Second dataset",
//                     data: [38, 48, 60],
//                     borderColor: gradientStroke2,
//                     borderWidth: "0",
//                     backgroundColor: gradientStroke2, 
//                     hoverBackgroundColor: gradientStroke2
//                 },
//                 {
//                     label: "My Third dataset",
//                     data: [58, 58, 70],
//                     borderColor: gradientStroke3,
//                     borderWidth: "0",
//                     backgroundColor: gradientStroke3, 
//                     hoverBackgroundColor: gradientStroke3
//                 }, 
//                 {
//                     label: "My Fourth dataset",
//                     data: [88, 68, 90],
//                     borderColor: gradientStroke4,
//                     borderWidth: "0",
//                     backgroundColor: gradientStroke4, 
//                     hoverBackgroundColor: gradientStroke4
//                 }
//             ]
//         },
//         options: {
//             legend: {
//                 display: false
//             }, 
//             maintainAspectRatio: false, 
//             responsive: true, 
//             scales: {
//                 yAxes: [{
//                     gridLines: {
//                         display: false
//                     }, 
//                     ticks: {
//                         beginAtZero: true, 
//                         display: false, 
//                         max: 100, 
//                         min: 0
//                     }, 
//                     display: false
//                 }],
//                 xAxes: [{
//                     gridLines: {
//                         display: false
//                     },
//                     ticks: {
//                         display: false
//                     }, 
//                     barPercentage: .6, 
//                     display: false, 
//                     categoryPercentage: 1.0
//                 }]
//             }
//         }
//     });

// })(jQuery);


// (function($){
//     'use strict'

//     $('.chart-1').easyPieChart({
//         easing: 'easeOutBounce',
//         barColor : '#F0466C',
//         lineWidth: 5,
//         animate: 1000,
//         lineCap: 'square',
//         trackColor: '#e5e5e5',
//         onStep: function(from, to, percent) {
//             $(this.el).find('.percent').text(Math.round(percent));
//         }
//     });

//     $('.chart-2').easyPieChart({
//         easing: 'easeOutBounce',
//         barColor : '#47CFED',
//         lineWidth: 5,
//         animate: 1000,
//         lineCap: 'square',
//         trackColor: '#e5e5e5',
//         onStep: function(from, to, percent) {
//             $(this.el).find('.percent').text(Math.round(percent));
//         }
//     });

// })(jQuery);


// (function($) {
//     "use strict"

//     new Datamap( {
//         scope: "world", 
//         element: document.getElementById("sales_monitor_map"), 
//         responsive: !0, 
//         geographyConfig: {
//             popupOnHover: !1, 
//             highlightOnHover: !1, 
//             borderColor: "transparent", 
//             borderWidth: 1, 
//             highlightBorderWidth: 3, 
//             highlightFillColor: "rgba(0,123,255,0.5)", 
//             highlightBorderColor: "transparent", 
//             borderWidth: 1
//         }, 
//         bubblesConfig: {
//             popupTemplate: function (e, i) {
//                 return '<div class="datamap-sales-hover-tooltip">' + i.country + '<span class="m-l-5"></span>' + i.sold + "</div>"
//             }, 
//             borderWidth: 1, 
//             highlightBorderWidth: 3, 
//             highlightFillColor: "rgba(0,123,255,0.5)", 
//             highlightBorderColor: "transparent", 
//             fillOpacity: .75
//         }, 
//         fills: {
//             Visited: "#00A2FF", 
//             neato: "#673AB7", 
//             white: "#FF9800", 
//             defaultFill: "#E7E8E9"
//         }
//     });

// })(jQuery);