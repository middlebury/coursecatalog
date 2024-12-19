import 'diff2html/bundles/css/diff2html.min.css';
import './styles/export.css';
import $ from 'jquery';
import 'jquery-ui';
// import { Diff2Html } from 'diff2html';
import Diff2HtmlUI from 'diff2html/bundles/js/diff2html-ui.min.js';
import * as Diff from 'diff';

function buildDiffView(text1, text2, label1, label2) {
    var changes = Diff.diffLines(text1, text2);
    if (changes.length > 1) {
        const diff = Diff.createPatch('', text1, text2, label1, label2, { options: { context: 5} });
        const configuration = {
            drawFileList: true,
            matching: 'lines',
            outputFormat: 'side-by-side',
            fileListToggle: false,
            fileListStartVisible: false,
            fileContentToggle: false,
            container: document.createElement('div'),
            diff2htmlUi:null,
            highlight: true
        };
        var diff2htmlUi = new Diff2HtmlUI.Diff2HtmlUI($('#diff').get(0), diff, configuration);
        diff2htmlUi.draw();
    } else {
        $('#diff').append("<div style='border:1px solid gray;padding:10px'><p>No difference in JSON data between these revisions</p></div>");
    }
}

$(document).ready(function() {
    buildDiffView(
        $("#rev1").val(),
        $("#rev2").val(),
        "Revision #" + $("#rev1").data('rev-id') + " (" + $("#rev1").data('rev-date') + ")",
        "Revision #" + $("#rev2").data('rev-id') + " (" + $("#rev2").data('rev-date') + ")",
    );
});
