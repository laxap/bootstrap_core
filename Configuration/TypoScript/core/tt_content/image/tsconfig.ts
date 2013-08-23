
# -----------------------------------------------------------------------------
# TCEFORM.tt_content
# -----------------------------------------------------------------------------
#
TCEFORM.tt_content {
    # Layouts
    layout {
        types {
            # Text/Image
            textpic {
                altLabels {
                    0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.layout.images.0
                    1 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.layout.images.1
                    2 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.layout.images.2
                    3 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.layout.images.3
                }
                # Slider, not anymore availbable
                #addItems.4 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.layout.images.4
            }

            # Image only
            image {
                altLabels {
                    0 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.layout.images.0
                    1 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.layout.images.1
                    2 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.layout.images.2
                    3 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.layout.images.3
                }
                #addItems.4 = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:tceform.tt_content.layout.images.4
            }
        }
    }

    # Image Position
    imageorient {
        disableNoMatchingValueElement = 1
    }

    # Available image cols for 12-grid and modified image rendering
    imagecols {
        removeItems = 5,7,8
        addItems {
            12 = 12
        }
    }
}



# -----------------------------------------------------------------------------
# TCAdefaults.tt_content
# -----------------------------------------------------------------------------
#
TCAdefaults.tt_content {
    imagecols = 1
    # used only when modified
    tx_bootstrapcore_imageswidth = 6
}