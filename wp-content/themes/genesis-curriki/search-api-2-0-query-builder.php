<?php
/*
 * Template Name: Search API 2.0 Query Builder Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Muhammad Furqan Aziz
 */
?>
<script type='text/javascript' src='https://www.curriki.org/wp-includes/js/jquery/jquery.js?ver=1.11.3'></script>
<div id="wrapper">

    <div id="content">

        <h2>Curriki Search API 2.0 Query Builder</h2>
        <p>This Query Builder demonstrates the basic Lucene query syntax for now such as AND, OR and NOT, range queries, phrase queries, as well as approximate queries.</p>
        <form id="querybuilder">
            <table width="85%" border="0" cellspacing="1" cellpadding="5">
                <tr>
                    <th></th>
                    <td width="45%"></td>
                </tr>
                <tr>
                    <td>With <b>all</b> of the words</td>
                    <td>
                        <input type="text" name="and" id="and" size="35" />
                    </td>
                </tr>
                <tr>
                    <td>With the <b>exact phrase</b></td>
                    <td>
                        <input type="text" name="phrase" id="phrase" size="35" />
                    </td>
                </tr>
                <tr>
                    <td>With <b>at least</b> one of the words</td>
                    <td>
                        <input type="text" name="or" id="or" size="35" />
                    </td>
                </tr>
                <tr>
                    <td><b>Without</b> the words</td>
                    <td>
                        <input type="text" name="not" id="not" size="35" />
                    </td>
                </tr>
                <tr>
                    <td>With the <b>approximate phrase</b></td>
                    <td>
                        <input type="text" id="proxphrase" size="35" /> <br/> within <input type="text" id="proximity" size="3" /> words of each other
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Between the range of</b>
                    </td><td><input type="text" name="rangestart" id="rangestart" size="6" /> and
                        <input type="text" name="rangeend" id="rangeend" size="6" />
                    </td>
                </tr>

            </table>
        </form>
        <br/><br/>
        <h2>Query:</h2>
        <h3 id="output" style="background:#ffe4c4;padding:10px"></h3>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#querybuilder').children().keydown(updateQuery).keyup(updateQuery).change(updateQuery);
            });

            function updateQuery() {
                var and = appendOperator(jQuery('#and').val(), '+');
                var not = appendOperator(jQuery('#not').val(), '-');
                var or = jQuery('#or').val();
                var phrase = jQuery('#phrase').val();
                phrase = jQuery.trim(phrase);
                if (phrase != '')
                    phrase = " \"" + phrase + "\" ";
                var proxphrase = jQuery('#proxphrase').val();
                proxphrase = jQuery.trim(proxphrase);
                if (proxphrase != '')
                    proxphrase = " \"" + proxphrase + "\"~" + jQuery('#proximity').val() + " ";
                var rangestart = jQuery('#rangestart').val();
                var rangeend = jQuery('#rangeend').val();
                var range = '';
                if (rangestart != '' && rangeend != '') {
                    range = "[" + rangestart + " TO " + rangeend + "]";
                }
                jQuery('#output').text(and + not + or + phrase + proxphrase + range);
            }

            function appendOperator(str, op) {
                str = jQuery.trim(str);
                if (str == '')
                    return '';
                var result = '';
                var split = str.split(' ');
                for (var i = 0; i < split.length; ++i) {
                    var s = jQuery.trim(split[i]);
                    if (s == '')
                        continue;
                    result += op + s + ' ';
                }
                return result;
            }
        </script>
    </div>
</div>

<link rel="stylesheet" href="http://www.solrtutorial.com/css/style.css" type="text/css" media="screen" />