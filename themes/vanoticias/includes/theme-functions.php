<?php 

// Remove image dimentions from woo_get_image images
update_option('woo_force_all',false);
update_option('woo_force_single',false);
    
function gaz_slider_height(){
?>
<style type="text/css">

#slider-holder,#slider-holder .slide  { height: <?php echo get_option('woo_carousel_height'); ?>px } 

</style>
<?php
}    

if(get_option("woo_carousel_height") != 270 ){
    add_action('wp_head','gaz_slider_height');
}

?>