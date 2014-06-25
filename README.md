wordpress-demos
===============

Demo code to learn how basic wordpress stuff works.

Sometimes the Wordpress Codex can be a little overwhelming or lacking to any
beginning programmer, so I'm creating this set of plugins to demo
basic functionality as I learn how to work with wordpress.

Coming from the Drupal world, I've found that the Codex could use more examples
compared to drupal.org. 

Sometimes I wish that I can find a simple examples module/plugin like you can find
on drupal.org:  http://drupal.org/project/examples

For instance, the books example plugin here was tied together from 5 or 6
pages of the codex. I couldn't find a working downloadable plugin that I
could install and start working on.

I'm including in this set of plugins:
* Batch Example - demonstrates how to run a whole bunch of processes without your server timing out.
* Books - example plugin to demo how to create a custom post type using a plugin. Shows how to create custom meta box.
* CSV Import - this plugin demonstrates how to upload a CSV file to wordpress and then process it to create categories
* Custom Menus - example on how to customize menu items
* Custom Meta Boxes - demonstrates how to add various meta boxes onto a post. demonstrates how to use nonces.
* Customize Theme Menu Mod - example how to add options to the customize menu to override some color options on the fly. This also injects css into your theme.
* file upload example - Uploading files into wordpress and using its api to handle saving
* filters example - example how to use filters. 
* foscam - just a plugin that allows you to display a camera feed onto any post or page using a shortcode. it demonstrates how to insert an options page and save this information
* js example - example plugin that demonstrates how to enqueue javascript, load dependencies, and pass parameters from wordpress into the js file.
* plugin activation example - demonstrates how to use register_activation_hook when a plugin is activated to do some setup stuff - create categories, or activate dependencies.
* query vars example - example how to use the query_vars filter in order to declare your own custom query vars and how to retrieve them.
* shortcode example - example on how to declare shortcodes and how to use them 
* tiny mce addon - demonstrates how to add a custom button into your WYSIWYG in order to insert a shortcode into the editor with a modal dialog. 
* Widget Dropdown Demo - example to show how to create a dropdown selector inside of a widget, and save this info.
* Widget Dynamic Fields - demonstrates how to use a widget and have it provide fields dynamically, and not require a hard coding of fields.

Questions, comments, anything else you'd like to see?  Please let me know and I'll try my best to add this.