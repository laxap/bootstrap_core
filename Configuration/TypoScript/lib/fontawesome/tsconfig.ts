
# -----------------------------------------------------------------------------
# TCEFORM.tt_content
# -----------------------------------------------------------------------------

# ======================================================
# Don't show icon field in gridelements
#
TCEFORM.tt_content.tx_bootstrapcore_icon {
    types {
        gridelements_pi1.disabled = 1
    }
}


# -----------------------------------------------------------------------------
# RTE.classes
# -----------------------------------------------------------------------------
RTE.classes {
    # --- Span classes  ---
    #
    # Add icons in RTE
    iconhome {
        name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.iconhome
    }
    iconphone {
        name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.iconphone
    }
    iconloc {
        name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.iconloc
    }
    iconemail {
        name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.iconemail
    }
}


# -----------------------------------------------------------------------------
# RTE.default
# -----------------------------------------------------------------------------
RTE.default {
    buttons {
        textstyle.tags {
            span.allowedClasses := addToList(iconphone, iconhome, iconloc, iconemail)
        }
    }
    proc {
        allowedClasses := addToList(iconphone, iconhome, iconemail, iconloc)
    }
}