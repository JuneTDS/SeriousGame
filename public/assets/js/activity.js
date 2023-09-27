class Activity {

    constructor() {
        this.form = $(".activity-form");
        this.initialMessage = $(".initial-message");
        this.title = $(".activity-title");
        this.table = $(".activity_table");
    }

    init(data){
        console.log(data);

        if (data.loginCount.length > 0) {
            $(".login_count").text(data.loginCount[0].count);
        }
        if (data.noLoginCount.length > 0) {
            $(".not_login_count").text(data.noLoginCount[0].count);
        }

        if (data.is_exist) {
            this.initialMessage.hide();
            this.title.show();
            
            this.form.css("display", "block");

            const keys = Object.keys(data.logs);

            let loop = 1;
            keys.forEach((key, index) => {
                let log = data.logs[key];
                console.log(log);
                this.table.append(`<tr class="${(loop % 2 == 0) ? "even": "odd"}">
                    <td>${ log.username }</td>
                    <td>${ log.login_count }</td>
                    <td>${ log.last_login }</td>
                    <td><label class="view-more">View More</label><img data-id="${key}" src="/assets/images/expand_more.svg" class="expand down" style="vertical-align: bottom;margin-left: 5px;" /></td>
                </tr>`);

                let childRow = "";
                Object.keys(log.user_logs).forEach((logKey, logIndex) => {
                    let subLog = log.user_logs[logKey];
                    childRow += `<tr>
                        <td>${subLog.subtopic}</td>
                        <td>${(subLog.pass > 0) ? subLog.pass : "N/A"}</td>
                        <td>${(subLog.pass_at.length > 0) ? subLog.pass_at[0].pass_at : "N/A"}</td>
                        <td>${(subLog.no_pass > 0) ? subLog.no_pass : "N/A"}</td>
                        <td>${(subLog.no_pass_at.length > 0) ? subLog.no_pass_at[0].no_pass_at : "N/A"}</td>
                    </tr>`
                });

                this.table.append(`<tr class="child logs-${key} ${(loop % 2 == 0) ? "even": "odd"}">
                    <td colSpan="4" style="padding: 0;">
                        <table class="inner_table">
                            <thead>
                                <tr>
                                    <th>Subtopic Name</th>
                                    <th>Pass Attempt Count</th>
                                    <th>Last Past Attempt Date</th>
                                    <th>Fail Attempt Count</th>
                                    <th>Last Fail Attempt Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${childRow}
                            </tbody>
                        </table>
                    </td>
                </tr>`);
                loop++;
            });
            this.table.show();
        } else {
            this.initialMessage.text("There is no data to show.");
            this.initialMessage.show();
            this.title.hide();
            this.table.hide();
            this.form.css("display", "flex");
        }
    }
}

$(document).ready(function() {
    let activity = new Activity();

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
        var ajaxurl = '/user/activity';
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                activity.init(data.data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $(document).on("click", ".expand", function() {
        
        console.log($(this).attr("data-id"));

        $(".logs-"+$(this).attr("data-id")).toggle();

        $(this).toggleClass("down");
    });
});