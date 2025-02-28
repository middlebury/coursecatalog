import "./styles/export.css";
import $ from "jquery";
import "jquery-ui";

var selected = [];

function sortSelected() {
    selected.sort(function (a, b) {
        // if a timestamp later than b timestamp
        var dateA = new Date(
            $("#" + a)
                .parents("tr")
                .find(".timestamp")[0].innerText
        );
        var dateB = new Date(
            $("#" + b)
                .parents("tr")
                .find(".timestamp")[0].innerText
        );
        if (dateA < dateB) {
            return -1;
        } else if (dateA === dateB) {
            return 0;
        } else {
            return 1;
        }
    });
}

function compare(url) {
    sortSelected();
    var rev1 = $(
        $("#" + selected[0])
            .parents("tr")
            .find(".revId")[0]
    ).val();
    var rev2 = $(
        $("#" + selected[1])
            .parents("tr")
            .find(".revId")[0]
    ).val();
    if (!rev1 || !rev2) return;
    var win = window.open(
        url.replace(/-rev1-/, rev1).replace(/-rev2-/, rev2),
        "_blank"
    );
    win.focus();
}

function renderSelected() {
    $("input[type=radio]").prop("checked", false);
    selected.forEach(function (element) {
        $("#" + element).prop("checked", true);
    });
}

function revertTo(url, revId) {
    $.ajax({
        url: url,
        type: "POST",
        data: {
            revId: revId,
            csrf_key: $('#csrf-key-config-revert').val(),
        },
        success: function (data) {
            location.reload();
        },
        error: function (error) {
            throw error;
        },
    });
}

$(document).ready(function () {
    $("input[type=radio]").change(function () {
        selected.push(this.id);
        if (selected.length > 2) selected.shift();
        renderSelected();
    });

    $(".compare-revisions-button").on("click", function () {
        compare($(this).data("url"));
    });

    $(".revert-button").on("click", function () {
        revertTo($(this).data("url"), $(this).data("rev-id"));
    });
});
