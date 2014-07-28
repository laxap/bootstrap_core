# --------------------
# TCEFORM.pages
# --------------------
TCEFORM.pages {

  # Layout
  layout {
    # change label name
    altLabels.0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.pages.layout.0
  }

  # Backend layout
  backend_layout {
    # not working in 6.2.2 (always all shown)
    # storagepage of backend layouts
    PAGE_TSCONFIG_ID = 2
    # hide no backend layout label
    removeItems = -1
    # instead of empty label
    altLabels.0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.pages.belayout.0
  }

  # Backend layout subpages
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
    # header_layout
    header_layout {
        # 1 = 0, 100 is hide
        removeItems = 0,100
    }

    # section_frame
    section_frame {
        disableNoMatchingValueElement = 1
        # remove "Invisible"
        removeItems = 1
        # rename "Frame 1", "Frame 2"
        altLabels {
            20 = Jumbotron (Hero)
            21 = Well
        }
        # additional bootstrap options
        addItems {
            22 = Well small
            23 = Well large

            40 = Info Box
            41 = Success Box
            42 = Caution Box
            43 = Alert Box
        }
    }

    # layout
    layout {
        disableNoMatchingValueElement = 1
        altLabels.0 = Normal

        types {
            # no layouts
            header.removeItems = 1,2,3
            text.removeItems = 1,2,3
            list.removeItems = 1,2,3
            #div.removeItems = 2,3

            # Text/Image
            textpic {
                altLabels {
                    0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.0
                    1 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.1
                    2 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.2
                    3 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.3
                }
                addItems.4 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.4
                addItems.5 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.5
                #addItems.6 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.6
                #addItems.7 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.7

            }

            # Image only
            image {
                altLabels {
                    0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.0
                    1 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.1
                    2 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.2
                    3 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.3
                }
                addItems.4 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.4
                addItems.5 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.5
                #addItems.6 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.6
                #addItems.7 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.images.7
            }

            # Menü
            menu {
                altLabels.0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.menu.0
                altLabels.1 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.menu.1
                altLabels.2 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.menu.2
                altLabels.3 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.menu.3
                addItems {
                    4 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.menu.4
                }
            }

            # Downloads
            uploads {
                # same as 2
                removeItems = 3
                altLabels {
                    1 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.uploads.1
                    2 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:tceform.tt_content.layout.uploads.2
                }
            }
        }
    }

    # Menu type options
    menu_type {
        # Remove sitemap elements
        #removeItems = 0,1,4,7,5,6
    }

    # Image Positions
    imageorient {
        disableNoMatchingValueElement = 1
    }

    # Available image cols for 12-grid and modified image rendering
    /*
    imagecols {
        removeItems = 5,7,8
        addItems {
            12 = 12
        }
    }
    */

    # Disable old table properties
    table_bgColor.disabled = 1
    table_border.disabled = 1
    table_cellspacing.disabled = 1
    table_cellpadding.disabled = 1
    /*
    pi_flexform {
        table {
            sDEF {
                acctables_nostyles.disabled = 1
                #acctables_tableclass.disabled = 1
            }
        }
    }
    */

}


# --------------------
# TCEMAIN.table
# --------------------
# Don't add 'Copy' to copied pages
TCEMAIN.table {
  pages.disablePrependAtCopy = 1
}
TCEMAIN.table {
  tt_content.disablePrependAtCopy = 1
}


# --------------------
# mod.web_list
# --------------------
mod.web_list {
  # hide content elements in list view
  #hideTables = tt_content
}
