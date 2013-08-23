
# --- tt_content.menu ----------
#
temp.defaultDefaultOuterWrap < tt_content.menu.20.default.stdWrap.outerWrap
temp.defaultStdWrapCObject < tt_content.menu.20.stdWrap.wrap

tt_content.menu {
  # Remove the ancient onfocus="blurLink(this);" from sitemap links
  # Unfortunately this hasn't been fully implemented in css_styled_content yet
  # See also bug 11367
  20 {
    default.1.noBlur = 1
    1.1.noBlur = 1
    4.1.noBlur = 1
    5.1.noBlur = 1
    6.1.noBlur = 1
    7.1.noBlur = 1
    7.2.noBlur = 1
  }

  # Navs (Twitter Bootstrap)
  20.default.stdWrap.outerWrap.cObject = CASE
  20.default.stdWrap.outerWrap.cObject {
    key.field = layout
    # Nav Pillen
    default = TEXT
    default.value < temp.defaultDefaultOuterWrap
    # Nav Pillen
    1 = TEXT
    1.value = <ul class="nav nav-pills">|</ul>
    # Nav Pillen Stacked
    2 = TEXT
    2.value = <ul class="nav nav-pills nav-stacked">|</ul>
    # Nav Listen
    3 = TEXT
    3.value = <ul class="nav nav-list">|</ul>
    # Dropdown
    4 = TEXT
    4.value = <ul class="dropdown-menu" role="menu" style="display: block; position: static; margin-bottom: 5px;">|</ul>
    # Button Dropdown
    5 = TEXT
    5.value = <ul class="dropdown-menu">|</ul>

  }

  20.default.1.SPC = 1
  20.default.1.SPC.doNotLinkIt = 1
  20.default.1.SPC.doNotShowLink = 1
  20.default.1.SPC.allWrap = <li class="divider"></li>

  20.stdWrap.wrap.cObject >
  20.stdWrap.wrap.if >

  20.stdWrap.wrap.cObject = CASE
  20.stdWrap.wrap.cObject {
    key.field = layout
    default < temp.defaultStdWrapCObject

    # Use accessibility title as title if set
    3 = COA
    3 {
      10 = TEXT
      10 {
        field = accessibility_title
        htmlSpecialChars = 1
        dataWrap = <div style="max-width: 250px; padding: 8px 0;" class="well"><ul class="nav nav-list"><li class="nav-header"></li>
        noTrimWrap = || |
      }
      20 = TEXT
      20.value = |</ul></div>
    }

    4 = TEXT
    4.value = <div class="dropdown clearfix">|</div>

    # Use accessibility title as button text if set
    5 = COA
    5 {
      10 = TEXT
      10 {
        field = accessibility_title
        htmlSpecialChars = 1
        #dataWrap = <div class="btn-group dropup"><button class="btn dropdown-toggle" data-toggle="dropdown">|</button><button class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        dataWrap = <div class="btn-group"><button class="btn dropdown-toggle" data-toggle="dropdown">|</button><button class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        noTrimWrap = || |
      }
      20 = TEXT
      20.value = |</div>
    }

  }


  # Menu of subpages of selected pages
  20.1.stdWrap.outerWrap.cObject < tt_content.menu.20.default.stdWrap.outerWrap.cObject

  # Sitemap
  #20.2.stdWrap.outerWrap.cObject < tt_content.menu.20.default.stdWrap.outerWrap.cObject

  # Section index (page content marked for section menus)
  20.3.stdWrap.outerWrap.cObject < tt_content.menu.20.default.stdWrap.outerWrap.cObject

  # Menu of subpages of selected pages including abstracts
  20.4.stdWrap.outerWrap.cObject < tt_content.menu.20.default.stdWrap.outerWrap.cObject

  # Recently updated pages
  20.5.stdWrap.outerWrap.cObject < tt_content.menu.20.default.stdWrap.outerWrap.cObject

  # Related pages (based on keywords)
  20.6.stdWrap.outerWrap.cObject < tt_content.menu.20.default.stdWrap.outerWrap.cObject

  # Menu of subpages of selected pages including sections
  20.7.stdWrap.outerWrap.cObject < tt_content.menu.20.default.stdWrap.outerWrap.cObject

  # Sitemaps of selected pages
  #20.8.stdWrap.outerWrap.cObject < tt_content.menu.20.default.stdWrap.outerWrap.cObject



}