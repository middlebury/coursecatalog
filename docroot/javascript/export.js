
/* Helper functions for catalog export configuration */

// ----- INIT ------ //

function buildList(jsonData, callback) {
  if (!jsonData || JSON.stringify(jsonData) === "{}") {
    newGroup();
  } else {
    $.each(jsonData, function(key, value) {
      var groupName = 'no-group';
      if(key.indexOf('group') !== -1 ) {
        groupName = '#' + key;
        $('#sections-list').append(groupHTML(key, "Unnamed Group", false));
        // Because $.each does not return a promise we have to use this hacky
        // strategy to fire reorderSectionsBasedOnIds() only on the last element.
        var count = $.map(value, function(el) { return el }).length;
        $.each(value, function(sectionKey, sectionValue) {
          if (sectionKey === 'title') {
            giveGroupTitle(groupName, sectionValue);
            --count;
          } else {
            generateInputTag(sectionValue.type, sectionValue.value, function(result) {
              var sectionTypeClass = "'section ui-state-default'";
              if(value.type === 'h1') sectionTypeClass= "'section h1-section ui-state-default'";
              var li = "<li id='" + sectionKey + "' class=" + sectionTypeClass + "><div class='position-helper'><span class='move-arrows'><img src='../images/arrow_cross.png'></span></div><span class='section-type'>Type: " + sectionValue.type + "</span><span class='section-value'>" + result + "</span><span class='section-controls'><button class='button-delete' onclick='deleteSection(this)'>Delete Section</button><button class='button-section-add' onclick='newSection(this)'>Add Section Below</button></span></li>";
              $(groupName).find(".section-group").append(li);
              if (!--count) reorderSectionsBasedOnIds(groupName, callback);
            });
          }
        });
      } else {
        throw "Invalid JSON: " + jsonData;
      }
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
        renameGroups();
        resetEventListeners();
      });
    }
  });

  // hide error message.
  $('.error-message').addClass('hidden');
}

// ----- HELPERS ------- //

function generateInputTag(type, value, callback) {
  switch(type) {
    case "h1":
      callback("<input class='section-input' placeholder='Please choose a title' value='" + value + "'></input>");
      break;
  	case "h2":
      callback("<input class='section-input' value='" + value + "'></input>");
      break;
  	case "page_content":
  		callback("<input class='section-input' placeholder='http://wwww.example.com' value='" + value + "'></input>");
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
          if (value === '') value = 'unselected';
          // Course filters.
          if (value.indexOf(',') !== -1) {
            var selection = value.substring(0, value.indexOf(','));
            var filters = value.substring(value.indexOf(',') +1);
            var filterHTML = "<br><span class='course-filters'>Course #'s to exclude: <input class='filter-input' name='filtering' placeholder='Separate with commas' value='" + filters + "'></input></span>"
          } else {
            var selection = value;
            var filterHTML = "<br><span class='course-filters'>Course #'s to exclude: <input class='filter-input' name='filtering' placeholder='Separate with commas'></input></span>"
          }
          var sectionInput = data;
          sectionInput = sectionInput.replace("<select class='section-dropdown' value='unselected'>", "<select class='section-dropdown' value='" + selection + "'>");
          sectionInput = sectionInput.replace("<option value='" + selection + "'>", "<option value='" + selection + "' selected='selected'>");
          sectionInput += filterHTML;
          callback(sectionInput);
        }
      });
  		break;
    default:
      throw "Invalid input tag type: " + type;
  }
}

function reorderSectionsBasedOnIds(groupId, callback) {
  var sections = $(groupId).find('.section').toArray();
  sections.sort(function(a, b) {
    var aId = parseInt(a['id'].substring(7));
    var bId = parseInt(b['id'].substring(7));
    return aId - bId;
  });
  $(groupId).find('.section-group').empty().append(sections);
  callback();
}

function resetEventListeners() {
  $( "#sections-list" ).sortable({
    stop: function( event, ui ) {}
  });
  $( ".group" ).find('.section-group').sortable({
    stop: function( event, ui ) {}
  });

  // Add event listeners for value changes.
  // I will never understand why javascript doesn't do this for us.
  $('.section-input').change(function() {
    $(this).attr('value', $(this).val());
    if ($(this).parent().parent().html().indexOf('Type: h1') !== -1) {
      $('.new').removeClass('new');
      giveGroupTitle(this, $(this).val());
      renameGroups();
    }
  });
  $('.filter-input').change(function() {
    $(this).attr('value', $(this).val());
  });
  $('.section-dropdown').change(function() {
    $(this).attr('value', $(this).val());
  });
  $( "#sections-list" ).on( "sortstop", function( event, ui ) {
    // TODO - is there really any reason to rename groups and sections?  As long
    // as their ids are unique do they really need to be in order?
    renameGroups();
    renameSections();
  });
  $( ".group" ).on( "sortstop", function( event, ui ) {
    // TODO - is there really any reason to rename groups and sections?  As long
    // as their ids are unique do they really need to be in order?
    renameGroups();
    renameSections();
  });
}

function reset() {
  $('#sections-list').html('');
  populate();
  $('.error-message').removeClass('success error');
  $('.error-message').addClass('hidden');
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
      console.log(value);
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
  var completelyValid = true;
  var JSONString = "{";

  var groups = $('.group').toArray();
  groups.forEach(function(element, index) {
    var groupId = element['id'];
    JSONString += "\"" + groupId + "\":{";

    var groupTitle = $(element).find('.group-title')[0].innerHTML;
    JSONString += "\"title\":\"" + groupTitle + "\",";

    var sections = $(element).find('.section').toArray();
    sections.forEach(function(element, index) {
      if (!completelyValid) return;

      var sectionId = element['id'];
      var sectionAsDOMObject = $.parseHTML($(element).html());
      // TODO - We can do better than using substrings to get this info.
      var sectionType = sectionAsDOMObject[1].innerHTML.substring(sectionAsDOMObject[1].innerHTML.indexOf(': ') + 2);
      var sectionValueHTML = sectionAsDOMObject[2].innerHTML;
      var sectionValue = sectionValueHTML.substring(sectionValueHTML.indexOf("value=") + 6, sectionValueHTML.indexOf('>'));

      if(sectionType === 'course_list' && $(element).find('.filter-input').val()) {
        var filters = $(element).find('.filter-input').val();

        // Remove trailing "
        sectionValue = sectionValue.substring(0, sectionValue.length - 1);
        sectionValue += "," + filters + "\"";
      }

      validateInput(sectionId, sectionType, sectionValue, function(error, sectionId) {
        if(error) {
          $('.error-message').html("<p>Error: " + error + "</p>");
          $('.error-message').addClass('error');
          $('.error-message').removeClass('hidden success');
          $("#" + sectionId).css('background', '#f95757');
          completelyValid = false;
        } else {
          $('.error-message').addClass('hidden');
          JSONString += "\"section" + eval(index + 1) + "\":{\"type\":\"" + sectionType +"\",\"value\":" + sectionValue + "},";
          completelyValid = true;
        }
      });
    });

    // Remove trailing ,
    JSONString = JSONString.substring(0, JSONString.length - 1);

    JSONString += "},";
  });

  if (completelyValid) {
    $('.section').css('background', '#b5c5dd');

    // Remove trailing ,
    JSONString = JSONString.substring(0, JSONString.length - 1);
    JSONString += "}";

    // Ensure valid JSON if no sections are present.
    if(JSONString === "}") JSONString = "{}";

    console.log(JSONString);

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
        $('.error-message').html("<p>Saved successfully</p>");
        $('.error-message').removeClass('hidden error');
        $('.error-message').addClass('success');
      }
    });
  }
}

// ------ CONFIGS ------- //

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

// ------ GROUPS ------- //

function groupHTML(id, title, visible) {
  if (visible) {
    return "<li id='" + id + "' class='group ui-state-default'><div class='position-helper'><span class='move-arrows'><img src='../images/arrow_cross.png'></span></div><span class='group-title'>" + title + "</span><div class='group-toggle-description' onclick='toggleGroup(this)'>show/hide</div><div class='group-controls visible'><button class='button-delete' onclick='deleteGroup(this)'>Delete group</button><button class='button' onclick='newGroup(this)'>Add group below</button></div><ul class='section-group visible'></ul></li>";
  } else {
    return "<li id='" + id + "' class='group ui-state-default'><div class='position-helper'><span class='move-arrows'><img src='../images/arrow_cross.png'></span></div><span class='group-title'>" + title + "</span><div class='group-toggle-description' onclick='toggleGroup(this)'>show/hide</div><div class='group-controls'><button class='button-delete' onclick='deleteGroup(this)'>Delete group</button><button class='button' onclick='newGroup(this)'>Add group below</button></div><ul class='section-group'></ul></li>";
  }
}

function renameGroups() {
  $('.group').toArray().forEach(function(element, index) {
    $(element).attr('id', 'group' + eval(index + 1));
    if(index % 2 === 0) {
      $(element).removeClass('odd');
      $(element).addClass('even');
    } else {
      $(element).removeClass('even');
      $(element).addClass('odd');
    }
  });
}

function giveGroupTitle(selector, value) {
  $(selector).closest('.group').find('.group-title')[0].innerHTML = value;
}

function toggleGroup(button) {
  $(button).closest('.group').find('.section-group').toggleClass('visible');
  $(button).closest('.group').find('.group-controls').toggleClass('visible');
}

function newGroup(thisButton) {

  // Only allow user to create one group at a time.
  // TODO - display error message if user tries to create many at once.
  if ($('.new').length) return;

  var newGroupHTML = groupHTML('temp', 'Please fill out an h1 section to give this group a name', true);

  if(!thisButton) {
    if($('#begin-message')) {
      $('#begin-message').remove();
    }
    $('#sections-list').append(newGroupHTML);
  } else {
    var li = $(thisButton).parent().parent();
    $(newGroupHTML).insertAfter(li);
  }

  $('#temp').addClass('new');

  // Create h1 section.
  newGroupFirstSection();
}

function deleteGroup(thisButton) {
  $(thisButton).closest('.group').remove();
  if ($('#sections-list').find('.group').length === 0) {
    newGroup();
  }

  renameGroups();
}

// ------ SECTIONS ----- //

function renameSections() {
  $('.section').toArray().forEach(function(element, index) {
    $(element).attr('id', 'section' + eval(index + 1));
  });
}

function newGroupFirstSection() {
  generateInputTag('h1', '', function(input) {
    var newSectionHTML = "<li class='section h1-section ui-state-default'>" + fullSectionHTML('h1', input) + "</li>";
    $('.new').children("ul").append(newSectionHTML);
    resetEventListeners();
  });
}

function fullSectionHTML(type, value) {
  return "<div class='position-helper'><span class='move-arrows'><img src='../images/arrow_cross.png'></span></div><span class='section-type'>Type: " + type + "</span><span class='section-value'>" + value + "</span><span class='section-controls'><button class='button-delete' onclick='deleteSection(this)'>Delete Section</button><button class='button-section-add' onclick='newSection(this)'>Add Section Below</button></span>";
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
    $(li).html(fullSectionHTML(sectionType, result));

    resetEventListeners();
  });
}

function deleteSection(thisButton) {
  if ($(thisButton).closest('.group').find('.section').length === 1) {
    deleteGroup(thisButton);
  } else {
    $(thisButton).closest('.section').remove();
  }
  renameSections();
}

$(document).ready(function() {
  populate();
});
