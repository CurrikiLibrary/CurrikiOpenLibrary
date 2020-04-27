/**
 * better-rss-widget.js - Javascript for the Settings page.
 *
 * @package Better RSS Widget
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2013
 * @access public
 * @since 2.1
 */

/* Function to search for shortcode in post types. */
function better_rss_widget_search() {
     var type = document.getElementById('better_rss_search_type').value;
     var url = document.getElementById('better_rss_url').value;
     window.location = url + type;
}

/* Function to submit the form from the save settings tab */
function better_rss_widget_save_settings () {
     document.getElementById('better_rss_widget_settings').submit();
}
/* Function to change tabs on the settings pages */
function better_rss_widget_show_tab(tab) {
     /* Close Active Tab */
     activeTab = document.getElementById('active_tab').value;
     document.getElementById('better_rss_widget_box_' + activeTab).style.display = 'none';
     document.getElementById('better_rss_widget_' + activeTab).removeAttribute('class','better-rss-widget-selected');

     /* Open new Tab */
     document.getElementById('better_rss_widget_box_' + tab).style.display = 'block';
     document.getElementById('better_rss_widget_' + tab).setAttribute('class','better-rss-widget-selected');
     document.getElementById('active_tab').value = tab;
}

/* Function to verify selection to reset options */
function better_rss_widget_reset(element) {
     if (element.checked) {
          if (prompt('Are you sure you want to reset all of your options? To confirm, type the word "reset" into the box.') == 'reset' ) {
               document.getElementById('better_rss_widget_settings').submit();
          } else {
               element.checked = false;
          }
     }
}
