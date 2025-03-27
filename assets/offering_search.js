import "./styles/offering_search.css";
import $ from "jquery";
import expander from "jquery-expander";
expander($);

$("document").ready(function () {
    $("p.description").expander({
        slicePoint: 475, // default is 100
        expandPrefix: "... ", // text to come before the expand link
        expandText: "read more", // default is 'read more...'
        userCollapseText: "[collapse]", // default is '[collapse expanded text]'
    });
});
