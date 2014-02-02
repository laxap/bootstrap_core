
# -----------------------------------------------------------------------------
# TCEFORM.tt_content
# -----------------------------------------------------------------------------
#
TCEFORM.tt_content {

  # Main text content
  bodytext {
    #RTEfullScreenWidth = 100%
    RTEfullScreenWidth = 940
    RTEfullScreenHeight = 600
  }

}


# -----------------------------------------------------------------------------
# RTE.classes
# -----------------------------------------------------------------------------
#
# Remove default completely
RTE.classes >

# Define all available classes
RTE.classes {

  # ======================================================
  # Default typo3 link classes
  # ======================================================
  internal-link {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.internal-link
  }
  internal-link-new-window {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.internal-link-new-window
  }
  external-link {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.external-link
  }
  external-link-new-window {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.external-link-new-window
  }

  # ======================================================
  # Default twitter bootstrap classes
  # ======================================================
  #
  # --- Paragraph classes ---
  #
  lead {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.lead
    value = font-size: 110%;
  }

  text-center {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.justifycenter
    value = text-align: center;
  }
  text-left {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.justifyleft
    value = text-align: left;
  }
  text-right {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.justifyright
    value = text-align: right;
  }

  text-muted {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.muted
    value = color: #999999;
  }
  text-warning {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.warning
    value = color: #c09853;
  }
  text-danger {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.error
    value = color: #B94A48;
  }
  text-info {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.info
    value = color: #3A87AD;
  }
  text-success {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.success
    value = color: #468847;
  }


  # --- Blockquote classes ---
  #
  pull-left {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.floatleft
  }
  pull-right {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.floatright
  }


  # --- Span classes ---
  #
  label {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.label
  }
  badge {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.badge
  }

  # --- Table classes ---
  #
  table {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.table
  }
  table-striped {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.table-striped
  }
  table-bordered {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.table-bordered
  }
  table-hover {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.table-hover
  }
  table-condensed {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.table-condensed
  }
  # -- div wrap around table
  table-responsive {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.table-responsive
  }

  # --- Table row classes ---
  #
  active {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.info
    value = background-color: #d9edf7;
  }
  success {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.success
    value = background-color: #dff0d8;
  }
  danger {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.error
    value = background-color: #f2dede;
  }
  warning {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.warning
    value = background-color: #fcf8e3;
  }

  # --- Link classes ---
  #
  btn {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.button
  }


  # ======================================================
  # Additional classes for bootstrap
  # ======================================================

  # --- Link classes ---
  #
  # for prettyPhoto
  lightbox {
    name = LLL:EXT:bootstrap_core/Resources/Private/Language/locallang.xlf:rte.classes.lightbox
  }

}


# -----------------------------------------------------------------------------
# RTE.classesAnchor
# -----------------------------------------------------------------------------
#
RTE.classesAnchor {

  # ======================================================
  # Default typo3 link classes
  # ======================================================
  #
  # --- Internal page links
  #
  internalLink {
    class = internal-link
    type = page
    titleText >
    image >
  }
  internalLinkInNewWindow >

  # --- External page links
  #
  # Default external links
  externalLink {
    class = external-link
    type = url
    titleText >
    image >
  }
  externalLinkInNewWindow >

  # --- File download links
  #
  download  {
    class = download
    type = file
    titleText >
    image >
  }

  # --- Email links
  #
  mail  {
    class = mail
    type = mail
    titleText >
    image >
  }


  # ======================================================
  # Additional link classes for bootstrap
  # ======================================================
  #
  # --- Button styles (for internal links)
  #
  button {
    class = "btn btn-default"
    titleText =
    type = page
  }
  button2 {
    class = "btn btn-primary"
    titleText =
    type = page
  }
  button3 {
    class = "btn btn-info"
    titleText =
    type = page
  }
  button4 {
    class = "btn btn-inverse"
    titleText =
    type = page
  }
  button5 {
    class = "btn btn-primary btn-lg"
    titleText =
    type = page
  }
  button6 {
    class = "btn btn-info btn-lg"
    titleText =
    type = page
  }


}


# -----------------------------------------------------------------------------
# RTE.default
# -----------------------------------------------------------------------------
#
RTE.default {

  # CSS file for RTE
  contentCSS = theme/default/css/rte/content.css

  # Use stylesheet file to style the contents (htmlArea RTE only), default = 1
  #ignoreMainStyleOverride = 1

  enableWordClean = 1

  # ======================================================
  # RTE toolbar container type and style selector
  # ======================================================

  buttons {
    formatblock {
      removeItems = article,aside,footer,header,h6,nav,section
    }

    blockstyle.tags {
      p.allowedClasses = lead, text-left, text-center, text-right, text-muted, text-warning, text-danger, text-info, text-success

      blockquote.allowedClasses = pull-left, pull-right

      h1.allowedClasses = text-left, text-center, text-right, media-heading
      h2.allowedClasses = text-left, text-center, text-right, media-heading
      h3.allowedClasses = text-left, text-center, text-right, media-heading
      h4.allowedClasses = text-left, text-center, text-right, media-heading

      table.allowedClasses = table, table-striped, table-bordered, table-hover, table-condensed
      div.allowedClasses = table-responsive

      tr.allowedClasses = success, danger, warning, active
      td.allowedClasses = text-left, text-center, text-right, success, danger, warning, active
    }

    textstyle.tags {
      span.allowedClasses = text-muted, text-warning, text-danger, text-info, text-success, label, badge
    }

    link {
       properties.class.allowedClasses := addToList(lightbox,btn btn-default,btn btn-primary,btn btn-info,btn btn-inverse,btn btn-primar btn-lg,btn btn-info btn-lg)
    }
  }


  # ======================================================
  # Available buttons and order in RTE toolbar
  # ======================================================

  #showButtons = *
  showButtons = blockstylelabel, blockstyle, textstylelabel, textstyle, formatblocklabel, formatblock, bold, italic, subscript, superscript, orderedlist, unorderedlist, outdent, indent, textindicator, insertcharacter, link, table, findreplace, chMode, removeformat, undo, redo, about, toggleborders, tableproperties, rowproperties, rowinsertabove, rowinsertunder, rowdelete, rowsplit, columninsertbefore, columninsertafter, columndelete, columnsplit, cellproperties, cellinsertbefore, cellinsertafter, celldelete, cellsplit, cellmerge, textindicator, acronym, line, copy, cut, paste, pastetoggle, pastebehaviour, pasteastext

  toolbarOrder (
    table, toggleborders, bar, tableproperties, rowproperties, cellproperties, bar,
        rowinsertabove,rowinsertunder,rowdelete,rowsplit,bar,
        columninsertbefore,columninsertafter,columndelete,columnsplit,bar,
        cellinsertbefore,cellinsertafter,celldelete,cellmerge,cellsplit, bar, about,
    linebreak,
    undo, redo, bar, removeformat, chMode, formatblock,
    linebreak,
    blockstylelabel, blockstyle,
    linebreak,
    bold, italic, bar, orderedlist, unorderedlist, textstyle, link, insertcharacter, line, bar, subscript, superscript, bar, copy, cut, paste, pastetoggle, pastebehaviour, pasteastext, bar, textindicator
  )


  # ======================================================
  # Processing config
  # ======================================================

  proc {
    # Requirements: static_info_tables, adding btns to 'toolbarOrder', defining acronym/abbreviation item records
    #allowTags := addToList(abbr, acronym)
    #denyTags = font

    # <button>
    allowTags := addToList(button)
    allowTagsOutside := addToList(button)

    # <br> are not converted to <p>, default = 1
    #dontConvBRtoParagraph = 1

    # allowed attributes in p and div tags, default = align,class,style,id
    #keepPDIVattribs = align,class,style,id

    # All allowed classes
    # text-justify not in set
    allowedClasses (
        external-link, internal-link, download, mail,
        btn, btn-default, btn-primary, btn-info, btn-success, btn-warning, btn-danger, btn-inverse, btn-link, btn-lg, btn-sm, btn-xs,
        text-left, text-center, text-right,
        text-info, text-success, text-warning, text-danger, text-muted,
        lead, label, badge,
        table, table-bordered, table-striped, table-condensed, table-hover, success, warning, danger, active,
        lightbox, indent
    )

    # Remapping b and i, b => strong => b (in db/output as strong-tag, in rte as b-tag)
    #
    exitHTMLparser_db = 1
    exitHTMLparser_db {
      allowTags < RTE.default.proc.entryHTMLparser_db.allowTags
      tags.b.remap = strong
      tags.i.remap = em
    }
    exitHTMLparser_rte = 1
    exitHTMLparser_rte  {
      allowTags < RTE.default.proc.entryHTMLparser_db.allowTags
      tags.strong.remap = b
      tags.em.remap = i
    }

  }
}


# -----------------------------------------------------------------------------
# RTE.default.FE
# -----------------------------------------------------------------------------
#
# Copy to FE editor
RTE.default.FE < RTE.default
RTE.default.FE.userElements >
RTE.default.FE.userLinks >


# -----------------------------------------------------------------------------
# RTE.config
# -----------------------------------------------------------------------------
#
/*
RTE.config {

}
*/

