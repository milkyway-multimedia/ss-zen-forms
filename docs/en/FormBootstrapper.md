FormBootstrapper
================
This wraps all your fields in the FormFieldBootstrapper decorator, and uses a new template.

## Usage

```

    // $form now has FormBootstrapper methods attached, as well as the underlying form methods
    $form = FormBootstrapper::create(Form::create($controller, $name, $fields, $actions));

```

## Methods

* $form->ajaxify() : Make the form submit via AJAX. You can listen to events via jQuery to ensure delivery etc. Events thrown are:

    * 'submits.ajax.forms.mwm': Trigger on submit (when button is clicked, no processing done). Arguments are formAction and buttonClicked.
    * 'submitting.ajax.forms.mwm': Form is processing, and has passed all validation. Arguments are formAction and buttonClicked.
    * 'successful.ajax.forms.mwm': Form has been processed succesfully. Arguments are formAction, buttonClicked, processedResponse, and raw arguments (usually an object with keys response, status and xhr)
    * 'error.ajax.forms.mwm': Form has returned with an error. Arguments are formAction, buttonClicked, processedResponse, and raw arguments (usually an object with keys response, status and xhr)
    * 'submitted.ajax.forms.mwm': Form has been processed (executes on success or error). Arguments are formAction, buttonClicked, processedResponse, and raw arguments (usually an object with keys status and xhr)
    * 'mwm::refreshed': Form has been replaced with AJAX