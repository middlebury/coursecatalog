import './jquery-ui/jquery-ui.css'
import '@toast-ui/calendar/dist/toastui-calendar.min.css';
import './styles/schedules.css';
import $ from 'jquery';
import 'jquery-ui';
import './email_controls.js';
import Calendar from '@toast-ui/calendar';

$('document').ready(function() {

    $('#schedules_term_choice select').change(function () {
        window.location = $(this).val();
    });

    $("a.remove_course").click(function () {
        if (confirm('Do you want to remove the bookmark for this course?')) {
            var li = $(this).parents('li.bookmarked_course');
            $.ajax({
                url: $(this).attr('href'),
                success: function () {
                    li.remove();
                }
            });
        }
        return false;
    });


    /*********************************************************
     * Set up the add-sections dialog controls
     *********************************************************/
    $(".add_section_dialog").each(function () {
        var form = $(this).siblings("form.add_to_schedule_form");
        var addDialog = $(this).dialog({
                autoOpen: false,
                width:  600,
                modal: true,
                close: function(event, ui) {
                    $(this).find("select.section_set").empty();
                }
            });

        form.data("addDialog", addDialog);
    });

    $("form.add_to_schedule_form select").change(function () {
        var form = $(this).parent("form");
        var addDialog = form.data("addDialog");
        var scheduleId = $(this).val();
        $(this).val("");

        addDialog.find("select.section_set").empty();
        getSectionSets(addDialog, scheduleId);
        addDialog.dialog("open");
    });

    $('form.delete_schedule').submit(function () {
        return confirm('Are you sure you want to delete this schedule?');
    });

    $('a.print_button').click(function() {
        var printView = window.open(this.href, this.target, "menubar=1,resizable=1,height=600,width=800,scrollbars=1");
        printView.focus();
        return false;
    });

    /*********************************************************
     * Remove dialog controls
     *********************************************************/
    $(".remove_dialog").each(function() {
        var button = $(this).siblings(".remove_button");
        var removeDialog = $(this).dialog({
                autoOpen: false,
                width:  600,
                modal: true
            });

        button.data("removeDialog", removeDialog);
    });

    $(".remove_button").click(function(){
        var removeDialog = $(this).data('removeDialog');
        removeDialog.dialog("open");
    });

    $(".remove_dialog .cancel").click(function() {
        $(this).parent().dialog('close');
    });

    $(".change_section_dialog").each(function () {
        var button = $(this).siblings(".change_sections_button");
        var addDialog = $(this).dialog({
                autoOpen: false,
                width:  600,
                modal: true,
                close: function(event, ui) {
                    $(this).find("ul.section_sets").empty();
                }
            });

        button.data("addDialog", addDialog);
    });

    $(".change_sections_button").click(function () {
        var removeDialog = $(this).parent();
        removeDialog.dialog("close");

        var addDialog = $(this).data("addDialog");
        var scheduleId = addDialog.find("input[name=scheduleId]").val();

        addDialog.find("ul.section_sets").empty();
        getSectionSets(addDialog, scheduleId);
        addDialog.dialog("open");
    });

    /*********************************************************
     * Calendar controls
     *********************************************************/
    $(".calendar").each(function() {
        var button = $(this).siblings(".calendar_button");
        var calendar = $(this).dialog({
                autoOpen: false,
                width:  900,
                modal: true,
            });

        button.data("calendar", calendar);
    });

    $(".calendar_button").click(function(){
        alert('not yet re-implemented. Needs a new calendar library.');
        return false;
        var jsonUrl = $(this).attr('href');
        var calendar = $(this).data('calendar');
        calendar.dialog("open");

        if (!calendar.data('initialized')) {
            renderSchedule(calendar, jsonUrl);
            calendar.data('initialized', true);
        }

        return false;
    });

    // Render the #calender item on a page if it exists (such as on preview)
    $("#calendar").each(function() {
        renderSchedule($(this), $(this).children('input[name=jsonUrl]').val(), 45, 11);
    });

});

function renderSchedule(calendarElement, jsonUrl, timeslotHeight, textSize) {
    if (!timeslotHeight)
        timeslotHeight = 20;
    if (!textSize)
        textSize = 13;

    var year = new Date().getFullYear();
    var month = new Date().getMonth();
    var day = new Date().getDate();

    var startHour = new Number(calendarElement.find('input:hidden[name=start_hour]').val());
    var endHour = new Number(calendarElement.find('input:hidden[name=end_hour]').val());

    var scheduleHeight = ((endHour - startHour + 1) * timeslotHeight) + 45;
    calendarElement.css({height: scheduleHeight + "px"});


    var showWeekend = true;
    if (calendarElement.find('input:hidden[name=show_sunday]').val() == 'no' && calendarElement.find('input:hidden[name=show_saturday]').val() == 'no') {
        showWeekend = false;
    }

    const formatTime = function(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var meridian = "am";
        if (hours > 11) {
            meridian = "pm";
            if (hours > 12) {
                hours = hours - 12;
            }
        }
        return hours + ":" + minutes.toString().padStart(2, '0') + " " + meridian;
    }

    $.getJSON(jsonUrl, function (data) {
        console.log(data);
        const calendar = new Calendar(
            calendarElement.get(0),
            {
                defaultView: 'week',
                usageStatistics: false,
                isReadOnly: true,
                week: {
                    taskView: false,
                    eventView: ['time'],
                    hourStart: startHour,
                    hourEnd: endHour,
                    dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                    showNowIndicator: false,
                    workweek: !showWeekend,
                },
                theme: {
                    week: {
                        today: {
                            color: 'black',
                            backgroundColor: 'transparent',
                        },
                        pastDay: {
                            color: 'black',
                        },
                    },
                    common: {
                        holiday: {
                            color: 'black',
                        }
                    }
                },
                calendars: [
                    {
                        id: 'sections',
                        name: 'Sections',
                        backgroundColor: '#03bd9e',
                    }
                ],
                template: {
                    time(event) {

                        return `<div class="calendar_event_time">${formatTime(event.start)} - ${formatTime(event.end)}</div><div class="calendar_event_title">${event.title}</div><div class="calendar_event_location">${event.location} - CRN: ${event.raw.crn}</div>`;
                    },
                }
            }
        );
        console.log(calendar);
        calendar.createEvents(data);
    });

    calendarElement.find('td.wc-day-column-header').each(function() {
        $(this).html($(this).html().replace(/<.*$/, ''));
    });

    $('form.delete_schedule').submit(function () {
        return confirm('Are you sure you want to delete this schedule?');
    });

    $('a.print_button').click(function() {
        var printView = window.open(this.href, this.target, "menubar=1,resizable=1,height=600,width=800,scrollbars=1");
        printView.focus();
        return false;
    });
}

function getSectionSets (dialog, scheduleId) {
    var lookupUrl = dialog.children("input[name=section_lookup_url]").val();
    var courseId = dialog.children("input[name=course_id]").val();

    dialog.find("input[name=scheduleId]").val(scheduleId);

    $.getJSON(lookupUrl, {course: courseId, scheduleId: scheduleId}, function (data, textStatus) {
        populateSectionSetSelect(dialog, scheduleId, data);
        populateSectionTypes(dialog, scheduleId, dialog.find("select.section_set").val(), data);
    });
}

function populateSectionSetSelect(dialog, scheduleId, data) {
    var selectList = dialog.find("select.section_set");
    selectList.empty();
    var i = 0;
    for (var linkSetId in data) {
        i++;
        var option = $('<option value="'+linkSetId+'">'+i+'</option>');
        if (data[linkSetId].selected) {
            option.attr('selected', 'selected');
        }
        selectList.append(option);
    }
    if (i > 1) {
        dialog.find("div.section_set").show();
    } else {
        dialog.find("div.section_set").hide();
    }

    selectList.data('section_data', data);
    selectList.data('dialog', dialog);
    selectList.data('scheduleId', scheduleId);
    selectList.change(function() {
        populateSectionTypes($(this).data('dialog'), $(this).data('scheduleId'), $(this).val(), $(this).data('section_data'));
    });
}

function populateSectionTypes (dialog, scheduleId, linkSetId, data) {

    var typesList = dialog.find("ul.section_types");
    typesList.empty();
    var i = 0;
    for (var linkTypeId in data[linkSetId]['types']) {
        var sectionType = data[linkSetId]['types'][linkTypeId];

        var typeListItem = $('<li></li>');
        typesList.append(typeListItem);
        var typeList = $('<ul class="section_type"></ul>');
        typeListItem.append(typeList);

        for (var j = 0; j < sectionType.length; j++) {
            var section = sectionType[j];

            var item = $('<li class="section"></li>');
            typeList.append(item);
            var radio = $('<input type="radio" name="section_group_'+i+'" value="'+section.id+'"/>');
            item.append(radio);
            if (section.selected) {
                radio.attr('checked', 'checked');
            }
            item.append(' ');
            item.append(section.name);
            item.append(' <div class="section_instructor section_info"><span class="section_type">'+section.type+'</span> - <span class="section_instructor">'+section.instructor+'</span></div>');
            item.append('');
            if (section.conflicts) {
                var conflictsClass = ' conflicting';
                var conflictNames = '<br/> &nbsp; &nbsp; ' + section.conflictString;
            } else {
                var conflictsClass = '';
                var conflictNames = '';
            }
            item.append('<div class="section_time section_info'+conflictsClass+'">'+section.schedule+conflictNames+'</div>');
            item.append('<div class="section_location section_info">'+section.location+'</div>');
            if (section.availability) {
                item.append('<div class="section_availability section_info">'+section.availability+'</div>');
            }
        }

        i++;
    }
}
