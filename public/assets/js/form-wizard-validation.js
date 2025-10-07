'use strict';

(function () {
  const select2 = $('.select2'),
    selectPicker = $('.selectpicker');

  // Wizard Validation
  // --------------------------------------------------------------------
  const wizardValidation = document.querySelector('.wizard-modern');
  if (typeof wizardValidation !== undefined && wizardValidation !== null) {
    // Wizard form
    const wizardValidationForm = wizardValidation.querySelector('form');
    // Wizard steps
    const wizardValidationFormStep1 = wizardValidationForm.querySelector('#account-details-vertical-modern');
    const wizardValidationFormStep2 = wizardValidationForm.querySelector('#personal-info-vertical-modern');
    const wizardValidationFormStep3 = wizardValidationForm.querySelector('#demographic-info-vertical-modern');
    const wizardValidationFormStep4 = wizardValidationForm.querySelector('#dynamic-filters-vertical-modern');
    const wizardValidationFormStep5 = wizardValidationForm.querySelector('#creative-vertical-modern');
    // Wizard next/prev buttons
    const wizardValidationNext = [].slice.call(wizardValidationForm.querySelectorAll('.btn-next'));
    const wizardValidationPrev = [].slice.call(wizardValidationForm.querySelectorAll('.btn-prev'));

    const validationStepper = new Stepper(wizardValidation, {
      linear: true
    });

    // Step 1: Campaign Details
    const FormValidation1 = FormValidation.formValidation(wizardValidationFormStep1, {
      fields: {
        campaign_name: {
          validators: {
            notEmpty: {
              message: 'The campaign name is required'
            },
            stringLength: {
              min: 3,
              max: 100,
              message: 'The campaign name must be between 3 and 100 characters'
            }
          }
        },
        brand_name: {
          validators: {
            notEmpty: {
              message: 'The brand name is required'
            },
            stringLength: {
              min: 3,
              max: 100,
              message: 'The brand name must be between 3 and 100 characters'
            }
          }
        },
        channel: {
          validators: {
            notEmpty: {
              message: 'Please select a channel'
            }
          }
        },
        brand_logo: {
          validators: {
            file: {
              extension: 'png,jpg,svg',
              type: 'image/png,image/jpeg,image/svg+xml',
              message: 'Please upload a valid image file (PNG, JPG, SVG)'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.col-sm-6, .col-sm-12'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      },
      init: instance => {
        instance.on('plugins.message.placed', function (e) {
          if (e.element.parentElement.classList.contains('input-group')) {
            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
          }
        });
      }
    }).on('core.form.valid', function () {
      console.log('form-wizard-validation', 'step 1 done');
      validationStepper.next();
    });

    // Step 2: Projection Info
    const FormValidation2 = FormValidation.formValidation(wizardValidationFormStep2, {
      fields: {
        client_view_name: {
          validators: {
            notEmpty: {
              message: 'The client view name is required'
            },
            stringLength: {
              min: 3,
              max: 100,
              message: 'The client view name must be between 3 and 100 characters'
            }
          }
        },
        impressions: {
          validators: {
            notEmpty: {
              message: 'The impressions field is required'
            },
            integer: {
              message: 'Please enter a valid number of impressions'
            },
            greaterThan: {
              min: 0,
              message: 'Impressions must be greater than 0'
            }
          }
        },
        ctr: {
          validators: {
            notEmpty: {
              message: 'The CTR percentage is required'
            },
            numeric: {
              message: 'Please enter a valid number'
            },
            between: {
              min: 1,
              max: 100,
              message: 'CTR must be between 0 and 100'
            }
          }
        },
        vtr: {
          validators: {
            numeric: {
              message: 'Please enter a valid number'
            },
            between: {
              min: 0,
              max: 100,
              message: 'VTR must be between 0 and 100'
            }
          }
        },
        budget_type: {
          validators: {
            notEmpty: {
              message: 'The budget type is required'
            }
          }
        },
        total_budget: {
          validators: {
            notEmpty: {
              message: 'The budget is required'
            },
            numeric: {
              message: 'Please enter a valid number'
            },
            greaterThan: {
              min: 1,
              message: 'budget must be greater than 0'
            }
          }
        },
        start_date: {
          validators: {
            notEmpty: {
              message: 'The start date is required'
            },
            date: {
              format: 'YYYY-MM-DD',
              message: 'Please enter a valid date'
            }
          }
        },
        end_date: {
          validators: {
            notEmpty: {
              message: 'The end date is required'
            },
            date: {
              format: 'YYYY-MM-DD',
              message: 'Please enter a valid date'
            },
            callback: {
              message: 'End date must be after start date',
              callback: function (input) {
                const startDate = wizardValidationFormStep2.querySelector('[name="start_date"]').value;
                const endDate = input.value;
                if (startDate && endDate) {
                  return new Date(endDate) >= new Date(startDate);
                }
                return true;
              }
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.col-md-3, .col-sm-4'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      var dateTotal = document.getElementById('dateTotal').textContent;
      if (dateTotal != 100) {
        Swal.fire({
          icon: 'error',
          title: 'error',
          text: `Date percentages must total exactly 100% to save.`
        });
        return;
      }
      validationStepper.next();
    });

    // Step 3: Demographic Info
    const FormValidation3 = FormValidation.formValidation(wizardValidationFormStep3, {
      fields: {
        master: {
          validators: {
            notEmpty: {
              message: 'Please select a master'
            }
          }
        },
        'locations[]': {
          validators: {
            notEmpty: {
              message: 'Please select at least one location'
            }
          }
        },
        'gender[]': {
          validators: {
            notEmpty: {
              message: 'Please select at least one gender'
            }
          }
        },
        'gender_percentages[]': {
          validators: {
            callback: {
              message: 'Gender percentages must sum to 100%',
              callback: function (input) {
                const inputs = document.querySelectorAll('#gender-percentages .percentage-input');
                if (inputs.length === 0) {
                  return true; // No percentages to validate if no genders selected
                }
                let total = 0;
                inputs.forEach(input => {
                  const value = parseInt(input.value) || 0;
                  total += Math.max(0, Math.min(100, value)); // Clamp between 0 and 100
                });
                console.log(total);
                return total === 100;
              }
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.col-12'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      const inputs = document.querySelectorAll('#gender-percentages .percentage-input');
      const hasGenders = document.querySelector('#gender').selectedOptions.length > 0;
      if (hasGenders) {
        let total = 0;
        inputs.forEach(input => {
          const value = parseInt(input.value) || 0;
          total += Math.max(0, Math.min(100, value));
        });
        if (total !== 100) {
          const warning = document.getElementById('gender-percentage-warning');
          if (warning) {
            warning.style.display = 'block';
          }
          return;
        }
      }
      validationStepper.next();
    });

    // Step 4: Dynamic Filters (No mandatory fields, so minimal validation)
    const FormValidation4 = FormValidation.formValidation(wizardValidationFormStep4, {
      fields: {},
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.col-12'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      validationStepper.next();
    });

    // Step 5: Creatives Details
    const FormValidation5 = FormValidation.formValidation(wizardValidationFormStep5, {
      fields: {
        'creative_files[]': {
          validators: {
            callback: {
              message: 'Please click the Upload button to add selected creative files',
              callback: function (input) {
                const fileInput = document.getElementById('creative-file');
                const creativeContainer = document.getElementById('creative-details-container');
                const hasFiles = fileInput.files.length > 0;
                const hasCreatives = creativeContainer.querySelectorAll('.row').length > 0;
                // If files are selected but no rows are added, validation fails
                if (hasFiles && !hasCreatives) {
                  return false;
                }
                // If no files are selected, require at least one creative row
                if (!hasFiles && !hasCreatives) {
                  return false;
                }
                return true;
              }
            },
            file: {
              extension: 'png,jpg,gif,mp4',
              type: 'image/png,image/jpeg,image/gif,video/mp4',
              message: 'Please upload valid creative files (PNG, JPG, GIF, MP4)'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          eleValidClass: '',
          rowSelector: '.col-12'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      const totalPercentage = parseFloat(document.getElementById('total-percentage').innerText);
      const creativeContainer = document.getElementById('creative-details-container');
      const hasCreatives = creativeContainer.querySelectorAll('.row').length > 0;

      // Check if total percentage is 100 when creatives are added
      if (hasCreatives && totalPercentage !== 100) {
        document.getElementById('percentage-warning').style.display = 'block';
        return;
      }

      // Prevent submission if no creatives are added
      if (!hasCreatives) {
        document
          .getElementById('creative-file')
          .parentElement.querySelector('.fv-plugins-message-container').innerText =
          'Please upload at least one creative file.';
        return;
      }

      // All good — submit the form
      wizardValidationForm.submit();
    });

    // Prevent form submission on direct submit button click
    wizardValidationForm.addEventListener('submit', function (event) {
      event.preventDefault();
      event.stopPropagation();
      FormValidation5.validate().then(function (status) {
        if (status === 'Valid') {
          const totalPercentage = parseFloat(document.getElementById('total-percentage').innerText);
          const creativeContainer = document.getElementById('creative-details-container');
          const hasCreatives = creativeContainer.querySelectorAll('.row').length > 0;

          if (hasCreatives && totalPercentage === 100) {
            wizardValidationForm.submit();
          } else {
            if (!hasCreatives) {
              document
                .getElementById('creative-file')
                .parentElement.querySelector('.fv-plugins-message-container').innerText =
                'Please upload at least one creative file.';
            } else if (totalPercentage !== 100) {
              document.getElementById('percentage-warning').style.display = 'block';
            }
          }
        }
      });
    });

    // Bootstrap Select
    if (selectPicker.length) {
      selectPicker.each(function () {
        var $this = $(this);
        $this.selectpicker().on('change', function () {
          FormValidation3.revalidateField('master');
        });
      });
    }

    // Select2
    if (select2.length) {
      select2.each(function () {
        var $this = $(this);
        select2Focus($this);
        $this.wrap('<div class="position-relative"></div>');
        $this
          .select2({
            placeholder: $this.attr('id') === 'gender' ? 'Select gender' : 'Select locations',
            dropdownParent: $this.parent()
          })
          .on('change', function () {
            if ($this.attr('id') === 'gender') {
              FormValidation3.revalidateField('gender[]');
            } else {
              FormValidation3.revalidateField('locations[]');
            }
          });
      });
    }

    // Navigation
    wizardValidationNext.forEach(item => {
      item.addEventListener('click', event => {
        switch (validationStepper._currentIndex) {
          case 0:
            FormValidation1.validate();
            break;
          case 1:
            FormValidation2.validate();
            break;
          case 2:
            FormValidation3.validate();
            break;
          case 3:
            FormValidation4.validate();
            break;
          case 4:
            FormValidation5.validate();
            break;
          default:
            break;
        }
      });
    });

    wizardValidationPrev.forEach(item => {
      item.addEventListener('click', event => {
        validationStepper.previous();
      });
    });
  }
})();
