jQuery(document).ready(function($) {
    
    $(".upme-woo-account-navigation-item").click(function(){
        var navigation_parent = $(this).parent().parent();
        $('.upme-woo-account-navigation-item').removeClass("upme-woo-active");
        $(this).addClass("upme-woo-active");
        var panel_class = $(this).attr("data-nav-ietm-id");
        $(".upme-woo-account-navigation-content").hide();
        navigation_parent.find("."+panel_class).show();
        
    });
    
    $(".upme-field-edit .upme-fire-editor").click(function(){
        $(this).parent('.upme-view-panel').find(".upme-woo-account-navigation-content").hide();
        $(this).parent('.upme-view-panel').find('.upme-woo-account-navigation-item').hide();
    });
    

});