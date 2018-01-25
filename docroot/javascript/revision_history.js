
var selected = [];

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
  sortSelected();
  var text1 = $('#' + selected[0]).parents('tr').find('.json-data')[0].innerText;
  var text2 = $('#' + selected[1]).parents('tr').find('.json-data')[0].innerText;
  var time1 = $('#' + selected[0]).parents('tr').find('.timestamp')[0].innerText + " (older)";
  var time2 = $('#' + selected[1]).parents('tr').find('.timestamp')[0].innerText + " (newer)";

  $('#text1').val(text1);
  $('#text2').val(text2);
  $('#time1').val(time1);
  $('#time2').val(time2);

  $('#diff-form').submit();
}

function revertTo(revId) {
  $.ajax({
    url: "../export/reverttorevision",
    type: "POST",
    data: {
      revId: revId
    },
    success: function(data) {
      location.reload();
    },
    error: function(error) {
      throw error;
    }
  });
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
