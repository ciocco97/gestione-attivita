TECHNICIAN = 0;
MANAGER = 1;
ADMINISTRATIVE = 2;
COMMERCIAL = 3;
ADMINISTRATOR = 4;
GREEN_COLOR = "#c7edc9";

function technician_script() {
    $('document').ready(function () {
        // Evidenzio il tab technician nella navbar
        $("#technician_nav_tab").children().addClass("active");

        table_setup();

        change_state_setup(TECHNICIAN);
        $("#activities_change_btn").removeClass("px-1");

    });
}

function manager_script() {
    $('document').ready(function () {
        // Evidenzio il tab technician nella navbar
        $("#manager_nav_tab").children().addClass("active");

        table_setup(MANAGER);

        change_state_setup(MANAGER);

    });

}

function administrative_activity_script() {
    $('document').ready(function () {
        // Evidenzio il tab technician nella navbar
        $("#administrative_nav_tab").children().addClass("active");

        table_setup(ADMINISTRATIVE);

        change_state_setup(ADMINISTRATIVE);

    });
}

function change_state_setup(page) {
    checked_activity_ids = [];
    $("input:checkbox[id^=check_select_]").on("change", function () {
        activity_checked($(this), page == ADMINISTRATIVE);
    });
    $("[id^=activities_change_]").not("[id=activities_change_btn]").on("click", function () {
        if (page == ADMINISTRATIVE) {
            activities_change_billing_state($(this));
        } else {
            activities_approve_confirmation($(this));
        }
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

function activity_checked(check, administrative) {
    let activity_id = getIDSuffix(check, "check_select_");
    let current_row = $("[id^=activity_row_" + activity_id + "]");
    if (check.prop("checked")) {
        checked_activity_ids.push(activity_id);
        current_row.addClass("table-active");
        if (checked_activity_ids.length === 1) {
            $("#activities_change_btn").fadeIn("fast");
            if (administrative) {
                $("#activities_change_1").fadeIn("fast");
            } else {
                $("#activities_change_4").fadeIn("fast");
            }
        }
    } else {
        checked_activity_ids = checked_activity_ids.filter(function (id) {
            return id !== activity_id;
        })
        current_row.removeClass("table-active");
        if (checked_activity_ids.length === 0) {
            $("#activities_change_1").fadeOut("fast");
            $("#activities_change_btn").fadeOut("fast");
        }
    }
}

function disable_row(id, administrative) {
    $("a.btn[id$=" + id + "][id!=show_" + id + "]")
        .addClass('disabled')
        // .children().removeClass('text-danger text-warning text-primary');
    if (!administrative) {
        $("input[id$=" + id + "]").attr("disabled", true);
    }
    $("select[id$=" + id + "]").attr("disabled", true);
    $("button[id$=" + id + "]").attr("disabled", true);
}

function table_setup(page) {
    let technician = page == TECHNICIAN;
    let manager = page == MANAGER;
    let administrative = page == ADMINISTRATIVE;

    if (!administrative) {
        // Modifico possibili azioni e colori per ogni attività
        $("tbody tr td[id^=state]").each(function () {
            let id = getIDSuffix($(this), "state_"); // Prendo l'activity_id dalla prima colonna della riga di appartenenza
            let state_id = $(this).attr("data-state-id");
            let description_td = $("#desc_" + id);
            switch (state_id) {
                case "1":
                    description_td.addClass('text-primary', 1000); // Coloro di grigio la descrizione dell'attività
                    $(this).addClass('text-primary'); // Coloro di blu lo stato dell'attività
                    break
                case "2":
                    // Non faccio nulla
                    break
                case "3":
                    $("#report_" + id).addClass('disabled') // Disabilito l'invio del rapportino
                        .children().removeClass('text-primary text-success text-danger'); // Scoloro il relativo bottone
                    description_td.addClass('text-secondary', 1000); // Coloro di grigio la descrizione dell'attività
                    $(this).addClass('text-secondary'); // Coloro di grigio lo stato dell'attività
                    break
                case "4":
                    if (!manager) {
                        $("a.btn[id$=" + id + "][id!=show_" + id + "][id!=report_" + id + "]").addClass('disabled') // Disabilito la modifica e l'eliminazione
                            .children().removeClass('text-danger text-warning'); // Scoloro i relativi bottoni
                        $("td[id$=" + id + "] input.form-check").attr('disabled', true);
                    }
                    description_td.addClass('text-success', 1000); // Coloro di verde la descrizione dell'attività
                    $(this).addClass('text-success'); // Coloro di verde lo stato dell'attività
                    break
            }
        });

        $("[id^=report_]").on("click", function () {
            send_activity_report($(this));
        });

        if (manager) {
            $("[id^=billable_duration_input_]").on("change", function () {
                change_billable_duration($(this));
            });
        }

        $("[id^=billing_state_select_]").on("change", function () {
            change_billing_state($(this), administrative);
        }).each(function () {
            let select = $(this);
            let activity_id = getIDSuffix(select, "billing_state_select_");
            if (select.val() == 4) {
                if (administrative) {
                    $('#activity_row_' + activity_id).css("background-color", GREEN_COLOR);
                } else {
                    select.attr("disabled", true);
                    $("a.btn[id$=" + activity_id + "][id!=show_" + activity_id + "]").addClass('disabled')
                        .children().removeClass('text-danger text-warning'); // Scoloro i relativi bottoni
                    $("td[id$=" + activity_id + "] input").attr('disabled', true);
                }
            }
        });
    }

    $("[id^=activity_row_]").each(function () {
        let row = $(this);
        if (row.attr("data-bill") == 1) {
            disable_row(getIDSuffix($(this), "activity_row_"), administrative);
            row.css("background-color", GREEN_COLOR);
        }
    });

    attach_table_tools("#master_tbody",
        "#master_search",
        "#master_num_rows",
        "#pagination_selector_1");

    $("[data-bs-toggle=tooltip]").tooltip();

    filter_setup();

}

function change_billing_state(clicked_element, administrative) {
    let activity_id = getIDSuffix(clicked_element, "billing_state_select_");
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

function getIDSuffix(element, id_prefix) {
    let myRegexp = new RegExp(id_prefix + "(.*)");
    let match = myRegexp.exec(element.attr("id"));
    return match[1];
}

//
// function changeActivityAjax(element, id_prefix) {
//     let activity_id = getIDSuffix(element, id_prefix);
// }

function change_billable_duration(changed_element) {
    let activity_id = getIDSuffix(changed_element, "billable_duration_input_");
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

function attach_table_tools(tbody_id, search_field_id, select_num_rows_id, save_current_page_element_id) {
    $(search_field_id).on("keyup", function () {
        search_and_pagination(tbody_id, search_field_id, select_num_rows_id, save_current_page_element_id);
    });
    $(select_num_rows_id).on("change", function () {
        search_and_pagination(tbody_id, search_field_id, select_num_rows_id, save_current_page_element_id);
    })

    change_pag_function_to_call = function f(method, clicked_element) {
        if (!clicked_element.hasClass("disabled")) {
            search_in_table(tbody_id, $(search_field_id).val());
            change_pag(method, clicked_element, save_current_page_element_id, tbody_id, select_num_rows_id);
        }
    }

    $("#pagination_back").on("click", function () {
        change_pag_function_to_call(0, $(this));
    });
    $('#pagination_selector_1').on("click", function () {
        change_pag_function_to_call(1, $(this));
    });
    $('#pagination_selector_2').on("click", function () {
        change_pag_function_to_call(1, $(this));
    });
    $('#pagination_selector_3').on("click", function () {
        change_pag_function_to_call(1, $(this));
    });
    $("#pagination_forward").on("click", function () {
        change_pag_function_to_call(3, $(this));
    });

    pagination(tbody_id, select_num_rows_id, save_current_page_element_id);
}

// Cerca e poi pagina
function search_and_pagination(tbody_id, search_field_id, select_num_rows_id, save_current_page_element_id) {
    console.log("{function: search_and_pagination}");
    search_in_table(tbody_id, $(search_field_id).val());
    pagination(tbody_id, select_num_rows_id, save_current_page_element_id);
}

// Nascondi nella tabella le righe in cui non compare la stringa input
function search_in_table(tbody_id, input) {
    console.log("{function: search_in_table}");
    let value = input.toUpperCase();
    console.log("{input: " + value + "}");
    $(tbody_id + " tr").filter(function () {
        $(this).toggle($(this).text().toUpperCase().indexOf(value) > -1);
    });
}

/* Pagina la tabella con id tbody_id in base al valore nal campo con id select_num_rows_id
La pagina corrente verrà salvata nell'elemento con id save_current_page_element_id*/
function pagination(tbody_id, select_num_rows_id, save_current_page_element_id) {
    let n_per_page, rows, current_page, n_pages, a, b;
    n_per_page = Number($(select_num_rows_id).val());
    rows = $(tbody_id + " tr:visible");
    current_page = Number($(save_current_page_element_id).attr("data-current"));
    n_pages = Math.ceil(rows.length / n_per_page);

    let back = $("#pagination_back");
    let sel_1 = $('#pagination_selector_1');
    let sel_2 = $('#pagination_selector_2');
    let sel_3 = $('#pagination_selector_3');
    let forward = $("#pagination_forward");

    console.log("{num_rows: " + n_per_page + ", " +
        "total: " + rows.length + ", " +
        "n_pages: " + n_pages + ", " +
        "current_page: " + current_page + "}");

    // Manca il caso in cui la current_page è maggiore del n_pages

    // Abilita o disabilita forward e back pagination e costruisce la navbar delle pagine
    if (current_page == n_pages) {
        forward.addClass('disabled');
        b = true;
    } else {
        forward.removeClass('disabled');
        b = false;
    }
    if (current_page == 1) {
        back.addClass('disabled');
        sel_1.addClass("active").children().first().text(1);
        a = true;
    } else {
        back.removeClass('disabled');
        a = false;
    }

    if (a && b || rows.length === 0) {
        sel_2.toggle(false);
        sel_3.toggle(false);
    } else if (!a && !b) {
        sel_1.removeClass("active").children().first().text(current_page - 1);
        sel_2.addClass("active").toggle(true).children().first().text(current_page);
        sel_3.removeClass("active").toggle(true).children().first().text(current_page + 1);
    } else if (a) {
        sel_2.removeClass("active").toggle(true).children().first().text(2);
        if (3 > n_pages) {
            sel_3.toggle(false);
        } else {
            sel_3.removeClass("active").toggle(true).children().first().text(3);
        }
    } else {
        if (current_page - 2 > 0) {
            sel_1.removeClass("active").children().first().text(current_page - 2);
            sel_2.removeClass("active").toggle(true).children().first().text(current_page - 1);
            sel_3.addClass("active").toggle(true).children().first().text(current_page);
        } else {
            sel_1.removeClass("active").children().first().text(1);
            sel_2.addClass("active").toggle(true).children().first().text(2);
        }
    }

    rows.each(function (index) {
        if (index < (current_page - 1) * n_per_page || index > (current_page * n_per_page)) {
            $(this).toggle(false);
        } else {
            $(this).toggle(true);
        }
    })
}

function change_pag(method, clicked_element, save_current_page_element_id, tbody_id, select_num_rows_id) {
    console.log("{function: change_pag}");
    let new_current;
    let current = $(save_current_page_element_id);
    if (method == 0) {
        new_current = Number(current.attr("data-current")) - 1;
    } else if (method == 1) {
        new_current = clicked_element.children().first().text();
    } else {
        new_current = Number(current.attr("data-current")) + 1;
    }
    current.attr("data-current", new_current);
    pagination(tbody_id, select_num_rows_id, save_current_page_element_id);
}

function send_activity_report(button) {
    let activity_id = getIDSuffix(button, "report_");
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

function filter_setup() {

    $("#master_period_filter").on('change', function () {
        let value = $(this).val();
        if (value == "") {
            $("#master_date_filter").prop("disabled", false);
        } else {
            $("#master_date_filter").prop("disabled", true);
        }
    })
    $("#master_date_filter").on('change', function () {
        let value = $(this).val();
        if (value == "") {
            $("#master_period_filter").prop("disabled", false);
        } else {
            $("#master_period_filter").prop("disabled", true);
        }
    })

    let pathname = window.location.pathname;
    if (pathname.includes("filter")) {
        let period = localStorage["master_period_filter"];
        if (period) {
            if (period !== "") {
                $("#master_date_filter").prop('disabled', true)
            } // Se il periodo è settato disabilita il filtro sulla data
            $("#master_period_filter option[value=" + period + "]").prop("selected", true);
        }
        let costumer = localStorage["master_costumer_filter"];
        if (costumer) {
            $("#master_costumer_filter option[value=" + costumer + "]").prop("selected", true);
        }
        let state = localStorage["master_state_filter"];
        if (state) {
            $("#master_state_filter option[value=" + state + "]").prop("selected", true);
        }
        let user = localStorage["master_user_filter"];
        if (user) {
            $("#master_user_filter option[value=" + user + "]").prop("selected", true);
        }
        let billing_state = localStorage["master_billing_state_filter"];
        if (billing_state) {
            $("#master_billing_state_filter option[value=" + billing_state + "]").prop("selected", true);
        }
        let date = localStorage["master_date_filter"];
        if (date) {
            if (date !== "") {
                $("#master_period_filter").prop('disabled', true)
            } // Se la data è settata disabilita il filtro sul periodo
            $("#master_date_filter").val(date);
        }
    } else {
        filter_reset();
    }

}

function save_filters() {
    console.log("{function: save_filters}");
    localStorage["master_period_filter"] = $("#master_period_filter").val();
    localStorage["master_costumer_filter"] = $("#master_costumer_filter").val();
    localStorage["master_state_filter"] = $("#master_state_filter").val();
    localStorage["master_date_filter"] = $("#master_date_filter").val();
    localStorage["master_user_filter"] = $("#master_user_filter").val();
    localStorage["master_billing_state_filter"] = $("#master_billing_state_filter").val();
    $("#master_filter_form").submit();
}

function filter_reset() {
    localStorage.removeItem("master_period_filter");
    localStorage.removeItem("master_costumer_filter");
    localStorage.removeItem("master_state_filter");
    localStorage.removeItem("master_date_filter");
    localStorage.removeItem("master_user_filter");
    localStorage.removeItem("master_billing_state_filter");
}

function show_activity_script() {
    $("document").ready(function () {
        $("#costumer").change(function () {
            filter_orders_when_costumer_selected();
        });
        $("#order").change(function () {
            filter_costumers_when_order_selected();
        });
    });
}

function filter_orders_when_costumer_selected() {
    console.log("{function: costumer_selected_activity}");
    let costumer = $("#costumer").val();
    console.log("{costumer_id: " + costumer + "}");
    $.ajax({
        url: '/ajax/orders',
        type: 'GET',
        data: {costumer_id: costumer},
        success: function (data) {
            var order_select = $('#order');
            order_select.find('option').remove().end();
            $.each(data, function () {
                order_select.append($("<option />").val(this.id).text(this.descrizione_commessa));
            });
        }
    });
}

function filter_costumers_when_order_selected() {
    console.log("{function: order_selected_activity}");
    var costumer = $('#costumer').val();
    var order = $("#order").val();
    console.log("{order_id: " + order + "}");
    $.ajax({
        url: '/ajax/costumer',
        type: 'GET',
        data: {order_id: order},
        success: function (data) {
            $("#costumer").val(data.id);
        }
    });
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
