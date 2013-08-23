
# --- tt_content.uploads ----------
#
tt_content.uploads {
    # Disable image rendering and jumpurl for filelist element
    20 {
        linkProc {
            combinedLink = 0
            jumpurl >
        }

        renderObj {
            # preview image
            10 {
                # With of preview image
                file.width = 100
            }

            # icon
            # change if a set of custom icons is used for downloads, default is typo3/gfx/fileicons/
            15.file.import = {$plugin.tx_bootstrapcore.resourceDir}/Public/Icons/fileicons/
            15.file.import.wrap = |.png

            # title of download
            20 {
                # instead of name use title (works only if media object used and title given)
                #data = file:current:title

                # activate if file extension should be stripped from filename
                #replacement.20 < .replacement._20
                #replacement._20 >
            }
        }

    }
}



