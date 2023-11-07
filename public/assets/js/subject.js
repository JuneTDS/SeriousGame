class Subject {

    constructor(data) {
        this.form = $(".white-form.subject-form");
        this.graphSection = $(".graph-section");
        this.initialMessage = $(".initial-message");
        this.graphTtile = $(".graph-title");

        this.indepthForm = $(".indepth-form");
        this.statsticForm = $(".statstic-form");
        this.indepthTitle = $(".indepth-title");
        this.topicTable = $(".topics_table tbody");
        this.subtopicTable = $(".subtopics_table tbody");
        this.statsticTable = $(".statstic_table tbody");

        this.avg_bar = $("#avg_bar");
        this.score_bar = $("#score_bar");
        this.class_bar = $("#class_bar");

        this.data = data;
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

        if (data.classes.length > 0) {
            $("#class option.can-remove").remove();
            data.classes.forEach(value => {
                let class_name = value.class_name;
                let subject_class_id = value.subject_class_id;
                
                $("#class").append(`
                    <option class="can-remove" value="${subject_class_id}">${class_name}</option>
                `);
                $(".class-column").show();
                $(".indepth-btn").show();
                $(".student-btn").show();
            });
        } else {
            $(".class-column").hide();
        }
    }

    renderClassData(action) {
        let renderdData = this.getData(this.data);
        console.log("render data", renderdData);

        // (data.calculation).forEach((value, key) => {
        //     if (maxValue < value.score) {
        //         maxValue = value.score;
        //     }
        //     seriesData = seriesData + value.score;
        // });

        let thisObj = this;
        let subTopicDatas = {};
        let topicDatas = {};

        if (renderdData.subtopics.length > 0) {
            (renderdData.subtopics).forEach(subtopic => {
                let subTopicData = {};
                // let topicData = {};
                // console.log(subTopicDatas.hasOwnProperty("merchant_id"));
                subTopicData["subtopic_id"] = subtopic.subtopic_id;
                subTopicData["subtopic_name"] = subtopic.subtopic_name;
                subTopicData["topic_id"] = subtopic.topic_id_fk;
                subTopicData["topic_name"] = subtopic.topic_name;

                // topicData["topic_id"] = subtopic.topic_id_fk;

                if (subTopicDatas.hasOwnProperty(subtopic.subtopic_id)) {
                    subTopicData["duration"] = thisObj.sumTime(subtopic.duration, subTopicDatas[subtopic.subtopic_id].duration);
                    subTopicData["score"] = parseInt(subtopic.score) + parseInt(subTopicDatas[subtopic.subtopic_id].score);
                } else {
                    subTopicData["duration"] = subtopic.duration;
                    subTopicData["score"] = parseInt(subtopic.score);
                }

                // if (topicDatas.hasOwnProperty(subtopic.topic_id_fk)) {
                //     topicData["duration"] = thisObj.sumTime(subtopic.duration, topicDatas[subtopic.topic_id_fk].duration);
                //     topicData["score"] = parseInt(subtopic.score) + parseInt(topicDatas[subtopic.topic_id_fk].score);
                //     topicData["row"] = topicDatas[subtopic.topic_id_fk].row + 1;
                // } else {
                //     topicData["duration"] = subtopic.duration;
                //     topicData["score"] = parseInt(subtopic.score);
                //     topicData["row"] = 1;
                // }

                subTopicDatas[subtopic.subtopic_id] = subTopicData;
                // topicDatas[subtopic.topic_id_fk] = topicData;
            });

            for (const key in subTopicDatas) {
                let topicData = {};

                topicData["topic_id"] = subTopicDatas[key].topic_id;
                topicData["topic_name"] = subTopicDatas[key].topic_name;

                if (topicDatas.hasOwnProperty(subTopicDatas[key].topic_id)) {
                    topicData["duration"] = thisObj.sumTime(subTopicDatas[key].duration, topicDatas[subTopicDatas[key].topic_id].duration);
                    topicData["score"] = parseInt(subTopicDatas[key].score) + parseInt(topicDatas[subTopicDatas[key].topic_id].score);
                    topicData["row"] = topicDatas[subTopicDatas[key].topic_id].row + 1;
                } else {
                    topicData["duration"] = subTopicDatas[key].duration;
                    topicData["score"] = parseInt(subTopicDatas[key].score);
                    topicData["row"] = 1;
                }
                
                // console.log(`${key}: ${subTopicDatas[key]}`);
                topicDatas[subTopicDatas[key].topic_id] = topicData;
            }
        }
        console.log(subTopicDatas);
        console.log(topicDatas);

        // this.subtopicTable.append(`<tr>
        //     <td>${subtopic.subtopic_name}</td>
        //     <td></td>
        //     <td></td>
        // </tr>`)
        
        let loop = 1;
        if (Object.keys(topicDatas).length > 0) {
            this.topicTable.find("tr").remove();
            if (action == "initial") {
                $("#topic").find("option.can-remove").remove();
            }
            for (const key in topicDatas) {
                const topic = topicDatas[key];
                this.topicTable.append(`<tr class="${(loop % 2 == 0) ? "even": "odd"}">
                    <td>${topic.topic_name}</td>
                    <td>${Math.ceil(topic.score / topic.row)}</td>
                    <td>${this.getAverageTime(topic.duration, topic.row)}</td>
                </tr>`);
                loop++;

                if (action == "initial") {
                    $("#topic").append(`<option class="can-remove" value="${topic.topic_id}">${topic.topic_name}</option>`)
                }
            }
        }

        loop = 1;
        if (Object.keys(subTopicDatas).length > 0) {
            this.subtopicTable.find("tr").remove();
            for (const key in subTopicDatas) {
                const subtopic = subTopicDatas[key];
                this.subtopicTable.append(`<tr class="${(loop % 2 == 0) ? "even": "odd"}">
                    <td>${subtopic.subtopic_name}</td>
                    <td>${subtopic.score}</td>
                    <td>${subtopic.duration}</td>
                </tr>`);
                loop++;
            }
        }

        loop = 1;
        if (Object.keys(renderdData.statstic).length > 0) {
            this.statsticTable.find("tr").remove();
            for (const key in renderdData.statstic) {
                let statstic = renderdData.statstic[key];
                this.statsticTable.append(`<tr class="${(loop % 2 == 0) ? "even": "odd"}">
                    <td>${statstic.username}</td>
                    <td>${(statstic.num_of_attempts[0].duration != null) ? statstic.num_of_attempts[0].duration : "N/A"}</td>
                    <td>${statstic.num_of_attempts[0].num_of_attempts}</td>
                </tr>`);
                loop++;
            }
        }

        this.indepthForm.css("display", "block");
        this.statsticForm.css("display", "block");
    }

    getData(data) {
        let topic = $("#topic").val();
        let student = $("#student").val();

        let filterByTopic = this.filterByTopic(data.subtopics, topic);
        
        let filteredByStudent = this.filterByStudent(filterByTopic, student);

        let statsticFilteredByStudent = this.filterByStudent(data.statstic, student);

        return {
            statstic: statsticFilteredByStudent,
            subtopics: filteredByStudent,
            subtopics_users: data.subtopics_users,
            topics: data.topics
        };
    }

    filterByTopic(data, topic) {
        
        let filteredStatstics = [];
        if (topic != null) {
            Object.keys(data).forEach(key => {
                let statstic = data[key];
                if (statstic.topic_id_fk == topic) {
                    filteredStatstics[key] = statstic;
                }
            });
        } else {
            filteredStatstics = data;
        }

        return filteredStatstics;
    }

    filterByStudent(data, student) {
        console.log("filterStudent", data);

        let filteredStatstics = [];
        if (student != '') {
            Object.keys(data).forEach(key => {
                let statstic = data[key];
                if (statstic.username == student) {
                    filteredStatstics[key] = statstic;
                }
            });
        } else {
            filteredStatstics = data;
        }

        return filteredStatstics;
    }

    sumTime(time1, time2) {

        var hour=0;
        var minute=0;
        var second=0;
        
        var splitTime1= time1.split(':');
        var splitTime2= time2.split(':');
        
        hour = parseInt(splitTime1[0])+parseInt(splitTime2[0]);
        minute = parseInt(splitTime1[1])+parseInt(splitTime2[1]);
        second = parseInt(splitTime1[2])+parseInt(splitTime2[2]);

        if (minute >= 60) {
            hour = hour + Math.ceil(minute/60);
            minute = minute % 60;
        }
        
        if (second >= 60) {
            minute = minute + Math.ceil(second/60);
            second = second%60;
        }

        if (hour   < 10) {hour   = "0"+hour;}
        if (minute < 10) {minute = "0"+minute;}
        if (second < 10) {second = "0"+second;}
        
        return hour+':'+minute+':'+second;
    }

    getAverageTime(time, row) {
        let timeArg = time.split(':');

        let hour = parseInt(timeArg[0]) * 3600;
        let minute = parseInt(timeArg[1]) * 60;
        let second = parseInt(timeArg[2]);

        let aveageSecond = Math.ceil((hour + minute + second) / row);

        let sec_num = parseInt(aveageSecond, 10); // don't forget the second param
        let hours   = Math.floor(sec_num / 3600);
        let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        let seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}

        return hours+':'+minutes+':'+seconds;
    }

    export() {
        const date = new Date();

        let day = (date.getDate() < 10) ? "0"+date.getDate() : date.getDate();
        let month = ((date.getMonth() + 1) < 10) ? "0"+(date.getMonth() + 1) : date.getMonth() + 1;
        let year = date.getFullYear();

        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${day}-${month}-${year}`;

        
        let topicTable = $(".topics_table");
        let subtopicTable = $(".subtopics_table");
        let statsticTable = $(".statstic_table");
        
        TableToExcel.convert(topicTable[0], {
            name: `Topic - ${currentDate}.xlsx`,
            sheet: {
                name: 'Topic'
            }
        });

        TableToExcel.convert(subtopicTable[0], {
            name: `Subtopic - ${currentDate}.xlsx`,
            sheet: {
                name: 'Subtopic'
            }
        });

        TableToExcel.convert(statsticTable[0], {
            name: `Student detail - ${currentDate}.xlsx`,
            sheet: {
                name: 'Student detail'
            }
        });
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

    let indepthSubject = new Subject();
    $("#class").on("change", function(e) {
        e.preventDefault();
        var formData = {
            class: $("#class").val(),
            subject: $("#subject").val(),
        };
        var type = "POST";
        var ajaxurl = '/user/subject/indepth';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                indepthSubject = new Subject(data.data);
                indepthSubject.renderClassData("initial");
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $("#topic").on("change", function(e) {
        filter();
    });

    $("#student").on("keyup", function(e) {
        setTimeout(() => {
            filter();
        }, 100);
    });

    function filter() {
        indepthSubject.renderClassData("filter");
    }

    $(".indepth-btn #indepth").on("click", function(e) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(".indepth-form").offset().top
        }, 1000);
    });

    $(".student-btn #student").on("click", function(e) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(".statstic-form").offset().top
        }, 1000);
    });

    $(".export").on("click", function(e) {
        e.preventDefault();
        indepthSubject.export();
    });
});