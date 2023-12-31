class Indepth {

    constructor(data) {
        this.form = $(".indepth-form");
        this.initialMessage = $(".initial-message");
        this.title = $(".indepth-title");
        this.table = $(".indepth_table tbody");
        this.section = $(".indepth-data-section");
        this.graphSection = $(".graph-section");

        this.statsticTable = $(".statstic_table tbody");

        this.exportTable = $(".export_table tbody");

        this.data = data;
    }

    init() {

        if (this.data.is_exist) {

            let data = this.getData(this.data);

            // console.log("after filter", data);

            this.initialMessage.hide();

            this.title.find("h3").text(`Indepth Dashboard for ${$("#class option:selected").text()}`);
            this.title.show();

            let loop = 1;
            this.table.find("tr").remove();
            Object.keys(data.logs).forEach(key => {
                let log = data.logs[key];
                this.table.append(`<tr class="${(loop % 2 == 0) ? "even": "odd"}">
                    <td>${ log.username }</td>
                    <td>${ log.login_count }</td>
                </tr>`);
                loop++;
            });

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
                    text: '', // Distribution of Scores
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
                        name: 'Score',
                        data: seriesData,
                        color: "#16E7CF"
                    }
                ]
            });

            let completeCount = 0;
            let incompleteCount = 0;
            loop = 1;
            this.statsticTable.find("tr").remove();
            Object.keys(data.statstic).forEach(key => {
                let statstic = data.statstic[key];
                let log = data.logs[key];

                this.statsticTable.append(`<tr class="${(loop % 2 == 0) ? "even": "odd"}">
                    <td>${ statstic.username }</td>
                    <td>${ statstic.class_name }</td>
                    <td>${ (statstic.num_of_attempts.length > 0 && statstic.num_of_attempts[0].duration != null) ? statstic.num_of_attempts[0].duration : "N/A" }</td>
                    <td>${ statstic.clear_topics }</td>
                    <td>${ (statstic.total_topics.length > 0 && statstic.total_topics[0].total_topics != null) ? statstic.total_topics[0].total_topics : "N/A" }</td>
                    <td>${ (statstic.num_of_attempts.length > 0 && statstic.num_of_attempts[0].total_score != null) ? statstic.num_of_attempts[0].total_score : "N/A" }</td>
                    <td>${ (statstic.total_topics.length > 0 && statstic.clear_topics == statstic.total_topics[0].total_topics && statstic.total_topics[0].total_topics > 0) ? "Completed" : "Incomplete" }</td>
                </tr>`);

                
                this.exportTable.append(`<tr class="${(loop % 2 == 0) ? "even": "odd"}">
                    <td>${ statstic.user_id }</td>
                    <td>${ "no" }</td>
                    <td>${ statstic.username }</td>
                    <td>${ statstic.subject_class_id }</td>
                    <td>${ statstic.class_name }</td>
                    <td>${ (statstic.num_of_attempts.length > 0 && statstic.num_of_attempts[0].total_score != null) ? statstic.num_of_attempts[0].total_score : "0" }</td>
                    <td>${ (statstic.num_of_attempts.length > 0 && statstic.num_of_attempts[0].duration != null) ? statstic.num_of_attempts[0].duration : "0" }</td>
                    <td>${ statstic.clear_topics }</td>
                    <td>${ (statstic.total_topics.length > 0 && statstic.total_topics[0].total_topics != null) ? statstic.total_topics[0].total_topics : "0" }</td>
                    <td>${ (statstic.num_of_attempts.length > 0 && statstic.num_of_attempts[0].num_of_attempts != null) ? statstic.num_of_attempts[0].num_of_attempts : "0" }</td>
                    <td>${ (typeof log != "undefined") ? log.login_count : "0" }</td>
                    <td>${ (statstic.total_topics.length > 0 && statstic.clear_topics == statstic.total_topics[0].total_topics && statstic.total_topics[0].total_topics > 0) ? "Completed" : "Incomplete" }</td>
                    <td>${ (statstic.num_of_attempts.length > 0 && statstic.num_of_attempts[0].last_attempt_time != null) ? statstic.num_of_attempts[0].last_attempt_time : "0" }</td>
                </tr>`);

                if (statstic.total_topics.length > 0 && statstic.clear_topics == statstic.total_topics[0].total_topics && statstic.total_topics[0].total_topics > 0) {
                    completeCount++;
                } else {
                    incompleteCount++;
                }
                loop++;
            });

            Highcharts.chart('pie', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: '', // Percentage of students completed the subject
                    align: 'center'
                },
                // tooltip: {
                //     pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                // },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    name: '',
                    colorByPoint: true,
                    data: [
                        {
                            name: 'Incomplete',
                            y: incompleteCount,
                            sliced: false,
                            selected: false,
                            color: "#16E7CF"
                        },
                        {
                            name: 'Complete',
                            y: completeCount,
                            sliced: false,
                            selected: false,
                            // color: "#16E7CF"
                        }
                    ]
                }]
            });

            

            this.table.show();
            this.section.show();
            this.graphSection.css("display", "flex");
            this.form.css("display", "block");
        } else {
            this.initialMessage.show();
            this.title.hide();
            this.table.hide();
            this.section.hide();
            this.graphSection.hide();
            this.form.css("display", "flex");
        }
    }

    initSlider() {
        if (this.data.is_exist) {
            let data = this.data;
            let maxLogCount = 0;
            let maxTopicClear = 0;
            let maxScore = 0;

            Object.keys(data.logs).forEach(key => {
                let log = data.logs[key];
                
                if (log.login_count > maxLogCount) {
                    maxLogCount = log.login_count;
                }
            });

            Object.keys(data.statstic).forEach(key => {
                let statstic = data.statstic[key];
                
                if (statstic.clear_topics > maxTopicClear) {
                    maxTopicClear = statstic.clear_topics;
                }

                if (statstic.total_topics.length > 0 && parseInt(statstic.total_topics[0].total_score) > maxScore) {
                    maxScore = parseInt(statstic.total_topics[0].total_score);
                }
            });

            $("#log_range").attr("max", maxLogCount);
            $("#topic_range").attr("max", maxTopicClear);
            $("#score_range").attr("max", maxScore);
        }
    }

    getData(data) {
        let complete = $("#complete").val();
        let student = $("#student").val();
        let topicRange = $("#topic_range").val();
        let scoreRange = $("#score_range").val();
        let logRange = $("#log_range").val();

        let logs = {...data.logs};

        let filteredByComplete = this.filterComplete(data.statstic, complete);
        
        let filteredByStudent = this.filterStudent(filteredByComplete, student);

        let filteredByTopicRange = this.filterTopicRange(filteredByStudent, topicRange);

        let filteredByScoreRange = this.filterScoreRange(filteredByTopicRange, scoreRange);
        console.log("filteredByScoreRange", filteredByScoreRange);

        let filteredBylogsRange = this.filterlogsRange(data.logs, logRange);
        console.log("filteredBylogsRange", filteredBylogsRange);

        let logsFilteredByStudent = this.filterStudent(filteredBylogsRange, student);
        console.log("logsFilteredByStudent", logsFilteredByStudent);
        

        return {
            bar: data.bar,
            is_exist: data.is_exist,
            statstic: filteredByScoreRange,
            logs: logsFilteredByStudent
        };
    }

    filterComplete(data, complete) {
        
        let filteredStatstics = [];
        if (complete != null) {
            if (complete == "1") {
                console.log("1")
                Object.keys(data).forEach(key => {
                    let statstic = data[key];
                    if (statstic.clear_topics == statstic.total_topics[0].total_topics && statstic.total_topics[0].total_topics > 0) {
                        filteredStatstics[key] = statstic;
                    }
                });
            } else if (complete == "0") {
                console.log("0")
                Object.keys(data).forEach(key => {
                    let statstic = data[key];
                    if (statstic.clear_topics < statstic.total_topics[0].total_topics || statstic.total_topics[0].total_topics == 0) {
                        filteredStatstics[key] = statstic;
                    }
                });
            }
        } else {
            filteredStatstics = data;
        }

        return filteredStatstics;
    }

    filterStudent(data, student) {
        console.log("filterStudent", data);

        let filteredStatstics = [];
        if (student != '') {
            Object.keys(data).forEach(key => {
                let statstic = data[key];
                if (statstic.username.includes(student)) {
                    filteredStatstics[key] = statstic;
                }
            });
        } else {
            filteredStatstics = data;
        }

        return filteredStatstics;
    }

    filterTopicRange(data, topicRange) {
        console.log("filterTopicRange", data);

        let filteredStatstics = [];
        if (topicRange != '0') {
            Object.keys(data).forEach(key => {
                let statstic = data[key];
                if (statstic.clear_topics >= parseInt(topicRange)) {
                    filteredStatstics[key] = statstic;
                }
            });
        } else {
            filteredStatstics = data;
        }

        return filteredStatstics;
    }

    filterScoreRange(data, scoreRange) {
        console.log("filterTopicRange", data);

        let filteredStatstics = [];
        if (scoreRange != '0') {
            Object.keys(data).forEach(key => {
                let statstic = data[key];
                if (statstic.total_topics.length > 0 && parseInt(statstic.total_topics[0].total_score) >= parseInt(scoreRange)) {
                    filteredStatstics[key] = statstic;
                }
            });
        } else {
            filteredStatstics = data;
        }

        return filteredStatstics;
    }

    filterlogsRange(data, logRange) {
        let filteredlogs = [];
        if (logRange != '') {
            Object.keys(data).forEach(key => {
                let log = data[key];
                if (log.login_count >= logRange) {
                    filteredlogs[key] = log;
                }
            });
        } else {
            filteredlogs = data;
        }

        return filteredlogs;
    }

    export() {
        const date = new Date();

        let day = (date.getDate() < 10) ? "0"+date.getDate() : date.getDate();
        let month = ((date.getMonth() + 1) < 10) ? "0"+(date.getMonth() + 1) : date.getMonth() + 1;
        let year = date.getFullYear();

        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${day}-${month}-${year}`;
        let table = $(".export_table");
        TableToExcel.convert(table[0], {
            name: `Data for Logic and Mathematics (LOMA) - ${currentDate}.xlsx`,
            sheet: {
                name: 'Indepth'
            }
        });
    }
}

$(document).ready(function() {
    let indepth = new Indepth();

    let _token = $("input[name=_token]").val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': _token
        }
    });

    $("#search").on("click", function(e) {
        e.preventDefault();
        var formData = {
            class: $("#class").val(),
            subject: $("#subject").val(),
        };
        var type = "POST";
        var ajaxurl = '/user/indepth';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                indepth = new Indepth(data.data);
                indepth.init();
                indepth.initSlider();
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $("#complete").on("change", function(e) {
        filter();
    });

    $("#student").on("keyup", function(e) {
        setTimeout(() => {
            filter();
        }, 100);
    });

    const topicEL = document.querySelector("#topic_range")
    const topicValue = document.querySelector(".topic_value")

    topicEL.addEventListener("input", (event) => {
        const tempTopicValue = event.target.value; 
        topicValue.textContent = tempTopicValue;
        
        const topic = (tempTopicValue / topicEL.max) * 100;
        
        topicEL.style.background = `linear-gradient(to right, #f50 ${topic}%, #ccc ${topic}%)`;

        filter();
    });

    const scoreEL = document.querySelector("#score_range")
    const scoreValue = document.querySelector(".score_value")

    scoreEL.addEventListener("input", (event) => {
        const tempScoreValue = event.target.value; 
        scoreValue.textContent = tempScoreValue;
        
        const score = (tempScoreValue / scoreEL.max) * 100;
        
        scoreEL.style.background = `linear-gradient(to right, #f50 ${score}%, #ccc ${score}%)`;

        filter();
    });

    const logEL = document.querySelector("#log_range")
    const logValue = document.querySelector(".log_value")

    logEL.addEventListener("input", (event) => {
        const tempLogValue = event.target.value; 
        logValue.textContent = tempLogValue;
        
        const log = (tempLogValue / logEL.max) * 100;
        
        logEL.style.background = `linear-gradient(to right, #f50 ${log}%, #ccc ${log}%)`;

        filter();
    });

    function filter() {
        indepth.init();
    }

    $(".indepth_export").on("click", function(e) {
        e.preventDefault();

        indepth.export();
    });
});