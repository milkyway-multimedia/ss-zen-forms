ValidPassword
==========
When you add this constraint, you can restrict a field depending on an SS Validator

## Usage

```

    $validator = ZenValidator::create();
    $validator->setConstraint(
      $field, 
      new \Milkyway\SS\ZenForms\Constraints\ValidPassword($passwordValidator, $strengthScaleFactor = 0.4)
    );

```

## Complexify
If you use the [Complexify Jquery Plugin](https://github.com/danpalmer/jquery.complexify.js/), this constraint automatically plugs into it. 