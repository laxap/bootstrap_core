
# ---------------------------------------------------------------------------------------
# bootstrap_core default grids
# ---------------------------------------------------------------------------------------
#

# Remove 2nd column from defaultGridSeetup. Three digit column ids are used (101, 102, 201...)
lib.gridelements.defaultGridSetup.columns.2 >


tt_content.gridelements_pi1.20.10.setup {

  # -------------------------------------------------------------------------------------
  # Column grids and special box grid element
  #
  # --- 2 columns ---
  #
  1 < lib.gridelements.defaultGridSetup
  1 {
    # row wrap
    #wrap = <div class="row">|</div>
    wrap.stdWrap.cObject = CASE
    wrap.stdWrap.cObject {
        key.field = flexform_rowclass2
        default = TEXT
        default.value = <div class="row">|</div>
    }

    columns {
        101 < .default
        101.wrap.stdWrap.cObject = CASE
        101.wrap.stdWrap.cObject {
            key.field = flexform_col21style
            default = TEXT
            default.value = <div class="{field:flexform_xsCol1} {field:flexform_smCol1} col-md-{field:flexform_mdCol1} {field:flexform_lgCol1} {field:flexform_col21class}">|</div>
            default.insertData = 1
        }
        102 < .101
        102.wrap.stdWrap.cObject {
            key.field = flexform_col22style
            default.value = <div class="{field:flexform_xsCol2} {field:flexform_smCol2} col-md-{field:flexform_mdCol2} {field:flexform_lgCol2} {field:flexform_col22class}">|</div>
        }
    }
  }

  # --- 3 columns ---
  #
  2 < lib.gridelements.defaultGridSetup
  2 {
    # row wrap
    wrap.stdWrap.cObject = CASE
    wrap.stdWrap.cObject {
        key.field = flexform_rowclass3
        default = TEXT
        default.value = <div class="row">|</div>
    }

    columns {
        101 < .default
        101.wrap.stdWrap.cObject = CASE
        101.wrap.stdWrap.cObject {
            key.field = flexform_col31style
            default = TEXT
            default.value = <div class="{field:flexform_xsCol1} {field:flexform_smCol1} col-md-{field:flexform_mdCol1} {field:flexform_lgCol1} {field:flexform_col31class}">|</div>
            default.insertData = 1
        }
        102 < .101
        102.wrap.stdWrap.cObject {
            key.field = flexform_col32style
            default.value = <div class="{field:flexform_xsCol2} {field:flexform_smCol2} col-md-{field:flexform_mdCol2} {field:flexform_lgCol2} {field:flexform_col32class}">|</div>
        }
        103 < .101
        103.wrap.stdWrap.cObject {
            key.field = flexform_col33style
            default.value = <div class="{field:flexform_xsCol3} {field:flexform_smCol3} col-md-{field:flexform_mdCol3} {field:flexform_lgCol3} {field:flexform_col33class}">|</div>
        }
    }
  }

  # --- 4 columns ---
  #
  3 < lib.gridelements.defaultGridSetup
  3 {
    # row wrap
    wrap.stdWrap.cObject = CASE
    wrap.stdWrap.cObject {
        key.field = flexform_rowclass4
        default = TEXT
        default.value = <div class="row">|</div>
        #special1 = TEXT
        #special1.value = <div class="row special">|</div>
    }

    columns {
        101 < .default
        101.wrap.stdWrap.cObject = CASE
        101.wrap.stdWrap.cObject {
            key.field = flexform_col41style
            default = TEXT
            default.value = <div class="{field:flexform_xsCol1} {field:flexform_smCol1} col-md-{field:flexform_mdCol1} {field:flexform_lgCol1} {field:flexform_col41class}">|</div>
            default.insertData = 1
            #specialStyle1 = TEXT
            #specialStyle1.value = <div class="col-md-{field:flexform_mdCol1}"><div class="box-inner-wrap">|</div></div>
        }
        102 < .101
        102.wrap.stdWrap.cObject {
            key.field = flexform_col42style
            default.value = <div class="{field:flexform_xsCol2} {field:flexform_smCol2} col-md-{field:flexform_mdCol2} {field:flexform_lgCol2} {field:flexform_col42class}">|</div>
        }
        103 < .101
        103.wrap.stdWrap.cObject {
            key.field = flexform_col43style
            default.value = <div class="{field:flexform_xsCol3} {field:flexform_smCol3} col-md-{field:flexform_mdCol3} {field:flexform_lgCol3} {field:flexform_col43class}">|</div>
        }
        104 < .101
        104.wrap.stdWrap.cObject {
            key.field = flexform_col44style
            default.value = <div class="{field:flexform_xsCol4} {field:flexform_smCol4} col-md-{field:flexform_mdCol4} {field:flexform_lgCol4} {field:flexform_col44class}">|</div>
        }

    }
  }

  # --- Custom single box 1 ---
  #
  4 < lib.gridelements.defaultGridSetup
  4 {
    # default wrap
    wrap = <div class="iconbox">|</div>
    columns {
        101 < .default
    }
  }

  # --- Custom single box 2 ---
  #
  9 < lib.gridelements.defaultGridSetup
  9 {
    # wrap around everything
    wrap.outerWrap.stdWrap.cObject = CASE
    wrap.outerWrap.stdWrap.cObject {
        key.field = flexform_style
        default = TEXT
        default.value = <div class="iconbox2">|</div>
        style1 = TEXT
        style1.value = <div class="post-it-yellow">|</div>
        style2 = TEXT
        style2.value = <div class="post-it-green">|</div>
    }
    #wrap = <div class="box2">|</div>

    /*
    # additional wrap in default wrap
    stdWrap.wrap.stdWrap.cObject = CASE
    stdWrap.wrap.stdWrap.cObject {
        key.field = flexform_rowclass
        default = TEXT
        default.value = <div class="stdWrap wrap">|</div>
        special1 = TEXT
        special1.value = <div class="service-one"><div class="row">|</div></div>
    }
    */

    columns {
        101 < .default
        # last additional wrap, around content
        #101.wrap.innerWrap = <div class="innerwrap2">|</div>
    }
  }



  # -------------------------------------------------------------------------------------
  # Grid elements for tabs (register) and accordion (collapsable)
  #
  # --- Accordion element for each content element in this grid ---
  #
  5 < lib.gridelements.defaultGridSetup
  5 {

    # wrap around all
    outerWrap.stdWrap.cObject = CASE
    outerWrap.stdWrap.cObject {
        key.field = flexform_rowclass
        default = TEXT
        default.value = <div class="panel-group" id="accordion-{field:uid}">|</div>
        #acc = TEXT
        #acc.value = <div class="tabs"><div class="accordion" id="accordion-{field:uid}">|</div></div>
    }
    outerWrap.insertData = 1


    columns {
     2 >
     101 < .default
     101 {
      renderObj {

        stdWrap.outerWrap = <div class="panel panel-default">|</div>

        5 = LOAD_REGISTER
        5 {
           AccordionCount.cObject = TEXT
           AccordionCount.cObject.data = register:AccordionCount
           AccordionCount.cObject.wrap = |+1
           AccordionCount.prioriCalc = intval
        }

        10 = CONTENT
        10 {
          table = tt_content
          select {
            selectFields = header, uid, tx_gridelements_container
            where = 1=1
            andWhere = uid={field:uid}
            andWhere.insertData = 1
            orderBy = sorting
          }

          renderObj = COA
          renderObj {
            10 = TEXT
            10 {
              field = header
              insertData = 1
              wrap          = <div class="panel-heading"><a class="accordion-toggle" data-parent="#accordion-{field:tx_gridelements_container}" href="#collapse-{field:uid}" data-toggle="collapse"><h4>|</h4></a></div><div id="collapse-{field:uid}" class="panel-collapse collapse"><div class="panel-body">
              wrap.override = <div class="panel-heading"><a class="accordion-toggle" data-parent="#accordion-{field:tx_gridelements_container}" href="#collapse-{field:uid}" data-toggle="collapse"><h4>|</h4></a></div><div id="collapse-{field:uid}" class="panel-collapse collapse in"><div class="panel-body">
              wrap.override.if.value = 1
              wrap.override.if.equals.data = register:AccordionCount
            }
          }
        }

        # don't show headers in accordion
        #20 =< tt_content
        20 < tt_content
        20.text.10 >
        20.image.10 >
        20.textpic.10 >

        30 = TEXT
        30.wrap = |</div></div>
      }
     }
    }
  }

  # --- Tabs for each content element ---
  #
  6 < lib.gridelements.defaultGridSetup
  6 {

    #stdWrap.wrap = <div class="tabbable" id="tabs-{field:tx_gridelements_container}">|</div>
    #stdWrap.insertData = 1

    # wrap around all (override wrap above)
    outerWrap.stdWrap.cObject = CASE
    outerWrap.stdWrap.cObject {
        key.field = flexform_rowclass
        default = TEXT
        default.value = |
        #tabs = TEXT
        #tabs.value = <div class="tabbable" id="tabs-{field:tx_gridelements_container}">|</div>
    }
    outerWrap.insertData = 1

    prepend = COA
    prepend {
      10 = CONTENT
      10 {
        table = tt_content
        select {
          selectFields = header, uid, tx_gridelements_container, sorting
          where = 1=1
          andWhere = tx_gridelements_container={field:uid}
          andWhere.insertData = 1
          orderBy = sorting
        }

        wrap = <ul class="nav nav-tabs">|</ul>

        renderObj = COA
        renderObj {
          5 = LOAD_REGISTER
          5 {
            TabCount.cObject = TEXT
            TabCount.cObject.data = register:TabCount
            TabCount.cObject.wrap = |+1
            TabCount.prioriCalc = intval
          }
          10 = TEXT
          10 {
            field = header
            insertData = 1
            wrap = <a href="#tab-{field:uid}" data-toggle="tab">|</a>
            outerWrap.cObject = CASE
            outerWrap.cObject {
              key.data = register:TabCount
              #key.field = sorting
              default = TEXT
              default.value = <li id="tab-element-{field:uid}">|</li>
              1 = TEXT
              1.value = <li class="active" id="tab-element-{field:uid}">|</li>
            }
          }
        } # end renderObj

      }
    }

    columns {
      2 >

      101 < .default
      101 {
        wrap = <div class="tab-content">|</div>
        table = tt_content
        select {
          selectFields = tx_gridelements_container, sorting
          where = 1=1
          andWhere = uid={field:uid}
          andWhere.insertData = 1
          orderBy = sorting
        }

        renderObj {
          5 = LOAD_REGISTER
          5 {
            TabCount2.cObject = TEXT
            TabCount2.cObject.data = register:TabCount2
            TabCount2.cObject.wrap = |+1
            TabCount2.prioriCalc = intval
          }
          stdWrap.insertData = 1
          stdWrap.outerWrap.cObject = CASE
          stdWrap.outerWrap.cObject {
            key.data = register:TabCount2
            #key.field = sorting
            default = TEXT
            default.value = <div class="tab-pane fade" id="tab-{field:uid}">|</div>
            1 = TEXT
            1.value = <div class="tab-pane fade active in" id="tab-{field:uid}">|</div>
          }

          # don't show headers in tabs
          #20 =< tt_content
          20 < tt_content
          20.text.10 >
          20.image.10 >
          20.textpic.10 >

        } # end renderObj
      }
    } # end columns

  }


  # -------------------------------------------------------------------------------------
  # FlexSlider2 Grid Element
  #
  7 < lib.gridelements.defaultGridSetup
  7 {

    columns {
      101 < .default
      101 {
        renderObj {
            wrap = <li>|</li>
        }
      }
    }

    wrap.stdWrap.cObject = COA
    wrap.stdWrap.cObject {
        10 = TEXT
        10.insertData = 1
        10.value (
            <div class="flexslider" id="flexslider{field:uid}">
                <ul class="slides">|</ul>
            </div>
        )

        #  note: in insertData/dataWraps the use of '{' does not work as-is
        20 = COA
        20 {
            20 = TEXT
            20.wrap = |
            20.insertData = 1
            20.value (
             uid: {field:uid}, direction: "{field:flexform_direction}", slideshowSpeed: {field:flexform_slidespeed},
             animation: "{field:flexform_animation}", animationSpeed: {field:flexform_animationspeed},
             animationLoop: {field:flexform_animationloop}, easing: "{field:flexform_easing}", useCSS: false,
             controlNav: {field:flexform_showcontrolnav}, directionNav: {field:flexform_showdirnav},
             pausePlay: {field:flexform_showpause}, pauseOnHover: {field:flexform_pauseonhover},
             startAt: {field:flexform_startat}, initDelay: {field:flexform_initdelay},
             randomize: {field:flexform_randomize}, reverse: {field:flexform_reverse}
            )

            /*
            50 = TEXT
            50.value = Previous
            50.lang.de = Zurück
            50.wrap = ,prevText: "|"
            51 = TEXT
            51.value = Next
            51.lang.de = Weiter
            51.wrap = ,nextText: "|"

            60 = TEXT
            60.value = Pause
            60.lang.de = Pause
            60.wrap = ,pauseText: "|"
            61 = TEXT
            61.value = Play
            61.lang.de = Abspielen
            61.wrap = ,playText: "|"
            */
        }
        20.wrap (
         <script>
         var flexsliderConf = ( typeof flexsliderConf != 'undefined' && flexsliderConf instanceof Array ) ? flexsliderConf : [];
         flexsliderConf.push({
         |
         });
         </script>
        )
    }
  }



  # -------------------------------------------------------------------------------------
  # Modal box grid element
  #
  8 < lib.gridelements.defaultGridSetup
  8 {
    columns {
     2 >

     101 < .default
     101 {
      renderObj {
        #stdWrap.outerWrap = <div class="outer-wrap">|</div>

        5 = CONTENT
        5 {
          table = tt_content
          select {
            selectFields = header, uid, tx_gridelements_container
            where = 1=1
            andWhere = uid={field:uid}
            andWhere.insertData = 1
            orderBy = sorting
          }
          renderObj = COA
          renderObj {
            10 = TEXT
            10 {
              field = header
              insertData = 1
              wrap = <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title" id="modalLabel{field:tx_gridelements_container}">|</h4></div><div class="modal-body">
            }
          }
        }

        # accordion content
        #20 =< tt_content
        20 < tt_content
        # don't show headers in accordion
        20.text.10 >
        20.image.10 >
        20.textpic.10 >


        # close div from 5.renderObj.10.wrap
        30 = TEXT
        30.wrap = |</div>

      }
     }
    }

    stdWrap.outerWrap.cObject = COA
    stdWrap.outerWrap.cObject {
      10 = TEXT
      10.value = <div class="modal fade" id="{field:flexform_modalid}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{field:uid}" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">|
      10.insertData = 1

      20 = COA
      20.wrap = <div class="modal-footer">|</div>
      20 {

        10 = TEXT
        10.data = {$plugin.tx_bootstrapcore.langFile}:grid.modal.buttonclose
        10.wrap = <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">|</button>
        10.if.value.data = field:flexform_buttontype
        10.if.equals = close

        20 = TEXT
        20.data = {$plugin.tx_bootstrapcore.langFile}:grid.modal.buttonok
        20.wrap = <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">|</button>
        20.if.value.data = field:flexform_buttontype
        20.if.equals = ok
      }

      # close divs from .10
      40 = TEXT
      40.value = </div></div></div>
    }

  }

}