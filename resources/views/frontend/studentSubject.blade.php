@extends('layouts.layout')

@section('content')

<div class="container custom-container">
    <div class="header-row">
        <div class="left">
            <h3>Subject</h3>
        </div>
        <div class="right" >
            <button type="button" id="game-btn" class="btn btn-outline-dark" onclick="location.href='http://test.game.seriousgameanalytics.com/index.html?id=<?= $url_parameter ?>'">Go to game</button>
        </div>
    </div>

        <!--  //row start -->
        <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
            <form href="/frontend/studentSubject" id="filter-form">
                <div class="search-section">
                    <div class="flex-box flex-column">
                        <label>Subject</label>
                        <select name="subjectFilter" id="subjectFilter">
                            <option value="" disabled="disabled" selected="true">Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-box flex-column" style="margin-left: 18px;">
                        <label>Topic</label>
                        <select name="class" id="classFilter">
                            <option value="" disabled="disabled" selected="true">Select a topic</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="primary" id="search">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- //row end -->

        <!--  //Subject Proficiency Title -->
        <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
            <div class="col-4" style="float: left;padding-top:41px">
                <div class="left">
                    <h5>Subject Proficiency</h5>
                </div>
                <div class="right"></div>
            </div>
        </div>

        <!--  //Subject Proficiency Meter (Start) -->
        <div class="row" style="display: flex; justify-content: center; align-items: center; height: 300px;">
            <div id="chart_div"></div>
        </div>
        <!--  //Subject Proficiency Meter (End) -->


        <!--  //Leaderboard Title -->
        <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
            <div class="col-4" style="float: left;padding-top:41px">
                <h5>Permission</h5>
            </div>
        </div>

        <!--  //Leaderboard Table (Start) -->
        <div class="table-container">
            <table class="table leftTable">
                <thead style="background-color: #CFDDE4;color:#45494C">
                    <tr>
                        <th>Ranking</th>
                        <th>Student</th>
                        <th>Score</th>
                        <th>Number of Topics Cleared</th>
                        <th>Time Taken</th>
                    </tr>
                </thead>
                <tbody style="background-color: #Neutral/50;">
                    <!-- <tr style="color:#737B7F">
                        <td>1</td>
                        <td>XimonC</td>
                        <td>949</td>
                        <td>3</td>
                        <td>00.43.13</td>
                    </tr> -->
                    @if($position > 4)
                        @for($i = 0; $i < 5 ; $i++)
                            @php
                                $rank = $i + 1;
                                $user = $leaderboard[$i] ?? null;
                            @endphp
                            <tr style="color:#737B7F">
                                <td>{{ $rank }}</td>
                                <td>{{ $user ? $user['username'] : 'NA' }}</td>
                                <td>{{ $user ? $user['total_score'] : 'NA' }}</td>
                                <td>{{ $user ? $user['topicsCleared'] : 'NA' }}</td>
                                <td>{{ $user ? $user['totalDuration'] : 'NA' }}</td>
                            </tr>
                        @endfor
                        <tr>
                            <td><b>...</b></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @php
                            $ownPosition = $position + 1;
                            $user = $leaderboard[$position] ?? null;
                        @endphp
                        <tr style="color:#737B7F">
                            <td>{{ $ownPosition }}</td>
                            <td>{{ $user ? $user['username'] : 'NA' }}</td>
                            <td>{{ $user ? $user['total_score'] : 'NA' }}</td>
                            <td>{{ $user ? $user['topicsCleared'] : 'NA' }}</td>
                            <td>{{ $user ? $user['totalDuration'] : 'NA' }}</td>
                        </tr>
                    @else
                        @for($i = 0; $i < 5; $i++)
                            @php
                                $rank = $i + 1;
                                $user = $leaderboard[$i] ?? null;
                            @endphp
                            <tr style="color:#737B7F">
                                <td>{{ $rank }}</td>
                                <td>{{ $user ? $user['username'] : 'NA' }}</td>
                                <td>{{ $user ? $user['total_score'] : 'NA' }}</td>
                                <td>{{ $user ? $user['topicsCleared'] : 'NA' }}</td>
                                <td>{{ $user ? $user['totalDuration'] : 'NA' }}</td>
                            </tr>
                        @endfor
                    @endif
                </tbody>
            </table>
        </div>
        <!--  //Leaderboard Table (End) -->

        <!--  //Performance Title -->
        <div class="row" style="padding-top: 35px; padding-bottom: 35px;">
            <div class="col-4" style="float: left;padding-top:41px">
                <h5>Your Performance for {{ $topicName }}</h5>
            </div>
        </div>

        <!--  //Performance Table (Start) -->
        <div class="table-container">
            <table class="table leftTable">
                <thead style="background-color: #CFDDE4;color:#45494C">
                    <tr>
                        <th>Sub-topic</th>
                        <th>Score</th>
                        <th>Time Taken (Hr:Min:Sec)</th>
                    </tr>
                </thead>
                <tbody style="background-color: #Neutral/50;">
                    @foreach ($student_statistic as $statistic)
                        <tr style="color: #737B7F">
                            <td>{{ $statistic['subtopic_name'] }}</td>
                            <td>{{ $statistic['subtopic_score'] }}</td>
                            <td>{{ date('H:i:s', strtotime($statistic['time_taken'])) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- <div id="table_div" style="height: 60%; width: 100%;"></div> -->
        </div>
        <!--  //Performance Table (End) -->
    </div>
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', {'packages': ['gauge']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Label', 'Value'],
            ['Score', <?php echo $meterData; ?>],
        ]);

        var options = {
            width: '100%',
            height: 300,
            redFrom: 0,
            redTo: 50,
            yellowFrom: 50,
            yellowTo: 80,
            greenFrom: 80,
            greenTo: 100,
            minorTicks: 5,
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

    $('#subjectFilter').on('change', function () {
        var subjectId = $(this).val();

        // Send an AJAX request to retrieve matching classes
        $.ajax({
            url: '/admin/getTopic/' + subjectId,
            type: 'GET',
            success: function (data) {
                // Clear the existing options in the Class dropdown
                $('#classFilter').empty();

                // Populate the Class dropdown with the retrieved data
                $.each(data.data, function (key, value) {
                    $('#classFilter').append('<option value="' + value.topic_id + '">' + value.topic_name + '</option>');
                });                
            }
        });
    });
    
</script>
@endsection