function renderSchedule(calendarJQ, jsonUrl, timeslotHeight, textSize) {
	if (!timeslotHeight)
		timeslotHeight = 20;
	if (!textSize)
		textSize = 13;

	var year = new Date().getFullYear();
	var month = new Date().getMonth();
	var day = new Date().getDate();

	var startHour = new Number(calendarJQ.find('input:hidden[name=start_hour]').val());
	var endHour = new Number(calendarJQ.find('input:hidden[name=end_hour]').val());

	// 4 timeslots per hour, 20 height per slot.
	// Add on the approximate size of the header.
	var scheduleHeight = ((endHour - startHour + 1) * 4 * timeslotHeight) + 70;

	var daysToShow = 7;
	var firstDayOfWeek = 0;
	if (calendarJQ.find('input:hidden[name=show_sunday]').val() == 'no') {
		daysToShow--;
		firstDayOfWeek = 1;
	}
	if (calendarJQ.find('input:hidden[name=show_saturday]').val() == 'no') {
		daysToShow--;
	}

	calendarJQ.weekCalendar({
		date:new Date(year, month, day, 9),
		readonly: true,
		allowCalEventOverlap: true,
		overlapEventsSeparate: true,
		timeslotsPerHour: 4,
		timeslotHeight: timeslotHeight,
		textSize: textSize,
		businessHours : {
			start: startHour,
			end: endHour,
			limitDisplay : true
		},
		daysToShow: daysToShow,
		firstDayOfWeek: firstDayOfWeek,
		height: function($calendarJQ){
			var maxHeight = $(window).height() - $("h1").outerHeight(true);
			if (maxHeight > scheduleHeight)
				return scheduleHeight;
			else
				return maxHeight;
		},
		eventRender : function(calEvent, $event) {
			if(calEvent.collisions > 0) {
				$event.css({"backgroundColor":"#F77", "border":"1px solid #F00"});
				$event.find(".wc-time").css({"backgroundColor": "#F22", "border":"1px solid #F00"});
			}
		},
		noEvents : function() {
			displayMessage("There are no events for this week");
		},
		data: jsonUrl
	});

	calendarJQ.find('td.wc-day-column-header').each(function() {
		$(this).html($(this).html().replace(/<.*$/, ''));
	});
}
