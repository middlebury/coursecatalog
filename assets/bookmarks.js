import './styles/bookmarks.css';
import $ from 'jquery';

$(function() { // on DOM ready

	$('.save_course a').click(function () {
		var clickedAnchor = $(this);
		var courseId = $(this).siblings('input[name=course_id]').val();

		$.ajax({
			url: clickedAnchor.attr('href'),
			success: function () {
				if (clickedAnchor.hasClass('save')) {
					bookmarks_show_forget(courseId);
				} else {
					bookmarks_show_save(courseId);
				}
			}

		});
		return false;
	});

});

function bookmarks_show_save(courseId) {
	$('.save_course input[name=course_id][value="' + courseId + '"]').siblings('a.save').show();
	$('.save_course input[name=course_id][value="' + courseId + '"]').siblings('a.forget').hide();
}
function bookmarks_show_forget(courseId) {
	$('.save_course input[name=course_id][value="' + courseId + '"]').siblings('a.save').hide();
	$('.save_course input[name=course_id][value="' + courseId + '"]').siblings('a.forget').show();
}
