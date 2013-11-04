
# --- tt_content.image ----------
#
tt_content.image {

  # image styles (rounded, circle, polaroid)
  20.1.params.cObject = CASE
  20.1.params.cObject {
    # Define wrappers for each column position (colPos 0,1,2)
    key.field = layout
    # Default is no wrapper
    default = TEXT
    default.value = class="img-content"
    1 = TEXT
    1.value = class="img-responsive img-content"
    2 = TEXT
    2.value = class="img-rounded img-content"
    3 = TEXT
    3.value = class="img-rounded img-responsive img-content"
    4 = TEXT
    4.value = class="img-circle img-content"
    5 = TEXT
    5.value = class="img-circle img-responsive img-content"
    6 = TEXT
    6.value = class="img-polaroid img-content"
    7 = TEXT
    7.value = class="img-polaroid img-responsive img-content"
  }

}

