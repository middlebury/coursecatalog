/* Helper functions for catalog export configuration */

$(document).ready(function() {
  $('.section-input').change(function() {
    $(this).attr('value', $(this).val());
  });
  $('.section-dropdown').change(function() {
    $(this).attr('value', $(this).val());
  });
});

function newSection(previousSection) {
  $("<li class='section'><select class='select-section-type' onchange='defineSection(this)'><option value='unselected' selected='selected'>Please choose a section type</option><option value='h1'>h1</option><option value='h2'>h2</option><option value='page_content'>External page content</option><option value='custom_text'>Custom text</option><option value='course_list'>Course list</option></select></li>").insertAfter(previousSection);
  // Rename sections according to new order.
  $('.section').toArray().forEach(function(element, index) {
    $(element).attr('id', 'section' + eval(index + 1));
  });
}

function defineSection(select) {
  var sectionType = $(select).val();
  var courseSelect = $.ajax({
    url: "../export/generateCourseList",
    type: "GET",
    error: function(error) {
      throw error;
    },
    success: function(data) {
      console.log(data);
    }
  });
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
      jsonData: JSONString
    },
    error: function(error) {
      throw error;
    },
    success: function(data) {
      console.log(data);
    }
  });
}
