(function( $ ) {
	'use strict';

	var ajaxUrl = wx_special_posts_object.wx_support_ajaxurl;
	var editFormLoaderHtml = wx_special_posts_object.edit_form_loader_html;
	var Wx_Special_Posts_Datatable_Cols = wx_special_posts_object.Wx_special_posts_datatable_cols;

	 
	var searchFilter = []; 
	$(document).ready(function () {
		var oldExportAction = function (self, e, dt, button, config) {
        
			// Excel export action
			
			if (button[0].className.indexOf('buttons-excel') >= 0) {
			  if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
				$.fn.dataTable.ext.buttons.excelHtml5.action.call(
				  self,
				  e,
				  dt,
				  button,
				  config,
				);
			  } else {
				$.fn.dataTable.ext.buttons.excelFlash.action.call(
				  self,
				  e,
				  dt,
				  button,
				  config,
				);
			  }
			}
		
			// Csv export action
			if(button[0].className.indexOf('buttons-csv') >= 0){
			  if ($.fn.dataTable.ext.buttons.csvHtml5.available( dt, config )) {
				$.fn.dataTable.ext.buttons.csvHtml5.action.call(
				  self,
				  e,
				  dt,
				  button,
				  config,
				);
			  }
			  else {
				$.fn.dataTable.ext.buttons.csvFlash.action(e, dt, button, config);
			  }
			}
		
			// Copy Action
			if(button[0].className.indexOf('buttons-copy') >= 0){
			  $.fn.dataTable.ext.buttons.copyHtml5.action.call( self,e, dt, button, config );
			}
		
			// Print Action
			if (button[0].className.indexOf('buttons-print') >= 0) {
			  $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
			}
		
		
		};
		var newExportAction = function (e, dt, button, config) {
		  var self = this;
		  var oldStart = dt.settings()[0]._iDisplayStart;
		
		
		  dt.one('preXhr', function (e, s, data) {
			data = JSON.parse(data);
			// Just this once, load all data from the server...
			data.start = 0;
			data.length = 2147483647;
		
			dt.one('preDraw', function (e, settings) {
			  // Call the original action function
			  oldExportAction(self, e, dt, button, config);
		
			  dt.one('preXhr', function (e, s, data) {
				data = JSON.parse(data);
				// DataTables thinks the first item displayed is index 0, but we're not drawing that.
				// Set the property to what it was before exporting.
				settings._iDisplayStart = oldStart;
				data.start = oldStart;
			  });
		
			  // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
			  setTimeout(dt.ajax.reload, 0);
		
			  // Prevent rendering of the full data to the DOM
			  return false;
			});
		  });
		
		  // Requery the server with the new one-time export settings
		  dt.ajax.reload();
		};
		 
		var WX_Special_Posts_Dashboard = $('#WX_Special_Posts_Dashboard').DataTable({
		  columns : Wx_Special_Posts_Datatable_Cols,
			buttons: [
			{
			  extend: 'excel',
			  exportOptions: {
				columns: [0,1,':visible'],
			  },
			  action: newExportAction,
			},
			{
			  extend: 'csv',
			  exportOptions: {
				columns: [0,1,':visible'],
			  },
			  action: newExportAction,
			},
			{
			  extend: 'copy',
			  exportOptions: {
				columns: [0,1,':visible'],
			  },
			  action: newExportAction,
			  text:'<i class="fas fa-copy"></i>',
			  titleAttr: 'Copy'
			},
			{
			  extend: 'print',
			  exportOptions: {
				columns: [ 0,1,':visible' ],
			  },
			  action: newExportAction,
			  text:'<i class="fas fa-print"></i>',
			  titleAttr: 'Print'
			}      
		  ],
		  lengthMenu: [
			[10, 25, 50,100, -1],
			[10, 25, 50,100, 'All'],
		  ],
		  paging: true,
		  pageLength: 25,        
		  // ordering: true,
		  order: [[0, 'asc']],
		  dom: 'B<"clear">lfrtip',
		  drawCallback: function (settings) { 
			var SpecialPostCats 	= settings.json.wx_special_post_cats;
			var SpecialPostSubCats 	= settings.json.wx_special_post_sub_cats;
			var SpecialPostBBGroups = settings.json.wx_special_post_bb_groups;
			var SpecialPostAuthors 	= settings.json.wx_special_post_authors;

			
		
			$('#WX_Special_Posts_Dashboard_length select.wx-table-filter').remove();

			this.api()
			  .columns()
			  .every(function (index, s) {
		
				if (index != 2 && index != 3 &&  index !=4 && index !=5) {
				  return;
				}
				var column = this;
				var optionSelect = '';
				
				if (index == 2) {
				  optionSelect = 'Filter by '+wx_special_posts_object.wx_special_post_category_name;
				}
		
				if (index == 3) {
				  optionSelect = 'Filter by sub '+wx_special_posts_object.wx_special_post_category_name;
				}

				if (index == 4) {
					optionSelect = 'Filter by group';
				}

				if (index == 5) {
					optionSelect = 'Filter by Author';
				}
				
		
								
				var select = $(
				  '<select class="wx-table-filter"><option value="">' +
					optionSelect +
					'</option></select>',
				)
				  .appendTo($('#WX_Special_Posts_Dashboard_length'))
				  .on('change', function () {
					var val = $.fn.dataTable.util.escapeRegex($(this).val());
					searchFilter[index] = val;
					column.search(val ? '^' + val + '$' : '', true, false).draw();
					if ($('#wxResetFiltersSpecialPosts').length == 0) {
					  $('#WX_Special_Posts_Dashboard_length').after(
						'<button id="wxResetFiltersSpecialPosts" class="wx-lms-actions"><i class="fa fa-refresh" aria-hidden="true"></i></button>',
					  );
					}
				  });
				if (index == 2) {
				  $.each(SpecialPostCats, function (j, d) {
					var searchString = searchFilter[index];
					searchString = searchString
					  ? searchString.replace(/\\/g, '')
					  : '';
					if (
					  searchFilter &&
					  searchFilter[index] &&
					  d.id == searchString
					) {
					  select.append(
						'<option  selected value="' +
						  d.id +
						  '">' +
						  d.name +
						  '</option>',
					  );
					} else {
					  select.append(
						'<option  value="' + d.id + '">' + d.name + '</option>',
					  );
					}
				  });
				}
				if (index == 3) {
				  $.each(SpecialPostSubCats, function (j, d) {
					var searchString = searchFilter[index];
					searchString = searchString
					  ? searchString.replace(/\\/g, '')
					  : '';
					if (
					  searchFilter &&
					  searchFilter[index] &&
					  d.id == searchString
					) {
					  select.append(
						'<option  selected value="' +
						  d.id +
						  '">' +
						  d.name +
						  '</option>',
					  );
					} else {
					  select.append(
						'<option  value="' + d.id + '">' + d.name + '</option>',
					  );
					}
				  });
				}
				if (index == 4) {
					$.each(SpecialPostBBGroups, function (j, d) {
					  var searchString = searchFilter[index];
					  searchString = searchString
						? searchString.replace(/\\/g, '')
						: '';
					  if (
						searchFilter &&
						searchFilter[index] &&
						d.id == searchString
					  ) {
						select.append(
						  '<option  selected value="' +
							d.id +
							'">' +
							d.name +
							'</option>',
						);
					  } else {
						select.append(
						  '<option  value="' + d.id + '">' + d.name + '</option>',
						);
					  }
					});
				}
				if (index == 5) {
					$.each(SpecialPostAuthors, function (j, d) {
					  var searchString = searchFilter[index];
					  searchString = searchString
						? searchString.replace(/\\/g, '')
						: '';
					  if (
						searchFilter &&
						searchFilter[index] &&
						d.id == searchString
					  ) {
						select.append(
						  '<option  selected value="' +
							d.id +
							'">' +
							d.name +
							'</option>',
						);
					  } else {
						select.append(
						  '<option  value="' + d.id + '">' + d.name + '</option>',
						);
					  }
					});
				}
								
			});		
		  },
		  processing: true,
		  serverSide: true,
		  ajax: { 
			url: ajaxUrl + '?action=wx_get_special_posts_datatable',
			contentType : "application/json",
			type : "POST",
			data : function ( d ) {
			  return JSON.stringify( d );
			}
		  }      
		});
		
		$(document).on('click', '#wxResetFiltersSpecialPosts', function (event) {
			event.preventDefault();
			$(".wx-table-filter").each(function(){
			  $(this).val('');
			});
			reset_search_filters_and_draw_datatable_special_posts();
			$(this).remove();
		});
	});

	//*Special Post Category Change Event
	$(document).on('change','.wx_special_post_category',function(){
		var parent = $(this).closest('.wxModals');
		var cat_id = $(this).val();

		parent.find('.wx_special_post_sub_category').html('<option value="">Loading...</option>');
		
		if(!cat_id){
			parent.find('.wx_special_post_sub_category').html('<option value="">Select Sub Category</option>');
		}

		$.ajax({
            url: ajaxUrl,
            type: "POST",
            dataType: "json",
            data: {
                'action': 'wx_special_post_load_sub_categories',
                'cat_id' : cat_id
            },
            success: function(response) {                
                if(response.success)
                {   
                    parent.find('.wx_special_post_sub_category').html(response.data.html);
                }
            },
            error: function(xhr, status, error){
            }
        });

	}); 

	//*Add Special Post Func 
	var wxAddSpecialPostForm = $("#wx-add-special-post-form").validate({   
		ignore: "",     
		rules: {
			"wx_special_post_title":"required",
		},
		messages: {},
		invalidHandler: function() {
			var errMsg = 'You have '+wxAddSpecialPostForm.numberOfInvalids()+' errors in form.';
			Swal.fire({
				html: errMsg,  
				icon: "warning",
			});
		},
		submitHandler: function(form, event) {
			event.preventDefault();
  
			tinyMCE.triggerSave();
			
			var parentForm = $('#'+event.target.id);
			var parent = parentForm.closest('.wxModals');
			parent.find('.wxAddPostSbmtBtn').text('Saving...');
			parent.find('.wxAddPostSbmtBtn').attr('disabled',true);
  
			$.ajax({
			  url: ajaxUrl,
			  type: "POST",
			  dataType: "json",
			  data: {
				  'action': 'wx_create_special_post',
				  'formData': $(form).serialize()
			  },
			  success: function(response) { 
				parent.find(".wxPostCreatedSuccess").show();
				parent.find(".wxAddPostSbmtBtn").attr('disabled',false);
				parent.find(".wxAddPostSbmtBtn").text('Saved');  
  
				parentForm.trigger('reset');
    
				$(".removeFlexibleElm:visible").click();
				reset_add_form_select2();
				setTimeout(function(){
					$(".wxPostCreatedSuccess").hide();
					var modalId = parent.closest('.wxModals').attr('id');
					$("#"+modalId).modal('hide');
					// $('body').removeClass('modal-open');
					// $('.modal-backdrop').remove();
					parent.find(".wxAddPostSbmtBtn").text('Save');
					reset_search_filters_and_draw_datatable_special_posts();
				},2500);                                      
			  },
			  error: function(xhr, status, error){
				parent.find(".wxPostCreatedSuccess").show();
				parent.find(".wxAddPostSbmtBtn").attr('disabled',false);
				parent.find(".wxAddPostSbmtBtn").text('Save');
			  }
		  });
		}        
	}); 

	//* Datatable Action Edit Btn Click 
	$(document).on('click','.wxEditSpecialPost',function(e){
		e.preventDefault();
		var special_post_id = $(this).data('id');
		localStorage.removeItem('wx_special_post_id');
		localStorage.setItem('wx_special_post_id',special_post_id);
		$('#wxEditSpecialPosts').modal("show");
	});
	
	
	//*Ticket View Modal Show Event
	$(document).on(
		'show.bs.modal',
		'#wxEditSpecialPosts',
		function (event) {
			var  wx_special_post_id = localStorage.getItem('wx_special_post_id');
			$.ajax({
			url: ajaxUrl,
			type: "POST",
			dataType: "json",
			data: {
				'action': 'wx_load_edit_special_post',
				'wx_special_post_id': wx_special_post_id,
			},
			success: function(response) {      
				$("#wxEditSpecialPosts").find('.modal-content').html(response.data.html);   
				setTimeout(function(){
						init_edit_special_post_validator();	
						//* Special Posts Tags Select 2
						$('.wx_special_post_tags_edit').select2({
							dropdownParent : '#wx_special_post_tags_edit'
						});
						//*Special Posts All Groups Members 
						$('.wx_special_post_all_group_members_edit').select2({
							dropdownParent : '#wx_special_post_all_group_members_edit'
						});
						//* Wx Speical Post Friends
						$('.wx_special_post_friends_edit').select2({
							dropdownParent : '#wx_special_post_friends_edit'
						});
						init_special_post_group_member_edit();
				},200);
			},
			error: function(xhr, status, error){
			}
			});
		},
	);
	
	  
	//*Ticket View Modal Hide Event  
	$(document).on(
		'hide.bs.modal',
		'#wxEditSpecialPosts',
		function (event) {
			var modal = $(this);
			modal.find('.modal-content').html(editFormLoaderHtml); 
		},
	);

	//* Edit Special Post Validator
	function init_edit_special_post_validator(){
		var wxEditSpecialPostForm = $("#wx-edit-special-post-form").validate({   
		  ignore: "",     
		  rules: {
			"wx_special_post_title":"required",
		  },
		  messages: {},
		  invalidHandler: function() {
			  var errMsg = 'You have '+wxEditSpecialPostForm.numberOfInvalids()+' errors in form.';
			  	Swal.fire({
					html: errMsg,  
					icon: "warning",
				});
		  },
		  submitHandler: function(form, event) {
			  event.preventDefault();
	  
			  tinyMCE.triggerSave();
			  
			  var parentForm = $('#'+event.target.id);
			  var parent = parentForm.closest('.wxModals');
			  parent.find('.wxAddPostSbmtBtn').text('Saving...');
			  parent.find('.wxAddPostSbmtBtn').attr('disabled',true);
	  
			  $.ajax({
				url: ajaxUrl,
				type: "POST",
				dataType: "json",
				data: {
					'action': 'wx_edit_special_post',
					'formData': $(form).serialize()
				},
				success: function(response) { 
				  parent.find(".wxPostCreatedSuccess").show();
				  parent.find(".wxAddPostSbmtBtn").attr('disabled',false);
				  parent.find(".wxAddPostSbmtBtn").text('Saved');  
	  
				  setTimeout(function(){
					  $(".wxPostCreatedSuccess").hide();
					  var modalId = parent.closest('.wxModals').attr('id');
					  $("#"+modalId).modal('hide');
					//   $('body').removeClass('modal-open');
					//   $('.modal-backdrop').remove();
					  parent.find(".wxAddPostSbmtBtn").text('Save');
					  reset_search_filters_and_draw_datatable_special_posts();
				  },2500);                                      
				},
				error: function(xhr, status, error){
				  parent.find(".wxPostCreatedSuccess").show();
				  parent.find(".wxAddPostSbmtBtn").attr('disabled',false);
				  parent.find(".wxAddPostSbmtBtn").text('Save');
				}
			});
		  }        
		});
	}
	
	//* Delete Support Ticket
	$(document).on('click','.wxDelSpecialPost',function(){
		var wx_special_post_id = $(this).data('id');
		var post_type_name = $(this).data('post-type-name');
		
		Swal.fire({
		title: 'Are you sure?',
		text: "Once deleted, you will not be able to recover this "+post_type_name+"!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
			$.ajax({
				url: ajaxUrl,
				type: "POST",
				dataType: "json",
				data: {
				'action'        : 'wx_delete_special_post',
				'wx_special_post_id'     : wx_special_post_id,
				},
				success: function(response) {    
					reset_search_filters_and_draw_datatable_special_posts();                  
					$("#wxEditSpecialPosts").modal('hide');
				},
				error: function(xhr, status, error){
					var err = JSON.parse(xhr.responseText);
				}
			});
	
			Swal.fire(
				'Deleted!',
				post_type_name+' has been deleted!',
				'success'
			);
	
			}
		});

  	}); 

	//* Special Posts Tags Select 2
	$('.wx_special_post_tags').select2({
		dropdownParent : '#wx_special_post_tags_add'
	});
	//*Special Posts All Groups Members 
	$('.wx_special_post_all_group_members').select2({
		dropdownParent : '#wx_special_post_all_group_members'
	});

	//* Sepcial post group to load its members
	$(document).on('change','.wx_special_post_group',function(){
		var group_id = $(this).val();
		var parent = $(this).closest('.wxModals');
		
		parent.find(".wx_special_post_group_members").html('<option value="">Loading...</option>');
		
		if(!group_id){
			parent.find(".wx_special_post_group_members").html('<option value="">Select</option>');
			return;
		}

		$.ajax({
            url: ajaxUrl,
            type: "POST",
            dataType: "json",
            data: {
                'action': 'wx_special_post_group_load_members',
                'group_id' : group_id
            },
            success: function(response) {                
                if(response.success)
                {   
                    parent.find('.wx_special_post_group_members').html(response.data.html);
                    parent.find('.wx_special_post_group_members_edit').html(response.data.html);
					setTimeout(function(){ 
						init_special_post_group_member();
						init_special_post_group_member_edit(); 
					},200);
                }
            },
            error: function(xhr, status, error){
            }
        });


	});

	//* Special Post Group Member 
	function init_special_post_group_member(){
		$('.wx_special_post_group_members').select2({
			dropdownParent : '#wx_special_post_group_members'
		});
	}
	init_special_post_group_member();

	//* Wx Speical Post Friends
	$('.wx_special_post_friends').select2({
		dropdownParent : '#wx_special_post_friends'
	});

	function reset_add_form_select2(){
		$('.wx_special_post_friends').select2({
			dropdownParent : '#wx_special_post_friends'
		}).val('').trigger('change');
		$('.wx_special_post_group_members').select2({
			dropdownParent : '#wx_special_post_group_members'
		}).val('').trigger('change');
		$('.wx_special_post_all_group_members').select2({
			dropdownParent : '#wx_special_post_all_group_members'
		}).val('').trigger('change');
		$('.wx_special_post_tags').select2({
			dropdownParent : '#wx_special_post_tags_add'
		}).val('').trigger('change');
	}

	//*Edit Form Select2 

	
	//* Special Post Group Member 
	function init_special_post_group_member_edit(){
		$('.wx_special_post_group_members_edit').select2({
			dropdownParent : '#wx_special_post_group_members_edit'
		});
	}
	init_special_post_group_member_edit();
	 
	//* Special Posts Filter Form 
	$("#wx-special-posts-filter-form").validate({
        ignore: "",
        rules: {
        },
        messages: {},
        invalidHandler: function() {
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var parentForm = $("#wx-special-posts-filter-form");

            parentForm.find(".wxAddPostSbmtBtn").text('Loading....'); 
           $(".wx-special-posts-page-container").html(editFormLoaderHtml); 
            

            $.ajax({
                url: ajaxUrl,
                type: "POST",
                dataType: "json",
                data: {
                    'action': 'wx_apply_special_posts_archive_page_filter',
                    'formData': $(form).serialize()
                },
                success: function(response) {  
                    parentForm.find(".wxAddPostSbmtBtn").text('Apply Filter');  
                    $(".wx-special-posts-page-container").html(response.data.html); 
                                       
                },
                error: function(xhr, status, error){
                    var err = JSON.parse(xhr.responseText);
                    parentForm.find(".wxPostCreatedError .alert-danger").text(err.data.message);
                    parentForm.find(".wxPostCreatedError").show();
                    parentForm.find(".wxAddPostSbmtBtn").attr('disabled',false);
                }
            });
        }        
    });
	

})( jQuery );

function reset_search_filters_and_draw_datatable_special_posts(){
	var WX_Special_Posts_Dashboard = jQuery('#WX_Special_Posts_Dashboard').DataTable();
	WX_Special_Posts_Dashboard.search('').columns().search('').draw();
  }