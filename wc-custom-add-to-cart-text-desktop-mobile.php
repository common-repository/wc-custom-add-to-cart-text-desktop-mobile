<?php
/**
 * @package   Zyonstudios\custom-add-to-cart-text-desktop-mobile
 * @author    Zyonstudios <addtocart@zyonstudios.co.uk>
 * @license   GPL-3.0
 * @copyright zyonstudios
 * 
 * Plugin Name:         Custom Add To Cart Text Desktop Mobile
 * Plugin URI:          https://zyonstudios.co.uk/plugins/
 * Description:         Customize the add to cart button text with the option to have different text on desktop and mobile devices.Also you can customize the out of stock button text.
 * Version:             1.2.0
 * Requires at least:   5.2
 * Requires PHP:        7.2
 * Author:              zyonstudios
 * Author URI:          https://github.com/zyonstudios/Custom-Add-To-Cart-Text-Desktop-Mobile/
 * Copyright:           zyonstudios
 * License:             GNU General Public License v3.0
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html 
 */

 if( !defined('ABSPATH')){
    exit;
 }


 use Carbon_Fields\Container;
 use Carbon_Fields\Field;
 
  
 function zyon_wcatctdm_settingspage() {
    Container::make( 'theme_options', __( 'Custom Add To Cart Text Desktop Mobile' ) )
        ->set_page_parent( 'options-general.php' )
        ->add_tab( __('Desktop Add to Cart Button'), array(
            Field::make( 'text', 'zyn_desktop_acb', 'Add to cart button text-(Desktop)' ),
            Field::make( 'html', 'zyn_desktop_information_text' )
                ->set_html( '<h2>Keep it short ! Ex:"Add to bag" ,</h2>' )        
        ) )
        ->add_tab( __('Mobile Add to Cart Button'), array(           
            Field::make( 'text', 'zyn_mob_acb', 'Add to cart button text-(Mobile)' ),
            Field::make( 'html', 'zyn_mobile_information_text' )
                ->set_html( '<h2>To view changes on add to cart text open on mobile devices</h2>' )            
        ) )
        ->add_tab( __('Out of stock Button'), array(            
            Field::make( 'text', 'zyn_oos_txt', 'Out of stock button text' ),
            Field::make( 'html', 'zyn_oos_information_text' )
                ->set_html( "<h2>To display this text on out of stock button,the product inventory must be set as below</h2><img src='https://zyonstudios.co.uk/wp_plugin_images/oos-product-stock.png' />" ),
        ) )
        ->add_tab( __('Select Options Button(Variable product)'), array(            
            Field::make( 'text', 'zyn_options_txt', 'Select options button text(Variable product)' ),
            Field::make( 'html', 'zyn_options_information_text' )
                ->set_html( "<h2>This text will display in variable products instead of 'Select options'<h2/>" ),
        ) );    
}


add_action( 'carbon_fields_register_fields', 'zyon_wcatctdm_settingspage' );

 
add_action( 'after_setup_theme', 'zyon_wcatctdm_carbonfields' );
    function zyon_wcatctdm_carbonfields() {
        require_once( 'vendor/autoload.php' );
        \Carbon_Fields\Carbon_Fields::boot();
 }


 //adding settings link
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'zyon_wcatctdm_addactionlinks' );
    function zyon_wcatctdm_addactionlinks ( $zatctdm_actions ) {
        $zynatc_links = array(
        '<a href="' . admin_url( 'options-general.php?page=crb_carbon_fields_container_custom_add_to_cart_text_desktop_mobile.php' ) . '">Settings</a>',
        );
        $zatctdm_actions = array_merge( $zatctdm_actions, $zynatc_links );
        return $zatctdm_actions;
 }
 

// Change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'zyon_wcatctdm_single' );


// Change add to cart text on product archives page
add_filter( 'woocommerce_product_add_to_cart_text', 'zyon_wcatctdm_archives' );   
   

//check the devive for cart btn
function zyon_wcatctdm_checkDevice(){
    $zatctdm_dttxt = sanitize_text_field(carbon_get_theme_option('zyn_desktop_acb'));
    $zatctdm_mobtxt = sanitize_text_field(carbon_get_theme_option('zyn_mob_acb'));    
    $zatctdm_useragent= sanitize_url($_SERVER['HTTP_USER_AGENT'],ENT_QUOTES, 'UTF-8');    
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$zatctdm_useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($zatctdm_useragent,0,4)))
    return __( $zatctdm_mobtxt, 'woocommerce' );
    else
    {       
        return __($zatctdm_dttxt, 'woocommerce' );        
    }    
}


function zyon_wcatctdm_showoosbtn(){   
    $zatctdm_oostxt = sanitize_text_field(carbon_get_theme_option('zyn_oos_txt')); 
        return __( $zatctdm_oostxt, 'woocommerce' );           
}


function zyon_wcatctm_varbtn() {
    $zatctdm_optionstxt = sanitize_text_field(carbon_get_theme_option('zyn_options_txt'));
         return __( $zatctdm_optionstxt, 'woocommerce' );        
}


function zyon_wcatctdm_showbtnsingle(){
    global $product;
    if ( !$product->is_in_stock() ) {
        return zyon_wcatctdm_showoosbtn();
    }
     else{
        return zyon_wcatctdm_checkDevice(); 
    }
}


function zyon_wcatctdm_showbtn(){
    global $product;
    if (is_product() && !$product->is_in_stock() ) {
        return zyon_wcatctdm_showoosbtn();
    }
    elseif(is_product() && $product->is_type( 'variable' ) ){
        return zyon_wcatctm_varbtn();
    }
    else{
        return zyon_wcatctdm_checkDevice(); 
    }
}


function zyon_wcatctdm_single() {
    return zyon_wcatctdm_showbtnsingle(); 
}


function zyon_wcatctdm_archives() {
    return zyon_wcatctdm_showbtn();
}