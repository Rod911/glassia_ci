var dtable = null;
$(function () {

	$.validator.methods.email = function (value, element) {
		return this.optional(element) || /^.+@.+\..+$/.test(value);
	}
	$('.validate-form').validate();
	var select2Config = {
		placeholder: "Select",
		theme: "bootstrap"
	}
	$('.select-widget').select2(select2Config);
	$('.date-widget').datetimepicker({
		format: 'D MMM, YYYY',
	});
	if ($('.datepicker').length > 0) {
		$('.datepicker').datetimepicker({
			format: 'DD-MM-YYYY',
			useCurrent: false,
		});
	}
	$('.wysiwyg-editor').summernote({
		toolbar: [
			['style', ['style', 'bold', 'italic', 'underline', 'clear']],
			['font', ['strikethrough', 'superscript', 'subscript']],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph', 'hr']],
			['edit', ['fullscreen', 'codeview', 'undo', 'redo', 'help']]
		],
		height: 250
	});


	var href = window.location.origin + window.location.pathname;
	var activePage = $('a[href="' + href + '"');
	var activeLi = activePage.parent("li");
	activeLi.addClass("active");
	if (activeLi[0]) {
		activeLi[0].scrollIntoView({
			behavior: "instant",
			block: "center"
		});
		setTimeout(() => {
			activeLi[0].scrollIntoView({
				behavior: "instant",
				block: "center"
			});
		}, 200);
	}
	activePage.parents("li.nav-item").children("a").click();

	$("body").on("input", ".numeric", function (e) {
		var currencyType = this.hasAttribute("data-currency");
		var input = e.target.value;

		if (currencyType) {
			input = input.replace(/[^0-9.]/gi, '')
			var ex = /^[0-9]+\.?[0-9]{0,2}$/;
			if (ex.test(input) == false) {
				input = input.substring(0, input.length - 1);
			}
		} else {
			input = input.replace(/\D/g, "");
		}

		e.target.value = input;
	});

	$("body").on("input", ".alphanumeric", function (e) {
		var input = e.target.value;

		input = input.replace(/[^0-9a-z]/gi, '')

		e.target.value = input;
	});

	$("body").on("input", ".alphabetic", function (e) {
		var input = e.target.value;

		input = input.replace(/[^a-z]/gi, '')

		e.target.value = input;
	});

	$(document).on('change', "[data-update]", function () {
		var dataUpdate = $(this).attr('data-update');
		var dataChange = $(this).attr('data-change');
		var dataChangeCb = $(this).attr('data-change-cb');
		var changeSelect = $("#" + dataUpdate);
		var currentSelectVal = $(changeSelect).val();
		var currentChangeVal = $(this).val();
		$.post({
			url: BASEURL + 'ajax/' + dataChange,
			data: {
				value: currentChangeVal,
			},
			dataType: 'JSON',
			success: function (res) {
				$(changeSelect).html($(res).map(function (index, option) {
					return '<option value="' + option.option_value + '">' + option.option_name + '</option>';
				}).get().join(''));
				$(changeSelect).select2('destroy');
				$(changeSelect).select2(select2Config);
				$(changeSelect).trigger('change');
				if (dataChangeCb) {
					window[dataChangeCb](currentChangeVal, res);
				}
			}
		})
	});

	$(document).on('click', '[data-popup-view]', function () {
		var id = $(this).data('id');
		var url = $(this).data('popup-view');
		var modalSize = $(this).data('modal-size');
		var hideBtn = $(this).data('no-btn');
		var showBtn = true;
		if (hideBtn == '1') {
			showBtn = false;
		}
		$.post({
			url: BASEURL + 'ajax/' + url,
			data: {
				value: id,
			},
			dataType: 'JSON',
			success: function (res) {
				swal.fire({
					title: res.title,
					html: res.content,
					showConfirmButton: showBtn,
					customClass: {
						popup: modalSize
					},
				})
			}
		});
	});

	$(document).on('click', '.delete-record', function () {
		var dataID = $(this).attr('data-id');
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Delete!'
		}).then((result) => {
			if (result.isConfirmed) {
				$.post({
					url: BASEURL + 'admin/ajax/delete_record',
					data: {
						id: dataID,
					},
					dataType: 'JSON',
					success: function (res) {
						dtable.ajax.reload()
						if (res.success) {
							Swal.fire(
								'Deleted!',
								'',
								'success'
							)
						} else if (res.map_view) {
							Swal.fire({
								title: 'Cannot delete this item',
								html: res.map_view
							});
						} else {
							Swal.fire(
								'Could not delete!',
								'Refresh page or try again',
								'error',
							)
						}
					}
				})
			}
		})
	})

	$(document).on('click', '.update-status', function () {
		$(this).removeClass(disabledStatusClass, enabledStatusClass).addClass(processingStatusClass).html(processingStatusIcon);
		var dataID = $(this).data('id');
		$.post({
			url: BASEURL + 'admin/ajax/status_update_record',
			data: {
				id: dataID,
			},
			dataType: 'JSON',
			success: function (res) {
				dtable.ajax.reload()
				if (!res.success) {
					Swal.fire(
						'Could not delete!',
						'Refresh page or try again',
						'error',
					)
				}
			}
		});
	})

	var disabledStatusClass = 'btn-info';
	var enabledStatusClass = 'btn-success';
	var processingStatusClass = 'btn-border btn-primary';
	var disabledStatusIcon = '<i class="fa fa-fw fa-ban"></i>';
	var enabledStatusIcon = '<i class="fa fa-fw fa-check"></i>';
	var processingStatusIcon = '<i class="loader loader-sm table-btn-spinner"></i>'
	dtable = $("[data-ajax-url]").DataTable({
		"bProcessing": true,
		"bServerSide": true,
		"ordering": false,
		"sAjaxSource": $(this).attr('data-ajax-url'),
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		'iDisplayLength': 10,
		"oLanguage": {
			"sProcessing": "Loading...",
		},
		"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			// console.log(this);
			// console.log(iDisplayIndexFull);
			var page = this.api().page();
			var length = this.api().context[0]._iDisplayLength;
			var index = (page * length + (iDisplayIndex + 1));
			$('td:eq(0)', nRow).html(index);
			var statusBtn = $('.update-status', nRow);
			var currentStatus = $(statusBtn).data('status');
			if (currentStatus == '0') {
				$(statusBtn).removeClass(enabledStatusClass).addClass(disabledStatusClass).html(disabledStatusIcon)
			} else {
				$(statusBtn).removeClass(disabledStatusClass).addClass(enabledStatusClass).html(enabledStatusIcon)
			}
		},
		'fnServerData': function (sSource, aoData, fnCallback) {
			var filter = null;
			if (typeof getFilter === "function") {
				filter = getFilter();
			}
			// console.log(aoData);
			var aoDataObj = {};
			aoData.forEach((aObj) => {
				aoDataObj[aObj.name] = aObj;
			});
			aoDataObj.columns.value.forEach((col, ci) => {
				aoDataObj.columns.value[ci].searchable = false;
				aoDataObj.columns.value[ci].orderable = false;
			});
			$.ajax({
				'dataType': 'json',
				'type': 'POST',
				'url': $(this).attr('data-ajax-url'),
				'data': {
					filter: filter,
					sEcho: '1',
					columns: aoDataObj.columns.value,
					iDisplayStart: aoDataObj.start.value,
					iDisplayLength: aoDataObj.length.value,
					search: aoDataObj.search.value,
					bRegex: 'false',
					iSortCol_0: '0',
					sSortDir_0: 'asc',
					iSortingCols: '1',
				},
				'success': fnCallback
			});
		}
	});

	// Input File Image

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$(input).parent('.input-file-image').find('.img-upload-preview').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	$(document).on('change', '.input-file-image input[type="file"]', function () {
		readURL(this);
	});
});