
var selected = [];

function prettifyDiff(diff) {
  var output = "<pre class='diff'>";
  diff.forEach(function(element, index) {
    if(element[0] === 1) {
      if(index > 0 && diff[index - 1][0] === 0) {
        if (diff[index - 1][1].indexOf("group") < 5) {}
        output += "...\n" + diff[index - 1][1].substring(diff[index - 1][1].lastIndexOf("group") - 2);
      }
      output += "<span class='added'>" + element[1] + "</span>";
      if(diff[index + 1] && diff[index + 1][0] === 0) {
        output += diff[index + 1][1].substring(0, Math.max(diff[index + 1][1].indexOf("group"), 10));
      }
    } else if (element[0] === -1) {
      if(index > 0 && diff[index - 1][0] === 0) {
        output += diff[index - 1][1].substring(diff[index - 1][1].lastIndexOf("group") - 2);
      }
      output += "<span class='removed'>" + element[1] + "</span>";
      if(diff[index + 1] && diff[index + 1][0] === 0) {
        output += diff[index + 1][1].substring(0, Math.max(diff[index + 1][1].indexOf("group"), 10));
      }
    } else {
      //output += element[1];
    }
  });
  output += "</pre>";

  // // Remove groups with no changes.
  // var groups = [];
  // var spans = [];
  // var groupReg = /group/gi;
  // var spanReg = /<span/gi;
  // var result;
  // while ( (result = groupReg.exec(output)) ) {
  //   groups.push(result.index);
  // }
  // while ( (result = spanReg.exec(output)) ) {
  //   spans.push(result.index);
  // }
  //
  // var validGroups = [];
  // groups.forEach(function(group, groupIndex) {
  //   spans.forEach(function(span, spanIndex) {
  //     if(span > group && ( span < groups[groupIndex + 1] || groupIndex === groups.length - 1) ) {
  //       validGroups.push(group);
  //     }
  //   });
  // });
  //
  // // Remove duplicate groups. Taken from https://stackoverflow.com/questions/9229645/remove-duplicate-values-from-js-array
  // validGroups = validGroups.reduce(function(a,b){if(a.indexOf(b)<0)a.push(b);return a;},[]);
  // validGroups.foreach(function(element) {
  //
  // });

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

  console.log(diff);

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
