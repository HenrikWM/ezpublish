<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
{let pagedesign=fetch_alias(by_identifier,hash(attr_id,gallery_package))}
<head>
{include uri="design:page_head.tpl" enable_glossary=false() enable_help=false()}

<style>
    @import url({"stylesheets/core.css"|ezdesign});
    {* @import url({$pagedesign.data_map.css.content|ezpackage(filepath,"cssfile")|ezroot});*}
    @import url("/design/gallery/stylesheets/gallery.css");
</style>

</head>

<body>

<div id="background">

    <div id="header">
        <div class="design">
        
           {let content=$pagedesign.data_map.image.content}
            <div id="logo">
                <a href={"/"|ezurl}><img src={$content[logo].full_path|ezroot} /></a>
            </div>
           {/let}
                  
        </div>
    </div>

    <div id="subheader">
        <div class="design">
        
            <div id="searchbox">
                <form action={"/content/search/"|ezurl} method="get">
                    <input class="searchtext" type="text" size="10" name="SearchText" id="Search" value="" />
                    <input class="searchbutton" name="SearchButton" type="submit" value="Search" />
                </form>
            </div>
        
        <div id="mainmenu">
            <div class="design">

                <h3 class="invisible">Main menu</h3>
                <ul>
                {let folder_list=fetch( content, list, hash( parent_node_id, 2, sort_by, array( array( priority ) ) ) )}
                {section name=Folder loop=$folder_list}
                    <li><a href={concat( "/content/view/full/", $Folder:item.node_id, "/" )|ezurl}>{$Folder:item.name|wash}</a></li>
                {/section}
                {/let}
                </ul>
            
            </div>
        </div>
        
        <div class="break"></div> {* This is needed for proper flow of floating objects *}
        
        </div>
    </div>

    <div id="infobox">
        <div class="design">
               {let image_list=fetch( content, tree, hash( parent_node_id, 2,
							   limit, 5,
							   sort_by, array( published, false() ),
							   class_filter_type, include, 
							   class_filter_array, array( 'gallery' ) ) )}
                                                          
            <h3>Gallery list</h3>
            <ul>
                   {section name=Gallery loop=$gallery_list}
                       <li>
                       <a href={$node.url_alias|ezurl}>{$Gallery:item.name|wash}</a>  
                       </li>
                    {/section}
            </ul>
               {/let}

               {let image_list=fetch( content, tree, hash( parent_node_id, 2,
							   limit, 5,
							   sort_by, array( published, false() ),
							   class_filter_type, include, 
							   class_filter_array, array( 2 ) ) )}
                                                          
            <h3>Latest images</h3>
            <ul>
                   {section name=Images loop=$image_list}
                       <li>
                       <a href={concat('content/view/full/',$Images:item.node_id)|ezurl}>{$Images:item.name|wash}</a>  
                       </li>
                    {/section}
            </ul>
               {/let}
	         {let news_list=fetch( content, tree, hash( parent_node_id, 2,
                                                          limit, 5,
                                                          sort_by, array( published, false() ),
                                                          class_filter_type, include, 
                                                          class_filter_array, array( 2 ) ) )}
                                                          
            <h3>Latest news</h3>
            <ul>
                   {section name=News loop=$news_list}
                       <li>
                       <a href={concat('content/view/full/',$News:item.node_id)|ezurl}>{$News:item.name|wash}</a>
                       <div class="date">
                        ({$News:item.object.published|l10n( shortdate )})
                       </div>  
                       </li>
                    {/section}
            </ul>
               {/let}

	       {let comments_list=fetch( content, tree, hash( parent_node_id, 2,
                                                          limit, 5,
                                                          sort_by, array( published, false() ),
                                                          class_filter_type, include, 
                                                          class_filter_array, array( 2 ) ) )}
                                                          
            <h3>Latest comments</h3>
            <ul>
                   {section name=Comments loop=$comments_list}
                       <li>
                       <a href={concat('content/view/full/',$Comments:item.node_id)|ezurl}>{$Comments:item.name|wash}</a>
                       <div class="date">
                        ({$Comments:item.object.published|l10n( shortdate )})
                       </div>  
                       </li>
                    {/section}
            </ul>
               {/let}
        
        </div>
    </div>

    <div id="maincontent">
        <div class="design">
        
    <div id="path">
        <div class="design">

           <p>
           &gt;
           {section name=Path loop=$module_result.path }
               {section show=$Path:item.url}
                  <a href={$Path:item.url|ezurl}>{$Path:item.text|wash}</a>
               {section-else}
    	      {$Path:item.text|wash}
               {/section}
    
               {delimiter}
                 /
               {/delimiter}
            {/section}
           </p>

        </div>
    </div>
            {$module_result.content}
        
        </div>
    </div>

    <div id="footer">
        <div class="design">
        
            <address>
            Copyright &copy; <a href="http://ez.no">eZ systems as</a> 1999-2003
            <a href="http://ez.no/">Powered by eZ publish Content Management System</a>
            </address>
        
        </div>
    </div>

</div>

</body>
{/let}
</html>

