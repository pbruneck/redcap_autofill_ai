<?php
// A $module variable will automatically be available and set to an instance of your module.
// It can be used like so:

renderPageTitle("<i class=\"fas fa-robot\"></i>&nbsp;Autofill AI Factory");

// $value = $module->getProjectSetting('my-project-setting');
// More things to do here, if you wish
?>

<?= $module->initializeJavascriptModuleObject() ?>
<?= $module->tt_transferToJavascriptModuleObject() ?>
<script>const module_autofill_ai = <?= $module->getJavascriptModuleObjectName() ?>;</script>
<script>var autofillAIHandlerPath="<?php print $module->getUrl('autofill_ai_handler.php'); ?>";</script>
<script src="<?php print $module->getUrl('js/autofill-ai.js'); ?>" defer></script>

<div id="working"><i class="fa-solid fa-spinner fa-spin-pulse me-3"></i><span>Working …</span></div>

<p style="margin-top:0px;">
    <?=$module->tt("ui_factory_intro")?>
    <br>&nbsp;<br>
    <div class="yellow">
        <?=$module->tt("ui_factory_outlook")?>
    </div>
    <div id="autofill-ai-factory-cyclic-dependencies" class="red" style="display:none;">
        <span id="autofill-ai-factory-cyclic-fields"></span>
    </div>
    <br>
	<a href="javascript:;" onclick="$('#moreInstructions').toggle('fade');" style="text-decoration:underline;">
        &nbsp;<?=$module->tt("ui_factory_read_more")?>
    </a>
</p>

<div id="moreInstructions" style="display:none;margin-top:20px;">
    <div class="p" style="font-weight:bold;font-size:14px;margin-top:15px;">
        <?=$module->tt("ui_factory_intro_element_context_header")?>
    </div>
    <div style="display: flex; gap: 10px;">
        <div style="flex: 0 1 auto; padding: 20px; box-sizing: border-box;">
            <svg xmlns="http://www.w3.org/2000/svg" width="80px" height="80px" viewBox="-0.5 -0.5 527 527">
                <ellipse cx="463" cy="63" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="463" cy="63" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="462" cy="63" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="323" cy="63" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="323" cy="63" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="322" cy="63" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="183" cy="63" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="183" cy="63" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="182" cy="63" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="43" cy="63" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="463" cy="203" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="463" cy="203" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="462" cy="203" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="323" cy="203" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="183" cy="203" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="43" cy="203" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="463" cy="343" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="463" cy="343" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="462" cy="343" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="323" cy="343" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="323" cy="343" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="322" cy="343" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="183" cy="343" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="43" cy="343" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="463" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="323" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="183" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="43" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/>
            </svg>
        </div>
        <div style="flex: 1; padding: 20px; box-sizing: border-box;">
            <p><?=$module->tt("ui_factory_intro_element_context_part_1")?></p>
        </div>
    </div>
    <div style="display: flex; gap: 10px;">
        <div style="flex: 0 1 auto; padding: 20px; box-sizing: border-box;">
            <svg xmlns="http://www.w3.org/2000/svg" width="80px" height="80px" viewBox="-0.5 -0.5 547 547">>
                <ellipse cx="483" cy="63" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="483" cy="63" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="482" cy="63" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="343" cy="63" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="343" cy="63" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="342" cy="63" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="203" cy="63" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="203" cy="63" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="202" cy="63" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="63" cy="63" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="63" cy="63" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="62" cy="63" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="483" cy="203" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="483" cy="203" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="482" cy="203" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="343" cy="203" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="343" cy="203" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="342" cy="203" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="203" cy="203" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="203" cy="203" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="202" cy="203" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="63" cy="203" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="63" cy="203" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="62" cy="203" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="483" cy="343" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="483" cy="343" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="482" cy="343" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="343" cy="343" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="343" cy="343" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="342" cy="343" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="203" cy="343" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="203" cy="343" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="202" cy="343" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="63" cy="343" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="63" cy="343" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="62" cy="343" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="483" cy="483" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="483" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="482" cy="483" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="343" cy="483" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="343" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="342" cy="483" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="203" cy="483" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="203" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="202" cy="483" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="63" cy="483" rx="60" ry="60" fill="rgb(255, 255, 255)" stroke="#e97132" stroke-width="7" pointer-events="all"/><ellipse cx="63" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="62" cy="483" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/>
            </svg>
        </div>
        <div style="flex: 1; padding: 20px; box-sizing: border-box;">
            <p><?=$module->tt("ui_factory_intro_element_context_part_2")?></p>
        </div>
    </div>
</div>

<style>
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(odd) {background-color: #f2f2f2;}
tr:nth-child(even) {background-color: #FFFFFF;}
</style>

<?php


$records = Records::getRecordList($project_id);
$fieldsWithPrompts = array();

if (empty($records)) {
    print("No data!");
    die();
}

$data =  reset(reset(Records::getData('array', reset($records))));
foreach ($data as $field => $value) {
    $prompt = $module->getValueInQuotesActionTag($Proj->metadata[$field]['misc']);
    if (strlen($prompt) > 0) $fieldsWithPrompts += [$field => $prompt];
    // print($record . " / " . $field . " / " . $prompt . "<br>");
}

$result = $module->promptDependencyCheck($fieldsWithPrompts);
$orderedFieldsWithPrompts = $result['fieldsWithPrompts'];
$cyclicFields = $result['cyclicFields'];

$records_json = json_encode(array_map('trim', array_values($records)));
$fields_json = json_encode(array_keys($orderedFieldsWithPrompts));

?>

<script>
var records_to_autofill = <?=$records_json?>;
var fields_to_autofill = <?=$fields_json?>;
var cyclic_fields = '<?=$cyclicFields?>';

function autofill_ai_factory_mode() {
    var mode = $('#autofill-ai-factory-mode').find(":selected").val();

    function table_visibility(flag) {
        if (flag) $('.autofill-ai-table').show();
        else $('.autofill-ai-table').hide();

        if ($('#autofill-ai-factory-table-rows').attr('all_done') == '1') {
            $('#autofill-ai-factory-all-done').show();
        } else {
            $('#autofill-ai-factory-all-done').hide();
        }
    }
    switch (mode) {
        case "0":
            table_visibility(false);
            $('#autofill-ai-factory-no-selection').show();
            $('#autofill-ai-factory-not-yet-implemented').hide();
            $('#autofill-ai-factory-all-done').hide();
            break;
        case "1":
        case "3":
            table_visibility(true);
            if (cyclic_fields !== "") {
                $('#autofill-ai-factory-cyclic-fields').html(module_autofill_ai.tt('ui_factory_cyclic_dependencies', cyclic_fields));
                $('#autofill-ai-factory-cyclic-dependencies').show();
            } else {
                $('#autofill-ai-factory-cyclic-dependencies').hide();
                $('#autofill-ai-factory-cyclic-fields').html('');
            }
            $('#autofill-ai-factory-no-selection').hide();
            $('#autofill-ai-factory-not-yet-implemented').hide();
            break;
        case "2":
        case "4":
            table_visibility(false);
            $('#autofill-ai-factory-no-selection').hide();
            $('#autofill-ai-factory-not-yet-implemented').show();
            $('#autofill-ai-factory-all-done').hide();
        default:
    }
}

function autofill_ai_factory_overwrite_cb() {
    $('#autofill-ai-factory-generate-btn').attr('disabled', false);
    if ($('#autofill-ai-factory-overwrite-cb').is(':checked')) {
        $('.autofill-ai-overwrite-allowed').show();
        $('.autofill-ai-overwrite-denied').hide();
        $('#autofill-ai-factory-all-done').hide();
    } else {
        $('.autofill-ai-overwrite-allowed').hide();
        $('.autofill-ai-overwrite-denied').show();
        if ($('#autofill-ai-factory-table-rows').attr('all_done') == '1') {
            $('#autofill-ai-factory-generate-btn').attr('disabled', true);
            $('#autofill-ai-factory-all-done').show();
        }
    }
}

</script>

<div id="autofill-ai-factory-parent">
    <div class="flexigrid" id="autofill-ai-factory" style="width: 1100px;">
		<div class="mDiv">
			<div class="ftitle">
            <div style="width:1030px">
                <div style="float:left;font-size:15px;padding:15px 0 0 10px;">
                    <!-- 
                    <svg xmlns="http://www.w3.org/2000/svg" width="80px" height="80px" viewBox="-0.5 -0.5 548 548">
                        <ellipse cx="483" cy="63" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="482" cy="63" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="343" cy="63" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="342" cy="63" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="203" cy="63" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="202" cy="63" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="63" cy="63" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="483" cy="203" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="482" cy="203" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="343" cy="203" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="203" cy="203" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="63" cy="203" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="483" cy="343" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="482" cy="343" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="343" cy="343" rx="40" ry="40" fill="rgb(255, 255, 255)" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="342" cy="343" rx="20" ry="20" fill="#e97132" stroke="none" pointer-events="all"/><ellipse cx="203" cy="343" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="63" cy="343" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="483" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="343" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="203" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><ellipse cx="63" cy="483" rx="40" ry="40" fill="#156082" stroke="#156082" stroke-width="7" pointer-events="all"/><path d="M 483 3 C 516.14 3 543 29.86 543 63 C 543 78.91 536.68 94.17 525.43 105.43 C 514.17 116.68 498.91 123 483 123" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="all"/><path d="M 63 3 C 96.14 3 123 29.86 123 63 C 123 78.91 116.68 94.17 105.43 105.43 C 94.17 116.68 78.91 123 63 123" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" transform="rotate(-180,63,63)" pointer-events="all"/><path d="M 63 3 L 483 3" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="stroke"/><path d="M 63 123 L 483 123" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="stroke"/><path d="M 483 143 C 516.14 143 543 169.86 543 203 C 543 218.91 536.68 234.17 525.43 245.43 C 514.17 256.68 498.91 263 483 263" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="all"/><path d="M 63 143 C 96.14 143 123 169.86 123 203 C 123 218.91 116.68 234.17 105.43 245.43 C 94.17 256.68 78.91 263 63 263" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" transform="rotate(-180,63,203)" pointer-events="all"/><path d="M 63 143 L 483 143" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="stroke"/><path d="M 63 263 L 483 263" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="stroke"/><path d="M 483 283 C 516.14 283 543 309.86 543 343 C 543 358.91 536.68 374.17 525.43 385.43 C 514.17 396.68 498.91 403 483 403" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="all"/><path d="M 63 283 C 96.14 283 123 309.86 123 343 C 123 358.91 116.68 374.17 105.43 385.43 C 94.17 396.68 78.91 403 63 403" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" transform="rotate(-180,63,343)" pointer-events="all"/><path d="M 63 283 L 483 283" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="stroke"/><path d="M 63 403 L 483 403" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="stroke"/><path d="M 483 423 C 516.14 423 543 449.86 543 483 C 543 498.91 536.68 514.17 525.43 525.43 C 514.17 536.68 498.91 543 483 543" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="all"/><path d="M 63 423 C 96.14 423 123 449.86 123 483 C 123 498.91 116.68 514.17 105.43 525.43 C 94.17 536.68 78.91 543 63 543" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" transform="rotate(-180,63,483)" pointer-events="all"/><path d="M 63 423 L 483 423" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="stroke"/><path d="M 63 543 L 483 543" fill="none" stroke="#e97132" stroke-width="7" stroke-miterlimit="10" pointer-events="stroke"/>
                    </svg>
                    -->
                    Records Status
                    <div style="float:right;font-weight:normal;color:#333;">
					    <span id="autofill-ai-factory-options">
                            <span style="padding-left:20px;vertical-align: top;">
                                <select id="autofill-ai-factory-mode" class="x-form-text x-form-field" style="max-width:300px;height: 100%;font-size:11px;margin-left:3px;vertical-align: middle;" onchange="autofill_ai_factory_mode();">
                                    <option value="0"><?=$module->tt("ui_factory_select_mode_0")?></option>
                                    <option value="1"><?=$module->tt("ui_factory_select_mode_1")?></option>
                                    <option value="2" disabled="disabled"><?=$module->tt("ui_factory_select_mode_2")?></option>
                                    <option value="3" disabled="disabled"><?=$module->tt("ui_factory_select_mode_3")?></option>
                                    <option value="4" disabled="disabled"><?=$module->tt("ui_factory_select_mode_4")?></option>
                                </select>
                            </span>
                            <span class="autofill-ai-table" id="autofill-ai-factory-overwrite" style="font-size:11px;padding-left:20px;display:none;">
                                <span style="float:right;font-weight:normal;color:#333;padding-left:5px;vertical-align: baseline;">
                                    <input type="checkbox" class="x-form-text x-form-field" id="autofill-ai-factory-overwrite-cb" style="vertical-align: middle;" onclick="autofill_ai_factory_overwrite_cb();">
                                        <span style="vertical-align: middle;">&nbsp;<?=$module->tt("ui_factory_overwrite_mode")?></span>
                                    </input>
                                </span>
                                <select id="choose_field" style="float:right;padding-left:5px;max-width:100px;font-size:11px;" onchange="" disabled="disabled">
                                    <option value="" selected="selected"><?=$module->tt("ui_factory_all_fields_selected")?></option>
<?php
$metadata = \REDCap::getDataDictionary($project_id, 'array', false);
// error_log(print_r($metadata, true));
foreach ($metadata as $field => $data) {
    print('<option value="' . $field . '">' . $field . ' (' . $data['field_label'] . ')</option>' . PHP_EOL);
}
?>
                                </select>
                                <select id="choose_field" style="float:right;padding-left:5px;max-width:100px;font-size:11px;" onchange="" disabled="disabled">
                                    <option value="" selected="selected"><?=$module->tt("ui_factory_all_records_selected")?></option>
<?php
$metadata = \REDCap::getDataDictionary($project_id, 'array', false);
// error_log(print_r($metadata, true));
foreach ($records as $record) {
    print('<option value="' . $record . '">' . $record . '</option>' . PHP_EOL);
}
?>
                                </select>
                            </span>
					    </span>
                    </div>
				</div>
                <div style="float:right;font-size:15px;padding:15px 0 0 10px;">
                    <button id="autofill-ai-factory-generate-btn" class="btn btn-xs btn-rcgreen fs12 ms-2 autofill-ai-table" onclick="autoFillAIFactoryMultiplePostRequests(records_to_autofill, fields_to_autofill, $('#autofill-ai-factory-overwrite-cb').is(':checked'));" style="display:none;"><?=$module->tt("ui_factory_generate_btn")?></button>
                    <?=$module->help_button()?>
				</div>
                <br>
                <br>
                <div style="clear:both;height:0;padding:0;"></div>
            </div>
		</div>
    </div>
    <div class="hDiv autofill-ai-table" id="autofill-ai-factory-table-header" style="display:none;">
		<div class="hDivBox">
			<table>
                <tbody>
                    <tr id="autofill-ai-factory-table-header-1">
                        <th style="cursor:initial;" align="center">
                            <div style="text-align:center;width:100px;font-weight:bold;">
                            Record ID
                            </div>
                        <th style="cursor:initial;">
                            <div style="width:400px;font-weight:bold;">
                            <!-- Already prefilled fields -->Status
                            </div>
                        </th><th style="cursor:initial;">
                            <div style="width:600px;font-weight:bold;">
                            <!-- Fields need to be filled out -->Result
                            </div>
                        </th>
                    </tr>
                </tbody>
            </table>
		</div>
	</div>
    <div class="bDiv autofill-ai-table" id="autofill-ai-factory-table-rows" style="display:none;" all_done="0">
        <table>
            <tbody id="autofill-ai-factory-table-rows-1">
<?php
$nmax = count($fieldsWithPrompts);
$button_flag = false;

foreach ($records as $record) {
    $data =  reset(reset(Records::getData('array', $record)));
    $n = 0;
    $empty_fields = array();
    $all_fields = array();
    foreach ($fieldsWithPrompts as $field => $prompt) {
        $value = $data[$field];
        array_push($all_fields, $field);

        if (gettype($value) == 'array') {
            if (!empty($value)) ++$n;
            else array_push($empty_fields, $field);
        }

        if (gettype($value) == 'string') {
            if (strlen(trim($value)) > 0) ++$n;
            else array_push($empty_fields, $field);
        }
    }
?>

                <tr>
                    <td style="cursor:initial;" align="center">
                        <div style="text-align:center;width:100px;"><b><?php print($record); ?></b></div>
                    </td>
                    <td style="cursor:initial;">
                        <div class="autofill-ai-overwrite-denied" style="width:400px;">
<?php
    print(sprintf("%d/%d field(s) filled out", $n, $nmax));
    if ($nmax - $n > 0) {
        print(sprintf("<br><b>%d field(s) need to be filled out:</b><br>", $nmax - $n));
        print('<em>' . implode(",", $empty_fields) . '</em>');
        $button_flag = true;
    }
?>
                        </div>
                        <div class="autofill-ai-overwrite-allowed" style="width:400px;display:none;">
<?php
    print(sprintf("%d/%d field(s) filled out", $n, $nmax));
    print(sprintf("<br><b>%d field(s) need to be filled out:</b><br>", $nmax));
    print('<em>' . implode(",", $all_fields) . '</em>');
?>
                        </div>
                    </td>
                    <td style="cursor:initial;">
                        <div style="width:600px;">
                            <span span_id="<?= $record ?>" span_record_complete="<?= ($nmax == $n) ? 1: 0 ?>">
                            </span>
                        </div>
                    </td>
                </tr>

<?php

}

?>

            </tbody>
        </table>
	</div>
</div>
<script>
<?php
if ($button_flag) {
    echo("$('#autofill-ai-factory-generate-btn').attr('disabled', false);" . PHP_EOL);
    echo("$('#autofill-ai-factory-table-rows').attr('all_done', '0');" . PHP_EOL);
} else {
    echo("$('#autofill-ai-factory-generate-btn').attr('disabled', true);" . PHP_EOL);
    echo("$('#autofill-ai-factory-table-rows').attr('all_done', '1');" . PHP_EOL);
}
?>
</script>
<br><br>
<div id="autofill-ai-factory-no-selection" class="yellow">
    Select an operation mode first.
    <a href="javascript:;" onclick="$('#moreInstructions').toggle('fade');" style="text-decoration:underline;">Read more ...</a>
</div>
<div id="autofill-ai-factory-not-yet-implemented" class="red" style="display:none;">
    This feature hasn't been implemented yet.
</div>
<div id="autofill-ai-factory-all-done" class="green" style="display:none;">
    Nothing to do.
</div>
