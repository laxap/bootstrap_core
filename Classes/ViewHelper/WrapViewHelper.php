<?php
namespace Laxap\BootstrapCore\ViewHelper;

/*
 *
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *
 */

class WrapViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var array
     */
    protected $renderConfig = array();


    /**
     * @var int
     */
    protected $uid = 0;

    /**
     * @var int
     */
    protected $layout = 0;

    /**
     * @var int
     */
    protected $section_frame = 0;

    /**
     * @var int
     */
    protected $sectionIndex = 0;

    /**
     * @var string
     */
    protected $visibility = '';

    /**
     * @var string
     */
    protected $ctype = '';


    /**
     * @var array
     */
    protected $classes = array();


    /**
     * @param array $data
     * @param array $settings
     * @return string
     */
    public function render($data, $settings) {
        // settings from lib.fluidContent.settings.bootstrap_core
        $this->settings = $settings;
        // get relevant data
        if ( ! $this->init($data) ) {
            return '';
        }
        // get id attrib
        $idAttrib = $this->getIdAttrib();
        // no div
        if ( $idAttrib == '' && in_array($this->section_frame, $this->renderConfig['nowrap']['section_frames']) ) {
            return $this->getWrapped($this->getInnerWrapped($this->reduceOutput($this->renderChildren())));
        }

        // get classes (for inner div)
        $classAttrib = $this->getClassAttrib();
        // container with content
        $output = '<div' . $idAttrib . $classAttrib . '>' . $this->getInnerWrapped($this->renderChildren()) . '</div>';

        // minify (remove whitespace)
        if ( isset($this->settings['reduceOutput']) && $this->settings['reduceOutput'] == 1 ) {
            return $this->getWrapped($this->reduceOutput($output));
        }
        return $this->getWrapped($output);
    }


    /**
     * @param array $data
     * @return bool
     */
    protected function init($data) {
        if ( ! isset($data['uid']) || ! $data['uid']  ) {
            return false;
        }
        $this->uid = $data['uid'];

        if ( isset($data['layout']) && $data['layout'] > 0  ) {
            $this->layout = $data['layout'];
        }
        if ( isset($data['section_frame']) && $data['section_frame'] > 0  ) {
            $this->section_frame = $data['section_frame'];
        }
        if ( isset($data['sectionIndex']) && $data['sectionIndex']  ) {
            $this->sectionIndex = $data['sectionIndex'];
        }
        if ( isset($data['tx_bootstrapcore_visibility']) && trim($data['tx_bootstrapcore_visibility']) != ''  ) {
            $this->visibility = trim($data['tx_bootstrapcore_visibility']);
        }
        if ( isset($data['CType']) && $data['CType']  ) {
            $this->ctype = $data['CType'];
        }

        // merge general and ctype specific settings
        if ( isset($this->settings[$this->ctype]) && is_array($this->settings[$this->ctype])  ) {
            $this->renderConfig = array_replace_recursive($this->settings['general'], $this->settings[$this->ctype]);
        } else {
            $this->renderConfig = $this->settings['general'];
        }

        // no wrap
        if ( isset($this->renderConfig['nowrap']) && is_array($this->renderConfig['nowrap']) ) {
            $noWrapDef = $this->renderConfig['nowrap'];
            // based on layout
            if ( isset($noWrapDef['layout']) && strlen(trim($noWrapDef['layout']))  ) {
                $this->renderConfig['nowrap']['layout'] = explode(',', $noWrapDef['layout']);
            } else {
                $this->renderConfig['nowrap']['layout'] = array();
            }
            // based on section_frame
            if ( isset($noWrapDef['section_frame']) && strlen(trim($noWrapDef['section_frame']))  ) {
                $this->renderConfig['nowrap']['section_frames'] = explode(',', $noWrapDef['section_frame']);
            } else {
                $this->renderConfig['nowrap']['section_frames'] = array();
            }
        }
        return true;
    }

    /**
     * @param string $prefix
     * @return string
     */
    protected function getIdAttrib($prefix = 'c') {
        // only if sectionIndex is enabled
        if ( $this->sectionIndex > 0 ) {
            return ' id="' . $prefix . $this->uid . '"';
        }
        return '';
    }

    /**
     * @return string
     */
    protected function getClassAttrib() {
        // get classes
        if ( isset($this->renderConfig['defaultClass']) && $this->renderConfig['defaultClass'] ) {
            $this->classes[] = $this->renderConfig['defaultClass'];
        }
        // get classes based on:
        // layout
        $this->getLayoutClasses();
        // section frame
        if ( $this->renderConfig['useSectionFrameOnlyIfNoLayoutSet'] == 1 && $this->layout ) {
            // do nothing
        } else {
            $this->getSectionFrameClasses();
        }
        // visibility
        $this->getVisibilityClasses();

        // return attrib if any used
        if ( count($this->classes) > 0 ) {
            return ' class="' . implode(' ', $this->classes) . '"';
        }
        return '';
    }

    /**
     * @return boolean
     */
    protected function getLayoutClasses() {
        // no layout, no classes
        if ( $this->layout == 0 ) {
            return false;
        }
        // check if classes defined
        if ( ! isset($this->renderConfig['class']['layout']) || ! is_array($this->renderConfig['class']['layout']) ) {
            return false;
        }
        // check if for this layout defined
        if ( ! isset($this->renderConfig['class']['layout'][$this->layout]) || trim($this->renderConfig['class']['layout'][$this->layout]) == '' ) {
            return false;
        }
        $this->classes[] = $this->renderConfig['class']['layout'][$this->layout];
        return true;
    }

    /**
     * @return boolean
     */
    protected function getSectionFrameClasses() {
        // no section_frame, no classes
        if ( $this->section_frame == 0 ) {
            return false;
        }
        // check if classes defined
        if ( ! isset($this->renderConfig['class']['section_frame']) || ! is_array($this->renderConfig['class']['section_frame']) ) {
            return false;
        }
        // get defined class(es) for section_frame
        if ( ! isset($this->renderConfig['class']['section_frame'][$this->section_frame]) || trim($this->renderConfig['class']['section_frame'][$this->section_frame]) == '' ) {
            return false;
        }
        $this->classes[] = $this->renderConfig['class']['section_frame'][$this->section_frame];
        return true;
    }

    /**
     * @return boolean
     */
    protected function getVisibilityClasses() {
        if ( $this->visibility && ( ! isset($this->renderConfig['tx_bootstrapcore_visibility ']['enabled']) || $this->renderConfig['tx_bootstrapcore_visibility ']['enabled'] != 0) ) {
            $this->classes[] = $this->visibility;
        }
        return true;
    }

    /**
     * @param string $output
     * @return string
     */
    protected function getWrapped($output) {
        // 1. section_frame
        if ( isset($this->renderConfig['outerWrap']['section_frame']) && is_array($this->renderConfig['outerWrap']['section_frame']) ) {
            if ( isset($this->renderConfig['outerWrap']['section_frame'][$this->section_frame]) && trim($this->renderConfig['outerWrap']['section_frame'][$this->section_frame]) != '' ) {
                $output = str_replace('|', $output, $this->renderConfig['outerWrap']['section_frame'][$this->section_frame]);
            }
        }
        // 2. layout
        if ( isset($this->renderConfig['outerWrap']['layout']) && is_array($this->renderConfig['outerWrap']['layout']) ) {
            if ( isset($this->renderConfig['outerWrap']['layout'][$this->layout]) && trim($this->renderConfig['outerWrap']['layout'][$this->layout]) != '' ) {
                $output = str_replace('|', $output, $this->renderConfig['outerWrap']['layout'][$this->layout]);
            }
        }
        // 3. ctype
        if ( isset($this->renderConfig['outerWrap']['ctype']) && is_array($this->renderConfig['outerWrap']['ctype']) ) {
            if ( isset($this->renderConfig['outerWrap']['ctype'][$this->ctype]) && trim($this->renderConfig['outerWrap']['ctype'][$this->ctype]) != '' ) {
                $output = str_replace('|', $output, $this->renderConfig['outerWrap']['ctype'][$this->ctype]);
            }
        }
        return $output;
    }


    /**
     * @param string $output
     * @return string
     */
    protected function getInnerWrapped($output) {
        // 1. section_frame
        if ( isset($this->renderConfig['innerWrap']['section_frame']) && is_array($this->renderConfig['innerWrap']['section_frame']) ) {
            if ( isset($this->renderConfig['innerWrap']['section_frame'][$this->section_frame]) && trim($this->renderConfig['innerWrap']['section_frame'][$this->section_frame]) != '' ) {
                $output = str_replace('|', $output, $this->renderConfig['innerWrap']['section_frame'][$this->section_frame]);
            }
        }
        // 2. layout
        if ( isset($this->renderConfig['innerWrap']['layout']) && is_array($this->renderConfig['innerWrap']['layout']) ) {
            if ( isset($this->renderConfig['innerWrap']['layout'][$this->layout]) && trim($this->renderConfig['innerWrap']['layout'][$this->layout]) != '' ) {
                $output = str_replace('|', $output, $this->renderConfig['innerWrap']['layout'][$this->layout]);
            }
        }
        // 3. ctype
        if ( isset($this->renderConfig['innerWrap']['ctype']) && is_array($this->renderConfig['innerWrap']['ctype']) ) {
            if ( isset($this->renderConfig['innerWrap']['ctype'][$this->ctype]) && trim($this->renderConfig['innerWrap']['ctype'][$this->ctype]) != '' ) {
                $output = str_replace('|', $output, $this->renderConfig['innerWrap']['ctype'][$this->ctype]);
            }
        }
        return $output;
    }

    /**
     * @param $content
     * @return mixed
     */
    protected function reduceOutput($content) {
        // from http://stackoverflow.com/questions/5312349/minifying-final-html-output-using-regular-expressions-with-codeigniter
        return preg_replace('#(?ix)(?>[^\S ]\s*|\s{2,})(?=(?:(?:[^<]++|<(?!/?(?:textarea|pre)\b))*+)(?:<(?>textarea|pre)\b|\z))#', ' ', $content);
    }
}