
function buildList(data, callback) {
  console.log(data);

  // Generate config dropdown
  var configDropDownHTML = "";
  // data[0][configs].foreach(function(element) {
  //   configDropDownHTML +=
  // });

  /*
  var jobs = data.split("; ");

  // This split creates an extra, blank job. So, delete it.
  jobs.pop();
  var jobHTML = "";
  jobs.forEach(function(element) {
    var values = element.split(", ");

    // ID && Active
    jobHTML += "<tr><td><input type='hidden' value='" + values[0] + "'></input><input type='checkbox'";
    if (values[1] === '1') jobHTML += " checked";
    jobHTML += "></input></td>";

    // Export Path
    jobHTML += "<td><input value='" + values[2] + "'></input></td>";

    // Config
    jobHTML += "<td><select><option>test</option></select></td>";

    jobHTML += "</tr>";
  });

  $('#job-table').append(jobHTML);
  */
  callback();
}

function populate() {
  // Load data.
  $.ajax({
    url: "../export/listjobs",
    type: "GET",
    success: function(data) {
      buildList($.parseJSON(data), function() {

      });
    }
  });
}

$(document).ready(function() {
  populate();
});
