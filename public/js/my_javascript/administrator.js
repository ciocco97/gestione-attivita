function administrator_script() {
    $('document').ready(function () {
        email_change_setup();
        team_change_setup();
        roles_change_setup();
        active_user_change_setup();
    });
}

function email_change_setup() {
    $("input[type=email][id^=user_email_]").on("keyup", function () {
        disable_enable_email_reset_confirm_button($(this))
    })
    $("button[id^=reset_button_]").on("click", function () {
        reset_user_email(getSuffix($(this), "reset_button_"))
    })
    $("button[id^=confirm_button_]").on("click", function () {
        change_user_email(getSuffix($(this), "confirm_button_"))
    })
}
function reset_user_email(user_id) {
    let input_email = $("#user_email_"+user_id);
    input_email.val(input_email.attr("value"))
    disable_enable_email_reset_confirm_button(input_email)
}
function change_user_email(user_id) {
    let input_email = $("#user_email_"+user_id);
    let new_email = input_email.val()
    input_email.attr("value", new_email)
    let icon = $("#icon_change_user_email_"+user_id)
    let spinner = $("#wait_change_user_email_"+user_id)
    let val = {}; val["user_id"] = user_id; val["new_email"] = new_email;
    user_change(val, GLOBAL.AJAX_METHODS['user_change_email'], spinner, icon)
    disable_enable_email_reset_confirm_button(input_email)
}
function disable_enable_email_reset_confirm_button(element) {
    let user_id = getSuffix(element, "user_email_")
    let confirm_button = $("#confirm_button_"+user_id)
    let reset_button = $("#reset_button_"+user_id)
    if (element.val() === element.attr("value")) {
        confirm_button.attr("disabled", true).removeClass("text-success border-success").addClass("text-secondary border-secondary")
        reset_button.attr("disabled", true).removeClass("text-warning border-warning").addClass("text-secondary border-secondary")
    } else {
        confirm_button.attr("disabled", false).removeClass("text-secondary border-secondary").addClass("text-success border-success")
        reset_button.attr("disabled", false).removeClass("text-secondary border-secondary").addClass("text-warning border-warning")
    }
}

function team_change_setup(){
    $("input[type=checkbox][id^=check_team_member_]").on("change", function () {
        let manager_id = $(this).attr("data-manager-id")
        let team_member_id = $(this).attr("data-team-member-id")
        let action = $(this).is(":checked")
        let val = {}; val["manager_id"] = manager_id; val["team_member_id"] = team_member_id; val["action"] = action;

        let spinner = $("#wait_change_user_team_"+manager_id)
        user_change(val, GLOBAL.AJAX_METHODS['user_change_team_member'], spinner)
        checkManager(manager_id)
    })
}
function checkManager(user_id) {
    let team_num = $("[id^=check_team_member_"+user_id+"]").filter(function () {
        return $(this).is(":checked")
    }).length
    $("#check_role_"+user_id+"_3").prop("checked", team_num > 0)
}
function roles_change_setup(){
    $("input[type=checkbox][id^=check_role_]").on("change", function () {
        let user_id = $(this).attr("data-user-id")
        let role_id = $(this).attr("data-role-id")
        let action = $(this).is(":checked")
        let val = {}; val["user_id"] = user_id; val["role_id"] = role_id; val["action"] = action;

        let spinner = $("#wait_change_user_roles_"+user_id)
        user_change(val, GLOBAL.AJAX_METHODS['user_change_role'], spinner)
    })
}
function active_user_change_setup(){
    $("input[type=checkbox][id^=active_user_switch_]").on("change", function () {
        let user_id = getSuffix($(this), "active_user_switch_")
        let action = $(this).is(":checked")
        let val = {}; val["user_id"] = user_id; val["action"] = action;
        let spinner = $("#wait_change_active_user_switch_"+user_id);
        user_change(val, GLOBAL.AJAX_METHODS['user_change_active_state'], spinner)
    })
}

function user_change(val, ajax_method, spinner, icon=null) {
    if (icon) { icon.hide(); }
    if (spinner) { spinner.show(); }

    $.ajax({
        url: '/ajax/user/change',
        type: 'GET',
        data: {val: val, ajax_method: ajax_method},
        success: function (data) {
            console.log(data)
            if (spinner) { spinner.hide(); }
            if (icon) { icon.show(); }
        }
    });

}
