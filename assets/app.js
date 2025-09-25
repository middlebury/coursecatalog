/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "theme.css";
import "./styles/app.css";
import "theme.js";
import "bookmarks";
import $ from "jquery";

$(function () {
    // on DOM ready

    $("a.login").click(function () {
        if ($(this).data("return-to")) {
            $(this).attr("href", $(this).attr("href") + '?returnTo=' + encodeURIComponent($(this).data("return-to")));
        } else {
            $(this).attr("href", $(this).attr("href") + '?returnTo=' + encodeURIComponent(window.location.pathname + window.location.search));
        }
    });
});
