
# -----------------------------------------------------------------------------
# pages
# -----------------------------------------------------------------------------
#
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bootstrap_core/Configuration/TypoScript/core/pages/tsconfig.ts">

# -----------------------------------------------------------------------------
# tt_content
# -----------------------------------------------------------------------------
#
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bootstrap_core/Configuration/TypoScript/core/tt_content/tsconfig.ts">
# Image
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bootstrap_core/Configuration/TypoScript/core/tt_content/image/tsconfig.ts">
# Menu & Sitemaps
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bootstrap_core/Configuration/TypoScript/core/tt_content/menu/tsconfig.ts">
# RTE
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bootstrap_core/Configuration/TypoScript/core/tt_content/rte/tsconfig.ts">
# Downloads
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bootstrap_core/Configuration/TypoScript/core/tt_content/uploads/tsconfig.ts">
# Videos
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:bootstrap_core/Configuration/TypoScript/core/tt_content/videos/tsconfig.ts">


# -----------------------------------------------------------------------------
# Additional Content Elements for special layouts
# -----------------------------------------------------------------------------
#

# --- Add new content element for textelement ---
#
mod.wizards.newContentElement.wizardItems.special.elements.bootstrapcore_textelement {
    icon = ../typo3conf/ext/bootstrap_core/Resources/Public/Icons/wizard_textelement.png
    title = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:textelement.ctype.title
    description = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:textelement.ctype.description
    tt_content_defValues {
        CType = bootstrapcore_textelement
    }
}
# Add to list with CEs
mod.wizards.newContentElement.wizardItems.special.show := addToList(bootstrapcore_textelement)


# --- Add new content element for imageelement ---
#
mod.wizards.newContentElement.wizardItems.special.elements.bootstrapcore_imageelement {
    icon = ../typo3conf/ext/bootstrap_core/Resources/Public/Icons/wizard_imageelement.png
    title = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:imageelement.ctype.title
    description = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang_db.xlf:imageelement.ctype.description
    tt_content_defValues {
        CType = bootstrapcore_imageelement
    }
}
# Add to list with CEs
mod.wizards.newContentElement.wizardItems.special.show := addToList(bootstrapcore_imageelement)