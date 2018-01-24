
var selected = [];

function compare() {
  var comparison = $('#comparison')[0];

  var dmp = new diff_match_patch();
  var text1 = $('#' + selected[0]).parents('tr').find('.json-data')[0].innerText;
  var text2 = $('#' + selected[1]).parents('tr').find('.json-data')[0].innerText;

  dmp.Diff_Timeout = 10;
  var diff = dmp.diff_main(text1, text2);
  dmp.diff_cleanupSemantic(diff);
  var ds = dmp.diff_prettyHtml(diff);
  $(comparison)[0].innerHTML = ds;
  console.log($(comparison));

  $(comparison).removeClass('hidden');
}

function hideComparison() {
  $('#comparison').addClass('hidden');
}

function revertTo(jsonData) {
  console.log('hola');
}

function showHide(revisionId) {
  $('#json' + revisionId).toggleClass('hidden');
}

function prettifyJSON() {
  $('.json-data').toArray().forEach(function(element) {
    var JSONObject = JSON.parse(element.innerHTML);
    element.innerHTML = "<pre>" + JSON.stringify(JSONObject, null, 2) + "</pre>";
  });
}

function renderSelected() {
  $('input[type=radio]').prop('checked', false);
  selected.forEach(function(element) { $('#' + element).prop('checked', true); });
}

$(document).ready(function() {
  prettifyJSON();

  $('input[type=radio]').change(function() {
    selected.push(this.id);
    if (selected.length > 2) selected.shift();
    renderSelected();
  });
});
