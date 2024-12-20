import "./styles/export.css";
import $ from "jquery";
import "jquery-ui";

var loading = false;

/* Helper functions for catalog export configuration */

function selectConfig(url) {
    var config = $("#config-selector").find("select").val();
    window.location.href = url + "/" + config;
}

// ------ GENERATORS ------ //
function generateSection(id, type, input) {
    return (
        "<li id='" +
        id +
        "' class='section ui-state-default'>" +
        generateSectionHTML(type, input)
    );
}

function generateSectionHTML(type, input) {
    return (
        "<div class='position-helper'><span class='move-arrows'><img src='" +
        assets.arrow_cross +
        "'></span></div><span class='section-type'>Type: " +
        type +
        "</span><span class='section-value'>" +
        input +
        "</span><span class='section-controls'><button class='button-delete button-delete-section'>Delete Section</button><button class='button-section-add'>Add Section Below</button></span>"
    );
}

// Cache of courselist data for reuse.
// We will fetch the lists asynchronously, but want to only do a single fetch.
let courselist_data = {};
let courselists_to_populate = [];
let loading_courselist = false;

function generateInputTag(type, value, callback) {
    switch (type) {
        case "h1":
        case "h2":
            if (value.indexOf(";") !== -1) {
                var toc = value.substring(value.indexOf(";") + 1);
                value = value.substring(0, value.indexOf(";"));
            } else {
                var toc = "";
            }
            callback(
                "<input class='section-input half-width' placeholder='Full title for section heading' value='" +
                    value +
                    "'></input><input class='toc section-input half-width' placeholder='Short title for TOC listing (Optional)' value='" +
                    toc +
                    "'></input>"
            );
            break;
        case "page_content":
            callback(
                "<input class='section-input' placeholder='http://wwww.example.com' value='" +
                    value +
                    "'></input>"
            );
            break;
        case "custom_text":
            callback(
                "<textarea class='section-input' rows='20' value='" +
                    value +
                    "'>" +
                    value +
                    "</textarea>"
            );
            break;
        case "course_list":
            // We only want to perform a single fetch of data for the course lists to
            // not fire of dozens of requests for the same data, so we'll fire off the
            // first request and then queue up addtional inputs to render that we'll
            // go through when the result comes back.

            // If we've already loaded the data, just use it.
            if (courselist_data[$("#catalogId").val()]) {
                setCourseListInputForData(
                    courselist_data[$("#catalogId").val()],
                    value,
                    callback
                );
            } else {
                // If we don't have data yet, add our element to the queue to be rendered.
                courselists_to_populate.push({
                    catalog: $("#catalogId").val(),
                    value: value,
                    callback: callback,
                });
                // Kick off a load of the data if we aren't loading yet.
                if (!loading_courselist) {
                    // Set up an overlay to prevent working with or saving the form until
                    // population is finished.
                    loading_courselist = true;
                    $("body").append(
                        "<div id='loading-overlay' class='loading-overlay'><p class='loading'>Loading course list options...</div></div>"
                    );
                    $(".loading-overlay").css("height", $(window).height());
                    $(window).resize(function () {
                        $(".loading-overlay").css("height", $(window).height());
                    });

                    // Run the request and work through our queue when we get the result back.
                    $.ajax({
                        url: $("#config-body").data("courselist-url"),
                        type: "GET",
                        error: function (error) {
                            throw error;
                        },
                        success: function (data) {
                            courselist_data[$("#catalogId").val()] = data;
                            var l;
                            while ((l = courselists_to_populate.pop())) {
                                setCourseListInputForData(
                                    courselist_data[l["catalog"]],
                                    l["value"],
                                    l["callback"]
                                );
                            }
                            loading_courselist = false;
                            $(".loading-overlay").hide();
                        },
                    });
                }
            }
            break;
        default:
            throw "Invalid input tag type: " + type;
    }
}

function setCourseListInputForData(data, value, callback) {
    if (value === "") value = "unselected";
    // Course filters.
    if (value.indexOf(",") !== -1) {
        var selection = value.substring(0, value.indexOf(","));
        var filters = value.substring(value.indexOf(",") + 1);
        var filterHTML =
            "<br><span class='course-filters'>Course #'s to exclude: <input class='filter-input' name='filtering' placeholder='Separate with commas' value='" +
            filters +
            "'></input></span>";
    } else {
        var selection = value;
        var filterHTML =
            "<br><span class='course-filters'>Course #'s to exclude: <input class='filter-input' name='filtering' placeholder='Separate with commas'></input></span>";
    }
    var sectionInput = data;
    sectionInput = sectionInput.replace(
        "<select class='section-dropdown' value='unselected'>",
        "<select class='section-dropdown' value='" + selection + "'>"
    );
    sectionInput = sectionInput.replace(
        "<option value='" + selection + "'>",
        "<option value='" + selection + "' selected='selected'>"
    );
    sectionInput += filterHTML;
    callback(sectionInput);
}

function generateGroup(id, title, visible) {
    if (visible) {
        return (
            "<li id='" +
            id +
            "' class='group ui-state-default'><div class='position-helper'><span class='move-arrows'><img src='" +
            assets.arrow_cross +
            "'></span></div><span class='group-title'>" +
            title +
            "</span><div class='group-toggle-description'>show/hide</div><div class='group-controls'><button class='button-delete button-delete-group'>Delete group</button><button class='button add-group-below'>Add group below</button></div><ul class='section-group visible'></ul></li>"
        );
    } else {
        return (
            "<li id='" +
            id +
            "' class='group ui-state-default'><div class='position-helper'><span class='move-arrows'><img src='" +
            assets.arrow_cross +
            "'></span></div><span class='group-title'>" +
            title +
            "</span><div class='group-toggle-description'>show/hide</div><div class='group-controls hidden'><button class='button-delete button-delete-group'>Delete group</button><button class='button add-group-below'>Add group below</button></div><ul class='section-group'></ul></li>"
        );
    }
}

// ----- INIT ------ //

function buildList(jsonData, callback) {
    if (!jsonData || JSON.stringify(jsonData) === "{}") {
        newGroup();
        callback();
    } else {
        /*
         * The count variable is used to ensure that this whole block flows synchronously.
         * Otherwise, callback will fire early or multiple times.
         * This is a hacky way to do this and if you have a better idea, please feel free
         * to implement.
         */
        var count = 0;
        $.each(jsonData, function (key, value) {
            $.each(value, function (sectionKey, sectionValue) {
                count++;
            });
        });
        // Subtract group names from count.
        count -= Object.keys(jsonData).length;
        $.each(jsonData, function (key, value) {
            var groupName = "no-group";
            if (key.indexOf("group") !== -1) {
                groupName = "#" + key;
                $("#sections-list").append(
                    generateGroup(key, "Unnamed Group", false)
                );
                $.each(value, function (sectionKey, sectionValue) {
                    if (sectionKey === "title") {
                        giveGroupTitle(groupName, sectionValue);
                    } else {
                        generateInputTag(
                            sectionValue.type,
                            sectionValue.value,
                            function (result) {
                                var li = generateSection(
                                    sectionKey,
                                    sectionValue.type,
                                    result
                                );
                                $(groupName).find(".section-group").append(li);
                                reorderSectionsBasedOnIds(groupName);
                                if (!--count) {
                                    callback();
                                }
                            }
                        );
                    }
                });
            } else {
                throw "Invalid JSON: " + jsonData;
            }
        });
    }
}

function populate() {
    $("#config-selector").change(function () {
        window.location = this.value;
    });
    if (!$("#configId").val()) {
        return;
    }
    $("#save-export-config-button").on("click", function () {
        saveJSON();
    });
    $("#reset-export-config-button").on("click", function () {
        reset();
    });
    $("#delete-export-config-button").on("click", function () {
        deleteConfig();
    });
    $("#show-hide-export-config-groups-button").on("click", function () {
        showHide();
    });

    loading = true;
    $.ajax({
        url: $("#config-body").data("latest-url"),
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            buildList(data, function () {
                renameGroups();
                resetEventListeners();
                loading = false;
                $(".error-message").removeClass("error");
                $(".error-message").addClass("hidden");
            });
        },
    });

    // hide error message.
    $(".error-message").addClass("hidden");
}

// ----- HELPERS ------- //

function reorderSectionsBasedOnIds(groupId) {
    var sections = $(groupId).find(".section").toArray();
    sections.sort(function (a, b) {
        var aId = parseInt(a["id"].substring(7));
        var bId = parseInt(b["id"].substring(7));
        return aId - bId;
    });
    $(groupId).find(".section-group").empty().append(sections);
}

function reset() {
    $("#sections-list").html("");
    populate();
    $(".error-message").removeClass("success error");
    $(".error-message").addClass("hidden");
}

function hasTOC(input) {
    return $($(input).parent().children(".toc")[0]).val() !== "";
}

function isTOC(input) {
    return $(input).hasClass("toc");
}

function resetEventListeners() {
    $("#sections-list").sortable({
        stop: function (event, ui) {},
    });
    $("#sections-list .group")
        .find(".section-group")
        .sortable({
            stop: function (event, ui) {},
        });

    // Add event listeners for value changes.
    // I will never understand why javascript doesn't do this for us.
    $(".section-input").change(function () {
        $(this).attr("value", $(this).val());
        if ($(this).parent().parent().html().indexOf("Type: h1") !== -1) {
            $("#sections-list .new").removeClass("new");
            if ((!isTOC(this) && !hasTOC(this)) || isTOC(this)) {
                giveGroupTitle(this, $(this).val());
                renameGroups();
            }
        }
    });
    $(".filter-input").change(function () {
        $(this).attr("value", $(this).val());
    });
    $(".section-dropdown").change(function () {
        $(this).attr("value", $(this).val());
    });
    $("#sections-list").on("sortstop", function (event, ui) {
        // TODO - is there really any reason to rename groups and sections?  As long
        // as their ids are unique do they really need to be in order?
        renameGroups();
        renameSections();
    });
    $("#sections-list .group").on("sortstop", function (event, ui) {
        // TODO - is there really any reason to rename groups and sections?  As long
        // as their ids are unique do they really need to be in order?
        renameGroups();
        renameSections();
    });

    // Attach handlers to buttons based on class.
    $(".group-toggle-description")
        .unbind("click")
        .on("click", function () {
            toggleGroup(this);
        });
    $(".add-group-below")
        .unbind("click")
        .on("click", function () {
            newGroup(this);
        });
    $(".button-section-add")
        .unbind("click")
        .on("click", function () {
            newSection(this);
        });
    $(".button-delete-section")
        .unbind("click")
        .on("click", function () {
            deleteSection(this);
        });
    $(".button-delete-group")
        .unbind("click")
        .on("click", function () {
            deleteGroup(this);
        });
    $(".button-confirm-delete")
        .unbind("click")
        .on("click", function () {
            confirmDelete($(this).data("config-id"));
        });
    $(".button-cancel-delete")
        .unbind("click")
        .on("click", function () {
            cancelDelete();
        });
    $(".select-section-type")
        .unbind("click")
        .on("click", function () {
            defineSection(this);
        });
}

function showHide() {
    var groups = $("#sections-list .group").toArray();
    var visible = true;
    groups.forEach(function (element, index) {
        if (index === 0) {
            visible = $(element).find(".section-group").hasClass("visible");
        }

        if (!visible) {
            $(element).find(".section-group").addClass("visible");
            $(element).find(".group-controls").removeClass("hidden");
        } else {
            $(element).find(".section-group").removeClass("visible");
            $(element).find(".group-controls").addClass("hidden");
        }
    });
}

function validateInput(id, type, value, callback) {
    // Strip ""s around value.
    value = value.substring(1, value.length - 1);
    switch (type) {
        case "h1":
        case "h2":
            callback();
            // var validCharacters = /^[\/*.?!,;:()&amp;&quot; 0-9a-zA-Z]+$/;
            // if (validCharacters.test(value)) {
            //   callback();
            // } else {
            //   callback("Headers may only contain letters, numbers, .,?!\&;:(), and double quotes (\"\").", id);
            //}
            break;
        case "page_content":
            var validURL = /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/;
            if (validURL.test(value)) {
                callback();
            } else {
                callback(
                    "Please enter a valid URL.  Example: http://catalog.middlebury.edu/spanish",
                    id
                );
            }
            break;
        case "custom_text":
            callback();
            break;
        case "course_list":
            if (value === "unselected") {
                callback("Please select a course from the course list", id);
            } else {
                callback();
            }
            break;
        default:
            callback(
                "Invalid input type.  I have no idea how you accomplished this.",
                id
            );
    }
}

// ------ SAVE -------- //

function saveJSON() {
    if (loading) {
        $(".error-message").html("<p>Still loading data... Please wait.</p>");
        $(".error-message").removeClass("hidden success");
        $(".error-message").addClass("error");
        return;
    }
    var completelyValid = true;
    var JSONString = "{";

    var groups = $("#sections-list .group").toArray();
    groups.forEach(function (element, index) {
        var groupId = element["id"];
        JSONString += '"' + groupId + '":{';

        var groupTitle = $(element).find(".group-title")[0].innerHTML;
        JSONString += '"title":"' + groupTitle + '",';

        var sections = $(element).find(".section").toArray();
        sections.forEach(function (element, index) {
            if (!completelyValid) return;

            var sectionId = element["id"];
            var section = $(element);
            var sectionType = section
                .find(".section-type")[0]
                .innerHTML.substring(
                    section.find(".section-type")[0].innerHTML.indexOf(": ") + 2
                );
            var sectionValue = "";
            switch (sectionType) {
                case "h1":
                case "h2":
                    sectionValue = $(
                        $($(element).find(".section-value")[0]).find("input")[0]
                    ).val();
                    var toc = $(
                        $($(element).find(".section-value")[0]).find("input")[1]
                    ).val();
                    if (toc) {
                        sectionValue += ";" + toc;
                    }
                    break;
                case "custom_text":
                    sectionValue = $(
                        $($(element).find(".section-value")[0]).find(
                            "textarea"
                        )[0]
                    ).val();
                    sectionValue = sectionValue.replace(
                        /(?:\r\n|\r|\n)/g,
                        "\\n"
                    );
                    sectionValue = sectionValue.replace(/\"/g, "&quot;");
                    break;
                case "course_list":
                    sectionValue = $(
                        $($(element).find(".section-value")[0]).find(
                            "select"
                        )[0]
                    ).val();
                    if ($(element).find(".filter-input").val()) {
                        var filters = $(element).find(".filter-input").val();
                        // Remove trailing "
                        //sectionValue = sectionValue.substring(0, sectionValue.length - 1);
                        sectionValue += "," + filters;
                    }
                    break;
                default:
                    sectionValue = $(
                        $($(element).find(".section-value")[0]).find("input")[0]
                    ).val();
                    break;
            }

            sectionValue = '"' + sectionValue + '"';

            validateInput(sectionId, sectionType, sectionValue, function (
                error,
                sectionId
            ) {
                if (error) {
                    $(".error-message").html("<p>Error: " + error + "</p>");
                    $(".error-message").addClass("error");
                    $(".error-message").removeClass("hidden success");
                    $("#" + sectionId).css("background", "#f95757");
                    completelyValid = false;
                } else {
                    $(".error-message").addClass("hidden");
                    JSONString +=
                        '"section' +
                        eval(index + 1) +
                        '":{"type":"' +
                        sectionType +
                        '","value":' +
                        sectionValue +
                        "},";
                    completelyValid = true;
                }
            });
        });

        // Remove trailing ,
        JSONString = JSONString.substring(0, JSONString.length - 1);

        JSONString += "},";
    });

    if (completelyValid) {
        $(".section").css("background", "#b5c5dd");

        // Remove trailing ,
        JSONString = JSONString.substring(0, JSONString.length - 1);
        JSONString += "}";

        // Ensure valid JSON if no sections are present.
        if (JSONString === "}") JSONString = "{}";

        $.ajax({
            url: $("#config-body").data("insert-revision-url"),
            type: "POST",
            dataType: "json",
            data: {
                csrf_key: $('#csrf-key-config-modify').val(),
                note: $("#note").val(),
                jsonData: JSONString,
            },
            error: function (error) {
                console.log(error);
            },
            success: function (data) {
                $(".error-message").html("<p>Saved successfully</p>");
                $(".error-message").removeClass("hidden error");
                $(".error-message").addClass("success");
                setTimeout(function () {
                    $(".error-message").addClass("hidden");
                    $(".error-message").removeClass("success");
                }, 5000);
            },
        });
    }
}

// ------ CONFIGS ------- //

function deleteConfig() {
    if ($("#warning-box").length) return;
    $("#config-body").append(
        "<div id='warning-box' class='warning-box'><p class='warning'>Are you sure you want to delete this configuration? This cannot be undone. All related revisions will be gone as well.</p><div class='warning-controls'><button class='button-delete button-confirm-delete'>Delete</button><button class='button-cancel-delete'>Cancel</button></div></div>"
    );
    resetEventListeners();
}

function confirmDelete() {
    $.ajax({
        url: $("#config-body").data("delete-url"),
        type: "POST",
        data: {
            csrf_key: $('#csrf-key-config-modify').val(),
        },
        error: function (error) {
            console.log(error);
        },
        success: function (data) {
            location.reload(true);
        },
    });
}

function cancelDelete() {
    $("#warning-box").remove();
}

// ------ GROUPS ------- //

function giveGroupTitle(selector, value) {
    $(selector)
        .closest("#sections-list .group")
        .find(".group-title")[0].innerHTML = value;
}

function renameGroups() {
    $("#sections-list .group")
        .toArray()
        .forEach(function (element, index) {
            $(element).attr("id", "group" + eval(index + 1));
        });
}

function toggleGroup(button) {
    $(button)
        .closest("#sections-list .group")
        .find(".section-group")
        .toggleClass("visible");
    $(button)
        .closest("#sections-list .group")
        .find(".group-controls")
        .toggleClass("hidden");
}

function newGroup(thisButton) {
    // Only allow user to create one group at a time.
    if ($("#sections-list .new").length) return;

    var newgenerateGroup = generateGroup(
        "temp",
        "Please fill out an h1 section to give this group a name",
        true
    );

    if (!thisButton) {
        if ($("#begin-message")) {
            $("#begin-message").remove();
        }
        $("#sections-list").append(newgenerateGroup);
    } else {
        var li = $(thisButton).parent().parent();
        $(newgenerateGroup).insertAfter(li);
    }

    $("#temp").addClass("new");

    // Create h1 section.
    newGroupFirstSection();
}

function deleteGroup(thisButton) {
    $(thisButton).closest("#sections-list .group").remove();
    if ($("#sections-list").find(".group").length === 0) {
        newGroup();
    }

    renameGroups();
}

// ------ SECTIONS ----- //

function renameSections() {
    $(".section")
        .toArray()
        .forEach(function (element, index) {
            $(element).attr("id", "section" + eval(index + 1));
        });
}

function newGroupFirstSection() {
    generateInputTag("h1", "", function (input) {
        var newSectionHTML =
            "<li class='section h1-section ui-state-default'>" +
            generateSectionHTML("h1", input) +
            "</li>";
        $("#sections-list .new").children("ul").append(newSectionHTML);
        resetEventListeners();
    });
}

function newSection(thisButton) {
    var newSectionHTML =
        "<li class='section ui-state-default'><select class='select-section-type'><option value='unselected' selected='selected'>Please choose a section type</option><option value='h1'>h1</option><option value='h2'>h2</option><option value='page_content'>External page content</option><option value='custom_text'>Custom text</option><option value='course_list'>Course list</option></select></li>";
    if (!thisButton) {
        if ($("#begin-message")) {
            $("#begin-message").remove();
        }
        $("#sections-list").append(newSectionHTML);
    } else {
        var li = $(thisButton).parent().parent();
        $(newSectionHTML).insertAfter(li);
    }
    resetEventListeners();
    renameSections();
}

function defineSection(select) {
    var sectionType = $(select).val();
    var li = $(select).parent();

    generateInputTag(sectionType, "", function (result) {
        $(li).html(generateSectionHTML(sectionType, result));

        resetEventListeners();
    });
}

function deleteSection(thisButton) {
    if (
        $(thisButton).closest("#sections-list .group").find(".section")
            .length === 1
    ) {
        deleteGroup(thisButton);
    } else {
        $(thisButton).closest(".section").remove();
    }
    renameSections();
}

$(document).ready(function () {
    populate();
    resetEventListeners();
});
