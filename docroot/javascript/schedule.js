
// RESET

function reset() {
  location.reload(true);
}

function resetEventListeners() {
  $('.config-dropdown').change(function() {
    $(this).attr('value', $(this).val());
  });
  $('.revision-dropdown').change(function() {
    $(this).attr('value', $(this).val());
  });
}

function repopulateRevisions(jobId) {
  $('#job' + jobId).find('.revision-dropdown')[0].remove();
  var newConfig = $('#job' + jobId).find('.config-dropdown').val();
  $.ajax({
    url: "../export/listrevisions",
    type: "GET",
    success: function(revisions) {
      revisions = $.parseJSON(revisions);
      var validRevisions = revisions.filter(function(revision) { return revision['arch_conf_id'] === newConfig; });
      var revisionsDropDownHTML = selectRevision(null, defineRevisionsDropDown(validRevisions));
      $('#job' + jobId).find('.job-revision-dropdown').append(revisionsDropDownHTML);
    }
  });
}

// INIT & CONFIG

function defineConfigDropDown(jobId, configs) {
  var configDropDownHTML = "<select onchange='repopulateRevisions(" + jobId + ")' class='config-dropdown' value='unselected'><option value='unselected' selected>Please select a config</option>";
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
  revisionDropDown = revisionDropDown.replace(currentValue, "class=\'revision-dropdown\' value=\'" + revisionId);
  return revisionDropDown;
}

function actions(jobId) {
  return "<button value='delete' onclick='deleteJob(" + jobId + ")'>Delete</button><button value='run'>Run</button>";
}

function buildList(data, callback) {

  var jobsHTML = "";
  data[2]['jobs'].forEach(function(element) {

    jobsHTML += "<tr id='job" + element['id'] + "' class='job'>";

    // ID && Active
    jobsHTML += "<td class='job-active'><input type='hidden' value='" + element['id'] + "'></input><input type='checkbox'";
    if (element['active'] === '1') jobsHTML += " checked";
    jobsHTML += "></input></td>";

    // Export Path
    jobsHTML += "<td class='job-export-path'><input value='" + element['export_path'] + "'></input></td>";

    // Config
    var configDropDownHTML = selectConfig(element['config_id'], defineConfigDropDown(element['id'], data[0]['configs']));
    jobsHTML += "<td class='job-config-dropdown'>" + configDropDownHTML + "</td>";

    // Revisions
    var validRevisions = data[1]['revisions'].filter(function(revision) { return revision['arch_conf_id'] === element['config_id']; });
    var revisionsDropDownHTML = selectRevision(element['revision_id'], defineRevisionsDropDown(validRevisions));
    jobsHTML += "<td class='job-revision-dropdown'>" + revisionsDropDownHTML + "</td>";

    // Terms
    jobsHTML += "<td class='job-terms'><input value='" + element['terms'] + "'></input></td>";

    // Actions
    jobsHTML += "<td class='job-actions'>" + actions(element['id']) + "</td>";
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
        resetEventListeners();
      });
    }
  });
}

// DELETE

function deleteJob(jobId) {
  if($('#warning-box').length) return;
  $('#jobs').prepend("<div id='warning-box' class='warning-box'><p class='warning'>Are you sure you want to delete this job? This cannot be undone.</p><div class='warning-controls'><button class='button-delete' onclick='confirmDelete(" + jobId + ")'>Delete</button><button onclick='cancelDelete()'>Cancel</button></div></div>");
}

function confirmDelete(jobId) {
  $.ajax({
    url: "../export/deletejob",
    type: "POST",
    data: {
      jobId: jobId
    },
    error: function(error) {
      console.log(error);
    },
    success: function(data) {
      location.reload(true);
    }
  });
}

function cancelDelete() {
  $('#warning-box').remove();
}

// INSERT

function save() {
  $(".job").each(function(index, job) {
    var jobData = [];

    jobData['jobId'] = $(job).find(':hidden').val();
    if($(job).find(':checkbox').is(':checked')) jobData['active'] = 1;
    else jobData['active'] = 0;
    jobData['export_path'] = $(job).find('.job-export-path').find('input').val();
    jobData['config_id'] = $(job).find('.job-config-dropdown').find('select').val();
    jobData['revision_id'] = $(job).find('.job-revision-dropdown').find('select').val();
    jobData['terms'] = $(job).find('.job-terms').find('input').val();

    console.log(jobData);

    $.ajax({
      url: "../export/updatejob",
      type: "POST",
      data: {
        jobId: jobData['jobId'],
        active: jobData['active'],
        export_path: jobData['export_path'],
        config_id: jobData['config_id'],
        revision_id: jobData['revision_id'],
        terms: jobData['terms']
      },
      error: function(error) {
        console.log(error);
      },
      success: function(data) {
        console.log('it worked!');
      }
    });
  });
}

$(document).ready(function() {
  populate();
});
