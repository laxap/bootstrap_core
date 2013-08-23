
# -----------------------------------------------------------------------------
# tt_content.stdWrap
# -----------------------------------------------------------------------------
#
tt_content.stdWrap {

  # toTop Link
  #
  #stdWrap.innerWrap2 = | <p class="csc-linkToTop no-print"><a href="#">{LLL:EXT:css_styled_content/pi1/locallang.xml:label.toTop}</a></p>

  # Add class to first div based on section_frame
  #
  innerWrap.cObject {

    # Remove predefined and not used from ts setup
    1 >
    5 >
    6 >
    10 >
    11 >
    12 >
    20 >
    21 >

    # Section styles
    #
    110 =< tt_content.stdWrap.innerWrap.cObject.default
    120 =< tt_content.stdWrap.innerWrap.cObject.default
    121 =< tt_content.stdWrap.innerWrap.cObject.default
    130 =< tt_content.stdWrap.innerWrap.cObject.default
    140 =< tt_content.stdWrap.innerWrap.cObject.default
    141 =< tt_content.stdWrap.innerWrap.cObject.default
    142 =< tt_content.stdWrap.innerWrap.cObject.default
    143 =< tt_content.stdWrap.innerWrap.cObject.default

    110.20.10.value = page-header
    120.20.10.value = well
    121.20.10.value = well well-small
    130.20.10.value = hero-unit
    140.20.10.value = alert alert-info
    141.20.10.value = alert alert-success
    142.20.10.value = alert alert-warning
    143.20.10.value = alert alert-error

    # Special wraps for specific frames
    #
    default.30.cObject.default.value >
    default.30.cObject.default.cObject = CASE
    default.30.cObject.default.cObject {
      key.field = section_frame
      default = TEXT
      default.value = >|</div>

      # Add  close button for alert boxes
      140 = TEXT
      140.value = ><button type="button" class="close" data-dismiss="alert">&times;</button>|</div>
      141 < .140
      142 < .140
      143 < .140
    }
  }

  # Define different wrappers for content elements
  # depending on the page column
  #
  /*
  outerWrap.cObject = CASE
  outerWrap.cObject {
    # Define wrappers for each column position (colPos 0,1,2)
    key.field = colPos
    # Default is no wrapper
    default = TEXT
    default.value = |
    # Add wrapper for content in right column (colPos=1)
    # we use this to style the box around content in this column
    1 = CASE
    1 {
      # Add wrapping to all content elements
      default = TEXT
      default.value = <div class="secondaryContentSection">|</div>
      # But - exclude inserted records from being wrapped
      key.field = CType
      shortcut = TEXT
      shortcut.value = |
      image = TEXT
      image.value = |
      html = TEXT
      html.value = |
    }
  }
  */
}


# -----------------------------------------------------------------------------
# tt_content.div
# -----------------------------------------------------------------------------
#
tt_content.div {
  #value = <hr>
  override >
  override {
    dataWrap = <hr class="style-{field:layout}">
    if.value = 0
    if.equals.field = layout
    if.negate = 1
  }
  wrap >
}

