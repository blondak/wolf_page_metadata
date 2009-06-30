About
-----

This is a simple plug-in for the [Frog CMS][frog]. It adds the ability to add more metadata to a page object.
Metadata is stored as simple keyword-value pairs.

Whenever metadata is required on page level _page\_metdata_ allows the user to add more metadata fields
as well as 'hidden' metadata that can be used by other plug-ins.

Motivation
----------

For the [page part forms][page_part_forms] plug-in I was searching for a way to store metadata information for a page
(in this case the selected page part form). The core Frog 'page' table is very specific with its metadata
(e.g. keywords, layout\_id, is\_protected, etc.). It is not possible to add additional fields without breaking with the
core of Frog.

Instead of creating another specific mapping table only for the page part forms plug-in, I created this 'page\_metadata'
to add any keyword-value pairs (generic metadata) to a page.

As a side effect the metadata can either be created by the user in the edit interface (visible) or by a plug-in with a
custom metadata handler (invisible).

Requirements
------------

- [jQuery plug-in](http://github.com/tuupola/frog_jquery/tree/master)

Install
-------

Activate the jQuery plug-in

[Protect your plug-ins](http://forum.madebyfrog.com/topic/1233). Edit config.php and add the following line:
    
    define('IN_FROG', true);

Download and extract the released zip file (_simple_)
    
POSIX based systems:
    
    cd frog/plugins/
    curl -O http://cloud.github.com/downloads/them/frog_page_metadata/page_metadata-v1.0.0.zip
    unzip page_metadata-v1.0.0.zip

Microsoft Windows:

1. Download the [zip file](http://cloud.github.com/downloads/them/frog_page_metadata/page_metadata-v1.0.0.zip)
2. Extract the content to 'frog\\plugins\\' as sub-folder 'page_metadata'

Use git and simple update to further releases (_advanced_)
    
    cd frog/plugins/
    git clone git://github.com/them/frog_page_metadata.git page_metadata

Active the page_metadata plug-in

Usage
-----

The 'visible' part of the plug-in adds another tab to the page view: the 'More Metadata' tab.
With this dialogue any metadata (keyword value pairs) can be added to an existing page.
If the value left blank, the metadata gets removed, and there is also a delete button.

### Attributes at the page object

The metadata is automatically attached as an associative array to the active page
(`$this` in page, layout, or snippet context).

    <?php echo $this->page_metadata["Keyword"]; ?>

If other pages where accessed (e.g. to generate a navigation menu) the plug-in _does not_ expand the metadata to this pages.
In this case the helper functions from the 'PageMetadata' model classes must be used. Instead of the `$page` object, the
page\_id can be used instead of the `$page` object, too.

    /* Returns only the specific value */
    PageMetadata::FindOneByPageAndKeyword($page, $keyword);

    /* Returns an associative array with all keywords and their values */
    PageMetadata::FindAllByPageAsArray($page);

    /* Returns the internal representation (active record) of the metadata */
    PageMetadata::FindAllByPage($page);
    
### Extensibility and Observers

Because the plug-in was initially generated as a generic plug-in for other plug-ins to store metadata, the plug-in offers
an [observer mechanism](http://www.madebyfrog.com/docs/plugins-api/the-observer-system.html) to hook-in functionality
(e.g. a custom form for the metadata).

Therefore the observers subscribing the topic '_view\_page\_page\_metadata_' will be called in the view with all metadata
objects as parameter.

    Observer::notify('view_page_page_metadata', $metadata);

#### Visibility

If the metadata has the 'visibility' of '0', the user is not able to alter the metadata directly. This value should
be manipulated (e.g. select box) by another plug-in that is using the observer topic.

[frog]: http://www.madebyfrog.com/
[page_part_forms]: http://github.com/them/frog_page_part_forms