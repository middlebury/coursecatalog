
function compare() {
  console.log('hola');
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
    console.log(JSON.stringify(JSONObject, null, 2));
    element.innerHTML = "<pre>" + JSON.stringify(JSONObject, null, 2) + "</pre>";
  });
}

$(document).ready(function() {
  prettifyJSON();
});
