GREEN_COLOR = "#c7edc9";
waiter_prefix = "wait_change_";

// AJAX_METHODS = {
//     order_change_report_index: 1,
//     order_change_state_index: 2,
//     activity_change_billable_duration_index: 3,
//     activity_change_billing_state_index: 4,
//     activity_send_report_index: 5,
//     activities_change_accounted_index: 6,
//     activities_change_state_index: 7
// };
//
// TECHNICIAN = 0;
// MANAGER = 3;
// ADMINISTRATIVE = 1;
// COMMERCIAL = 2;
// ADMINISTRATOR = 4;


function disable_row(id, administrative, accounted) {
    $("a.btn[id$=" + id + "][id!=show_" + id + "]")
        .addClass('disabled')
        .children().removeClass('text-danger text-warning');
    if (!administrative) {
        $("input[id$=" + id + "]").attr("disabled", true);
        if (accounted) {
            $("button[id$=" + id + "]").attr("disabled", true);
        }
    }
    $("select[id$=" + id + "]").attr("disabled", true);
}

function getSuffix(element, id_prefix) {
    let myRegexp = new RegExp(id_prefix + "(.*)");
    let match = myRegexp.exec(element.attr("id"));
    return match[1];
}


function change_password_script() {
    $("document").ready(function () {
        $("#password_validity_alert").toggle(false);
        $("#retype_password_alert").toggle(false);

        $("#newPassword").on('keyup', function () {
            check_password_validity($(this).val());
        });
        $("#retype_password").on('keyup', function () {
            check_password_equality($("#newPassword").val(), $(this).val())
        });
        $("#change_password_button").on('click', function () {
            event.preventDefault();
            if (check_password_validity($("#newPassword").val()) &&
                check_password_equality($("#newPassword").val(), $("#retype_password").val())) {
                $("#change_password_form").submit();
            }
        });
    });
}

function check_password_validity(password) {
    let regular_expression = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/;
    let valid = password.search(regular_expression) !== -1;
    console.log("check_password_validity" + valid);
    $("#password_validity_alert").toggle(!valid && password !== "");
    return valid;
}

function check_password_equality(p_new, p_retipe) {
    let equal = p_new === p_retipe;
    console.log("check_password_equality" + equal);
    $("#retype_password_alert").toggle(!equal && p_retipe !== "");
    return equal;
}


function change_billable_duration(changed_element) {
    let activity_id = getSuffix(changed_element, "billable_duration_input_");
    let billable_duration = changed_element.val();
    $("[id=wait_change_billable_duration_" + activity_id + "]").show();
    $.ajax({
        url: '/ajax/activity/change/change_billable_duration',
        type: 'GET',
        data: {activity_id: activity_id, billable_duration: billable_duration},
        success: function () {
            $("[id=wait_change_billable_duration_" + activity_id + "]").hide();
        }
    });
}

function change_billing_state(clicked_element, administrative) {
    let activity_id = getSuffix(clicked_element, "billing_state_select_");
    let billing_state = clicked_element.val();
    $("[id=wait_change_billing_" + activity_id + "]").show();
    $.ajax({
        url: '/ajax/activity/change/billing_state',
        type: 'GET',
        data: {activity_id: activity_id, billing_state: billing_state},
        success: function () {
            $("[id=wait_change_billing_" + activity_id + "]").hide();
            if (administrative) {
                if (billing_state == 4) {
                    $("#activity_row_" + activity_id).css('background-color', GREEN_COLOR);
                } else {
                    $("#activity_row_" + activity_id).css('background-color', 'white');
                }
            }
        }
    });
}

function send_activity_report(button) {
    let activity_id = getSuffix(button, "report_");
    $("[id=wait_" + activity_id + "]").show();
    $.ajax({
        url: '/ajax/activity/send/report',
        type: 'GET',
        data: {activity_id: activity_id},
        success: function () {
            $("[id=wait_" + activity_id + "]").hide();
            $("[id=report_" + activity_id + "]").html("<i class=\"bi bi-clipboard-check text-success\"></i>");
        }
    });
}

function nav_script() {
    $("document").ready(function () {
        console.log("{function: nav_script}");
        $.ajax({
            url: '/ajax/user/roles',
            type: 'GET',
            data: {},
            success: function (data) {
                if (!data.includes(3)) {
                    $("#manager_nav_tab").hide();
                }
                if (!data.includes(1)) {
                    $("#administrative_nav_tab").hide();
                }
                if (!data.includes(2)) {
                    $("#commercial_nav_tab").hide();
                }
            }
        });
    });
}

function activities_change_billing_state(button) {
    let state = button.attr("data-state");
    $.ajax({
        url: '/ajax/activity/mass/billing_state/change',
        type: 'GET',
        data: {ids: checked_activity_ids, state: state},
        success: function (data) {
            location.reload();
        }
    });
}

function activities_approve_confirmation(button) {
    let state = button.attr("data-state");
    let titles = $("#modal_confirmation_title").attr("data-titles").trim().replace(/(\r\n|\n|\r)/gm, "").split("-");
    titles.forEach((val, index) => titles[index] = val.trim());
    $("#modal_confirmation_title").text(titles[state - 1]);
    $("#modal_confirmation_num_activities").text(checked_activity_ids.length);
    $("#modal_confirmation_confirm").on("click", function () {
        activities_change_state(state);
    });
    $("#modal_confirmation").modal("show");
}

function activities_change_state(state) {
    console.log(checked_activity_ids)
    $.ajax({
        url: '/ajax/activity/mass/change',
        type: 'GET',
        data: {ids: checked_activity_ids, state: state},
        success: function (data) {
            location.reload();
        }
    });
}
