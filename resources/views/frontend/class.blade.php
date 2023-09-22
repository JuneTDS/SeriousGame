@extends('layouts.layout')

@section('content')

<div class="container page classes-page">
    <h3 class="page-header">Classes</h3>
    <form action="{{ route('search.class') }}" method="get">
        @csrf
        <div class="search-section">
            <div class="flex-box flex-column">
                <label>Subject</label>
                <select name="subject" id="">
                    @foreach($data["classes"] as $key => $class)
                        <option value="{{ $class->subject_id }}">{{ $class->subject_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="primary">Search</button>
            </div>
        
            <div>
                <button type="button">Activity Tracker</button>
            </div>
            <div>
                <button type="button">Go to Indepth</button>
            </div>
        </div>
    </form>

    @php
        $chartOne = "";
        if(isset($data["chart1"])) {
            $chartOne = $data["chart1"];
        }
    @endphp

    <div class="tab-wrap">

    <!-- active tab on page load gets checked attribute -->
        <input type="radio" id="tab1" name="tabGroup1" class="tab" checked>
        <label for="tab1">Summary</label>

        <input type="radio" id="tab2" name="tabGroup1" class="tab">
        <label for="tab2">Leadership</label>

        <input type="radio" id="tab3" name="tabGroup1" class="tab">
        <label for="tab3">Scatter</label>
        
        <div class="tab__content">
            <h3>Summary Dashboard for Classes</h3>
            <div class="flex-box flex-between">
                <div class="chart" id="chart_one"></div>
                <div class="chart" id="chart_two"></div>
            </div>
        </div>

        <div class="tab__content">
            <h3>Leadership for Classes</h3>
            <table class="leadership_table">
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>Student Name</th>
                        <th>Score</th>
                        <th>Number of Topics Cleared</th>
                        <th>Time Taken (Hr : Min : Sec)</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($data["leaderboard"]))
                        @foreach ($data["leaderboard"] as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value->username }}</td>
                                <td>{{ $value->score }}</td>
                                <td>{{ $value->topic_count }}</td>
                                <td>{{ $value->duration }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="tab__content">
            <h3>Scatter for Classes</h3>
            <p>Praesent nonummy mi in odio. Nullam accumsan lorem in dui. Vestibulum turpis sem, aliquet eget, lobortis pellentesque, rutrum eu, nisl. Nullam accumsan lorem in dui. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>

            <p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Morbi mattis ullamcorper velit. Pellentesque posuere. Etiam ut purus mattis mauris sodales aliquam. Praesent nec nisl a purus blandit viverra.</p>

            <p>Praesent nonummy mi in odio. Nullam accumsan lorem in dui. Vestibulum turpis sem, aliquet eget, lobortis pellentesque, rutrum eu, nisl. Nullam accumsan lorem in dui. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>

            <p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Morbi mattis ullamcorper velit. Pellentesque posuere. Etiam ut purus mattis mauris sodales aliquam. Praesent nec nisl a purus blandit viverra.</p>
        </div>
    </div>
</div>

<script type="text/javascript">
    let chartOne = <?php echo json_encode($chartOne); ?>;

    console.log(chartOne);

    let categories = [];
    let seriesSpace = [];
    let seriesData = [];
    let maxValue = 0;

    (chartOne.calculation).forEach((value, key) => {
        if (maxValue < value.score) {
            maxValue = value.score;
        }
    });
    
    (chartOne.calculation).forEach((value, key) => {
        categories[key] = value.subtopic_name;
        seriesSpace[key] = 0;
        seriesData[key] = value.score;
    });

    if (chartOne != "") {
        Highcharts.chart('chart_one', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
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
                }
            },
            series: [
                {
                    name: 'Score',
                    data: seriesData,
                    color: "#16E7CF"
                }
            ]
        });
    }
</script>

@endsection