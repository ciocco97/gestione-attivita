function pullSharedVariables() {
    let variables
    $.ajax({
        url: '/ajax/shared/vars',
        type: 'GET',
        async: false,
        data: {},
        success: function (data) {
            variables = {
                ACTIVITY_STATES: data["ACTIVITY_STATES"],
                ACTIVITY_BILLING_STATES: data["ACTIVITY_BILLING_STATES"],
                ACTIVITY_ACCOUNTED_STATES: data["ACTIVITY_ACCOUNTED_STATES"],
                ROLES: data["ROLES"],
                AJAX_METHODS: data["AJAX_METHODS"],
                PAGES: data["PAGES"],
                REPORT_SENT: data["REPORT_SENT"]
            }
        }
    });
    return variables
}

var shared_vars = ( function () {
        var instance
        return {
            getInstance: function () {
                let get_from_server = false
                if (!instance) {
                    instance = sessionStorage.global_variables
                    if (!instance || instance === "{}") {
                        get_from_server = true
                    } else {
                        try {
                            instance = JSON.parse(instance)
                        } catch (e) {
                            console.log(e)
                            get_from_server = true
                        }
                   }
                }
                if (get_from_server) {
                    console.log("Get shared vars from server")
                    instance = pullSharedVariables()
                    sessionStorage.setItem("global_variables", JSON.stringify(instance))
                }
                return instance;
            }

        };
    }
)();

$("document").ready(function () {
    GLOBAL = shared_vars.getInstance()
})
