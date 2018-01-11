/* Helper functions for catalog export configuration */

function generateInputTag(type, value, callback) {
  switch(type) {
    case "h1":
  	case "h2":
      callback("<input class ='section-input' value='" + value + "'></input>");
      break;
  	case "page_content":
  		callback("<input class ='section-input' placeholder='http://wwww.example.com' value='" + value + "'></input>");
  		break;
  	case "custom_text":
  		callback("<textarea class='section-input' value='" + value + "'>" + value + "</textarea>");
  		break;
  	case "course_list":
      $.ajax({
        url: "../export/generateCourseList",
        type: "GET",
        data: {
          catalogId: $('#catalogId').val()
        },
        error: function(error) {
          throw error;
        },
        success: function(data) {
          if(value === '') value = 'unselected';
          var sectionInput = data;
          sectionInput = sectionInput.replace("<select class='section-dropdown' value='unselected'>", "<select class='section-dropdown' value='" + value + "'>");
          sectionInput = sectionInput.replace("<option value='" + value + "'>", "<option value='" + value + "' selected='selected'>");
          callback(sectionInput);
        }
      });
  		break;
    default:
      throw "Invalid input tag type: " + type;
  }
}

function reorderSectionsBasedOnIds(callback) {
  var sections = $('.section').toArray();
  sections.sort(function(a, b) {
    var aId = parseInt(a['id'].substring(7));
    var bId = parseInt(b['id'].substring(7));
    return aId - bId;
  });
  $('#sections-list').empty().append(sections);
  callback();
}

function buildList(jsonData, callback) {
  if (!jsonData || JSON.stringify(jsonData) === "{}") {
    $('#sections-list').append("<li id='begin-message'>Please add a new section to begin</li>");
  } else {
    // Because $.each does not return a promise we have to use this hacky
    // strategy to fire reorderSectionsBasedOnIds() only on the last element.
    var count = $.map(jsonData, function(el) { return el }).length;
    $.each(jsonData, function(key, value) {
      generateInputTag(value.type, value.value, function(result) {
        var li = "<li id='" + key + "' class='section ui-state-default'><div class='position-helper'><span class='move-arrows'><img src='../images/arrow_cross.png'></span></div><span class='section-type'>Type: " + value.type + "</span><span class='section-value'>" + result + "</span><span class='section-controls'><button class='button-section-delete' onclick='deleteSection(this)'>Delete</button><button class='button-section-add' onclick='newSection(this)'>Add Section Below</button></span></li>";
        $('#sections-list').append(li);
        if (!--count) reorderSectionsBasedOnIds(callback);
      });
    });
  }
}

function populate() {
  // Load data.
  $.ajax({
    url: "../export/list",
    type: "GET",
    data: {
      configId: $('#configId').val()
    },
    success: function(data) {
      buildList($.parseJSON(data), function() {
        $( "#sections-list" ).sortable({
          stop: function( event, ui ) {}
        });
        resetEventListeners();
      });
    }
  });

  // hide error message.
  $('#error-message').css('display', 'none');
}

function renameSections() {
  $('.section').toArray().forEach(function(element, index) {
    $(element).attr('id', 'section' + eval(index + 1));
  });
}

function resetEventListeners() {
  // Add event listeners for value changes.
  // I will never understand why javascript doesn't do this for us.
  $('.section-input').change(function() {
    $(this).attr('value', $(this).val());
  });
  $('.section-dropdown').change(function() {
    $(this).attr('value', $(this).val());
  });
  $( "#sections-list" ).on( "sortstop", function( event, ui ) {
    renameSections();
  });
}

function newSection(thisButton) {
  var newSectionHTML = "<li class='section ui-state-default'><select class='select-section-type' onchange='defineSection(this)'><option value='unselected' selected='selected'>Please choose a section type</option><option value='h1'>h1</option><option value='h2'>h2</option><option value='page_content'>External page content</option><option value='custom_text'>Custom text</option><option value='course_list'>Course list</option></select></li>";
  if(!thisButton) {
    if($('#begin-message')) {
      $('#begin-message').remove();
    }
    $('#sections-list').append(newSectionHTML);
  } else {
    var li = $(thisButton).parent().parent();
    $(newSectionHTML).insertAfter(li);
  }
  renameSections();
}

function defineSection(select) {
  var sectionType = $(select).val();
  var li = $(select).parent();

  generateInputTag(sectionType, '', function(result) {
    $(li).html("<div class='position-helper'><span class='move-arrows'><img src='../images/arrow_cross.png'></span></div><span class='section-type'>Type: " + sectionType + "</span><span class='section-value'>" + result + "</span><span class='section-controls'><button class='button-delete' onclick='deleteSection(this)'>Delete</button><button class='button-section-add' onclick='newSection(this)'>Add Section Below</button></span>");

    resetEventListeners();
  });
}

function deleteSection(thisButton) {
  $(thisButton).parent().parent().remove();
  renameSections();
}

function deleteConfig(configId) {
  $('#config-body').append("<div id='warning-box' class='warning-box'><p class='warning'>Are you sure you want to delete this configuration? This cannot be undone. All related revisions will be gone as well.</p><div class='warning-controls'><button class='button-delete' onclick='confirmDelete(" + configId + ")'>Delete</button><button onclick='cancelDelete()'>Cancel</button></div></div>")
}

function confirmDelete(confId) {
  $.ajax({
    url: "../export/deleteconfig",
    type: "POST",
    data: {
      configId: confId
    },
    error: function(error) {
      alert(error);
    },
    success: function(data) {
      location.reload(true);
    }
  });
}

function cancelDelete() {
  $('#warning-box').remove();
}

function reset() {
  $('#sections-list').html('');
  populate();
}

function validateInput(id, type, value, callback) {
  // Strip ""s around value.
  value = value.substring(1, value.length - 1);

  switch(type) {
    case 'h1':
    case 'h2':
      var validCharacters = /^[\/*.?!,;:()&amp;&quot; 0-9a-zA-Z]+$/;
      if (validCharacters.test(value)) {
        callback();
      } else {
        callback("Headers may only contain letters, numbers, .,?!\&;:(), and double quotes (\"\").", id);
      }
      break;
    case 'page_content':
      var validURL = /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/;
      if (validURL.test(value)) {
        callback();
      } else {
        callback("Please enter a valid URL.  Example: http://catalog.middlebury.edu/spanish", id);
      }
      break;
    case 'custom_text':
      var validCharacters = /^[\/*.?!,;:()&amp;&quot; 0-9a-zA-Z]+$/;
      if (validCharacters.test(value)) {
        callback();
      } else {
        callback("Custom text may only contain letters, numbers, .,?!\&;:(), double quotes (\"\") and <a href='http://hammer.middlebury.edu/~afranco/catalog_markup/'>Catalog Markup Language</a>", id);
      }
      break;
    case 'course_list':
      if(value === "unselected") {
        callback('Please select a course from the course list', id);
      } else {
        callback();
      }
      break;
    default:
      callback("Invalid input type.  I have no idea how you accomplished this.", id);
  }
}

function saveJSON() {

  var completelyValid = false;

  var JSONString = "{";

  var sections = $('.section').toArray();
  sections.forEach(function(element, index) {
    var sectionId = element['id'];
    var sectionAsDOMObject = $.parseHTML($(element).html());
    var sectionType = sectionAsDOMObject[1].innerHTML.substring(sectionAsDOMObject[1].innerHTML.indexOf(': ') + 2);
    var sectionValueHTML = sectionAsDOMObject[2].innerHTML.substring(sectionAsDOMObject[2].innerHTML.indexOf(": ") + 2);
    var sectionValue = sectionValueHTML.substring(sectionValueHTML.indexOf('value=') + 6, sectionValueHTML.indexOf('>'));

    validateInput(sectionId, sectionType, sectionValue, function(error, sectionId) {
      if(error) {
        $('#error-message').html("<p>Error: " + error + "</p>");
        $('#error-message').css('display', 'block');
        $("#" + sectionId).css('background', '#f95757');
        completelyValid = false;
        return;
      } else {
        $('#error-message').css('display', 'none');
        JSONString += "\"section" + eval(index + 1) + "\":{\"type\":\"" + sectionType +"\",\"value\":" + sectionValue + "},";
        completelyValid = true;
      }
    });
  });

  if (completelyValid) {
    $('.section').css('background', '#b5c5dd');

    // Remove trailing ,
    JSONString = JSONString.substring(0, JSONString.length - 1);
    JSONString += "}";

    // Ensure valid JSON if no sections are present.
    if(JSONString === "}") JSONString = "{}";

    $.ajax({
      url: "../export/insert",
      type: "POST",
      dataType: 'json',
      data: {
        configId: $('#configId').attr('value'),
        jsonData: JSONString
      },
      error: function(error) {
        alert(error);
      },
      success: function(data) {
        $('#error-message').html("<p>Saved successfully</p>");
        $('#error-message').css('display', 'block');
      }
    });
  }
}

$(document).ready(function() {
  populate();
});
