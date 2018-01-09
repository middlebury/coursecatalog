/* Helper functions for catalog export configuration */

$(document).ready(function() {
  $('.section-input').change(function() {
    $(this).attr('value', $(this).val());
  });
  $('.section-dropdown').change(function() {
    $(this).attr('value', $(this).val());
  });
});

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

  // We do this for every section even if it's not a course list because of
  // asyncronicity issues.
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
      switch(sectionType) {
        case 'h1':
        case 'h2':
        case 'page_content':
          var sectionInput = "<input class ='section-input' value=''></input>";
          break;
        case 'custom_text':
          var sectionInput = "<textarea class='section-input' value=''></textarea>";
          break;
        case 'course_list':
          var sectionInput = data;
          break;
        default:
          throw 'Invalid section type: ' + sectionType;
      }

      $(li).html("<span class='section-type'>Type: " + sectionType + "</span><span class='section-value'>Value: " + sectionInput + "</span><span class='section-controls'><button class='button-section-delete' onclick='deleteSection(this)'>Delete</button><button class='button-section-add' onclick='newSection(this)'>Add Section Below</button></span>");

      // Add event listeners for value changes.
      // I will never understand why javascript doesn't do this for us.
      $('.section-input').on('change', function() {
        $(this).attr('value', $(this).val());
      });
      $('.section-dropdown').on('change', function() {
        $(this).attr('value', $(this).val());
      });
    }
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

  $.ajax({
    url: "../export/add",
    type: "POST",
    dataType: 'json',
    data: {
      catalogId: $('#configId').attr('value'),
      uid: $('#uid').attr('value'),
      udn: $('#udn').attr('value'),
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
