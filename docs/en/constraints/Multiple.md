Multiple
========
This is a special field for allowing multiple constraints of the same type on one field. This is mainly for [RequiredIf](RequiredIf.md) constraints, not sure if it will work well with other constraints.

## Usage

```

    $validator = ZenValidator::create();
    $validator->setConstraint(
      $field, 
      new \Milkyway\SS\ZenForms\Constraints\Multiple([
        new \Milkyway\SS\ZenForms\Constraints\RequiredIf($checkboxField, $state = 'checked'),
        new \Milkyway\SS\ZenForms\Constraints\RequiredIf($textField, $state = '[value="Something"]')
      ])
    );

```