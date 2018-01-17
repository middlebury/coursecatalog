
function defineConfigDropDown(configs) {
  var configDropDownHTML = "<select class='config-dropdown' value='unselected'><option value='unselected' selected>Please select a config</option>";
  configs.forEach(function(element) {
    configDropDownHTML += "<option value='" + element['id'] + "'>" + element['label'] + "</option>";
  });
  configDropDownHTML += "</select>";
  return configDropDownHTML;
}

function selectConfig(configId, configDropDown) {
  configDropDown = configDropDown.replace(" selected", "");
  configDropDown = configDropDown.replace("value=\'" + configId + "\'", "value=\'" + configId + "\' selected");
  var currentValue = configDropDown.substring(configDropDown.indexOf("class=\'config-dropdown\' value=\'"), configDropDown.indexOf("\'><option"));
  configDropDown = configDropDown.replace(currentValue, "class=\'config-dropdown\' value=\'" + configId);
  return configDropDown;
}

function defineRevisionsDropDown(revisions) {
  var revisionsDropDownHTML = "<select class='revision-dropdown' value='unselected'><option value='latest' selected>Latest</option>";
  revisions.forEach(function(element) {
    revisionsDropDownHTML += "<option value='" + element['id'] + "'>" + element['last_saved'] + "</option>";
  });
  revisionsDropDownHTML += "</select>";
  return revisionsDropDownHTML;
}

function selectRevision(revisionId, revisionDropDown) {
  revisionDropDown = revisionDropDown.replace(" selected", "");
  revisionDropDown = revisionDropDown.replace("value=\'" + revisionId + "\'", "value=\'" + revisionId + "\' selected");
  var currentValue = revisionDropDown.substring(revisionDropDown.indexOf("class=\'revision-dropdown\' value=\'"), revisionDropDown.indexOf("\'><option"));
  revisionDropDown = revisionDropDown.replace(currentValue, "class=\'revisions-dropdown\' value=\'" + revisionId);
  return revisionDropDown;
}

function actions() {
  return "<button value='delete'>Delete</button><button value='run'>Run</button>";
}

function buildList(data, callback) {

  //defineConfigDropDown(data[0]['configs']);
  //defineRevisionsDropDown(data[1]['revisions']);

  var jobsHTML = "";
  data[2]['jobs'].forEach(function(element) {

    // ID && Active
    jobsHTML += "<tr><td><input type='hidden' value='" + element['id'] + "'></input><input type='checkbox'";
    if (element['active'] === '1') jobsHTML += " checked";
    jobsHTML += "></input></td>";

    // Export Path
    jobsHTML += "<td><input value='" + element['export_path'] + "'></input></td>";

    // Config
    var configDropDownHTML = selectConfig(element['config_id'], defineConfigDropDown(data[0]['configs']));
    jobsHTML += "<td>" + configDropDownHTML + "</td>";

    // Revisions
    var validRevisions = data[1]['revisions'].filter(function(revision) { return revision['arch_conf_id'] === element['config_id']; });
    var revisionsDropDownHTML = selectRevision(element['revision_id'], defineRevisionsDropDown(validRevisions));
    jobsHTML += "<td>" + revisionsDropDownHTML + "</td>";

    // Terms
    jobsHTML += "<td><input value='" + element['terms'] + "'></input></td>";

    // Actions
    jobsHTML += "<td>" + actions() + "</td>";
    jobsHTML += "</tr>";
  });

  $('#job-table').append(jobsHTML);

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
