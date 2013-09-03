
# -----------------------------------------------------------------------------
# TCEFORM.tt_content
# -----------------------------------------------------------------------------

# ======================================================
# header_layout
#
TCEFORM.tt_content.header_layout {
    # 1 = 0, 100 is hide
    removeItems = 0,100
    altLabels {
        #0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.header_layout.0
        1 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.header_layout.1
        2 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.header_layout.2
        3 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.header_layout.3
        4 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.header_layout.4
        5 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.header_layout.5
    }

    types {
        gridelements_pi1.disabled = 1
    }
}


# ======================================================
# section_frame
#
TCEFORM.tt_content.section_frame {

    # Remove all predefined frames execpt 0 (default) and 66 (no frame).
    # Special keys for user-friendly ordering/grouping of elements.
    removeItems = 1,5,6,10,11,12,20,21

    # Rename default frame labels
    altLabels {
        #0 = Normal
        #66 = Standard (no wrap)
    }
    # Add new frame types for bootstrap
    addItems {
        120 = Well
        121 = Well condensed
        130 = Hero Unit
        140 = Info Box
        141 = Success Box
        142 = Caution Box
        143 = Alert Box
    }

    # Ctype specific section_frame
    types {
        # only page-header frame (and normal, no-frame)
        header {
            # remove all except default and no-frame
            removeItems = 1,5,6,10,11,12,20,21,120,121,130,140,141,142,143
            # Add additional frame types for header
            addItems {
                110 = Page Header
            }
        }

        # remove standard div wrap (0)
        # no-frame becomes default (plugins, divider, html, gridelements)
        list.removeItems = 0,1,5,6,10,11,12,20,21
        div.removeItems = 0,1,5,6,10,11,12,20,21
        html.removeItems = 0,1,5,6,10,11,12,20,21
        gridelements_pi1.removeItems = 0,1,5,6,10,11,12,20,21
    }
}



# ======================================================
# layout
#
TCEFORM.tt_content.layout {
    altLabels.0 = Normal
    #altLabels.1 = Layout 1
    #altLabels.2 = Layout 2
    #altLabels.3 = Layout 3

    types {
        # no layouts
        header.removeItems = 1,2,3
        text.removeItems = 1,2,3
        list.removeItems = 1,2,3
        #div.removeItems = 2,3
        gridelements_pi1.removeItems = 1,2,3
    }
}



# ======================================================
# header_link
#
TCEFORM.tt_content.header_link {
    # Don't show field if gridelement
    types {
        gridelements_pi1.disabled = 1
    }
}

# ======================================================
# header_position
#
TCEFORM.tt_content.header_position {
    types {
        gridelements_pi1.disabled = 1
    }
}

# ======================================================
# header_layout
#
TCEFORM.tt_content.header_layout {
    types {
        gridelements_pi1.disabled = 1
    }
}

# ======================================================
# date
#
TCEFORM.tt_content.date {
    types {
        gridelements_pi1.disabled = 1
    }
}


# -----------------------------------------------------------------------------
# TCEMAIN.table.tt_content
# -----------------------------------------------------------------------------

TCEMAIN.table.tt_content {
  # Don't add 'Copy' to copied elements
  disablePrependAtCopy = 1
}


# -----------------------------------------------------------------------------
# mod.web_list
# -----------------------------------------------------------------------------

mod.web_list {
  # hide content elements in list view
  #hideTables = tt_content
}


# -----------------------------------------------------------------------------
# TCAdefaults.tt_content
# -----------------------------------------------------------------------------
#
TCAdefaults.tt_content {
    # header position
    #header_position = center

    # image col width
    #tx_bootstrapcore_imageswidth = 6

    # section frame
    #section_frame = 66
}