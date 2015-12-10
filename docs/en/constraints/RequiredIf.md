RequiredIf
==========
When you add this constraint, you can restrict a field depending on the state of another field.

NOTE: **This will pass if any states are true**. Use RequiredIfStrict if all states must be true.

## Usage

```

    $validator = ZenValidator::create();
    $validator->setConstraint(
      $field, 
      new \Milkyway\SS\ZenForms\Constraints\RequiredIf($checkboxField, $state = ':checked')
    );

```

## States
The states are jquery selectors. You should be able to use any of the selectors at: https://api.jquery.com/category/selectors/.

This module includes new states as well:

* :blank - Checks if field is blank
* :filled - Checks if field has any value
* :unchecked - Checks if field is not checked (valid for checkboxes and options only)
* :in-list(1,2,3) - Check if field is in a list of comma separated values
* :not-in-list(1,2,3) - Check if field is not in a list of comma separated values
* :has-value(test) - Checks if field value is equal to parameter (in this case, test)
* :not-value(test) - Checks if field value is not equal to parameter (in this case, test)
* :less-than(2) - Checks if field value is less than parameter
* :less-than-or-equal-to(2) - Checks if field value is less than or equal to parameter
* :greater-than(2) - Checks if field value is greater than parameter
* :greater-than-or-equal-to(2) - Checks if field value is greater than or equal to parameter
* :starts-with(test) - Checks if field value starts with parameter
* :ends-with(test) - Checks if field value ends with parameter
* :between(1-40) - Checks if field value is between parameters separated by -
* :selected-at-least(2) - Checks if field has at least parameter selected
* :selected-less-than(2) - Checks if field has less than parameter selected
* :valid - Checks if field validates
* :invalid - Checks if field does not validate