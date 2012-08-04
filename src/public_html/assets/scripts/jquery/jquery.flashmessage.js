/**
 * Flash Message jQuery Plugin
 *
 * Shows a message for a duration then hides it.
 * Uses jQuery UI classes by default
 * Positioning and size will need to be set on the element separately
 *
 * @author Jonathan Bernardi
 * @version 1.1 10/14/2009
 */
(function(b){b.fn.flashmessage=function(d){var a=b.extend({message:"",containerclass:"ui-widget",infoclass:"ui-state-highlight ui-corner-all",addicon:true,infoicon:"ui-icon ui-icon-info",speed:"slow",duration:2E3},d);return this.each(function(){var c=b(this);c.empty().hide();content=b('<div class="'+a.containerclass+'"></div>');content.append('<span class="'+a.infoicon+'"></span>');message=b('<span class="'+a.infoclass+'"></span>').html(a.message).appendTo(content);c.html(content);c.show(a.speed,
function(){setTimeout(function(){c.hide(a.speed,function(){c.empty()})},a.duration)})})}})(jQuery);