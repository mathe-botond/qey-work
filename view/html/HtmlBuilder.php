<?php
namespace qeywork;

/**
 * Y is an alias of HtmlElement. Derives from the secod letter of Hypertext,
 * also the tird letter of QeyWork, as a major compoent of this framework
 *
 * @author Dexx
 * @method HtmlNode a() Creates a node of a
 * @method HtmlNode abbr() Creates a node of abbr
 * @method HtmlNode acronym() Creates a node of acronym
 * @method HtmlNode applet() Creates a node of applet
 * @method HtmlNode article() Creates a node of article
 * @method HtmlNode b() Creates a node of b
 * @method HtmlNode big() Creates a node of big
 * @method HtmlNode blockquote() Creates a node of blockquote
 * @method HtmlNode body() Creates a node of body
 * @method HtmlNode br() Creates a node of br
 * @method HtmlNode button() Creates a node of button
 * @method HtmlNode caption() Creates a node of caption
 * @method HtmlNode center() Creates a node of center
 * @method HtmlNode cite() Creates a node of cite
 * @method HtmlNode code() Creates a node of code
 * @method HtmlNode col() Creates a node of col
 * @method HtmlNode colgroup() Creates a node of colgroup
 * @method HtmlNode dir() Creates a node of dir
 * @method HtmlNode div() Creates a node of div
 * @method HtmlNode dl() Creates a node of dl
 * @method HtmlNode dt() Creates a node of dt
 * @method HtmlNode em() Creates a node of em
 * @method HtmlNode fieldset() Creates a node of fieldset
 * @method HtmlNode font() Creates a node of font
 * @method HtmlNode form() Creates a node of form
 * @method HtmlNode h1() Creates a node of h1
 * @method HtmlNode h2() Creates a node of h2
 * @method HtmlNode h3() Creates a node of h3
 * @method HtmlNode h4() Creates a node of h4
 * @method HtmlNode h5() Creates a node of h5
 * @method HtmlNode h6() Creates a node of h6
 * @method HtmlNode head() Creates a node of head
 * @method HtmlNode hr() Creates a node of hr
 * @method HtmlNode html() Creates a node of html
 * @method HtmlNode i() Creates a node of i
 * @method HtmlNode iframe() Creates a node of iframe
 * @method HtmlNode img() Creates a node of img
 * @method HtmlNode input() Creates a node of input
 * @method HtmlNode ins() Creates a node of ins
 * @method HtmlNode label() Creates a node of label
 * @method HtmlNode legend() Creates a node of legend
 * @method HtmlNode li() Creates a node of li
 * @method HtmlNode link() Creates a node of link
 * @method HtmlNode map() Creates a node of map
 * @method HtmlNode menu() Creates a node of menu
 * @method HtmlNode meta() Creates a node of meta
 * @method HtmlNode noscript() Creates a node of noscript
 * @method HtmlNode object() Creates a node of object
 * @method HtmlNode ol() Creates a node of ol
 * @method HtmlNode optgroup() Creates a node of optgroup
 * @method HtmlNode option() Creates a node of option
 * @method HtmlNode p() Creates a node of p
 * @method HtmlNode param() Creates a node of param
 * @method HtmlNode pre() Creates a node of pre
 * @method HtmlNode q() Creates a node of q
 * @method HtmlNode s() Creates a node of s
 * @method HtmlNode script() Creates a node of script
 * @method HtmlNode select() Creates a node of select
 * @method HtmlNode small() Creates a node of small
 * @method HtmlNode span() Creates a node of span
 * @method HtmlNode source() Creates a node of ul
 * @method HtmlNode strike() Creates a node of strike
 * @method HtmlNode strong() Creates a node of strong
 * @method HtmlNode style() Creates a node of style
 * @method HtmlNode sub() Creates a node of sub
 * @method HtmlNode sup() Creates a node of sup
 * @method HtmlNode table() Creates a node of table
 * @method HtmlNode tbody() Creates a node of tbody
 * @method HtmlNode td() Creates a node of td
 * @method HtmlNode textarea() Creates a node of textarea
 * @method HtmlNode tfoot() Creates a node of tfoot
 * @method HtmlNode th() Creates a node of th
 * @method HtmlNode thead() Creates a node of thead
 * @method HtmlNode title() Creates a node of title
 * @method HtmlNode tr() Creates a node of tr
 * @method HtmlNode u() Creates a node of u
 * @method HtmlNode ul() Creates a node of ul
 * @method HtmlNode video() Creates a node of ul
 */
class HtmlBuilder {
    private static $selfClosedTags = array('br', 'hr', 'meta', 'link', 'input', 'img', 'source');
    /**
     * @param string $tag
     * @param array $params
     * @return HtmlNode
     */
    public function __call($tag, $params) {
        if (in_array($tag, self::$selfClosedTags)) {
            $selfClosed = true;
        } else {
            $selfClosed = false;
        }
        
        return new HtmlNode($tag, $selfClosed);
     }
}
