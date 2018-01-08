/* Helper functions for catalog export configuration */

// Update the input type of a section when user changes input type.
function updateSection(sectionId) {

  // retrieve the newly selected Section Type
  var newType = $(sectionId).find('.section-type').find('select').val();

  // retrieve this section's input value
  var currentValue = $(sectionId).find('.section-value').find('input').val();

  // there will be no value if the input is a textarea so we must select differently.
  if (currentValue === undefined) {
    currentValue = $(sectionId).find('.section-value').find('textarea').val();
  }

  alert(currentValue);

  // set the html of this section-value span to a new input field based on the new type.
  // put the old value into the new type if applicable.

}

function saveJSON() {

  var JSONString = "{";
  var sections = $('.section').toArray();

  sections.forEach(function(element) {
    var sectionAsDOMObject = $.parseHTML($(element).html());
    //console.log(sectionAsDOMObject);
    var sectionType = sectionAsDOMObject[0].innerHTML.substring(sectionAsDOMObject[0].innerHTML.indexOf(": ") + 2);
    var sectionValueHTML = sectionAsDOMObject[1].innerHTML.substring(sectionAsDOMObject[1].innerHTML.indexOf(": ") + 2);
    //console.log(sectionValueHTML);
    // Extract the value based on sectionType.
    switch(sectionType) {
      case 'h1':
      case 'h2':
      case 'page_content':
      case 'custom_text':
        var sectionValue = sectionValueHTML.substring(sectionValueHTML.indexOf('value=') + 6, sectionValueHTML.indexOf('>'));
        break;
      case 'course_list':
        var sectionValue = 'not yet';
        break;
      default:
        throw 'Invalid section type: ' + sectionType;
    }
    console.log(sectionValue);
  });

  JSONString += "}";

  console.log(JSONString);
}
