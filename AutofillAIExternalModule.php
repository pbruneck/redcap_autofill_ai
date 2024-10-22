<?php

namespace DE\CHARITE\AutofillAIExternalModule;

use ExternalModules\AbstractExternalModule;

use \Records as Records;
use \Proj as Proj;

class AutofillAIExternalModule extends AbstractExternalModule {

    // This is generally where your module's hooks will live
    function redcap_every_page_top($project_id) {
        // example how to retrieve a config setting
        // print_r($this->getProjectSettings()['llm-provider']);
    }

	function help_button() {
		return "<span style=\"color:#808080;font-size:11px;margin-right:6px;padding-left:5px;font-style:normal;\">" .
			$this->tt("ui_how_to_use") .
			"</span><button class=\"btn btn-xs ms-2\" style=\"line-height: 14px;padding:1px 3px;font-size:11px;background-color:#608050;color:#FFFFFF;\" onclick=\"window.open('" .
			$this->getUrl('README.md') .
			"', '_blank');\"><i style=\"color:#E8FFE8;\" class=\"fa-solid fa-robot\"></i>&nbsp;" .
			$this->tt("module_name") .
			"</button>";
	}

    function getValueInQuotesActionTag($field_annotation, $actionTag="@AUTOFILL-AI")
    {
        preg_match("/(".$actionTag."\s*=\s*)((\"[^\"]+\")|('[^']+'))/", $field_annotation, $matches);
        if (isset($matches[2]) && $matches[2] != '') {
            $value = substr($matches[2], 1, -1);
        } else {
            $value = '';
        }

        return $value;
    }

    function modifyWithinQuotesActionTag($field_annotation, $replacement, $actionTag="@AUTOFILL-AI")
    {
        $misc = "";
        preg_match("/(".$actionTag."\s*=\s*)((\"[^\"]+\")|('[^']+'))/", $field_annotation, $matches);
        if (isset($matches[2]) && $matches[2] != '') {
            $misc = str_replace($matches[0], $actionTag . "='" . $replacement . "'", $field_annotation);
        } else {
            $misc = $field_annotation;
        }

        return $misc;
    }

	function getFieldTypeSystemPrompt($project_id, $field_type) {
		$prompt = $field_type . " is an invalid field type.";

		$field_type_label = $field_type;
		if ($field_type == 'radio' || $field_type == 'dropdown') $field_type_label = 'radio-dropdown';

		if (strlen($this->getProjectSettings()['llm-system-prompt-' . $field_type_label]) > 0)
			$prompt = $this->getProjectSettings()['llm-system-prompt-' . $field_type_label];

		return $prompt;
	}

    function createFieldValidationPrompt($validation_type) {
		$prompt = "";

		$template = "Only {UNIT} in the format {FORMAT} is accepted.";

		switch ($validation_type) {
			case 'date_ymd':
				$prompt = strtr($template, array('{UNIT}' => 'date', '{FORMAT}' => 'YYYY-MM-DD'));
				break;
			case "date_mdy":
				$prompt = strtr($template, array('{UNIT}' => 'date', '{FORMAT}' => 'MM/DD/YYYY'));
				break;
			case "date_dmy":
				$prompt = strtr($template, array('{UNIT}' => 'date', '{FORMAT}' => 'DD/MM/YYYY'));
				break;
			case "datetime_ymd":
				$prompt = strtr($template, array('{UNIT}' => 'timestamp', '{FORMAT}' => 'YYYY-MM-DD HH:MM'));
				break;
			case "datetime_mdy":
				$prompt = strtr($template, array('{UNIT}' => 'timestamp', '{FORMAT}' => 'MM/DD/YYYY HH:MM'));
				break;
			case "datetime_dmy":
				$prompt = strtr($template, array('{UNIT}' => 'timestamp', '{FORMAT}' => 'DD/MM/YYYY HH:MM'));
				break;
			case "datetime_seconds_ymd":
				$prompt = strtr($template, array('{UNIT}' => 'timestamp', '{FORMAT}' => 'YYYY-MM-DD HH:MM:SS'));
				break;
			case "datetime_seconds_mdy":
				$prompt = strtr($template, array('{UNIT}' => 'timestamp', '{FORMAT}' => 'MM/DD/YYYY HH:MM:SS'));
				break;
			case "datetime_seconds_dmy":
				$prompt = strtr($template, array('{UNIT}' => 'timestamp', '{FORMAT}' => 'DD/MM/YYYY HH:MM:SS'));
				break;
			case "time":
				$prompt = strtr($template, array('{UNIT}' => 'time', '{FORMAT}' => 'HH:MM'));
				break;
			case "time_mm_ss":
				$prompt = strtr($template, array('{UNIT}' => 'time', '{FORMAT}' => 'MM:SS'));
				break;
			case "time_hh_mm_ss":
				$prompt = strtr($template, array('{UNIT}' => 'time', '{FORMAT}' => 'HH:MM:SS'));
				break;
			default:
		}

		$template = "Must be an floating point number with {DECIMAL} as decimal and {PLACES} decimal places.";

		if (strlen($prompt) > 0) return $prompt;

		switch ($validation_type) {
			case 'email':
				$prompt = "Must be an plausible email adress.";
				break;
			case 'integer':
				$prompt = "Must be an integer number.";
				break;
			case 'zip':
			case 'zipcode':
				$prompt = "Must be a valid zip code. ";
				break;
			case 'phone':
				$prompt = "Must be an US phone number in the format (XXX) XXX-XXXX.";
				break;
			case 'number_comma_decimal':
				$prompt = "Must be an floating point number with comma as decimal.";
				break;
			case 'number':
				$prompt = "Must be an floating point number with dot as decimal.";
				break;
			case 'number_1dp_comma_decimal':
				$prompt = strtr($template, array('{DECIMAL}' => 'comma', '{PLACES}' => '1'));
				break;
			case 'number_1dp':
				$prompt = strtr($template, array('{DECIMAL}' => 'dot', '{PLACES}' => '1'));
				break;
			case 'number_2dp_comma_decimal':
				$prompt = strtr($template, array('{DECIMAL}' => 'comma', '{PLACES}' => '2'));
				break;
			case 'number_2dp':
				$prompt = strtr($template, array('{DECIMAL}' => 'dot', '{PLACES}' => '2'));
				break;
			case 'number_3dp_comma_decimal':
				$prompt = strtr($template, array('{DECIMAL}' => 'comma', '{PLACES}' => '3'));
				break;
			case 'number_3dp':
				$prompt = strtr($template, array('{DECIMAL}' => 'dot', '{PLACES}' => '3'));
				break;
			case 'number_4dp_comma_decimal':
				$prompt = strtr($template, array('{DECIMAL}' => 'comma', '{PLACES}' => '4'));
				break;
			case 'number_4dp':
				$prompt = strtr($template, array('{DECIMAL}' => 'dot', '{PLACES}' => '4'));
				break;
			default:
		}

		return $prompt;
	}

	function createFieldValidationJSONSchema($validation_type) {
		// patterns in JSON schema are currently not supported by OpenAI but could like this for MM/DD/YYYY date format
		// $pattern = "^(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)\\d\\d$";

		$schema = [
			"type" => "json_schema",
			"json_schema" => [
				"name" => "date_response",
				"strict" => true,
				"schema" => [
					"type" => "object",
					"properties" => [
						"result" => [
							"type" => "string"
						]
					],
					"required" => [ "result" ],
					"additionalProperties" => false
				]
			]
		];

		return $schema;
	}

	function getSystemPrompt($project_id, $field_type, $field_label, $field_enum, $field_validation, $system_prompt, $field_prompt) {
		$prompt_freetext = $this->getProjectSettings()['llm-system-prompt-freetext'];
		$prompt_no_freetext = $this->getProjectSettings()['llm-system-prompt-no-freetext'];

		$field_type_label = $field_type;
		if ($field_type == 'radio' || $field_type == 'dropdown') $field_type_label = 'radio-dropdown';

		switch ($field_type_label) {
			case 'text':
				$prompt = strlen($field_prompt) == 0? $this->getProjectSettings()['llm-system-prompt-text']: $field_prompt;
				$prompt =  str_replace('[prompt-general]', $prompt_no_freetext, $prompt);

				$field_validation_prompt = $this->createFieldValidationPrompt($field_validation);
				$prompt = str_replace('[prompt-validation]', $field_validation_prompt, $prompt);

				if (strlen($field_label) > 0) $prompt =  str_replace('[prompt-label]', $field_label, $prompt);
				break;
			case 'notes':
				$prompt = strlen($field_prompt) == 0? $this->getProjectSettings()['llm-system-prompt-notes']: $field_prompt;
				$prompt =  str_replace('[prompt-general]', $prompt_freetext, $prompt);
				if (strlen($field_label) > 0) $prompt =  str_replace('[prompt-label]', $field_label, $prompt);
				break;
			case 'radio-dropdown':
				$prompt = strlen($field_prompt) == 0? $this->getProjectSettings()['llm-system-prompt-radio-dropdown']: $field_prompt;
				$prompt =  str_replace('[prompt-general]', $prompt_no_freetext, $prompt);
				if (strlen($field_label) > 0) $prompt =  str_replace('[prompt-label]', $field_label, $prompt);
				if (strlen($field_enum) > 0) {
					$field_enum = str_replace("\\n", "\n", $field_enum);
					$prompt =  str_replace('[prompt-choices]', "\n\ncode,fact\n" . $field_enum . "\n\n", $prompt);
				}
				break;
			case 'checkbox':
				$prompt = strlen($field_prompt) == 0? $this->getProjectSettings()['llm-system-prompt-checkbox']: $field_prompt;
				$prompt =  str_replace('[prompt-general]', $prompt_no_freetext, $prompt);
				if (strlen($field_label) > 0) $prompt =  str_replace('[prompt-label]', $field_label, $prompt);
				if (strlen($field_enum) > 0) {
					$field_enum = str_replace("\\n", "\n", $field_enum);
					$prompt =  str_replace('[prompt-choices]', "\n\ncode,fact\n" . $field_enum . "\n\n", $prompt);
				}
				break;
			case 'yesno':
				$prompt = strlen($field_prompt) == 0? $this->getProjectSettings()['llm-system-prompt-yesno']: $field_prompt;
				$prompt =  str_replace('[prompt-general]', $prompt_no_freetext, $prompt);
				if (strlen($field_label) > 0) $prompt =  str_replace('[prompt-label]', $field_label, $prompt);
				if (strlen($field_enum) > 0) {
					$field_enum = str_replace("\\n", "\n", $field_enum);
					$prompt =  str_replace('[prompt-choices]', "\n\ncode,fact\n" . $field_enum . "\n\n", $prompt);
				}
				break;
			case 'truefalse':
				$prompt = strlen($field_prompt) == 0? $this->getProjectSettings()['llm-system-prompt-truefalse']: $field_prompt;
				$prompt =  str_replace('[prompt-general]', $prompt_no_freetext, $prompt);
				if (strlen($field_label) > 0) $prompt =  str_replace('[prompt-label]', $field_label, $prompt);
				if (strlen($field_enum) > 0) {
					$field_enum = str_replace("\\n", "\n", $field_enum);
					$prompt =  str_replace('[prompt-choices]', "\n\ncode,fact\n" . $field_enum . "\n\n", $prompt);
				}
				break;
			case 'slider':
				$prompt = strlen($field_prompt) == 0? $this->getProjectSettings()['llm-system-prompt-slider']: $field_prompt;
				$prompt =  str_replace('[prompt-general]', $prompt_no_freetext, $prompt);
				if (strlen($field_label) > 0) $prompt =  str_replace('[prompt-label]', $field_label, $prompt);
				if (strlen($field_enum) > 0) {
					$field_enum = str_replace("\\n", "\n", $field_enum);
					$prompt =  str_replace('[prompt-choices]', "\n\ncode,fact\n" . $field_enum . "\n\n", $prompt);
				}
				break;
			default:
			    $prompt = $prompt_freetext;
		}

		return $prompt;
	}

	function inject_html_and_js($project_id) {
		
		// https://github.com/vanderbilt-redcap/external-module-framework-docs/blob/main/javascript.md
		
?>

<?= $this->initializeJavascriptModuleObject() ?>
<?= $this->tt_transferToJavascriptModuleObject() ?>
<script>const module_autofill_ai = <?= $this->getJavascriptModuleObjectName() ?>;</script>
<script>const autofillAIHandlerPath="<?php print $this->getUrl('autofill_ai_handler.php'); ?>";</script>
<script src="<?php print $this->getUrl('js/autofill-ai.js'); ?>" defer></script>

<!-- adapted from Design/online_designer.php -> LOGIC BUILDER DIALOG POP-UP -->

<div id="autofill_ai_workbench" title='<span style="color:#608050;"><i style="color:#608050;" class="fa-solid fa-robot"></i><span>&nbsp;<?=$this->tt("ui_dialog_title")?></span></span>' style="display:none;">
	<p style="line-height: 1.2em;font-size:12px;border-bottom:1px solid #ccc;padding-bottom:10px;margin:5px 0 0;">
		<?=$this->tt("ui_dialog_intro")?><br>&nbsp;<br><?=$this->help_button()?>
	</p>

	<div style="padding-top:10px;">
		<table cellspacing="0" width="100%">

			<tr>
				<td valign="top" colspan="2" style="padding-bottom:4px;font-family:verdana;color:#777;font-weight:bold;">
					<div style="width:700px;overflow:hidden;text-overflow:ellipsis;">
						<?=$this->tt("ui_dialog_headline")?>
						<span id="autofill_ai_workbench_field" style="color:#008000;padding-left:4px;"></span><br>
						<span style="color:#008000;font-weight:normal;"><i id="autofill_ai_workbench_label"></i><br><i id="autofill_ai_workbench_enum" style="font-size: smaller;"></i></span>
					</div>
				</td>
			</tr>

			<!-- Autofill AI text box -->
			<tr>
				<td valign="top">
					<div style="font-weight:bold;padding:15px 20px 0 0;color:#800000;font-family:verdana;">
						<?=$this->tt("ui_dialog_prompt_section")?>
					</div>
					<div id="autofill_ai_workbench_advanced" class="chklist" style="border:1px solid #ccc;padding:8px 10px 2px;margin:5px 0 15px;max-width: 710px;">
						<div id="autofill_ai_workbench_system_prompt_div" style="padding-bottom:2px;display:none;">
							<table style='width: 98%; border: 0;'>
								<tr>
									<td colspan='2' style=' width: 45%; border: 0;'>
									<?=$this->tt("ui_dialog_system_prompt_instruction")?>
										<textarea id="autofill_ai_workbench_system_prompt" hasrecordevent="0" style="padding:1px;width:100%;height:65px;resize:auto;" onblur="" onkeydown="" onfocus=""></textarea>
									</td>
									<td colspan='2' style=' width: 45%; border: 0;'>
									<?=$this->tt("ui_dialog_field_prompt_instruction")?>&nbsp;<span id="autofill_ai_workbench_field_type"></span>
										<textarea id="autofill_ai_workbench_field_type_prompt" hasrecordevent="0" style="padding:1px;width:100%;height:65px;resize:auto;" onblur="" onkeydown="" onfocus=""></textarea>
									</td>
								</tr>
							</table>
						</div>
						<div style="padding-bottom:2px;">
							<?=$this->tt("ui_dialog_prompt_instruction")?>
							<table style='width: 98%; border: 0;'>
								<tr>
									<td colspan='2' style=' width: 100%; border: 0;'>
										<textarea id="autofill_ai_workbench_prompt" hasrecordevent="0" style="padding:1px;width:100%;height:65px;resize:auto;" onblur="" onblur="" onkeydown="" onfocus=""></textarea>
									</td>
								</tr>
								<tr>
									<td style='border: 0; font-weight: bold; text-align: left; vertical-align: middle; height: 20px;' id='autofill_ai_workbench_placeholder_1'>&nbsp;</td>
									<td style='border: 0; text-align: right; vertical-align: top;padding-right:10px;'><a id="autofill_ai_workbench_clear_prompt" style="font-family:tahoma;font-size:11px;text-decoration:underline;" href="javascript:;" onclick="$('#autofill_ai_workbench_prompt').val('');$('#autofill_ai_workbench_raw_header').html('&nbsp;');$('#autofill_ai_workbench_result_raw').html('');$('#autofill_ai_workbench_raw_footer').html('&nbsp;');"><?=$this->tt("ui_dialog_clear_prompt_btn")?></a></td>
								</tr>
							</table>
						</div>
						<div style="display: flex; gap: 10px;">
							<div style="flex: 0 1 auto; padding: 20px; box-sizing: border-box;">
								<button id="autofill_ai_workbench_edit_system_prompt_button" class="jqbuttonmed ui-button ui-corner-all ui-widget" style="padding:.4em 1em;text-align:left;"></button>
							</div>
							<div style="flex: 1; padding: 20px; box-sizing: border-box;">
								<span class="logicTesterRecordDropdownLabel"><?=$this->tt("ui_dialog_record_dd")?></span>
								<select id="autofill_ai_workbench_record_dropdown" class="fs11 x-form-text x-form-field" style="" onchange="">
									<option value=""><?=$this->tt("ui_dialog_no_record_opt")?></option>
<?php
	$records = Records::getRecordList($project_id);
	foreach ($records as $record) {
		print('<option value="' . $record . '">' . $record . '</option>' . "\n");
	}
?>
								</select>
							</div>
							<div style="flex: 0 1 auto; padding: 20px; box-sizing: border-box;">
								<button id="autofill_ai_workbench_generate_button" class="jqbuttonmed ui-button ui-corner-all ui-widget" style="padding:.4em 1em;text-align:right;"><?=$this->tt("ui_dialog_generate_btn")?></button>
							</div>
						</div>
					</div>
				</td>
			</tr>

			<tr>
				<td valign="top">
					<div style="font-weight:bold;padding:15px 20px 0 0;font-family:verdana;color:#800000;">
						<?=$this->tt("ui_dialog_result_section")?>
					</div>
					<div class="chklist" style="height:270px;border:1px solid #ccc;padding:10px 10px 2px;margin:5px 0;">			
						<table cellspacing="0">
							<tr>
								<td valign="bottom" style="width:290px;padding:20px 2px 2px;">
									<!-- Div containing options to drag over -->
									<b><?=$this->tt("ui_dialog_raw_output_title")?></b><br>
									<span id="autofill_ai_workbench_raw_header">&nbsp;</span>
									<div class="listBox" id="autofill_ai_workbench_result_raw" style="height:150px;overflow:auto;cursor:move;">
										<ul id="autofill_ai_workbench_result_raw_list"></ul>
									</div>
									<span id="autofill_ai_workbench_raw_footer">&nbsp;</span>
								</td>
								<td valign="middle" style="text-align:center;font-weight:bold;font-size:11px;color:green;padding:0px 20px;">
									<img src="<?php echo APP_PATH_IMAGES ?>arrow_right.png">
									<?=$this->tt("ui_dialog_arrows_label")?>
									<img src="<?php echo APP_PATH_IMAGES ?>arrow_right.png">
								</td>
								<td valign="bottom" style="width:290px;padding:0px 2px 2px;">
									<!-- Div where options will be dragged to -->
									<b><?=$this->tt("ui_dialog_interpreted_output_title")?></b><br>
									<span id="autofill_ai_workbench_result_header">&nbsp;</span>
									<div class="tableBox" style="height:150px;overflow:auto;">
                                        <table id="autofill_ai_workbench_result_table" cellpadding="2" cellspacing="0" class="ReportTableWithBorder" style="table-layout:fixed; width:100%;">
                                            <tbody>
                                                <tr valign="top"><td>1</td><td>True</td></tr>
                                                <tr valign="top"><td>0</td><td>False</td></tr>
                                            </tbody>
                                        </table>
									</div>
									<span>&nbsp;</span>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>		
		</table>
	</div>
</div>

<?php


    }

    function renderAutofillAI($project_id, $instrument, $record, $event_id, $repeat_instance) {
        ?>

		<?= $this->initializeJavascriptModuleObject() ?>
		<?= $this->tt_transferToJavascriptModuleObject() ?>
		<script>const module_autofill_ai = <?= $this->getJavascriptModuleObjectName() ?>;</script>
        <script>var autofillAIHandlerPath="<?php print $this->getUrl('autofill_ai_handler.php'); ?>";</script>
        <script src="<?php print $this->getUrl('js/autofill-ai.js'); ?>" defer></script>
<?php
    }

	
	function redcap_every_page_before_render($project_id) {
		// error_log(PAGE);
		if (PAGE == 'Design/online_designer.php') {
			if (isset($_GET["page"])) {
				$page = $_GET["page"];
			   // print '<div class="red">Page: [' . $page . '] </div>';
			   $this->inject_html_and_js($project_id);
			}
		}
	}

    function redcap_data_entry_form ($project_id, $record = NULL, $instrument, $event_id, $group_id = NULL, $repeat_instance = 1) {
        $this->renderAutofillAI($project_id, $instrument, $record, $event_id, $repeat_instance, NULL);
    }

    function redcap_data_entry_form_top($project_id, $record, $instrument, $event_id, $group_id, $repeat_instance) {
		// print '<div class="yellow">Special announcement text to display at the top of every data entry form.</div>';
    }

	function redcap_module_ajax($action, $payload, $project_id, $record, $instrument, $event_id, $repeat_instance, $survey_hash, $response_id, $survey_queue_hash, $page, $page_full, $user_id, $group_id) {
		if (!isset($payload['field_name'])) return array();

		switch ($action) {
			case "load-prompt":
				return $this->ajax_handler_load_prompt($project_id, $payload['field_name']);
			case "save-prompt":
				if (!isset($payload['prompt']) ||
					!isset($payload['system_prompt']) ||
					!isset($payload['field_prompt'])) return array();
				return $this->ajax_handler_save_prompt($project_id, $payload['field_name'], $payload['prompt'], $payload['system_prompt'], $payload['field_prompt']);
			case "generate":
				if (!isset($payload['prompt']) ||
					!isset($payload['system_prompt']) ||
					!isset($payload['field_prompt']) ||
					!isset($payload['record_id'])) return array();
				return $this->ajax_handler_generate($project_id, $payload['field_name'], $payload['prompt'], $payload['system_prompt'], $payload['field_prompt'], $payload['record_id']);
			case "autofill":
				if (!isset($payload['record_id']) ||
					!isset($payload['overwrite_flag']) ||
					!isset($payload['request_number'])) return array();
				$dry_run = 0;
				if (isset($payload['dry_run'])) $dry_run = $payload['dry_run'];
				return $this->ajax_handler_autofill($project_id, $payload['field_name'], $payload['record_id'], $payload['overwrite_flag'], $payload['request_number'], $dry_run);
			default:
		}

		return array();
	}

    function getMetaDataExt($project_id, $field_name) {
		$metadata = \REDCap::getDataDictionary($project_id, 'array', false, $field_name);
		if (!isset($metadata[$field_name])) return false;
		$field_metadata = reset($metadata);

		$field_type = $field_metadata['field_type'];
		$field_label = nl2br(strip_tags(label_decode($field_metadata["field_label"])));
		$field_annotation = $field_metadata["field_annotation"];
		$field_enum = strip_tags(label_decode($field_metadata["select_choices_or_calculations"]));
		$field_validation = strip_tags(label_decode($field_metadata["text_validation_type_or_show_slider_number"]));
		$form_name = $field_metadata["form_name"];

		switch ($field_type) {
			case 'yesno':
				$field_enum = "1, Yes | 0, No";
				break;
			case 'truefalse':
				$field_enum = "1, True | 0, False";
				break;
		}

		if (ends_with($field_name, '_complete') && substr($field_name, 0, -9) == $form_name) {
			$field_enum = "0, Incomplete | 1, Unverified | 2, Complete";
		}

		return array(
			'field_type' => $field_type,
			'field_label' => $field_label,
			'field_annotation' => $field_annotation,
			'field_enum' => $field_enum,
			'field_validation' => $field_validation,
			'form_name' => $form_name
		);
	}

	function promptDependencyCheck($fieldsWithPrompts) {
		$graph = [];
		foreach ($fieldsWithPrompts as $field => $prompt) {
			$bracketed_fields = getBracketedFields($prompt, true, true, true);
			if (!empty($bracketed_fields)) {
				$refs = [];
				foreach ($bracketed_fields as $bracketed_field => $none) {
					array_push($refs, $bracketed_field);
				}
				$graph += [$field => $refs];
			} else {
				$graph += [$field => []];
			}
		}

		$result = $this->kahnDependencySort($graph);
		$orderedFields = $result['orderedFields'];
		$cyclicFieldsHTML = $result['cyclicFields'];

		$orderedFieldsWithPrompts = [];
		foreach ($orderedFields as $orderedField) {
			if (array_key_exists($orderedField, $fieldsWithPrompts)) {
				$orderedFieldsWithPrompts += [$orderedField => $fieldsWithPrompts[$orderedField]];
			}
		}

		return array('fieldsWithPrompts' => $orderedFieldsWithPrompts, 'cyclicFields' => $cyclicFieldsHTML);
	}

	function kahnDependencySort($graph) {
		$in = [];

		foreach ($graph as $node => $neighbors) {
			if (!isset($in[$node])) {
				$in[$node] = 0;
			}

			foreach ($neighbors as $neighbor) {
				if (!isset($in[$neighbor])) {
					$in[$neighbor] = 0;
				}
				$in[$neighbor]++;
			}
		}

		$queue = [];
		foreach ($in as $node => $degree) {
			if ($degree == 0) {
				array_push($queue, $node);
			}
		}

		$dependencyOrder = [];

		// Kahn's algorithm
		while (!empty($queue)) {
			$node = array_shift($queue);
			$dependencyOrder[] = $node;

			foreach ($graph[$node] as $neighbor) {
				$in[$neighbor]--;
				if ($in[$neighbor] == 0) {
					array_push($queue, $neighbor);
				}
			}
		}

		// if not all nodes are covered at least one cycle exists
		if (count($dependencyOrder) !== count($graph)) {
			$cycleNodes = [];

			// not sorted nodes are cyclic
			foreach ($graph as $node => $neighbors) {
				if (!in_array($node, $dependencyOrder)) {
					$cycleNodes[] = $node;
				}
			}

			// create a list of affected nodes
			$cycleMessage = "";
			foreach ($cycleNodes as $cycleNode) {
				foreach ($graph[$cycleNode] as $neighbor) {
					if (in_array($neighbor, $cycleNodes)) {
						$cycleMessage .= $cycleNode . " &rarr; " . $neighbor . "<br>";
					}
				}
			}
		}

		return array('orderedFields' => array_reverse($dependencyOrder), 'cyclicFields' => $cycleMessage);
	}

	function ajax_handler_load_prompt($project_id, $field_name) {
		$meta = $this->getMetaDataExt($project_id, $field_name);

		$field_type_prompt = $this->getFieldTypeSystemPrompt($project_id, $meta['field_type']);

		if ($meta['field_type'] == 'notes') {
			$system_prompt = $this->getProjectSettings()['llm-system-prompt-freetext'];
		} else {
			$system_prompt = $this->getProjectSettings()['llm-system-prompt-no-freetext'];
		}

		$old_prompt = $this->getValueInQuotesActionTag($meta['field_annotation']);
		$old_prompt = str_replace('&quot;', '"', $old_prompt);
		$old_prompt = str_replace('&apos;', '\'', $old_prompt);

		return array('prompt' => $old_prompt, 'field_label' => $meta['field_label'], 'field_type' => $meta['field_type'], 'system_prompt' => $system_prompt, 'field_type_prompt' => $field_type_prompt, 'field_enum'=>$meta['field_enum']);
	}

	function ajax_handler_save_prompt($project_id, $field_name, $prompt, $system_prompt, $field_prompt) {
		$meta = $this->getMetaDataExt($project_id, $field_name);

		$new_prompt = $prompt;
		$new_prompt = str_replace('"', '&quot;', $new_prompt);
		$new_prompt = str_replace('\'', '&apos;', $new_prompt);

		$old_prompt = $this->getValueInQuotesActionTag($meta['field_annotation']);

		$new_field_annotation = "";
		if ($old_prompt == '') {
			$new_field_annotation = "@AUTOFILL-AI='" . $new_prompt . "' " . $meta['field_annotation'];
		} else {
			$new_field_annotation = $this->modifyWithinQuotesActionTag($meta['field_annotation'], $new_prompt);
		}

		$status = $this->query('UPDATE redcap_metadata SET misc = ? WHERE project_id = ? AND field_name = ?', [$new_field_annotation, $project_id, $field_name]);
		// TODO: check status of db update

		if ($system_prompt !== 0) {
			if ($meta['field_type'] == 'notes') {
				$this->setProjectSetting('llm-system-prompt-freetext', $system_prompt);
			} else {
				$this->setProjectSetting('llm-system-prompt-no-freetext', $system_prompt);
			}
		}

		if ($field_prompt !== 0) {
			$field_type_label = $meta['field_type'];
			if ($meta['field_type'] == 'radio' || $meta['field_type'] == 'dropdown') $field_type_label = 'radio-dropdown';

			$this->setProjectSetting('llm-system-prompt-' . $field_type_label, $field_prompt);
		}

		return array('status' => 1);
	}

	function llm_request($provider, $system_prompt, $user_prompt) {
		switch ($provider) {
			case 'openai':
				return $this->llm_request_openai($system_prompt, $user_prompt, false);
				break;
			case 'google':
				return $this->llm_request_google($system_prompt . " " . $user_prompt);
				break;
			default:
		}

		return array('success' => false);
	}

	function llm_cost_calculation($prompt_tokens, $completion_tokens, $total_tokens, $response_len) {
		// cost calculation, adapted from https://levelup.gitconnected.com/reduce-your-openai-api-costs-by-70-a9f123ce55a6

		$exchange_rate = (float) $this->getProjectSettings()['llm-local-currency-rate'];
		$vat_factor = 1.0 + ((float) $this->getProjectSettings()['llm-pricing-vat'])/100.0;
		$pricing_input_tokens = (float) $this->getProjectSettings()['llm-pricing-input-tokens'];
		$pricing_output_tokens = (float) $this->getProjectSettings()['llm-pricing-output-tokens'];

		// calculate the price for input tokens
		$input_price = $prompt_tokens * ($pricing_input_tokens / 1e6) * $vat_factor;

		// calculate the price for input tokens
		$output_price = $completion_tokens * ($pricing_output_tokens / 1e6) * $vat_factor;

		// calculate the total price
		$total_price = $input_price + $output_price;

		$currency = $this->getProjectSettings()['llm-local-currency'];
		$stats = sprintf("%d chars, %d tokens, %.2f/1000 %s", $response_len, $total_tokens, $total_price*$exchange_rate*1000.0, $currency);

		$total_costs_usd = (float) $this->getProjectSettings()['llm-total-costs-usd'];
		$total_costs_usd +=  $total_price;
		$costs_total = sprintf("%.2f %s", $total_costs_usd*$exchange_rate, $currency);

		$this->setProjectSetting('llm-total-costs-usd', sprintf("%f", $total_costs_usd));

		return array('stats' => $stats, 'costs_total' => $costs_total);

	}

	function llm_request_openai($system_prompt, $user_prompt, $json_schema_flag) {
		if ($this->getProjectSettings()['llm-mock-mode']) {
			return array('success' => true, 'response' => "42", 'total_tokens' => 42, 'stats' => "2 chars, 42 tokens, 0.02/1000 USD", 'costs_total' => "0.05 USD");
		}

		$api_key = $this->getProjectSettings()['llm-api-key'];
		$model = $this->getProjectSettings()['llm-model-name'];

		// API URL
		$url = 'https://api.openai.com/v1/chat/completions'; // for GPT-4 and GPT-3.5-turbo
		// use 'https://api.openai.com/v1/completions' for GPT-3 and earlier models

		// Initialize cURL session
		$ch = curl_init($url);

		// Prepare the data you want to send
		$data = [
			"model" => $model, // or "gpt-4" or any other model you are using
			"messages" => [
				[
					"role" => "system",
					"content" => $system_prompt
				],
				[
					"role" => "user",
					"content" => $user_prompt
				]
			],

			"max_tokens" => 500,
			"temperature" => 0.7
		];

		if ($json_schema_flag) {
			$schema = $this->createFieldValidationJSONSchema("string");
			$data += ["response_format" => $schema];
		}

		// Encode the data to JSON
		$json_data = json_encode($data);

		// Set the options for cURL
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $api_key,
		]);

		// if using a http proxy
		curl_setopt($ch, CURLOPT_PROXY, PROXY_HOSTNAME);
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, PROXY_USERNAME_PASSWORD);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

		// Execute the cURL request
		$response = curl_exec($ch);

		// FIXME: error handling

		// Close the cURL session
		curl_close($ch);

		// Decode and display the response
		$response_data = json_decode($response, true);

		$response = $response_data['choices'][0]['message']['content'];
		$response_len = strlen($response);

		if ($json_schema_flag) {
			$response = json_decode($response, true)['result'];
		}

		$prompt_tokens = (float) $response_data['usage']['prompt_tokens'];
		$completion_tokens = (float) $response_data['usage']['completion_tokens'];
		$total_tokens = (int) $response_data['usage']['total_tokens'];

		$cost_stats = $this->llm_cost_calculation($prompt_tokens, $completion_tokens, $total_tokens, $response_len);

		return array('success' => true, 'response' => $response, 'total_tokens' => $total_tokens, 'stats' => $cost_stats['stats'], 'costs_total' => $cost_stats['costs_total']);
	}

	function llm_request_google($prompt) {
		if ($this->getProjectSettings()['llm-mock-mode']) {
			return array('success' => true, 'response' => "42", 'total_tokens' => 42, 'stats' => "2 chars, 42 tokens, 0.02/1000 USD", 'costs_total' => "0.05 USD");
		}

		$api_key = $this->getProjectSettings()['llm-api-key'];
		$model = $this->getProjectSettings()['llm-model-name'];

		$text = filter_var($textPrompt, FILTER_SANITIZE_STRING);
		$base_url = "https://generativelanguage.googleapis.com/v1";
		$url = sprintf("%s/models/%s:generateContent?key=%s", $base_url, $model, $api_key);

		// Initialize cURL session
		$ch = curl_init($url);

		// Prepare the data you want to send
		$data = [
			"contents" => [
				[
					"role" => "user",
					"parts" => [
						[
							"text" => filter_var($prompt, FILTER_SANITIZE_STRING)
						]
					]
				]
			]
		];

		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		// if using a http proxy
		curl_setopt($ch, CURLOPT_PROXY, PROXY_HOSTNAME);
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, PROXY_USERNAME_PASSWORD);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		// Execute the cURL request
		$response = curl_exec($ch);

		// FIXME: error handling

		// Close the cURL session
		curl_close($ch);

		// Decode and display the response
		$response_data = json_decode($response, true);

		$response = $response_data['candidates'][0]['content']['parts'][0]['text'];
		$response_len = strlen($response);

		$prompt_tokens = (float) $response_data['usageMetadata']['promptTokenCount'];
		$completion_tokens = (float) $response_data['usageMetadata']['candidatesTokenCount'];
		$total_tokens = (int) $response_data['usageMetadata']['totalTokenCount'];

		$cost_stats = $this->llm_cost_calculation($prompt_tokens, $completion_tokens, $total_tokens, $response_len);

		return array('success' => true, 'response' => $response, 'total_tokens' => $total_tokens, 'stats' => $cost_stats['stats'], 'costs_total' => $cost_stats['costs_total']);

	}

	function resolve_bracketed_fields($project_id, $field_name, $record_id, $user_prompt) {
		$bracketed_fields = getBracketedFields($user_prompt, true, true, true);

		if (!empty($bracketed_fields)) {
			if (strlen($record_id) > 0) {
				$data = \Records::getData('array', $record_id);
				if (!empty($data)) {
					$data = reset(reset($data));

					foreach ($bracketed_fields as $field => $x) {
						$meta_bf = $this->getMetaDataExt($project_id, $field);

						$type = $meta_bf["field_type"];
						$form = $meta_bf["form_name"];
						$enum = preg_replace('/ \| /', "\\n", $meta_bf['field_enum']);

						switch ($type) {
							case 'yesno':
								$enum = "1, Yes \\n 0, No";
								break;
							case 'truefalse':
								$enum = "1, True \\n 0, False";
								break;
						}

						/*
						if (ends_with($field, '_complete') && substr($field, 0, -9) == $form) {
							$enum = "0, Incomplete \\n 1, Unverified \\n 2, Complete";
							$type = "dropdown";
						}
						*/

						$enum_array = ($enum == '') ? array() : parseEnum($enum);

						$value = $data[$field];
						if (gettype($value) == "array") {
							$temp_value = array();
							foreach ($value as $k => $v) {
								if ($v == 1) array_push($temp_value, trim($enum_array[$k]));
							}
							$value = implode(", ", $temp_value);
						}
						else {
							if ($type == "radio" || $type == "dropdown" || $type == "yesno" || $type == "truefalse") {
								$value = trim($enum_array[$value]);
							}
						}
						$user_prompt = preg_replace('/\[' . $field . '\]/', $value, $user_prompt);
					}
				}
			} else {
				foreach ($bracketed_fields as $field => $x) {
					$user_prompt = preg_replace('/\[' . $field . '\]/', "", $user_prompt);
				}
			}
		}

		return $user_prompt;
	}

	function ajax_handler_autofill($project_id, $field_name, $record_id, $overwrite_flag, $request_number, $dry_run) {
		$meta = $this->getMetaDataExt($project_id, $field_name);

		$field_enum = preg_replace('/ \| /', "\\n", $meta['field_enum']);
		$field_enum_array = ($field_enum == '') ? array() : parseEnum($field_enum);

		if ($overwrite_flag == 0) {
			$record_data = \Records::getData('array', $record_id, $field_name);

			if (!empty($record_data))
				return array('result' => $field_name, 'field_type' => '', 'value' => 0, 'record_id' => $record_id, 'request_number' => $request_number, 'success' => false);
		}

		$data_logging_flag = true;
		$commit_data_flag = true;

		if ($dry_run != 0) {
			$data_logging_flag = false;
			$commit_data_flag = false;
		}

		$user_prompt = $this->getValueInQuotesActionTag($meta['field_annotation']);

		if ($user_prompt == '')
			return array('result' => $field_name, 'field_type' => '', 'value' => $this->tt("ui_factory_error_missing_user_prompt"), 'record_id' => $record_id, 'request_number' => $request_number, 'success' => false);

		$user_prompt = str_replace('&quot;', '"', $user_prompt);
		$user_prompt = str_replace('&apos;', '\'', $user_prompt);

		$system_prompt = "";
		if ($meta['field_type'] == 'notes') {
			$system_prompt = $this->getProjectSettings()['llm-system-prompt-freetext'];
		} else {
			$system_prompt = $this->getProjectSettings()['llm-system-prompt-no-freetext'];
		}

		$field_prompt = "";
		$field_type_label = $meta['field_type'];
		if ($meta['field_type'] == 'radio' || $meta['field_type'] == 'dropdown') $field_type_label = 'radio-dropdown';

		$field_prompt = $this->getProjectSettings()['llm-system-prompt-' . $field_type_label];
		$system_prompt = $this->getSystemPrompt($project_id, $meta['field_type'], $meta['field_label'], $field_enum, $meta['field_validation'], $system_prompt, $field_prompt);
		$user_prompt = $this->resolve_bracketed_fields($project_id, $field_name, $record_id, $user_prompt);

		$result = $this->llm_request($this->getProjectSettings()['llm-provider'], $system_prompt, $user_prompt);
		if (!$result['success']) return array('result' => $field_name, 'field_type' => '', 'value' => $this->tt("ui_factory_error_llm_request_failed"), 'record_id' => $record_id, 'request_number' => $request_number, 'success' => false);

		$response = $result['response'];
		$response_header = $result['stats'];
		$response_footer = sprintf("Total costs: <b>%s</b>", $result['costs_total']);

		$redcap_import_check = array();
		$value_checkbox = array();
		$label_checkbox = array();
		$value_notes = "";
		$enum_unset_checkbox = array();

		if ($meta['field_type'] == 'checkbox') {
			foreach ($field_enum_array as $k => $v) {
				$enum_unset_checkbox[trim($k)] = '0';
			}
		}

		if ($meta['field_type'] == 'notes') {
			$payload = array(array());
			$payload[1][1] = array('record_id' =>  $record_id, $field_name => $response);
			$check = \REDCap::saveData('array' , $payload, 'overwrite', 'YMD', 'flat', null, $data_logging_flag, false, $commit_data_flag);

			if (isset($check['ids'][$record_id])) {
				$redcap_import_check = array('status'=> 'valid', 'value' => $response, 'label' => "");
			} else {
				return array('result' => $field_name, 'field_type' => '', 'value' => $this->tt("ui_factory_error_validation_failed"), 'record_id' => $record_id, 'request_number' => $request_number, 'success' => false);
			}
		} else {
			foreach (explode(PHP_EOL, $response) as $line) {
				$payload = array(array());
				if ($meta['field_type'] == 'checkbox') {
					$payload[1][1] = array('record_id' =>  $record_id, $field_name . '___' . trim($line) => '1');
					if (!isset($enum_unset_checkbox[trim($line)])) continue;
					unset($enum_unset_checkbox[trim($line)]);
				} else {
					$payload[1][1] = array('record_id' =>  $record_id, $field_name => trim($line));
				}

				$dateFormat = "YMD";
				switch ($meta['field_validation']) {
					case 'date_ymd':
					case "datetime_ymd":
					case "datetime_seconds_ymd":
						$dateFormat = "YMD";
						break;
					case "date_mdy":
					case "datetime_mdy":
					case "datetime_seconds_mdy":
						$dateFormat = "MDY";
						break;
					case "date_dmy":
					case "datetime_dmy":
					case "datetime_seconds_dmy":
						$dateFormat = "DMY";
						break;
					default:
				}

				$check = \REDCap::saveData('array' , $payload, 'overwrite', $dateFormat, 'flat', null, $data_logging_flag, false, $commit_data_flag);
				if (isset($check['ids'][$record_id])) {
					$label = "";
					if (array_key_exists(trim($line), $field_enum_array)) $label = $field_enum_array[trim($line)];

					if ($meta['field_type'] == "checkbox") {
						array_push($value_checkbox, trim($line));
						array_push($label_checkbox, trim($label));
					} else {
						$redcap_import_check = array('status'=> 'valid', 'value' => trim($line), 'label' => trim($label));
					}
				} else {
					return array('result' => $field_name, 'field_type' => '', 'value' => $this->tt("ui_factory_error_validation_failed"), 'record_id' => $record_id, 'request_number' => $request_number, 'success' => false);
				}

				if ($meta['field_type'] != 'checkbox') break;
			}
		}

		if ($meta['field_type'] == 'checkbox') {
			$payload = array(array());
			$payload[1][1] = array('record_id' =>  $record_id);
			foreach ($enum_unset_checkbox as $k => $v) {
				$payload[1][1][$field_name . '___' . trim($k)] = '0';
			}

			$check = \REDCap::saveData('array' , $payload, 'overwrite', 'YMD', 'flat', null, $data_logging_flag, false, $commit_data_flag);

			if (isset($check['ids'][$record_id])) {
				$redcap_import_check = array('status'=> 'valid', 'value' => $value_checkbox, 'label' => $label_checkbox);
			} else {
				return array('result' => $field_name, 'field_type' => '', 'value' => $this->tt("ui_factory_error_validation_failed"), 'record_id' => $record_id, 'request_number' => $request_number, 'success' => false);
			}
		}

		return array('result' => $field_name, 'field_type' => $meta['field_type'], 'value' => $redcap_import_check['value'], 'record_id' => $record_id, 'request_number' => $request_number, 'success' => true);
	}

	function ajax_handler_generate($project_id, $field_name, $user_prompt, $system_general_prompt, $field_prompt, $record_id) {
		$meta = $this->getMetaDataExt($project_id, $field_name);

		$field_enum = preg_replace('/ \| /', "\\n", $meta['field_enum']);
		$field_enum_array = ($field_enum == '') ? array() : parseEnum($field_enum);

		$system_prompt = $this->getSystemPrompt($project_id, $meta['field_type'], $meta['field_label'], $field_enum, $meta['field_validation'], $system_general_prompt, $field_prompt);
		$user_prompt = $this->resolve_bracketed_fields($project_id, $field_name, $record_id, $user_prompt);

		$result = $this->llm_request($this->getProjectSettings()['llm-provider'], $system_prompt, $user_prompt);
		if (!$result['success']) array('result' => "", 'header' => "no connection to LLM");

		$response = $result['response'];
		$response_header = $result['stats'];
		$response_footer = sprintf("Total costs: <b>%s</b>", $result['costs_total']);

		$redcap_import_check = array();
		foreach (explode(PHP_EOL, $response) as $line) {
			$payload = array(array());
			if ($meta['field_type'] == 'checkbox') {
				$payload[1][1] = array('record_id' => '1', $field_name . '___' . trim($line) => '1');
			} else {
				$payload[1][1] = array('record_id' => '1', $field_name => trim($line));
			}

			$dateFormat = "YMD";
			switch ($meta['field_validation']) {
				case 'date_ymd':
				case "datetime_ymd":
				case "datetime_seconds_ymd":
					$dateFormat = "YMD";
					break;
				case "date_mdy":
				case "datetime_mdy":
					case "datetime_seconds_mdy":
					$dateFormat = "MDY";
					break;
				case "date_dmy":
				case "datetime_dmy":
				case "datetime_seconds_dmy":
					$dateFormat = "DMY";
					break;
				default:
			}

			$check = \REDCap::saveData('array' , $payload, 'overwrite', $dateFormat, 'flat', null, false, false, false);
			if (isset($check['ids'][1])) {
				$label = "";
				if (array_key_exists(trim($line), $field_enum_array)) $label = $field_enum_array[trim($line)];
				$redcap_import_check[] = array('status'=> 'valid', 'value' => $line, 'label' => $label);
			} else {
				if (isset($check['errors'][0])) {
					$csv = str_getcsv($check['errors'][0]);
					$redcap_import_check[] = array('status'=> 'error', 'value' => trim($csv[2]) . "&nbsp;", 'label' => trim($csv[3]) . "&nbsp;");
				} else {
					$redcap_import_check[] = array('status'=> 'warning', 'value' => trim($csv[2]) . "&nbsp;", 'label' => trim($csv[3]) . "&nbsp;");
				}
			}
		}

		return array('result'=>$response, 'header'=>$response_header, 'footer'=>$response_footer, 'redcap_import_check'=>$redcap_import_check);
	}
}
