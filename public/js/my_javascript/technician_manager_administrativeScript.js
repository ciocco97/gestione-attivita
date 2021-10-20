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
        // Evidenzio il tab manager nella navbar
        $("#manager_nav_tab").children().addClass("active");

        table_setup(MANAGER);

        change_state_setup(MANAGER);

    });

}

function administrative_activity_script() {
    $('document').ready(function () {
        // Evidenzio il tab administrative nella navbar
        $("#administrative_nav_tab").children().addClass("active");

        table_setup(ADMINISTRATIVE);

        change_state_setup(ADMINISTRATIVE);

    });
}


function table_setup(page) {
    let technician = page == TECHNICIAN;
    let manager = page == MANAGER;
    let administrative = page == ADMINISTRATIVE;

    if (!administrative) {
        // Modifico possibili azioni e colori per ogni attività
        $("tbody tr td[id^=state]").each(function () {
            let id = getSuffix($(this), "state_"); // Prendo l'activity_id dalla prima colonna della riga di appartenenza
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

        $("[id^=send_report_]").on("click", function () {
            activity_change($(this), "send_report_", AJAX_METHODS.activity_send_report_index);
        });

        $("[id^=billable_duration_input_]").on("change", function () {
            activity_change($(this), "billable_duration_input_", AJAX_METHODS.activity_change_billable_duration_index);
        });

        $("[id^=billing_state_select_]").on("change", function () {
            activity_change($(this), "billing_state_select_", AJAX_METHODS.activity_change_billing_state_index);
        })
    }

    $("[id^=activity_row_]").each(function () {
        let row = $(this);
        if (row.attr("data-bill") == 2) {
            disable_row(getSuffix($(this), "activity_row_"), administrative);
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

function search_and_pagination(tbody_id, search_field_id, select_num_rows_id, save_current_page_element_id) {
    console.log("{function: search_and_pagination}");
    search_in_table(tbody_id, $(search_field_id).val());
    pagination(tbody_id, select_num_rows_id, save_current_page_element_id);
}

function search_in_table(tbody_id, input) {
    console.log("{function: search_in_table}");
    let value = input.toUpperCase();
    console.log("{input: " + value + "}");
    $(tbody_id + " tr").filter(function () {
        $(this).toggle($(this).text().toUpperCase().indexOf(value) > -1);
    });
}

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


function activity_change(element, id_prefix, ajax_method) {
    let activity_id = getSuffix(element, id_prefix);
    let value = element.val();
    switch (ajax_method) {
        case AJAX_METHODS.activity_change_billing_state_index:
            break
        case AJAX_METHODS.activity_change_billable_duration_index:
            break
        case AJAX_METHODS.activity_send_report_index:
            break
    }
    $("[id=" + waiter_prefix + id_prefix + activity_id + "]").show();

    $.ajax({
        url: '/ajax/activity/change',
        type: 'GET',
        data: {activity_id: activity_id, value: value, ajax_method: ajax_method},
        success: function (data) {
            $("[id=" + waiter_prefix + id_prefix + activity_id + "]").hide();
            if (ajax_method == AJAX_METHODS.activity_send_report_index && data) {
                $("[id=" + id_prefix + activity_id + "]").html(
                    "<i class=\"bi bi-clipboard-check text-success\"></i>");
            }
        }
    });
}

function activities_change(element, ajax_method) {
    let activity_ids = checked_activity_ids;
    let value = null;
    value = element.attr("data-state");
    switch (ajax_method) {
        case AJAX_METHODS.activities_change_billed_index:
            break
        case AJAX_METHODS.activities_change_billed_index:
            break
    }

    $.ajax({
        url: '/ajax/activities/change',
        type: 'GET',
        data: {activity_ids: activity_ids, value: value, ajax_method: ajax_method},
        success: function (data) {
            if (data) {
                location.reload();
            } else {
                console.log("error")
            }
        }
    });
}


function change_state_setup(page) {
    checked_activity_ids = [];
    $("input:checkbox[id^=check_select_]").on("change", function () {
        activity_checked($(this), page == ADMINISTRATIVE);
    });

    $("[id^=activities_change_]").not("[id=activities_change_btn]").on("click", function () {
        if (page == ADMINISTRATIVE) {
            activities_change($(this), AJAX_METHODS['activities_change_billed_index']);
        } else {
            activities_change($(this), AJAX_METHODS['activities_change_state_index']);
        }
    });

}

function activity_checked(check) {
    let activity_id = getSuffix(check, "check_select_");
    let current_row = $("[id^=activity_row_" + activity_id + "]");
    if (check.prop("checked")) {
        checked_activity_ids.push(activity_id);
        current_row.addClass("table-active");
        if (checked_activity_ids.length === 1) {
            $("#activities_change_btn").fadeIn("fast");
            $("#activities_change_main").fadeIn("fast");
        }
    } else {
        checked_activity_ids = checked_activity_ids.filter(function (id) {
            return id !== activity_id;
        })
        current_row.removeClass("table-active");
        if (checked_activity_ids.length === 0) {
            $("#activities_change_main").fadeOut("fast");
            $("#activities_change_btn").fadeOut("fast");
        }
    }
}
