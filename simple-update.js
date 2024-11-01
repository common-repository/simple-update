jQuery(document).ready( function() {
  jQuery(".wp-editor-area#content").on("change keyup paste", function() {
    source_changed();
  });
  jQuery("#simple_update").on("change keyup paste", 'input.su', function() {
    id = jQuery(this).attr('data-title');
    update_source(id);
  });
  source_changed();

  jQuery('#simple_update').on('click', '.remove_su_link', function() {
    id = jQuery(this).siblings('label').text();
    data = jQuery('.simple_update[data-title="'+id+'"').html();
    jQuery('.simple_update[data-title="'+id+'"').before(data).remove();
    jQuery(".wp-editor-area#content").val(jQuery('.simple_update_temp_data').html());
    tinyMCE.activeEditor.setContent(jQuery('.simple_update_temp_data').html());
    jQuery(this).parent().remove();
  })

  jQuery('[name="hide_wp_editor"]').change(function() {
    check_hide_editor();
  })
  check_hide_editor();

})

function check_hide_editor() {
    if(jQuery('[name="hide_wp_editor"]').is(':checked')) {
      jQuery('#wp-content-wrap').css('display','none');
    } else {
      jQuery('#wp-content-wrap').css('display','block');
    }
}

jQuery(window).load(function(){ jQuery(".wp-switch-editor.switch-html").click(); })

function update_source(id) {
  val = jQuery('.simple_update_temp_vars [data-title="'+id+'"]').val();
  id = id.substring(3);
  jQuery('.simple_update_temp_data [data-title="'+id+'"]').html(val);
  jQuery(".wp-editor-area#content").val(jQuery('.simple_update_temp_data').html());
  if (typeof(tinyMCE) != "undefined") {
    if (tinyMCE.activeEditor !== null && tinyMCE.activeEditor.isHidden() === false) {
      tinyMCE.activeEditor.setContent(jQuery('.simple_update_temp_data').html());
    }
  }
}

function source_changed(html) {

  if (!html) var html = jQuery('.wp-editor-area#content').val();
  jQuery('.simple_update_temp_data').html(html);
  
  if (jQuery('.simple_update_temp_data .simple_update').length>0) {
    jQuery('.simple_update_temp_vars').html('');
  } else {
    jQuery('.simple_update_temp_vars').text('no simple update variables found');
  }
  jQuery('.simple_update_temp_data .simple_update').each(function() {
      id = jQuery(this).attr('data-title');
      rel_id = "su_"+id;
      val = jQuery(this).html();
      data = '<li><label>'+id+'</label><input data-title="'+rel_id+'" class="su" value="'+val+'"/><b class="remove_su_link">x</b></li>';
      jQuery('.simple_update_temp_vars').append(data);
  })
}