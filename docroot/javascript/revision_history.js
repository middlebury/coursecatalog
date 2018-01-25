
var selected = [];

function prettifyDiff(diff) {
  var output = "<pre class='diff'>";
  diff.forEach(function(element) {
    if(element[0] != '0') {
      output += element[1];
    }
  });
  output += "</pre>";
  console.log(output);
  return output;
}

function sortSelected() {
  selected.sort(function(a, b) {
    // if a timestamp later than b timestamp
    var dateA = new Date($('#' + a).parents('tr').find('.timestamp')[0].innerText);
    var dateB = new Date($('#' + b).parents('tr').find('.timestamp')[0].innerText);
    if (dateA < dateB) {
      return -1;
    } else if (dateA === dateB) {
      return 0;
    } else {
      return 1;
    }
  });
}

function compare() {
  var comparison = $('#comparison')[0];

  var dmp = new diff_match_patch();
  dmp.Diff_Timeout = 10;

  sortSelected();
  var text1 = $('#' + selected[0]).parents('tr').find('.json-data')[0].innerText;
  var text2 = $('#' + selected[1]).parents('tr').find('.json-data')[0].innerText;
  var diff = dmp.diff_main(text1, text2);
  dmp.diff_cleanupSemantic(diff);

  $(comparison)[0].innerHTML = prettifyDiff(diff);
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
    element.innerHTML = "<pre class='preform-json'>" + JSON.stringify(JSONObject, null, 2) + "</pre>";
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
