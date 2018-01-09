/* Helper functions for catalog export configuration */

function generateInputTag(type, value, callback) {
  switch(type) {
    case "h1":
  	case "h2":
  	case "page_content":
  		callback("<input class ='section-input' value='" + value + "'></input>");
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

function populateList(jsonData, callback) {
  if (!jsonData) {
    $('#sections-list').append("<li id='begin-message'>Please add a new section to begin</li>");
  } else {
    // Because $.each does not return a promise we have to use this hacky
    // strategy to fire reorderSectionsBasedOnIds() only on the last element.
    var count = $.map(jsonData, function(el) { return el }).length;
    $.each(jsonData, function(key, value) {
      generateInputTag(value.type, value.value, function(result) {
        var li = "<li id='" + key + "' class='section'><span class='section-type'>Type: " + value.type + "</span><span class='section-value'>Value: " + result + "</span><span class='section-controls'><button class='button-section-delete' onclick='deleteSection(this)'>Delete</button><button class='button-section-add' onclick='newSection(this)'>Add Section Below</button></span></li>";
        $('#sections-list').append(li);
        if (!--count) reorderSectionsBasedOnIds(callback);
      });
    });
  }
}

function renameSections() {
  $('.section').toArray().forEach(function(element, index) {
    $(element).attr('id', 'section' + eval(index + 1));
  });
}

function newSection(thisButton) {
  var newSectionHTML = "<li class='section'><select class='select-section-type' onchange='defineSection(this)'><option value='unselected' selected='selected'>Please choose a section type</option><option value='h1'>h1</option><option value='h2'>h2</option><option value='page_content'>External page content</option><option value='custom_text'>Custom text</option><option value='course_list'>Course list</option></select></li>";
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
    $(li).html("<span class='section-type'>Type: " + sectionType + "</span><span class='section-value'>Value: " + result + "</span><span class='section-controls'><button class='button-section-delete' onclick='deleteSection(this)'>Delete</button><button class='button-section-add' onclick='newSection(this)'>Add Section Below</button></span>");

    // Add event listeners for value changes.
    // I will never understand why javascript doesn't do this for us.
    $('.section-input').change(function() {
      $(this).attr('value', $(this).val());
    });
    $('.section-dropdown').change(function() {
      $(this).attr('value', $(this).val());
    });
  });
}

function deleteSection(thisButton) {
  $(thisButton).parent().parent().remove();
  renameSections();
}

function saveJSON() {

  var JSONString = "{";

  var sections = $('.section').toArray();
  sections.forEach(function(element, index) {
    var sectionAsDOMObject = $.parseHTML($(element).html());
    var sectionType = sectionAsDOMObject[0].innerHTML.substring(sectionAsDOMObject[0].innerHTML.indexOf(": ") + 2);
    var sectionValueHTML = sectionAsDOMObject[1].innerHTML.substring(sectionAsDOMObject[1].innerHTML.indexOf(": ") + 2);
    var sectionValue = sectionValueHTML.substring(sectionValueHTML.indexOf('value=') + 6, sectionValueHTML.indexOf('>'));
    JSONString += "\"section" + eval(index + 1) + "\":{\"type\":\"" + sectionType +"\",\"value\":" + sectionValue + "}," ;
  });

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
      throw error;
    },
    success: function(data) {
      console.log('Saved successfully');
    }
  });
}

$(document).ready(function() {

  // Load data.
  $.ajax({
    url: "../export/list",
    type: "GET",
    data: {
      configId: $('#configId').val()
    },
    success: function(data) {
      populateList($.parseJSON(data), function() {
        $('.section-input').change(function() {
          $(this).attr('value', $(this).val());
        });
        $('.section-dropdown').change(function() {
          $(this).attr('value', $(this).val());
        });
      });
    }
  });
});
