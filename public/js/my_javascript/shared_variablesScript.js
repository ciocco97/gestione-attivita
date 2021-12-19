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
                console.log("Try to load global variables from RAM")
                if (!instance) {
                    console.log("Var in RAM not found. Try to load from session storage")
                    instance = sessionStorage.global_variables
                    if (!instance || instance == "{}") {
                        console.log("Var in session storage not found. Load from server")
                        instance = pullSharedVariables()
                        sessionStorage.setItem("global_variables", JSON.stringify(instance))
                    } else {
                        instance = JSON.parse(instance)
                    }
                }
                return instance;
            }

        };
    }
)();

$("document").ready(function () {
    GLOBAL = shared_vars.getInstance()
})
