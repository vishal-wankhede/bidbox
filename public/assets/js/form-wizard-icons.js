/**
 * Form Wizard jjkkk
 */
'use strict';

$(function () {
  const select2 = $('.select2'),
    selectPicker = $('.selectpicker');

  // Bootstrap select
  if (selectPicker.length) {
    selectPicker.selectpicker();
    handleBootstrapSelectEvents();
  }

  // Select2
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>');
      $this.select2({
        placeholder: 'Select value',
        dropdownParent: $this.parent()
      });
    });
  }
});

(function () {
  // Function to initialize a wizard
  function initializeWizard(wizardSelector, stepperOptions = { linear: false }) {
    const wizard = document.querySelector(wizardSelector);
    console.log('form-wizard-icons', wizard);
    if (!wizard) return;

    const btnNextList = [].slice.call(wizard.querySelectorAll('.btn-next')),
      btnPrevList = [].slice.call(wizard.querySelectorAll('.btn-prev')),
      btnSubmit = wizard.querySelector('.btn-submit');

    const stepper = new Stepper(wizard, stepperOptions);

    // Next button
    if (btnNextList) {
      btnNextList.forEach(btnNext => {
        btnNext.addEventListener('click', event => {
          console.log(btnNext);
          event.preventDefault(); // Prevent form submission
          stepper.next();
        });
      });
    }

    // Previous button
    if (btnPrevList) {
      btnPrevList.forEach(btnPrev => {
        btnPrev.addEventListener('click', event => {
          event.preventDefault(); // Prevent form submission
          stepper.previous();
        });
      });
    }

    // Submit button
    if (btnSubmit) {
      btnSubmit.addEventListener('click', event => {
        event.preventDefault(); // Allow validation before submission
        const form = btnSubmit.closest('form');
        // Optional: Add validation logic here
        form.submit(); // Trigger form submission
      });
    }
  }

  // Initialize all wizards
  initializeWizard('.wizard-icons-example');
  initializeWizard('.wizard-vertical-icons-example');
  // initializeWizard('.wizard-modern-icons-example');
  initializeWizard('.wizard-modern-vertical-icons-example');
})();
