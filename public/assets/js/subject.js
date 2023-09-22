class Subject {

    constructor() {
        this.form = $(".white-form");
        this.graphSection = $(".graph-section");
        this.initialMessage = $(".initial-message");
        this.graphTtile = $(".graph-title");
    }

    renderGraph(data) {

        if (data.bar.calculation.length > 0) {

            this.initialMessage.hide();
            this.graphTtile.show();
            this.graphSection.css('display', 'flex');
            this.form.css('display', 'block');

            let categories = [];
            let seriesSpace = [];
            let seriesData = [];
            let maxValue = 0;

            (data.bar.calculation).forEach((value, key) => {
                if (maxValue < value.score) {
                    maxValue = value.score;
                }
            });
            
            (data.bar.calculation).forEach((value, key) => {
                categories[key] = value.subtopic_name;
                seriesSpace[key] = 0;
                seriesData[key] = value.score;
            });

            Highcharts.chart('bar', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Average score of each topic for all classes',
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
                        name: 'Average Time',
                        data: seriesData,
                        color: "#16E7CF"
                    }
                ]
            });

            Highcharts.chart('line', {

                title: {
                    text: 'Average time taken to clear each topic',
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
                    data: seriesData
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
}

$(document).ready(function() {
    let subject = new Subject();

    let _token = $("input[name=_token]").val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': _token
        }
    });

    $("#search").on("click", function(e) {
        e.preventDefault();
        var formData = {
            subject: $("#subject").val(),
        };
        var type = "POST";
        var ajaxurl = '/user/getGraphData';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                subject.renderGraph(data.data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
});