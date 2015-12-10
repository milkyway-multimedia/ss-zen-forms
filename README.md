Zen Forms
=========

## Still in heavy development
Due to some limitations, this may not work the way I want it, so the API will be changing frequently until I am happy with it. Please use with caution if you must use it at all.

**Zen Forms** is a module that allows changes on Silverstripe Forms and Validators using the decorator pattern. Name stolen from some other awesome Silverstripe Developers @sheadawson and @unclecheese

#### Decorator Pattern?
The decorator pattern works by wrapping other objects in with new methods, simply by passing them as the constructor. You can wrap as many decorators as you like around the same object. It is also great for separating some of your logic from your model, so model classes don't get cluttered.

An example of this within the Silverstripe Framework is the way the SS_ListDecorator works.

You could use this pattern in more than just a form. You could wrap DataObjects who only need temporary functionality that you can easily inject/change without changing the underlying model class.

#### Why not just use extensions?
Extensions are great, but sometimes not every Object will need that extension (for example, I wouldn't need a form within the CMS to use Twitter Bootstrap templates and functionality).

Decorators work on a **per instance** rather than on a **per class** basis, and sometimes that is what you really need.

If you prefer the extensions version, unclecheese offers the **bootstrap-form extension by @unclecheese**.

#### Why does this use zen validator?
The default Silverstripe validator does not support JS validation at this stage, so I use the zen validator by @sheadawson. I have included some constraints that work nicely with the FormFieldBootstrapper decorator.

## Install
Add the following to your composer.json file
```

    "require"          : {
		"milkyway-multimedia/silverstripe-zen-forms": "dev-master"
	}
	
```

## Usage
It is quite simple to use and easy to make your own. For example, if you would like a Form confirming to the Twitter Bootstrap layout scheme, you would do the following when defining your form:

```

    // $form now has FormBootstrapper methods attached, as well as the underlying form methods
    $form = new FormBootstrapper(new Form($controller, $name, $fields, $actions));

```

You can also wrap as many as you need to:

```

    $form = new ModaliseForm(new FormBootstrapper(new Form($controller, $name, $fields, $actions)));

```

Below are some of the decorators that come with the module. There is more documentation for each one in the docs/ folder

### For Forms
1. [FormBoostrapper](docs/en/FormBoostrapper.md): Set your form to use the Twitter Bootstrap Template scheme. This has a lot of code from UncleCheese's module. If you use this, the field list is automatically decorated as well.
2. ModaliseForm: Set your form to display in a Modal.

### For Field Lists
1. FieldListBootstrapper: Set your field list to use the Twitter Bootstrap Template scheme. This has a lot of code from UncleCheese's module. If you use this, the fields are automatically decorated as well.

### For Fields
1. [FormFieldBoostrapper](docs/en/FormFieldBoostrapper.md): Set your field to use the Twitter Bootstrap Template scheme. This has a lot of code from UncleCheese's module.

To make your own, you can simply extend the specific Decorator, the BaseDecorator or implement \Milkyway\ZenForms\Contracts\Decorator.

```

    class TreatDataObjectSpecial extends BaseDecorator {
        // Do something special with the decorator, and you can refer to original object using $this->original();
    }

    class TreatFormFieldSpecial extends FormFieldDecorator {
        // Do something special with the decorator, and you can refer to original object using $this->original();
    }

```

## Constraints
This module automatically pulls in the [Silverstripe ZenValidator](https://github.com/sheadawson/silverstripe-zenvalidator) module, since it adds new constraints.

* [Milkyway\SS\ZenForms\Constraints\RequiredIf](docs/en/constraints/RequiredIf.md)
* [Milkyway\SS\ZenForms\Constraints\ValidPassword](docs/en/constraints/ValidPassword.md)
* [Milkyway\SS\ZenForms\Constraints\ConfirmPassword](docs/en/constraints/ConfirmPassword.md)
* [Milkyway\SS\ZenForms\Constraints\Multiple](docs/en/constraints/Multiple.md)

## ConfirmedPasswordField
When you wrap the ConfirmedPasswordField, it allows you to use the password measure helper and password generator. It only works properly when wrapped with the FormFieldBootstrapper though, and you must include jquery.complexify.js in your scripts for it to work (it is not included with the module).

## License 
* MIT

## Version 
* Version 0.2 (Alpha)

## Contact
#### Milkyway Multimedia
* Homepage: http://milkywaymultimedia.com.au
* E-mail: mell@milkywaymultimedia.com.au
* Twitter: [@mwmdesign](https://twitter.com/mwmdesign "mwmdesign on twitter")