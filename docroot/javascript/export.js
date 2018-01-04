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
