FormFieldBootstrapper
=====================
This wraps a field in a FormFieldBootstrapper decorator, which uses new Holder Templates and allows access to some new methods

## Usage

```

    // $field now has FormFieldBootstrapper methods attached, as well as the underlying FormField methods
    $field = FormFieldBootstrapper::create(TextField::create($name));

```

## Methods
* $field->hideIf($dependsOnField, 'checked') : This will hide a field depending on the state of the other field. The state is a jQuery filter. Please check the [RequiredIf Constraint](constraints/RequiredIf.md) for more information on what states you can use.
* $field->showIf($dependsOnField, 'checked') : This will show a field depending on the state of the other field. The state is a jQuery filter. Please check the [RequiredIf Constraint](constraints/RequiredIf.md) for more information on what states you can use.
* $field->addHolderClass($class) : Add a class to the holder of a field (in bootstrap, this is the form-group)
* $field->removeHolderClass($class) : Remove a class to the holder of a field (in bootstrap, this is the form-group)
* $field->setHolderAttribute($attribute, $value) : Set an attribute on the holder (in bootstrap, this is the form-group)
* $field->removeHolderAttribute($attribute) : Remove an attribute from the holder (in bootstrap, this is the form-group)
* $field->addLabelClass($class) : Add a class to the label of a field
* $field->removeLabelClass($class) : Remove a class to the label of a field
* $field->setLabelAttribute($attribute, $value) : Set an attribute on the label
* $field->removeLabelAttribute($attribute) : Remove an attribute from the label