About
-----

This is a simple plugin for the [Frog CMS][frog]. It adds the ability to add more metadata to a page object.

Motivation
----------

I'm currently writing on a plugin that needs extra, custom metadata for a page. I didn't want to create a specific database table, so I contribute
this generic plugin that can be used to store generic metadata for a page.

Requirements
------------

- [jQuery plugin](http://github.com/tuupola/frog_jquery/tree/master)

Install
-------

[Protect your plugins](http://forum.madebyfrog.com/topic/1233). Edit config.php and add the following line:

    define('IN_FROG', true);

Copy plugin files to _frog/plugins/page\_metadata/_ folder.

    cd frog/plugins/
    git clone git://github.com/them/frog_page_metadata.git page_metadata

Usage
-----

The 'visible' part of the plugin adds another tab to the page view: the 'More Metadata' tab.
With this dialog any metadata (keyword value pairs) can be added to an existing page.
If the value left blank, the metadata gets removed

### Attributes at the page object

The current head revision of this plugin extends the page object with the metadata as property.

While in layout/page context: `<?php echo $this->page_metadata["Keyword"]; ?>` and in controller context: `<?php echo $page->page_metadata["Keyword"]; ?>`.

### Extensibility

Because the plugin was generated as a helper plugin for other plugins, the plugin can be extended as well.

#### Observers

The [Frog CMS][frog] offers an [observer mechanism](http://www.madebyfrog.com/docs/plugins-api/the-observer-system.html) to extend the core.
The 'PageMetadata' plugin exports the following subscribable 'topic':

`view_page_page_metadata` with the page as parameter.

#### Visibility

If the metadata has the 'visibility' of '0', the user is not able to alter the metadata directly. This value should be manipulated (e.g. select box)
by another plugin that is using the 'observer topic'.

[frog]: http://www.madebyfrog.com/