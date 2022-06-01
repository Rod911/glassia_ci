var dtable = null;
$(function () {
	var select2Config = {
		placeholder: "Select",
		theme: "bootstrap-5",
		allowClear: true,
	}
	$('.select-widget').select2(select2Config);
	$(document).on('select2:open', () => {
		document.querySelector(".select2-container--open .select2-search__field").focus()
	});

	var href = window.location.origin + window.location.pathname;
	var activePage = $('a[href="' + href + '"');
	activePage.addClass("active");

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
		swal.fire({
			title: 'Loading...',
			html: ' ',
			showConfirmButton: showBtn,
			customClass: {
				popup: modalSize
			},
			showCloseButton: true,
			// showCancelButton: true,
			didOpen: () => {
				Swal.showLoading()
			},
		})
		$.post({
			url: BASEURL + 'ajax/' + url,
			data: {
				value: id,
			},
			dataType: 'JSON',
			success: function (res) {
				Swal.hideLoading();
				$("#swal2-title").text(res.title);
				$("#swal2-html-container").html(res.content);
				$('#swal2-html-container .select-widget').select2({
					...select2Config,
					dropdownParent: $('#swal2-html-container'),
				});
			}
		});
	});

	dtable = $("#d-table").DataTable({
		bProcessing: true,
		bServerSide: true,
		ordering: false,
		sAjaxSource: $(this).attr('data-ajax-url'),
		bJQueryUI: true,
		sPaginationType: "full_numbers",
		iDisplayLength: 10,
		oLanguage: {
			sProcessing: "Loading...",
		},
		fnServerData: function (sSource, aoData, fnCallback) {
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
				dataType: 'json',
				type: 'POST',
				url: $(this).attr('data-ajax-url'),
				data: {
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
					url: BASEURL + 'ajax/delete_record',
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
							var error_message = res.error_message;
							if (!error_message) {
								error_message = 'Refresh page or try again'
							}
							Swal.fire(
								'Could not delete!',
								error_message,
								'error',
							)
						}
					},
					error: function (xhr, err, res) {
						Swal.fire(
							'Could not delete!',
							'Refresh page or try again',
							'error',
						)
					}
				})
			}
		})
	})
});