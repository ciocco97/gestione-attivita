
function commercial_script() {
    $('document').ready(function () {
        // Evidenzio il tab commercial nella navbar
        $("#commercial_nav_tab").children().addClass("active");

        // Abilito azioni switch
        $("[id^=report_switch_]").on("change", function () {
            order_change_report($(this));
        })
    });
}

function order_change_report(report_switch) {
    let checked = report_switch.is(":checked") ? 1 : 0;
    let order_id = getIDSuffix(report_switch, "report_switch_");
    $("[id=wait_change_report_" + order_id + "]").show();
    $.ajax({
        url: '/ajax/order/report/change',
        type: 'GET',
        data: {order_id: order_id, checked: checked},
        success: function (data) {
            $("[id=wait_change_report_" + order_id + "]").hide();
        }
    });
}
