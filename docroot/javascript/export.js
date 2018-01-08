/* Helper functions for catalog export configuration */

$(document).ready(function() {
  $('.section-input').change(function() {
    $(this).attr('value', $(this).val());
  });
  $('.section-dropdown').change(function() {
    //alert(this.selectedIndex);
    //$("option:selected", this).prop('selected', false);
    $(this).attr('value', $(this).val());
    //var test = $(this + "option[value=" + this.value + "]", this);
    // .attr("selected", true).siblings()
    // .removeAttr("selected");
    $(this).val()
  });
});

function saveJSON() {

  var JSONString = "{";
  var sections = $('.section').toArray();

  sections.forEach(function(element, index) {
    var sectionAsDOMObject = $.parseHTML($(element).html());
    var sectionType = sectionAsDOMObject[0].innerHTML.substring(sectionAsDOMObject[0].innerHTML.indexOf(": ") + 2);
    var sectionValueHTML = sectionAsDOMObject[1].innerHTML.substring(sectionAsDOMObject[1].innerHTML.indexOf(": ") + 2);
    var sectionValue = sectionValueHTML.substring(sectionValueHTML.indexOf('value=') + 6, sectionValueHTML.indexOf('>'));

    // Construct JSON.
    JSONString += "\"section" + eval(index + 1) + "\":{\"type\":\"" + sectionType +"\",\"value\":" + sectionValue + "}," ;
  });

  // Remove trailing ,
  JSONString = JSONString.substring(0, JSONString.length - 1);

  JSONString += "}";

  console.log(JSONString);
}
