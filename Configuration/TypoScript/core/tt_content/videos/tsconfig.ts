

# --- Add new content element ---
#
mod.wizards.newContentElement.wizardItems.common.elements.bootstrap_core {
    icon = ../typo3conf/ext/bootstrap_core/Resources/Public/Icons/wizard_videocontent.png
    title = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:video.ctype.title
    description = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:video.ctype.description
    tt_content_defValues {
        CType = bootstrapcore_videocontent
    }
}
mod.wizards.newContentElement.wizardItems.common.show := addToList(bootstrap_core)



# --- Rename labels (from image to video) ---
#
TCEFORM.tt_content.image_link {
    types {
        bootstrapcore_videocontent.label = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:video.links
    }
}

TCEFORM.tt_content.imagecaption {
    types {
        bootstrapcore_videocontent.label = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:video.caption
    }
}

TCEFORM.tt_content.imagewidth {
    types {
        bootstrapcore_videocontent.label = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:video.width
    }
}
TCEFORM.tt_content.imageheight {
    types {
        bootstrapcore_videocontent.label = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:video.height
    }
}
TCEFORM.tt_content.imagecaption_position {
    types {
        bootstrapcore_videocontent.label = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:video.position
    }
}
