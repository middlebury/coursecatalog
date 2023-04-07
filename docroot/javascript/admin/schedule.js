
var exporting = false;

// ------ RESET ------ //

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

// ------ INIT ------- //

function defineConfigDropDown(jobId, configs) {
  var configDropDownHTML = "<select onchange='repopulateRevisions(" + jobId + ")' class='config-dropdown' value='unselected'><option value='unselected' selected>Please select a config</option>";
  configs.forEach(function(element) {
    configDropDownHTML += "<option data-catalog='" + element['catalog_id'] + "' value='" + element['id'] + "'>" + element['label'] + "</option>";
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
    if (element['note'] != '') {
      var note = element['note'].substring(0, 24);
      if (element['note'].length > 25) { note += "..."; }
      revisionsDropDownHTML += "<option value='" + element['id'] + "'>" + note + "</option>";
    } else {
      revisionsDropDownHTML += "<option value='" + element['id'] + "'>" + element['last_saved'] + "</option>";
    }
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
  return "<button value='delete' onclick='deleteJob(" + jobId + ")'>Delete</button><button value='run' onclick='runJob(" + jobId + ")'>Run</button>";
}

function buildList(data, callback) {
  var jobsHTML = "";
  data[2]['jobs'].forEach(function(element) {

    jobsHTML += "<tr id='job" + element['id'] + "' class='job'>";

    // ID && Active
    jobsHTML += "<td class='job-active'><input type='hidden' value='" + element['id'] + "'></input><input type='checkbox'";
    if (element['active'] === '1' || element['active'] === 1) jobsHTML += " checked";
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

      $('.error-message').addClass('hidden');
    }
  });
}

// ---- DELETE ----- //

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
      throw error;
    },
    success: function(data) {
      location.reload(true);
    }
  });
}

function cancelDelete() {
  $('#warning-box').remove();
}

// ----- INSERT ------ //

function validateJobTerms(jobTerms, catalogId, callback, jobId) {
  jobTerms.forEach(function(element, index) {
    $.ajax({
      url: "../export/validterm",
      type: "GET",
      data: {
        catalogId: catalogId,
        term: element
      },
      error: function(error) {
        if(jobId) {
          $('#job' + jobId).addClass('job-error');
        }
        callback('One or more of these terms is invalid or is not yet active.  This job will not be run until all terms are valid and active.');
      },
      success: function(data) {
        if(jobId) {
          $('#job' + jobId).removeClass('job-error');
        }
        if(index === jobTerms.length - 1) {
          callback();
        }
      }
    });
  });
}

function validateInput(jobData, callback) {
  var numsOnly = /[0-9]+/;
  var pathsOnly = /[a-zA-Z0-9]+\/[a-zA-Z0-9]+/;
  var numsAndCommaOnly = /([0-9],?)+/;

  if (!numsOnly.test(jobData['jobId']))                   { callback("Invalid ID: " + jobData['jobId']); return false; }
  if(jobData['active'] !== 0 && jobData['active'] !== 1)  { callback("Invalid active state: " + jobData['active']); return false; }
  if (!pathsOnly.test(jobData['export_path']))            { callback("Invalid export path. Please use letters, numbers, and '-' only, and use format catalog/terms."); return false; }
  if(!numsOnly.test(jobData['config_id']))                { callback("Invalid config ID: " + jobData['config_d']); return false; }
  if(!numsOnly.test(jobData['revision_id'])
      && jobData['revision_id'] !== 'latest')             { callback("Invalid revision ID: " + jobData['revision_id']); return false; }
  var jobTerms = jobData['terms'].split(',');
  if (jobTerms[0] === '') { callback("Please enter at least one term"); }
  validateJobTerms(jobTerms, jobData['catalog_id'], callback, jobData['jobId']);

}

function generateJobData(job) {
  var jobData = [];

  if ($(job).find(':checkbox').is(':checked')) { jobData['active'] = 1; }
  else { jobData['active'] = 0; }

  jobData['jobId'] = $(job).find(':hidden').val();
  jobData['export_path'] = $(job).find('.job-export-path').find('input').val();
  jobData['config_id'] = $(job).find('.job-config-dropdown').find('select').val();
  jobData['catalog_id'] = $(job).find('.job-config-dropdown').find(':selected').data('catalog');
  jobData['revision_id'] = $(job).find('.job-revision-dropdown').find('select').val();
  jobData['terms'] = $(job).find('.job-terms').find('input').val();

  return jobData;
}

function save() {

  var completelyValid = true;

  $(".job").each(function(index, job) {

    var jobData = generateJobData(job);

    validateInput(jobData, function(error) {
      if(error) {
        $('.error-message').html("<p>Save successful, but produced warning:<br><br>" + error + "</p>");
        $('.error-message').addClass('error');
        $('.error-message').removeClass('hidden success');
        $("#job" + jobData['jobId']).css('background', '#f95757');
        $($("#job" + jobData['jobId']).find('.job-active').find('input')[1]).prop('checked', false);
        jobData['active'] = 0;
        completelyValid = false;
      } else if(completelyValid) {
        $('.error-message').html("<p>Save successful!</p>");
        $('.error-message').addClass('success');
        $('.error-message').removeClass('hidden error');
        // TODO - I'm pretty sure this does nothing.
        $("#job" + jobData['jobId']).css('background', 'white');
        // Hide the message after a few seconds.
        setTimeout(function() {
          $('.error-message').addClass('hidden');
          $('.error-message').removeClass('success');
        }, 5000);
      }
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
          $('.error-message').html("<p>Error: " + error + "</p>");
          $('.error-message').addClass('error');
          $('.error-message').removeClass('hidden success');
          throw error;
        },
        success: function(data) {
        }
      });
    });
  });
}

// ---- RUN JOB ----- //

function generateParams(jobData) {
  var params = "";
  params = 'config_id=' + jobData['config_id'];
  params += "&dest_dir=" + jobData['export_path'];
  var jobTerms = jobData['terms'].split(',');
  jobTerms.forEach(function(element) {
    params += "&term[]=term/" + element;
  });
  params += "&revision_id=" + jobData['revision_id'];

  return params;
}

function getProgress(exportPath) {
  $.ajax({
    url: "../archive/jobprogress",
    type: "GET",
    success: function(response) {
      if(response != 'Export finished') {

        $('.error-message').html(response);
        setTimeout(function() { getProgress(exportPath); }, 1000);
      } else {
        exporting = false;
        var url = "../archive/" + exportPath + "/" + exportPath.substring(0, exportPath.indexOf('/')) + "-" + exportPath.substring(exportPath.indexOf('/') + 1) + "_latest.html";
        var jobHTML = "<p>Export finished: <a href='" + url + "' target='_blank'>" + url + "</a></p> ";
        $('.error-message').html(jobHTML);
      }
    }
  })
}

function runJob(jobId) {
  // Don't let user overload the job exports.
  if (exporting) { return; }

  var jobData = generateJobData($('#job' + jobId));

  validateInput(jobData, function(error) {
    if(error) {
      $('.error-message').html("<p>Error: " + error + "</p>");
      $('.error-message').addClass('error');
      $('.error-message').removeClass('hidden success');
    } else {
      var params = generateParams(jobData);
      console.log(params);
      $.ajax({
        url: "../archive/exportjob",
        type: "GET",
        data: params
      });
      exporting = true;
      $('.error-message').removeClass('hidden error');
      $('.error-message').addClass('success');
      $('.error-message').html('Initializing job export...');
      setTimeout(getProgress, 3000, jobData.export_path);
    }
  });
}

$(document).ready(function() {
  populate();
});
