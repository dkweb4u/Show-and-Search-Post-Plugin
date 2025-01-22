<?php

/*
Plugin Name: DK Custom Plugin
Description: Show and Search Blog Posts plugin with shortcode
Version: 1.0
Author: Dinesh
Author Uri: //ddk.netlify.app
*/

if(!defined('ABSPATH')) exit;


class dkCustomTheme{

    public $settings = [
        "Recent Category" => [
        'Per Post Category' => 5,
        'Show Empty Category' => false,
        'Show Particular Category' => 'all',
        'Specific Cate' => []
        ]
    ];


    function __construct(){

        add_action('admin_menu',array($this,'menuSetup'));
        add_shortcode('myblogpost',array($this,'shortcodeHTML'));    

        add_shortcode('blogpostsearch',array($this,'searchHtml'));  

        add_action('wp_enqueue_scripts',array($this,'dk_search_script'));
    }

    function menuSetup(){
        $mainpage = add_menu_page('Dk Custom Plugin',"Dk Custom Post","manage_options","dk-custom-post",array($this,'loadwordHTML'),'dashicons-smiley',4);
    }

    function loadwordHTML(){

        ?>

        <style>
            .dk-theme-table td{
              padding: 10px 0px;
            }
            .dk-theme-table  tr td:nth-child(3),
            .dk-searchbox  tr td:nth-child(3){
              padding-left: 10px;
            }
            .dk-theme-table .show-all-cate-set label {
                  margin-right: 10px;
            }
            .dk-theme-table .show-all-cate-set{
               display: none;
               margin-bottom: 10px;
            }
            .dk-theme-table .show-all-cate-set.active{
               display: block;
            }
            .dk-searchbox table{
                margin: 10px 0px;
            }
            .dk-searchbox   input.resultlimit {
    width: 60px;
}
        </style>

        <div class="wrap">
            <h2>Recent Articles by Category</h2>
            <p>Recent Articles by Category Shortcode : <b class="dk-plugin-shortcode">[myblogpost]</b></p>
            <div class="dk-custom-plugin-form">
            <table class="dk-theme-table">
                <tr>
                    <td>Per Post Category</td>
                    <td>:</td>
                    <td><input type="number" name="Per_Post_Category" class="perPost"  min="1" max="10" value="5">
                   </td>
                </tr>


                <tr>
                    <td>Show Empty Category also</td>
                    <td>:</td>
                    <td><input type="checkbox" name="Show_Empty_Category"  class="showhiddenpost"  value="true" checked></td>
                </tr>


                <tr style="padding: 5px">
                    <td>Show Particular Category</td>
                    <td>:</td>
                    <td><label for="showAll"><input type="radio"  class="showmycate" name="show_cate" id="showAll"
                    value="all" checked onClick="toggleCategoy(event)"> All</label>  | 
                         <label for="showSpecific"><input type="radio"  class="showmycate"  name="show_cate" id="showSpecific" value="specific" onClick="toggleCategoy(event)"> Specific</label></td>
                </tr>
                <tr>
                    <td colspan="3">
<?php

$categories = get_categories(array(
    'hide_empty' => false, 
));

echo "<div class='show-all-cate-set'>";
foreach ($categories as $category) {

   echo '<label for="'.esc_html($category->name).'"><input type="checkbox"   class="specificcateitems" name="Specific_Cate[]" id="'.esc_html($category->name).'" value="'.esc_html($category->name).'"> '.esc_html($category->name).'</label>';

}
echo "</div>";

?>


                    </td>
                </tr>              
            </table>
            
            <button class="button button-primary">Copy</button>
               
</div>

<hr>

<div class="dk-searchbox">
<p>Seacrch Post Shortcode: <b class="searchboxshortcode">[blogpostsearch limit='5']</b></p>
<span>Limit is nothing but first 5 results</span>

<table>
   <tr>
    <td>Result Limit</td>
    <td>:</td>
    <td><input type="number" class="resultlimit" value="5" min="1" ></td>
   </tr>
</table>
<button class="button button-primary">Copy</button>
</div>


<script>
        
       
        let formdiv = document.querySelector('.dk-custom-plugin-form');
        let showshortcode = document.querySelector('.dk-plugin-shortcode');
        let formdivCopybtn = document.querySelector('.dk-custom-plugin-form button.button-primary');
        let showallcate = document.querySelector('.dk-custom-plugin-form .showmycate:checked');


       

        function dkthemeonchange(){
            let perpost = document.querySelector('.dk-custom-plugin-form .perPost');
            let showhiddenpost = document.querySelector('.dk-custom-plugin-form .showhiddenpost').checked;
            let showallcate = document.querySelector('.dk-custom-plugin-form .showmycate:checked');

            let specificcateitems = document.querySelectorAll('.dk-custom-plugin-form .specificcateitems:checked');

        let sepcificArr = [];

         if(showallcate.value != "all"){
            specificcateitems.forEach(item => {
                if(item.value != ''){
                    sepcificArr.push(item.value);
                }                
            });
         }

         let specificCate = "";

         if(sepcificArr.length > 0){
            specificCate =  `category= '${sepcificArr.toString()}' `;
         }


         showshortcode.innerText = `[myblogpost posts_per_category='${perpost.value}' include_empty='${showhiddenpost}' ${specificCate}]`;
            
        }
       
        dkthemeonchange();

        formdiv.addEventListener('click',()=>{
            dkthemeonchange();
        });

        formdivCopybtn.addEventListener('click',()=>{
            
            navigator.clipboard.writeText(showshortcode.innerText);

            formdivCopybtn.innerText = "Copied!";

            setTimeout(() => {
                formdivCopybtn.innerText = "Copy";
            }, 2000);

        });

    function toggleCategoy(event){
        if(event.target.value == "all"){
                document.querySelector('.dk-theme-table .show-all-cate-set').classList.remove("active");
            }
            else{
                document.querySelector('.dk-theme-table .show-all-cate-set').classList.add("active");
        }
    }
    // -------------------------------------------------------------------------------------------

            // searchbox

        let searchshortcodeCopybtn = document.querySelector('.dk-searchbox button.button-primary');
        let searchlimit = document.querySelector('.dk-searchbox   input.resultlimit');
        let searchshortcode = document.querySelector('.searchboxshortcode');

     searchshortcodeCopybtn.addEventListener('click',()=>{
             
            navigator.clipboard.writeText(searchshortcode.innerText);

            searchshortcodeCopybtn.innerText = "Copied!";

            setTimeout(() => {
                searchshortcodeCopybtn.innerText = "Copy";
            }, 2000);

        });

        searchlimit.addEventListener('input',(e)=>{
            searchshortcode.innerText = `[blogpostsearch limit='${e.target.value}']`;
        });
        
    </script>




        </div>

<?php 

    }
    function shortcodeHTML($atts){

        $atts = shortcode_atts(
            array(
                'posts_per_category' => 5, 
                'include_empty' => 'true', 
                'category' => '', 
            ),
            $atts,
            'categories_with_posts'
        );
    
        $include_empty = ($atts['include_empty'] === 'true');
    
        $category_slugs = array_map('trim', explode(',', $atts['category']));
    
        $categories_args = array(
            'hide_empty' => !$include_empty, 
        );
    
        if (!empty($atts['category'])) {
            $categories_args['slug'] = $category_slugs;
        }
    
        $categories = get_categories($categories_args);
    
       
        ob_start();
    
       
        if (!empty($categories)) {
            foreach ($categories as $category) {
                echo '<h3>' . esc_html($category->name) . '</h3>';
    
                $args = array(
                    'category__in' => array($category->term_id),
                    'posts_per_page' => intval($atts['posts_per_category']),
                );
                $query = new WP_Query($args);
    
                if ($query->have_posts()) {
                    echo '<ul>';
                    while ($query->have_posts()) {
                        $query->the_post();
                        echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>No posts available in this category.</p>';
                }
                wp_reset_postdata();
            }
        } else {
            echo '<p>No matching categories found.</p>';
        }

        return ob_get_clean();

}

function searchHtml($atts){

    $atts = shortcode_atts(
        array(
            'limit' => 5
        ),
        $atts,
        ''
    );
  
   $searchCate =  '<div class="dk-cate-search">
   <input type="hidden" name="url"  class="roolUrl" value="'.get_site_url().'/wp-json/wp/v2/posts?search=">
   <input type="text" class="searchtext" name="search">
   <input type="hidden" class="limit" name="limit" value="'.$atts['limit'].'">
   <div class="results"></div>
   </div>';


    return $searchCate;


}

function dk_search_script(){
    wp_register_script('search-category', plugin_dir_url(__FILE__) . 'search.js', null, null,true);
    wp_enqueue_script('search-category');
}


}
$dkCustomTheme =  new dkCustomTheme();

?>
