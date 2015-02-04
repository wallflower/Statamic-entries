# Statamic-entries
A select/multi-select fieldtype that pulls its values from existing entries in a folder


## Installation

Place `ft.entries.php` in `_add-ons\entries\`.

## Usage

Inside your fields yaml file, you can add something similar to the following:

```
fields:
  myservices:
    type: entries
    display: Provided Services
    instructions: "Please list the services you provide from the list below (max 2 choices)."
    folder: services
    conditions: "categories:spa"
    sort_dir: desc
    field-type: suggest
    field-key: slug
    field-value: title
    field-options:
      create: false
      max_items: 2
```

In this case, the form will have a `suggest` field, comprised of all the services entries within the spa category.  It will display the `title` to the user and save the `slug` to the yaml file.  With the field-options specified here, the user cannot create new entries and they are limited to selecting two entries. 

###Options
You can use any of fieldset options that are generally available (i.e `display`, `instructions`, etc.). There are the following additional options:

 * **folder** *(required)* is the folder of entries to list. Multiple folders can be separated with a pipe (|).
 * **conditions** *(optional)* will filter results by field conditions. Works just the like `entries:listing` tag.
 * **sort_dir** *(optional)* will change the sort order of the results. *Default:* `asc`.
 * **field-type** *(optional)* this is the type of select field to use.  Can use any Statamic fieldtype that accepts an array (i.e. `suggest`, `select` and `checkboxes`). *Default:* `suggest`.
 * **field-key** *(optional)* the entries field that should be used as the key.  *Default:* `slug`.
 * **field-value** *(optional)* the entries field that should be used as the value.  *Default:* `title`.
 * **field-options** *(optional)* any additional options that the field-type's fieldtype has.  In the example, we are using the `suggest` fieldtype and specifying that the user cannot create new entries, and that a max of two entries are allowed. Note: If using `suggest`, **you should not  use any of the Content Mode options**, and you can either place the `sort_dir` option, here or in the main options.

## Template Usage
To display your choices in a template, you would use the `entries:listing` tag, with the following conditions:

 * The folder should match the folder that was used in the `entries` fieldset `folder` option. 
 * The `conditions` option should filter the entries to those that are saved in the field.  This is achieved with the following formula: `<field-key>:<field name>|options_list`.
 
This will allow you to display any of the fields in the existing entries.

So, given the above example, the template tag would look like:
```
  {{ entries:listing folder="services" conditions="slug:{myservices|option_list}" }}
    {{ if no_results }}
      <p>No services provided at this time.</p>
    {{ else }}
      {{ if first }}Services: {{ /if }}
      <a href="{{ url }}">{{ title }}</a>{{ if !last }}, {{ /if }}
    {{ /if }}
  {{ /entries:listing }}
```

## Notes
 1. After I started, I did find chuckhendo's attempt at this
[https://github.com/chuckhendo/statamic-entries-field](https://github.com/chuckhendo/statamic-entries-field), so I have to thank him for getting me started with the content_set code...

