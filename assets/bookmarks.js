import "./styles/bookmarks.css";
import $ from "jquery";

$(function () {
    // on DOM ready

    $("a.bookmark-control").click(function () {
        var clickedAnchor = $(this);
        var courseId = $(this).data('course-id');
        $.ajax({
            url: clickedAnchor.attr("href"),
            success: function () {
                if (clickedAnchor.hasClass("bookmark-save")) {
                    bookmarks_show_forget(courseId);
                } else {
                    bookmarks_show_save(courseId);
                }
            },
        });
        return false;
    });
});

function bookmarks_show_save(courseId) {
    $('a.bookmark-save[data-course-id="' + courseId + '"]').show();
    $('a.bookmark-forget[data-course-id="' + courseId + '"]').hide();
}
function bookmarks_show_forget(courseId) {
    $('a.bookmark-save[data-course-id="' + courseId + '"]').hide();
    $('a.bookmark-forget[data-course-id="' + courseId + '"]').show();
}
