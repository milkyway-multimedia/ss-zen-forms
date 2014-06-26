Zen Forms
======
**Zen Forms** is a module that allows changes on Silverstripe Forms and Validators using the decorator pattern. Name stolen from some other awesome Silverstripe Developers @sheadawson and @unclecheese

#### Decorator Pattern?
The decorator pattern works by wrapping other objects in with new methods, simply by passing them as the constructor. You can wrap as many decorators as you like around the same object. It is also great for separating some of your logic from your model, so model classes don't get cluttered.

An example of this within the Silverstripe Framework is the way the SS_ListDecorator works.

You could use this pattern in more than just a form. You could wrap DataObjects who only need temporary functionality that you can easily inject/change without changing the underlying model class.

#### Why not just use extensions?
Extensions are great, but sometimes not every Object will need that extension (for example, I wouldn't need a form withing the CMS to use Twitter Bootstrap templates and functionality).

Decorators work on a **per instance** rather than on a **per class** basis, and sometimes that is what you really need.

If you prefer the extensions version, unclecheese offers the **bootstrap-form extensionby @unclecheese**.

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
    $form = new Form_Bootstrapped(new Form($controller, $name, $fields, $actions));

    // Or if you are a chain freak
    $form = Form_Bootstrapped::decorate(new Form($controller, $name, $fields, $actions));

```

You can also wrap as many as you need to:

```

    $form = new Form_ReturnWithJSON(new Form_ValidateWithParsley(new Form_Bootstrapped(new Form($controller, $name, $fields, $actions))));

```

Below are some of the decorators that come with the module. There is more documentation for each one in the docs/ folder

### For Forms
1. Form_Bootstrapped: Set your form to use the Twitter Bootstrap Template scheme. This has a lot of code from UncleCheese's module. If you use this, the field list is automatically decorated as well.

### For Field Lists
1. FieldList_Bootstrapped: Set your field list to use the Twitter Bootstrap Template scheme. This has a lot of code from UncleCheese's module. If you use this, the fields are automatically decorated as well.

### For Fields
1. FormField_Bootstrapped: Set your field to use the Twitter Bootstrap Template scheme. This has a lot of code from UncleCheese's module.

To make your own, you can simply extend the specific Decorator, the BaseDecorator or implement \Milkyway\ZenForms\Contracts\Decorator.

```

    class TreatDataObjectSpecial extends BaseDecorator {
        // Do something special with the decorator, and you can refer to original object using $this->failover or $this->original;
    }

    class TreatFormFieldSpecial extends FormFieldDecorator {
        // Do something special with the decorator, and you can refer to original object using $this->failover or $this->original;
    }

```

### Other Goodies
I have also included some other form fields (most specifically related to Twitter Bootstrap) to allow developing using forms to be simpler. It is your choice whether to use them.

#### Composite Fields
These fields work as composite fields, with the ability to group form fields into certain components

1. HasOneCompositeField: Save a has one relationship as if it is part of the current form. Can also be used to completely save a different record if need be.
2. AccordionComponentField: A composite field that acts like an accordion. Uses Twitter Bootstrap styling.
3. ModalWindowField: A composite field that acts like a modal window, with the option to set a trigger, or to trigger automatically. Uses Twitter Bootstrap styling.
4. PanelComponentField: A composite field that displays as a panel
5. SliderComponentField: A composite field that displays a slider. Uses Twitter Bootstrap styling.
5. TabComponentField: A composite field that displays fields in a tab. Uses Twitter Bootstrap styling.

#### Helper Fields
These are fields that use the LiteralField as a base, but are just there to make developing with forms a little bit faster (and more zen)

1. FormActionLink: Display a link like a button - uses Twitter Bootstrap styling - to get it to work in the CMS, make sure you use FormActionLink::create($name, $content, $link)->cms()
2. FormMessageField: Display a message to the user - uses Twitter Bootstrap styling - to get it to work in the CMS, make sure you use FormMessage::create($name, $content, $type)->cms()
3. SpacerField: Display a spacer (usually just an empty paragraph to separate a bunch of fields) - you can use this as a horizontal rule SpacerField::create($name)->hr()
4. IframeField: Display a page in an iframe within the form

## License 
* MIT

## Version 
* Version 0.1 - Alpha

## Contact
#### Milkyway Multimedia
* Homepage: http://milkywaymultimedia.com.au
* E-mail: mell@milkywaymultimedia.com.au
* Twitter: [@mwmdesign](https://twitter.com/mwmdesign "mwmdesign on twitter")