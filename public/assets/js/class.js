class Class {

    constructor() {
        this.form = $(".graph-form");
        this.graphSection = $(".graph-section");
        this.initialMessage = $(".initial-message");
        this.graphTtile = $(".graph-title");

        
        this.leadershipTable = $(".leadership_table");
    }

    init(data){
        this.renderGraph(data.bar);
        this.renderLeaderboard(data.leaderboard);
    }

    renderGraph(data) {

        if (data.calculation.length > 0) {

            this.initialMessage.hide();
            this.graphTtile.show();
            this.graphSection.css('display', 'flex');
            this.form.css('display', 'block');

            let categories = ['Score'];
            let seriesData = 0;
            let maxValue = 0;
            let totalLength = data.calculation.length;

            (data.calculation).forEach((value, key) => {
                if (maxValue < value.score) {
                    maxValue = value.score;
                }
                seriesData = seriesData + value.score;
            });
            
            // (data.calculation).forEach((value, key) => {
            //     categories[key] = value.subtopic_name;
            //     seriesSpace[key] = 0;
            //     seriesData[key] = value.score;
            // });

            Highcharts.chart('bar', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Average score of class',
                    align: 'center'
                },
                xAxis: {
                    categories: categories
                },
                yAxis: {
                    min: 0,
                    max: maxValue,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    // pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                    shared: true
                },
                plotOptions: {
                    // column: {
                    //     stacking: 'percent'
                    // }
                    bar: {
                        // borderRadius: '50%',
                        dataLabels: {
                            enabled: false
                        },
                        groupPadding: 0.1
                    },
                    shadow: false,
                },
                series: [
                    {
                        name: 'Average Score',
                        data: [seriesData / totalLength],
                        color: "#16E7CF"
                    }
                ]
            });

            Highcharts.chart('line', {

                title: {
                    text: 'Average time taken of class',
                    align: 'center'
                },
                yAxis: {
                    title: {
                        text: ''
                    }
                },
                xAxis: {
                    categories: categories,
                    accessibility: {
                        description: ''
                    }
                },
                plotOptions: {
                    bar: {
                        // borderRadius: '50%',
                        dataLabels: {
                            enabled: true
                        },
                        groupPadding: 0.1
                    }
                },
                series: [{
                    name: 'Average Time',
                    data: [seriesData / totalLength]
                }],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                    }]
                }
            });

        } else {
            this.initialMessage.text("There is no data to show.");
            this.initialMessage.show();
            this.graphTtile.hide();
            this.graphSection.hide();
            this.form.css('display', 'flex');
        }
    }

    renderLeaderboard(data) {
        let loop = 1;
        (data).forEach((value, key) => {
            this.leadershipTable.append(`<tr class="${loop % 2 == 0 ? "even": "odd"}">
                <td>${key + 1}</td>
                <td>${ value.username }</td>
                <td>${ value.score }</td>
                <td>${ value.topic_count }</td>
                <td>${ value.duration }</td>
            </tr>`);
            loop++;
        });
    }
}

$(document).ready(function() {
    let classObj = new Class();

    let _token = $("input[name=_token]").val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': _token
        }
    });

    $("#subject").on("change", function() {
        let subject = $("#subject").val();

        $(".activity_link").attr('href', '/frontend/activity?subject='+subject);
        $(".indepth_link").attr('href', '/frontend/indepth?subject='+subject);
    })

    $("#search").on("click", function(e) {
        e.preventDefault();
        var formData = {
            subject: $("#subject").val(),
        };
        var type = "POST";
        var ajaxurl = '/user/getClassGraphData';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                classObj.init(data.data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $(".tab").on("change", function(e) {
        e.preventDefault();
        
        if ($("#tab1").is(":checked")) {
            $(".tab1__content").show();
            $(".tab2__content").hide();
            $(".tab3__content").hide();
        }
        if ($("#tab2").is(":checked")) {
            $(".tab1__content").hide();
            $(".tab2__content").show();
            $(".tab3__content").hide();
        }
        if ($("#tab3").is(":checked")) {
            $(".tab1__content").hide();
            $(".tab2__content").hide();
            $(".tab3__content").show();
        }
    });
});