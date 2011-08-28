/**
 * Simplecart General Class
 */
var simplecart = new function($) {

  /**
   * Show an alert message
   * @param string message
   * @param string title
   * @param string icon
   */
  this.alert = function(message, title, icon) {
    if(!icon){
      icon = '<span class="icon icon-alert" style="float:left; margin:0 7px 20px 0;"></span>';
    }else{
      icon = '<span class="'+icon+'" style="float:left; margin:0 7px 20px 0;"></span>';
    }
    $( '<div>'+icon+'</div>' ).append(message).dialog({
			modal: true,
      autoOpen: true,
      title: title,
      close: function(e){
        $( this ).dialog( "destroy" );
      },
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});
  };
}(jQuery);

// Setup Admin JS
jQuery(document).ready(function($){
  // Set metadata to be loaded from html5 data attibutes
  $.metadata.setType('attr','data');
  // Make all buttons jquery-ui buttons
  $( "input:submit, button, input:button, a.ui-button" ).each(function(){
    var icon = $(this).data('icon');
    if(icon){
      $(this).button({ icons:{primary:icon} });
    }else{
      $(this).button();
    }
  });
  // Set validation defaults
  $.validator.setDefaults({
    meta: "validate",
    ignoreTitle: true,
    errorElement: 'li',
    errorClass: "error",
    errorPlacement: function(error, element) {
      var container = element.parent('div');
      var error_ul = container.find('ul.errors').eq(0);
      if(!error_ul.length){
        container.append( $('<ul class="errors"></ul>') );
        var error_ul = container.find('ul.errors').eq(0);
      }
      error_ul.empty();
      error.appendTo( error_ul );
    },
    submitHandler: function(form) {
      // Disable submit buttons
      $(form).find('input:submit').button('disable');
      // Submit Form
      form.submit();
    }
  });
  // Superfish menu
  $('ul.sf-menu').supersubs({
    minWidth:    12,   // minimum width of sub-menus in em units
    maxWidth:    27,   // maximum width of sub-menus in em units
    extraWidth:  1     // extra width can ensure lines don't sometimes turn over
                       // due to slight rounding differences and font-family
  }).superfish().find('ul').bgIframe({opacity:false});
  // Javascript Validation
  $('form.validate').attr('novalidate', 'novalidate').validate();
  $("form.validate .form-element-password input:password").keyup(function() {
    $(this).valid();
  });
});