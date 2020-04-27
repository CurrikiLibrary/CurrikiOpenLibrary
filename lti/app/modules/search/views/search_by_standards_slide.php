<?php global $search; ?>
<div class="search-slide standards-slide"  style="display: none;">

    <ul id="standards-accordion" class="border-grey rounded-borders-full">
        <li class="standards-accordion-tab"><div class="standards-tab-header"><h3>Jurisdiction/Organization</h3></div>
            <select class="block" size="9" name="jurisdictioncode" id="jurisdictioncode" onchange="getDocumentTitle()"> 
                <?php
                foreach ($search->jurisdictioncode as $ind => $row)
                {
                    $row = (array)$row;
                    echo '<option value="' . $row['jurisdictioncode'] . '">' . $row['jurisdictioncode'] . '</option>';
                }
                ?>
            </select>
        </li>
        <li class="standards-accordion-tab"><div class="standards-tab-header"><h3>Document Title</h3></div>
            <select class="block" size="9" name="standardtitle" id="standardtitle" onchange="getNotation()">
                <?php
                foreach ($search->standardtitles as $ind => $row)
                {
                    $row = (array)$row;
                    echo '<option value="' . $row['standardid'] . '" jurisdictioncode="' . $row['jurisdictioncode'] . '" >' . $row['title'] . '</option>';
                }
                ?>
            </select>
        </li>
        <li class="standards-accordion-tab"><div class="standards-tab-header"><h3>Course of Study</h3></div>
            <select class="block" multiple size="9" name="notations[]" id="notations">
                <option value="">Please Select Document Title First</option>
            </select>
        </li>
    </ul>

    <div class="clearfix"></div>
</div>


<?php
echo "<script>";
echo "var selectednotations = \"" . implode(",", $search->request['notations']) . "\";";
echo "jQuery(document).ready(function () {";
if (isset($search->request['jurisdictioncode']))
    echo "jQuery('#jurisdictioncode').val(\"" . urldecode($search->request['jurisdictioncode']) . "\");jQuery('#jurisdictioncode').change();";
if (isset($search->request['standardtitle']))
    echo "jQuery('#standardtitle').val(\"" . urldecode($search->request['standardtitle']) . "\");jQuery('#standardtitle').change();";
echo "});";
echo "</script>";
?>