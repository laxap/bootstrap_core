
# -----------------------------------------------------------------------------
# lib.parseFunc_RTE
# -----------------------------------------------------------------------------
#
lib.parseFunc_RTE {

  nonTypoTagStdWrap.encapsLines {
    # remove class 'bodytext' from p-tags
    addAttributes.P.class >
    addAttributes.P.class.setOnly >
  }

  # allow buttons
  allowTags := addToList(button)
  # no p-tag around buttons?
  #externalBlocks := addToList(button)

  # still required/ok?
  externalBlocks.table {
    # Allow more classes than only 'contenttable'
    stdWrap.HTMLparser.tags.table.fixAttrib.class.list >
    # Default table tag class
    stdWrap.HTMLparser.tags.table.fixAttrib.class.default = table
    # p-Tags in th/td entfernen
    HTMLtableCells.default >
    HTMLtableCells.default.stdWrap.parseFunc =< lib.parseFunc
  }

}