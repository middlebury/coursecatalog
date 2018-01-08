/* Helper functions for catalog export configuration */

$(document).ready(function() {
  $('.section-input').change(function() {
    $(this).attr('value', $(this).val());
  });
});

function saveJSON() {

  var JSONString = "{";
  var sections = $('.section').toArray();

  sections.forEach(function(element, index) {
    var sectionAsDOMObject = $.parseHTML($(element).html());
    var sectionType = sectionAsDOMObject[0].innerHTML.substring(sectionAsDOMObject[0].innerHTML.indexOf(": ") + 2);
    var sectionValueHTML = sectionAsDOMObject[1].innerHTML.substring(sectionAsDOMObject[1].innerHTML.indexOf(": ") + 2);
    console.log(sectionAsDOMObject);

    // Extract the value based on sectionType.
    switch(sectionType) {
      case 'h1':
      case 'h2':
      case 'page_content':
      case 'custom_text':
        var sectionValue = sectionValueHTML.substring(sectionValueHTML.indexOf('value=') + 6, sectionValueHTML.indexOf('>'));
        break;
      case 'course_list':
        var selectedIndex = sectionValueHTML.indexOf('selected=');
        var valueIndex = selectedIndex - 2;
        /* To avoid getting stuck in an infinite loop, we cap the number of
         * iterations.  If we have reached that max, then something clearly went
         * wrong anyway */
        var foundIndex = false;
        while(!foundIndex && selectedIndex - valueIndex < 30) {
          valueIndex--;
          if(sectionValueHTML.substring(valueIndex, valueIndex + 6) === 'value=') {
            var sectionValue = sectionValueHTML.substring(valueIndex + 6, selectedIndex - 1);
            foundIndex = true;
          }
        }
        break;
      default:
        throw 'Invalid section type: ' + sectionType;
    }

    // Construct JSON.
    JSONString += "\"section" + eval(index + 1) + "\":{\"type\":\"" + sectionType +"\",\"value\":" + sectionValue + "}," ;
  });

  // Remove trailing ,
  JSONString = JSONString.substring(0, JSONString.length - 1);

  JSONString += "}";

  console.log(JSONString);
}
