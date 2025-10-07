'use strict';
// just to check
(function () {
  const select2 = $('.select2'),
    selectPicker = $('.selectpicker');

  // Wizard Validation
  // --------------------------------------------------------------------
  const wizardValidation = document.querySelector('#wizard-modern');
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
              value: 0,
              message: 'Impressions must be greater than 0'
            }
          }
        },
        ctr: {
          validators: {
            callback: {
              message: 'The CTR percentage is required',
              callback: function (input) {
                const channel = wizardValidationFormStep1.querySelector('[name="channel"]').value;
                // Skip validation if channel is Connected TV Advertising
                if (channel === 'Connected TV Advertising') {
                  return true; // valid even if empty
                }
                // Otherwise enforce numeric + range
                return input.value !== '' && !isNaN(input.value) && input.value >= 0 && input.value <= 100;
              }
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
      // Custom validation for creative percentages
      const totalPercentage = parseFloat(document.getElementById('total-percentage').innerText);
      if (totalPercentage !== 100) {
        document.getElementById('percentage-warning').style.display = 'block';
        return;
      }
      // Submit the form
      wizardValidationForm.submit();
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
