
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
    default.value =
    1 = TEXT
    1.value = class="img-rounded"
    2 = TEXT
    2.value = class="img-circle"
    3 = TEXT
    3.value = class="img-polaroid"
  }

}

