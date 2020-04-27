<?php
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
    die('You are not allowed to call this page directly.');
}
/**
 * settings.php - View for the Settings page.
 *
 * @package Better RSS Widget
 * @subpackage includes
 * @author GrandSlambert
 * @copyright 2009-2016
 * @access public
 * @since 2.1
 */
?>
<div class = "wrap">
    <h2><?php echo $this->pluginName; ?></h2>
    <div style="width:49%; float:left">
        <div class="postbox">
            <form method="post" action="options.php">
                <?php wp_nonce_field('update-options'); ?>
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="page_options" value="<?php echo $this->optionsName; ?>" />

                <table class="form-table">
                    <thead></thead>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr align="top">
                            <th scope="row"><label class="primary" for="<?php echo $this->optionsName; ?>_allow_intro"><?php _e('Use Intro Text?', 'better-rss-widget'); ?></label></th>
                            <td>
                                <input type="checkbox" name="<?php echo $this->optionsName; ?>[allow_intro]" id="<?php echo $this->optionsName; ?>_allow_intro" value="1" <?php checked($this->options->allow_intro, 1); ?>>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label class="primary" for="<?php echo $this->optionsName; ?>_link_target"><?php _e('Default Link Target', 'better-rss-widget'); ?> : </label></th>
                            <td>
                                <select name="<?php echo $this->optionsName; ?>[link_target]" id="<?php echo $this->optionsName; ?>_link_target">
                                    <option>None</option>
                                    <option value="_blank" <?php selected($this->options->link_target, '_blank'); ?>><?php _e('New Window', 'better-rss-widget'); ?></option>
                                    <option value="_top" <?php selected($this->options->link_target, '_top'); ?>><?php _e('Top Window', 'better-rss-widget'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label class="primary"><?php _e('Display items', 'better-rss-widget'); ?> : </label></th>
                            <td>
                                <p>
                                    <label>
                                        <input name="<?php echo $this->optionsName; ?>[show_summary]" type="checkbox" id="better_rss_widget_show_summary" value="1" <?php checked($this->options->show_summary, 1); ?> />
                                        <?php _e('Item Summary', 'better-rss-widget'); ?>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input name="<?php echo $this->optionsName; ?>[show_author]" type="checkbox" id="better_rss_widget_show_summary" value="1" <?php checked($this->options->show_author, 1); ?> />
                                        <?php _e('Item Author', 'better-rss-widget'); ?>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input name="<?php echo $this->optionsName; ?>[show_date]" type="checkbox" id="<?php echo $this->optionsName; ?>[show_date]" value="1" <?php checked($this->options->show_date, 1); ?> />
                                        <?php _e('Item Date', 'better-rss-widget'); ?>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input name="<?php echo $this->optionsName; ?>[show_time]" type="checkbox" id="<?php echo $this->optionsName; ?>_show_time" value="1" <?php checked($this->options->show_time, 1); ?> />
                                        <?php _e('Item Time', 'better-rss-widget'); ?>
                                    </label>
                                </p>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label class="primary" for="<?php echo $this->optionsName; ?>_nofollow"><?php _e('Add nofollow to links', 'better-rss-widget'); ?> : </label></th>
                            <td><input name="<?php echo $this->optionsName; ?>[nofollow]" type="checkbox" id="<?php echo $this->optionsName; ?>_nofollow" value="1" <?php checked($this->options->nofollow, 1); ?> /> </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label class="primary" for="<?php echo $this->optionsName; ?>_items"><?php _e('Default Items to Display', 'better-rss-widget'); ?></label> : </th>
                            <td>
                                <input type="number" name="<?php echo $this->optionsName; ?>[items]" id="<?php echo $this->optionsName; ?>_items" value="<?php echo $this->options->items; ?>" min="1" max="25" />
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label class="primary" for="<?php echo $this->optionsName; ?>_title_length"><?php _e('Max Length of Title', 'better-rss-widget'); ?> : </label></th>
                            <td colspan="2">
                                <input  name="<?php echo $this->optionsName; ?>[title_length]" type="number" id="<?php echo $this->optionsName; ?>_title_length" value="<?php echo $this->options->title_length; ?>" min="0" max="100" />
                                <?php _e('( Enter "0" for no limit. )', 'better-rss-widget'); ?>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label class="primary" for="<?php echo $this->optionsName; ?>_excerpt"><?php _e('Length of Excerpt', 'better-rss-widget'); ?> : </label></th>
                            <td colspan="2"><input  name="<?php echo $this->optionsName; ?>[excerpt]" type="number" id="<?php echo $this->optionsName; ?>_excerpt" value="<?php echo $this->options->excerpt; ?>" min="25" /></td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label class="primary" for="<?php echo $this->optionsName; ?>_suffix"><?php _e('Add after the excerpt', 'better-rss-widget'); ?> : </label></th>
                            <td colspan="2"><input name="<?php echo $this->optionsName; ?>[suffix]" type="text" id="<?php echo $this->optionsName; ?>_suffix" value="<?php echo $this->options->suffix; ?>" /></td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label class="primary" for="<?php echo $this->optionsName; ?>_enable_cache"><?php _e('RSS Cache', 'better-rss-widget'); ?> : </label></th>
                            <td>
                                <label><input name="<?php echo $this->optionsName; ?>[enable_cache]" type="radio" id="better_rss_widget_enable_cache" value="1" <?php checked($this->options->enable_cache, 1); ?> /> <?php _e('Enabled', 'better-rss-widget'); ?></label>
                                <label><input name="<?php echo $this->optionsName; ?>[enable_cache]" type="radio" id="better_rss_widget_disable_cache" value="0" <?php checked($this->options->enable_cache, 0); ?> /> <?php _e('Disabled', 'better-rss-widget'); ?></label>
                            </td>
                        </tr>
                        <tr align="top">
                            <th scope="row"><label class="primary" for="<?php echo $this->optionsName; ?>_cache_duration"><?php _e('Cache Duration (seconds)<br /><small>ex. 3600 seconds = 60 minutes</small>', 'better-rss-widget'); ?> : </label></th>
                            <td><input  name="<?php echo $this->optionsName; ?>[cache_duration]" type="number" id="<?php echo $this->optionsName; ?>_cache_duration" value="<?php echo $this->options->cache_duration; ?>" min="3600" /> <?php _e('seconds', 'better-rss-widget'); ?>. </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>


    <div style="width:49%; float:right">
        <div class="postbox">
            <h3 class="handl" style="margin:0; padding:3px;cursor:default;">
                <?php _e('Plugin Information', 'better-rss-widget'); ?>
            </h3>
            <div style="padding:5px;">
                <p><?php _e('This page sets the defaults for the plugin. Each of these settings can be overridden when you add an index to your page.', 'better-rss-widget'); ?></p>
                <p><span><?php _e('You are using', 'better-rss-widget'); ?> <strong> <a href="http://grandslambert.net/plugin/better-rss-widget/" target="_blank"><?php echo $this->pluginName; ?> <?php echo $this->version; ?></a></strong> by <a href="http://grandslambert.tk" target="_blank">GrandSlambert</a>.</span> </p>
            </div>
            <h3 class="handl" style="margin:0; padding:3px;cursor:default;">
                <?php _e('Usage', 'better-rss-widget'); ?>
            </h3>
            <div style="padding:5px;">
                <p>
                    <?php printf(__('See the %1$s for this plugin for more details on what each of these settings does.', 'better-rss-widget'), '<a href="http://grandslambert.net/plugin/better-rss-widget/documentation/" target="_blank">' . __('Documentation Page', 'better-rss-widget') . '</a>');
                    ?>
                </p>
            </div>
            <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Credits', 'better-rss-widget'); ?></h3>
            <div style="padding:8px;">
                <p>
                    <?php
                    printf(__('Thank you for trying the %1$s plugin - I hope you find it useful. For the latest updates on this plugin, vist the %2$s. If you have problems with this plugin, please use our %3$s or check out the %4$s.', 'better-rss-widget'), $this->pluginName, '<a href="http://grandslambert.net/plugin/better-rss-widget/" target="_blank">' . __('official site', 'better-rss-widget') . '</a>', '<a href="https://wordpress.org/support/plugin/better-rss-widget" target="_blank">' . __('Support Forum', 'better-rss-widget') . '</a>', '<a href="http://grandslambert.net/plugin/better-rss-widget/documentation/" target="_blank">' . __('Documentation Page', 'better-rss-widget') . '</a>'
                    );
                    ?>
                </p>
                <p>
                    <?php
                    printf(__('This plugin is &copy; %1$s by %2$s and is released under the %3$s', 'better-rss-widget'), '2009-' . date("Y"), '<a href="http://grandslambert.net" target="_blank">GrandSlambert</a>', '<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">' . __('GNU General Public License', 'better-rss-widget') . '</a>'
                    );
                    ?>
                </p>
            </div>
            <h3 class="handl" style="margin:0; padding:3px;cursor:default;"><?php _e('Donate', 'better-rss-widget'); ?></h3>
            <div style="padding:8px">
                <p>
                    <?php printf(__('If you find this plugin useful, please consider supporting this and our other great %1$s.', 'better-rss-widget'), '<a href="http://grandslambert.net/plugins/" target="_blank">' . __('plugins', 'better-rss-widget') . '</a>'); ?>
                    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BRRGNC3ZW8X7Y" target="_blank"><?php _e('Donate a few bucks!', 'better-rss-widget'); ?></a>
                </p>
                <p style="text-align: center;"><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BRRGNC3ZW8X7Y"><img width="147" height="47" alt="paypal_btn_donateCC_LG" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" title="paypal_btn_donateCC_LG" class="aligncenter size-full wp-image-174"/></a></p>
            </div>
        </div>
    </div>