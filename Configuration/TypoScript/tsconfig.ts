# --------------------
# TCEFORM.pages
# --------------------
TCEFORM.pages {
  layout {
    altLabels.0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.pages.layout.0
  }

  backend_layout {
    # not working in 6.2.2 (always all shown)
    # storagepage of backend layouts
    PAGE_TSCONFIG_ID = 2
    # hide no backend layout label
    removeItems = -1
    # instead of empty label
    altLabels.0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.pages.belayout.0
  }

  backend_layout_next_level {
    # not working in 6.2.2 (always all shown)
    PAGE_TSCONFIG_ID = 2
    removeItems = -1
    altLabels.0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.pages.belayoutnext.0
  }

  TSconfig.config.cols = 50
}


# --------------------
# TCEFORM.tt_content
# --------------------
TCEFORM.tt_content {
    header_layout {
        # 1 = 0, 100 is hide
        removeItems = 0,100
    }

    section_frame {
        disableNoMatchingValueElement = 1
        # remove "Invisible"
        removeItems = 1
        # rename "Frame 1", "Frame 2"
        altLabels {
            20 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.sectionframe.20
            21 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.sectionframe.21
        }
        # additional bootstrap options
        addItems {
            22 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.sectionframe.22
            23 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.sectionframe.23
            40 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.sectionframe.40
            41 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.sectionframe.41
            42 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.sectionframe.42
            43 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.sectionframe.43
        }
    }

    layout {
        disableNoMatchingValueElement = 1
        altLabels.0 = Normal

        types {
            # no layouts
            header.removeItems = 1,2,3
            text.removeItems = 1,2,3
            list.removeItems = 1,2,3

            textpic {
                altLabels {
                    0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.0
                    1 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.1
                    2 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.2
                    3 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.3
                }
                addItems.4 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.4
                addItems.5 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.5
            }

            image {
                altLabels {
                    0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.0
                    1 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.1
                    2 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.2
                    3 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.3
                }
                addItems.4 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.4
                addItems.5 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.5
            }

            menu {
                altLabels.0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.menu.0
                altLabels.1 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.menu.1
                altLabels.2 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.menu.2
                altLabels.3 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.menu.3
                addItems {
                    4 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.menu.4
                }
            }

            uploads {
                removeItems = 3
                altLabels {
                    1 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.uploads.1
                    2 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.uploads.2
                }
            }
        }
    }

    imageorient {
        disableNoMatchingValueElement = 1
    }

    # Disable old table properties
    table_bgColor.disabled = 1
    table_border.disabled = 1
    table_cellspacing.disabled = 1
    table_cellpadding.disabled = 1
}


# --------------------
# TCEMAIN.table
# --------------------
# Don't add 'Copy' to copied pages
TCEMAIN.table {
  pages.disablePrependAtCopy = 1
  tt_content.disablePrependAtCopy = 1
}
