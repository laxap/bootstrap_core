
# -----------------------------------------------------------------------------
# TCEFORM.pages
# -----------------------------------------------------------------------------

TCEFORM.pages {

  # Layout
  layout {
    # change label name
    altLabels.0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.pages.layout.0
  }

  # Backend layout
  backend_layout {
    # storagepage of gridlayouts
    PAGE_TSCONFIG_ID = 2
    # hide no backend layout label
    removeItems = -1
    # instead of empty label
    altLabels.0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.pages.belayout.0
  }

  # Backend layout subpages
  backend_layout_next_level {
    PAGE_TSCONFIG_ID = 2
    removeItems = -1
    altLabels.0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.pages.belayoutnext.0
  }

  TSconfig.config.cols = 50

}


# -----------------------------------------------------------------------------
# TCEMAIN.
# -----------------------------------------------------------------------------

TCEMAIN.table {
  # Don't add 'Copy' to copied pages
  pages.disablePrependAtCopy = 1
}
