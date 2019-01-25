# Installation

Install with [Composer](https://getcomposer.org/):

`composer require felipelavinz/queulat:dev-master`

Composer will install on `wp-content/mu-plugins/queulat`

If you need to install on a different folder, you should add something like this to your project's composer.json:

```json
{
	"extra" : {
		"installer-paths" : {
			"htdocs/wp-content/mu-plugins/{$name}" : ["type:wordpress-muplugin"]
		}
	}
}
```

Where `htdocs/wp-content/mu-plugins/{$name}` it's the path to your mu-plugins directory. Queulat will be installed as a sub-folder on the specified folder.

## Loading Queulat as mu-plugin

Since mu-plugins installed on a sub-folder are not automatically loaded by WordPress you must manually require the main file, which you can do with a single file right on the mu-plugins folder, such as:

```php
<?php
/**
 * Plugin Name: Queulat Loader
 * Description: Load Queulat mu-plugin
 */

require_once __DIR__ .'/queulat/queulat.php';
```

Plugin headers are optional, but recommended.

You could also use something like [Bedrock's autoloader](https://github.com/roots/bedrock/blob/master/web/app/mu-plugins/bedrock-autoloader.php), which will load all mu-plugins installed on sub-folders (you can just copy that file on your mu-plugin folder and it will automagically load Queulat).

# Using Queulat Forms

<!-- @todo: add general description -->

## Available form fields

<!-- @todo: add description for each form field -->

* Button
* Div
* Fieldset
* Form
* Google_Map
* Input
	- Input_Text
	- Input_Hidden
	- Input_Email
	- Input_Checkbox
	- Input_Number
	- Input_Radio
	- Input_Submit
	- Input_Url
* Recaptcha
* Select
* Textarea
* UI_Select2
* WP_Editor
* WP_Gallery
* WP_Image
* WP_Media
* WP_Nonce
* Yes_No

## Validating forms

<!-- @todo -->

## Creating new form views

<!-- @todo -->

## Instantiating form elements with Node_Factory

`Node_Factory`it's a simple factory class that's able to create any kind of form element.

It exposes a single `make` method that can instantiate and configure an element. This method takes two parameters:

1. An "element name" as string, which should be a fully qualified name for a form element or component.
2. An associative array of "arguments" that are used to configure the object.

By default, Queulat is configured to handle the following attributes:

* attributes: HTML element attributes, such as class, id, type, etc; as associative array.
* children: nested elements, which can also be created with the `Node_Factory` as an array.
* label: the text labeling a form element.
* name: the field "name" that's used on form submission.
* options: an associative array of element options (for fields such as input radios, checkboxes, selects, etc.)
* properties: an array of arbitrary node properties which can be used by the form view or whatever, as an associative array.
* text_content: the textual content of the node.
* value: the form field value.

Arguments that are not supported by the element are skipped.

You can extend the supported arguments using `Node_Factory::register_argument()`.

### Usage

```php
<?php

use Queulat\Forms\Element\Div;
use Queulat\Forms\Node_Factory;
use Queulat\Forms\Element\Button;

$submit = Node_Factory::make(
	Div::class, [
		'attributes' => [
			'class'  => 'col-md-4 col-md-offset-8',
			'id'     => 'form-buttons'
		],
		'text_content' => '* required field',
		'children'     => [
			Node_Factory::make(
				Button::class,
				'attributes' => [
					'class'  => 'btn-lg',
					'type'   => 'submit'
				],
				'text_content' => 'Submit'
			)
		]
	];
);

echo $submit;
```

## Node_Factory

### Registering new argument handlers

You can register new arguments used by the Node_Factory using the `register_argument` method.

This method takes a `Node_Factory_Argument_Handler`, which needs:

* An `$argument` (string) which is the name of the argument key that you'll handle.
* A `$method` (string) which is the name of the method that will receive the parameters used on the factory method.
* An optional `$call_type` (string) which determines how the $method will treat the received configuration values.

The `$call_type` can be one of:

`Node_Factory::CALL_TYPE_VALUE`: pass all arguments as a single array to the handler. This is the default setting. Example: `$obj->$method( $args );`

`Node_Factory::CALL_ARRAY`: pass arguments as individual parameters to the handler. Example: `call_user_func_array( [ $obj, $method ], $args );`

`Node_Factory::CALL_KEY_VALUE`: for each item in the argument, pass its key and value as parameters to the handler. Example:

```php
foreach ( $args as $key => $val ) {
	$obj->$method( $key, $val );
}
```

`Node_Factory::CALL_TYPE_VALUE_ITEMS`: for each item in the argument, use the value as parameter for the handler. Example: `array_walk( $args, [ $obj, $method ] );`

# Interfaces

## Node_Interface

| Related Interfaces | Related Traits |
| ------------------ | -------------- |
| `Component_Interface` | `Node_Trait` |
| `Element_Interface` | `Childless_Node_Trait` |

Nodes are the lowest level of objects that should be used with forms

Use `Node_Trait` to help implement this interface or `Childless_Node_Trait`. In general terms,
elements should use the former and components the latter.

### Component_Interface

Extends the Node_Interface and Attributes_Interface.

### Element_Interface

Extends the Node_Interface and Attributes_Interface. Also, adds the `get_tag_name` method.

#### HTML_Element_Interface

Extends the Element_Interface and Properties_Interface.

##### Form_Element_Interface

Extends HTML_Element_Interface and Form_Node_Interface.

## Attributes_Interface

"Attributes" are special properties used by objects implementing this interface. They're rendered as HTML attributes `key="val"`

Use `Attributes_Trait` to help implement this interface.

## Form_Node_Interface

Objects implementing this interface (elements or components) are used as form objects. They have a "name" which is used to send data to the server, a "value" and a "label".

The `Form_Control_Trait` helps implementing the "label" and "name" getters and setters from this interface.
The "value" getter and setter should be defined by your own custom component.

## Node_List_Interface

Extends ArrayAccess, SeekableIterator, Countable, Serializable.

Most commonly used to get an array-like set of children from a Node.

## Option_Node_Interface

Used by controls such as checkboxes, radios, selects and every component where the user is presented with several alternatives they can choose from.

Use `Options_Trait` to help implement this interface.

## Properties_Interface

Node properties (not to be confused with regular object properties) can store arbitrary data, such as view settings, error data or validation state.

Use `Properties_Trait` to help implement this interface.

## View_Interface

Base interface to be used by form views.

# Creating new elements or components

