var $scope = [];
var previousWidth = jQuery(document).width();

function clearSearch() {
  jQuery("#language").val('');
  jQuery("#query").val('');
  jQuery('input[type="checkbox"]').attr('checked', false);
}

function advance($type) {

  console.log($type);
  switch ($type) {
    case 'close':
      if (jQuery(document).width() > 430)
        jQuery('.standards-search').show();

      jQuery('.advance-search').show();
      jQuery('.close-button').hide();

      jQuery('.search-slide').slideUp('slow', function () {
        if (jQuery(".advanced-slide").is(":hidden")) {
          jQuery('.search-options').removeClass('toggled');
        }
      });
      break;
    case 'advanced':
      jQuery('.advance-search').hide();
      jQuery('.standards-search').hide();
      jQuery('.close-button').show();
      jQuery('.advanced-slide').slideDown('slow');
      jQuery('.search-options').addClass('toggled');
      break;
    case 'standard':
      jQuery('.advance-search').hide();
      jQuery('.standards-search').hide();
      jQuery('.close-button').show();
      jQuery('.standards-slide').slideDown('slow');
      jQuery('.search-options').addClass('toggled');
      break;
  }
}

function uncheck_subject_areas($this, sub) {
  if (!jQuery($this).is(':checked')) {
    jQuery('.' + sub).attr('checked', false);
    jQuery('.' + sub).hide();
  } else {
    jQuery('.' + sub).show();
  }
}

function check_subject($this, sub) {
  if (jQuery($this).is(':checked') && !jQuery('#' + sub).is(':checked')) {
    jQuery('#' + sub).click();
  }
}

function showHoverSubjects(subjectArea, subject) {
  if (jQuery(document).width() > 541) {
    jQuery('.subjectarea').hide();
    jQuery('.subject-optionset li.hover').removeClass('hover');

    jQuery('.' + subjectArea).show();
    jQuery(subject).addClass('hover');
  }
}

jQuery(document).ready(function () {

  activeItem = jQuery("#standards-accordion li:first");
  if (activeItem.length) {
    jQuery(activeItem).addClass("active");
    jQuery("#standards-accordion li .standards-tab-header").click(function () {
      jQuery(activeItem).removeClass("active");
      activeItem = jQuery(this).parent();
      jQuery(activeItem).addClass("active");
    });
    jQuery(activeItem).click();
  }

  jQuery('.search-tool-tip').qtip({// Grab some elements to apply the tooltip to
    content: {
      title: {
        text: 'Search Tips & Advance Features',
        button: true
      },
      text: jQuery('.search-tool-tip-text').html()

    },
    style: {
      classes: 'qtip-blue qtip-shadow toolTipCustomClass',
    },
    position: {
      my: 'top right', // Position my top left...
      at: 'bottom left', // at the bottom right of...
      target: jQuery('.search-tool-tip') // my target
    },
    show: 'click',
    hide: {
      event: 'click',
      event: 'blur',
              //inactive: 6000
    }
  });

  handleResizeSubjectAreas();

});

jQuery(window).resize(function () {
  handleResizeSubjectAreas();
});

var handleResizeSubjectAreas = function () {
  if (jQuery(document).width() < 542 && jQuery(".subjectareas li.subjectarea").length) {
    jQuery('.subjectarea').hide();
    listItems = jQuery(".subjectareas li.subjectarea").detach();
    listItems.each(function (index, value) {
      subjectid = jQuery(value).attr('subjectid');
      jQuery('.subjects li[subjectid="' + subjectid + '"] ul').append(value);
      if (jQuery('#subject_' + subjectid).is(':checked'))
        jQuery(value).show();
    });
    console.log('Moved to subjects');
  } else if (jQuery(document).width() > 541 && jQuery(".subjects li.subjectarea").length) {
    listItems = jQuery(".subjects li.subjectarea").detach();
    jQuery(".subjectareas").append(listItems);
    jQuery('.subjectarea').hide();
    console.log('Moved back to subjects areas');
  }
  previousWidth = jQuery(document).width();
}