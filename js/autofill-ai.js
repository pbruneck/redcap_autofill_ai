$(document).ready(function() {
	// updateAutofillAIIcons();

	var observer = new MutationObserver(function(mutations_list) {
        $(mutations_list).each(function(index, mutation) {
            if (mutation.type === 'childList') {
                $(mutation.addedNodes).each(function(index, addedNode) {
                    // trigger icon update when the added node is an element (nodeType == 1)
                    if (addedNode.nodeType === 1) {
                        updateAutofillAIIcons();
                    }
                });
            }
        });
    });

	observer.observe($('body')[0], { childList: true, subtree: true });
});

function updateAutofillAIIcons() {
	// designer page only
	$("span.od-field-icons.me-2").each(function(index) {
		var field_id = $(this).parents("tr").parents("tr").attr("sq_id");

		// continue if already there
		if ($(this).find('a[data-field-action="autofillai"]').length > 0) return true;

		// add icon if not
		$(this).append('<a href="javascript:;" onclick="openAutofillAIWorkbench(\'' + field_id + '\')" data-rc-lang-attrs="title=design_1135" data-bs-toggle="tooltip" class="field-action-link" data-field-action="autofillai" aria-label="<b>Edit</b> Autofill AI" data-bs-original-title="<b>Edit</b> Autofill AI"><i style="color:#608050;" class="fa-solid fa-robot"></i></a>');
	});

	// data entry only
	if ($("#auto-fill-btn").length > 0) {
		// retrieve record id
		const url_params = new URLSearchParams(window.location.search);
		const record_id = url_params.get('id');

		// check if already there
		if ($("#auto-fill-btn").parent().find('button[id="autofillai-btn"]').length == 0) {
			$("#auto-fill-btn").parent().append('<br><button id="autofillai-btn" class="btn btn-link btn-xs" style="font-size:11px ' + 
				'!important;padding:1px 5px !important;margin:0 !important;color:#007bffcc;" onclick="autoFillAI(\'' + record_id + '\');">' + 
				'<i class="fs10 fa-solid fa-robot mr-1" style="color:#608050;"></i>' + module_autofill_ai.tt('js_data_entry_btn') + '&nbsp;<i id="autofill-ai-spinner" class="fa-solid fa-spinner fa-spin-pulse me-3" style="display:none;"></i></button>');
		}
	}
};

// adapted from Resources/js/DesignFields.js -> openLogicBuilder()

function openAutofillAIWorkbench(field_name) {
	$('#autofill_ai_workbench').attr('field', field_name);
	module_autofill_ai.ajax('load-prompt', {field_name: field_name}).then(function(json_data) {
		if (typeof(json_data) == 'object') {
			$('#autofill_ai_workbench_result_raw').html('');
			$('#autofill_ai_workbench_result_table').empty();
			$('#autofill_ai_workbench_result_header').html('&nbsp;');
            $('#autofill_ai_workbench_raw_header').html('&nbsp;');
			$('#autofill_ai_workbench_raw_footer').html('&nbsp;');
			$('#autofill_ai_workbench_field_type').html('<b>' + json_data.field_type + '</b>');
			$('#autofill_ai_workbench_prompt').val(json_data.prompt);
			$('#autofill_ai_workbench_system_prompt').val(json_data.system_prompt);
			$('#autofill_ai_workbench_field_type_prompt').val(json_data.field_type_prompt);
			$('#autofill_ai_workbench_field').html(field_name);
			$('#autofill_ai_workbench_label').html(json_data.field_label);
			$('#autofill_ai_workbench_enum').html(json_data.field_enum.replace(/\\n/g, '<br>'));
			// console.log(json_data.field_enum);
			$('#autofill_ai_workbench_system_prompt_div').hide();
			$('#autofill_ai_workbench_edit_system_prompt_button').attr('onclick', '(function(){$(\'#autofill_ai_workbench_system_prompt_div\').show();})();return false;');
			$('#autofill_ai_workbench_generate_button').attr('onclick', 'autofillAIGenerate(\'' + field_name + '\', $(\'#autofill_ai_workbench_record_dropdown\').find(":selected").val());');
			$('#autofill_ai_workbench').dialog({ bgiframe: true, modal: true, width: 810, open: function(){fitDialog(this)},
				buttons: [{
					text: window.lang.global_53,
					click: function() { $(this).dialog("close"); }
				},{
					html: '<b>'+lang.designate_forms_13+'</b>',
					click: function() {
						if ($('#autofill_ai_workbench_system_prompt_div').is(":visible")) {
							saveAutofillAIWorkbench(field_name, $('#autofill_ai_workbench_prompt').val(), $(this),
								$('#autofill_ai_workbench_system_prompt').val(), $('#autofill_ai_workbench_field_type_prompt').val());
						} else {
							saveAutofillAIWorkbench(field_name, $('#autofill_ai_workbench_prompt').val(), $(this), null, null);
						}
					}
				}]
			});
		} else {
			showProgress(0,1);
			alert(woops);
		}
	}).catch(function(err) {
		// Handle error
	});
}

function saveAutofillAIWorkbench(field_name, prompt, dlg, system_prompt, field_prompt) {
	$('#autofill_ai_workbench').attr('field', field_name);
	module_autofill_ai.ajax('save-prompt', {field_name: field_name, prompt: prompt, system_prompt: system_prompt, field_prompt: field_prompt}).then(function(json_data) {
		if (typeof(json_data) == 'object') {
            dlg.dialog("close");
		} else {
			showProgress(0,1);
			alert(woops);
		}
	}).catch(function(err) {
		// Handle error
	});
}


function autofillAIGenerate(field_name, record_id) {
	console.log(record_id);
	module_autofill_ai.ajax('generate', {field_name: field_name, record_id: record_id, prompt: $('#autofill_ai_workbench_prompt').val(), system_prompt: $('#autofill_ai_workbench_system_prompt').val(), field_prompt: $('#autofill_ai_workbench_field_type_prompt').val()}).then(function(json_data) {
		if (typeof(json_data) == 'object') {
			$('#autofill_ai_workbench_result_raw').html(nl2br(json_data.result));
            $('#autofill_ai_workbench_raw_header').html(json_data.header);
			$('#autofill_ai_workbench_raw_footer').html(json_data.footer);
			$('#autofill_ai_workbench_result_table').empty();

			var n_valid = 0;
			var n_error = 0;
			var n_warning = 0;

			for (var n in json_data.redcap_import_check) {
				var o = json_data.redcap_import_check[n];
				var line;

				if (o.status == 'valid') {
					line = '<tr valign="top"><td style="border:solid thin;">' + o.value + '</td>';
					if (o.label.trim().length > 0) line = line + '<td style="border:solid thin;">' + o.label + '</td>';
					line = line + '</tr>';
					++n_valid;
				}

				if (o.status == 'warning') {
					line = '<tr valign="top"><td style="background: lightyellow;border:solid thin;">' + o.value + '</td>';
					if (o.label.trim().length > 0) line = line + '<td style="background: lightyellow;border:solid thin;">' + o.label + '</td>';
					line = line + '</tr>';
					++n_warning;
				}

				if (o.status == 'error') {
					line = '<tr valign="top"><td style="background: lightpink;border:solid thin;">' + o.value + '</td>';
					if (o.label.trim().length > 0) line = line + '<td style="background: lightpink;border:solid thin;">' + o.label + '</td>';
					line = line + '</tr>';
					++n_error;
				}

				$('#autofill_ai_workbench_result_table').append(line);
			}
			$('#autofill_ai_workbench_result_header').html(n_valid.toString() + ' value(s) valid, ' + n_error.toString() + ' erroneous');
	    } else {
			showProgress(0,1);
			alert(woops);
		}
	}).catch(function(err) {
			// Handle error
	});
}

// adapted from Resources/js/DataEntrySurveyCommon.js

function autoFillAIRow(tr, field_type, field_value)
{
	switch (field_type) {
		case 'dropdown':
			var dropdown = $(tr).find('select');
			if (dropdown.length == 0) break;
			var option = dropdown.find('option[value="' + field_value + '"]');
			option.prop('selected', true);
		    if (dropdown.hasClass('rc-autocomplete')) {
				$(':input#rc-ac-input_'+ dropdown.prop('name')).val(option.html()).trigger('blur');
			}
			break;
		case 'checkbox':
			// regular checkboxes only
			if (!Array.isArray(field_value)) break;

			var checkboxes = $(tr).find("input[type=checkbox]").filter(":visible").filter(":not([id='__LOCKRECORD__'])");
			var enhancedchoice = $(tr).find("div.enhancedchoice label.selectedchkbox, div.enhancedchoice label.unselectedchkbox").filter(":visible");
			var checkboxes_checked = $('input:checked',tr);
			if (checkboxes.length > 0 && checkboxes_checked.length == 0 && enhancedchoice.length == 0) {
				for (let i = 0; i < checkboxes.length; i++) $(checkboxes[i]).prop('checked', false);

				field_value.forEach((code, index) => {
					$(tr).find('input[code="' + code + '"]').trigger('click'); // prop('checked', true); 
				});

				break;
			}
			break;
		case 'radio':
			// regular radio buttons only
			var radios = $(tr).find("input[type=radio]").filter(":visible");
			var radios_checked = $(tr).find("input[type=radio]:checked");
			if ((radios.length > 0) && radios_checked.length == 0) {
				var choice = $(tr).find('input[type=radio][value="' + field_value + '"]');
				if (choice.length > 0) {
					if (choice.prop('checked', false)) {
						choice[0].checked = true;
						choice.trigger('click').trigger('blur');
					}
				}
			}
			break;
		case 'slider':
			var slider = $(tr).find("div.slider:first");
			if (slider.length > 0) {
				slider.trigger('mousedown');
				slider.slider('value', field_value);
			}
			break;
		case 'text':
			var input = $(tr).find("input[type=text]:first");
			if (input.length > 0 && input.val() == "") {
				input.val(field_value);
				input.trigger('blur');
			}
			break;
		case 'notes':
			var textarea = $(tr).find('textarea');
			if (textarea.length > 0 && textarea.val() == '') {
				textarea.val(field_value);
				textarea.trigger('blur');
			}
			break;
		default:
	}
}

function autoFillAI(record_id) {
	field_list = [];

    $("tr[sq_id], .rc-field-embed[var]").each(function(){
        if ($(this).is(':visible')) {
            $('html, body').animate({scrollTop: $(this).offset().top},1);
            var field_name = $(this).attr($(this).hasClass('rc-field-embed') ? 'var' : 'sq_id');
            if (field_name != '{}' && field_name != '') {
                doBranching(field_name);
				field_list.push($(this));
            }
            dataEntryFormValuesChanged = true;
        }
    });
    // console.log(autoFillAIFieldList);
	autoFillAIMultiplePostRequests(field_list, record_id);
}

async function autoFillAIPostRequest(data) {
    return new Promise((resolve, reject) => {
		module_autofill_ai.ajax('autofill', data)
			.then(response => resolve(response))
			.catch(error => reject(error));
    });
}

async function autoFillAIMultiplePostRequests(field_list, record_id) {
    const max_parallel_requests = 3;
    const promises = [];

	var number_of_requests = field_list.length;

	$('#autofill-ai-spinner').show();

    for (let request_number = 0; request_number < number_of_requests; request_number++) {
        var field_name = field_list[request_number].attr('sq_id');

        const data = {request_number: request_number, field_name: field_name, record_id: record_id, overwrite_flag: 1, dry_run: 1};

		promises.push(autoFillAIPostRequest(data));
		// console.log(data);

        if (promises.length === max_parallel_requests || request_number === number_of_requests - 1) {
            try {
                const results = await Promise.all(promises);
                results.forEach((obj, index) => {
					if (typeof(obj) == 'object') {
						if (obj.success) {
							autoFillAIRow(field_list[obj.request_number], obj.field_type, obj.value);
						}
					}
                });
            } catch (error) {
                console.error(module_autofill_ai.tt('js_failed_requests'), error);
            }
			// Clear array for the next request
            promises.length = 0;
        }
    }

	$('#autofill-ai-spinner').hide();
}

async function autoFillFactoryAIPostRequest(data) {
    return new Promise((resolve, reject) => {
		module_autofill_ai.ajax('autofill', data)
			.then(response => resolve(response))
			.catch(error => reject(error));
    });
}

async function autoFillAIFactoryMultiplePostRequests(record_list, field_list, overwrite_flag) {
    const maxParallelRequests = 3;
    const promises = [];

	var records = JSON.parse(record_list);
	var fields = JSON.parse(field_list);
	var fields_per_record = new Map;
	records.forEach((record) => {
		fields_per_record.set(record, fields.length);
	});

	for (const record of records) {
		$("span[span_id='" + record + "']").html('<i spinner_id="' + record + '" class="fa-solid fa-spinner fa-spin-pulse"></i>');
		// $("span[span_id='" + record + "']").html('');
	}

	var request_number = 0;
	var number_of_requests = records.length * fields.length;
	for (const record of records) {

		/*
		// skip unused rows - not yet working
		if ($("span[span_id='" + record + "']").attr('span_record_complete') && !overwrite_flag) {
			number_of_requests -= fields.length;
			fields_per_record.set(record, 0);
			continue;
		}
		*/

		for (const field_name of fields) {
			const data = { request_number: request_number, field_name: field_name, record_id: record, overwrite_flag: overwrite_flag ? 1: 0, dry_run: 0};

			promises.push(autoFillFactoryAIPostRequest(data));
	
			if (promises.length === maxParallelRequests || request_number === number_of_requests - 1) {
				try {
					const results = await Promise.all(promises);
					results.forEach((obj, index) => {
						if (typeof(obj) == 'object') {
							fields_per_record.set(obj.record_id, fields_per_record.get(obj.record_id) - 1);
							if (fields_per_record.get(obj.record_id) == 0)
								$("i[spinner_id='" + obj.record_id + "']").remove();

							if (obj.success) {
								// obj.value can be array or string
								let values = Array.isArray(obj.value) ? obj.value.join(', ') : obj.value;

								$("span[span_id='" + obj.record_id + "']").html(obj.result + ': ' + values + '<br>' + $("span[span_id='" + obj.record_id + "']").html());
							} else {
								if (typeof obj.value === 'string') {
									console.log('ERROR', obj.record_id, obj.result, obj.value);
									$("span[span_id='" + obj.record_id + "']").html('<span style="color:red">' + obj.result + ' (ERROR): ' + obj.value + '</span><br>' + $("span[span_id='" + obj.record_id + "']").html());
								}
							}
						}
					});
				} catch (error) {
					console.error(module_autofill_ai.tt('js_failed_requests'), error);
				}
				// Clear array for the next request
				promises.length = 0;
			}
	
			++request_number;
			// break;
		}
		// break;
	}

	// cleanup
	fields_per_record.forEach((counter, record) => {
		if (counter > 0) {
			$("span[span_id='" + record + "']").append('<br><span style="color:red;">' + module_autofill_ai.tt('js_error_generating_results') + '</span>');
			$("i[spinner_id='" + record + "']").remove();
		}
	});

}

